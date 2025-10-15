<?php

namespace app\users;

class UserPasswordResetService {
    public static function generateRecoveryLink(int $otpId, string $otp): string {
        $otpIdEncoded = urlencode(base64_encode($otpId));
        $otpEncoded = urlencode($otp);

        return Router->generate("auth-recovery-reset", [], true) . "?otpid=" . $otpIdEncoded . "&otp=" . $otpEncoded;
    }

    public static function requestRecovery(User $user): void {
        $oneTimePassword = UserService::generateOneTimePassword();

        $now = new \DateTimeImmutable();
        $oneTimePasswordExpiration = $now->modify("+15 minutes");

        $user->setOneTimePassword($oneTimePassword);
        $user->setOneTimePasswordExpiration($oneTimePasswordExpiration);
        User::dao()->save($user);

        AuthNotificationService::sendPasswordRecoveryInstructions($user, $oneTimePassword);

        Logger->tag("Recovery")->info("Requested password recovery for user with email \"{$user->getEmail()}\" (User ID \"{$user->getId()}\")");
    }

    public static function verifyOtp(int|string $otpId, string $otp, bool $isUrlEncoded): User {
        if($isUrlEncoded) {
            $otpId = base64_decode(urldecode($otpId));
            $otp = urldecode($otp);
        }

        // Find the user from the one-time password
        $user = User::dao()->getObject([
            "id" => $otpId,
            "emailVerified" => true,
            new \struktal\ORM\DAOFilter(
                \struktal\ORM\DAOFilterOperator::NOT_EQUALS,
                "oneTimePassword",
                null
            ),
            new \struktal\ORM\DAOFilter(
                \struktal\ORM\DAOFilterOperator::GREATER_THAN_EQUALS,
                "oneTimePasswordExpiration",
                new \DateTimeImmutable()
            )
        ]);

        if(!$user instanceof User) {
            Logger->tag("Recovery")->info("Attempted to recover password, but couldn't find user with otpid \"{$otpId}\"");
            throw new UserNotFoundException();
        }
        if(!password_verify($otp, $user->getOneTimePassword())) {
            Logger->tag("Recovery")->info("Attempted to recover password, but one-time password does not match");
            throw new UserNotFoundException();
        }

        return $user;
    }

    public static function setPassword(User $user, string $newPassword): void {
        $user->setPassword($newPassword);
        $user->setOneTimePassword(null);
        $user->setOneTimePasswordExpiration(null);
        $user->setUpdated(new \DateTimeImmutable());
        User::dao()->save($user);

        Logger->tag("Recovery")->info("Changed password for user with email \"{$user->getEmail()}\" (User ID \"{$user->getId()}\")");
    }
}
