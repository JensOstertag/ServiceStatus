<?php

namespace app\users;

class UserVerificationService {
    public static function verify(User $user): void {
        $user->setEmailVerified(true);
        $user->setOneTimePassword(null);
        $user->setOneTimePasswordExpiration(null);
        $user->setUpdated(new \DateTimeImmutable());
        User::dao()->save($user);

        Logger->tag("Email-Verification")->info("The email address \"{$user->getEmail()}\" (User ID \"{$user->getId()}\") has been verified");
    }

    public static function generateVerificationLink(int $otpId, string $otp): string {
        $otpIdEncoded = urlencode(base64_encode($otpId));
        $otpEncoded = urlencode($otp);

        return Router->generate("auth-verify-email", [], true) . "?otpid=" . $otpIdEncoded . "&otp=" . $otpEncoded;
    }

    public static function verifyOtp(int|string $otpId, string $otp, bool $isUrlEncoded): User {
        if($isUrlEncoded) {
            $otpId = base64_decode(urldecode($otpId));
            $otp = urldecode($otp);
        }

        // Find the user from the one-time password
        $user = \app\users\User::dao()->getObject([
            "id" => $otpId,
            "emailVerified" => false,
            new \struktal\ORM\DAOFilter(
                \struktal\ORM\DAOFilterOperator::NOT_EQUALS,
                "oneTimePassword",
                null
            ),
            "oneTimePasswordExpiration" => null
        ]);

        if(!$user instanceof User) {
            Logger->tag("Email-Verification")->info("Attempted to verify an email, but couldn't find user with otpid \"{$otpId}\"");
            throw new UserNotFoundException();
        }
        if(!password_verify($otp, $user->getOneTimePassword())) {
            Logger->tag("Email-Verification")->info("Attempted to verify an email, but one-time password does not match");
            throw new UserNotFoundException();
        }

        return $user;
    }
}
