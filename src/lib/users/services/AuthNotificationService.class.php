<?php

namespace app\users;

class AuthNotificationService {
    public static function sendEmailVerification(User $user, string $oneTimePassword): void {
        $verificationLink = UserVerificationService::generateVerificationLink($user->getId(), $oneTimePassword);

        // Send verification mail
        $mail = new \struktal\MailWrapper\MailWrapper();
        $mail->Subject = t("Verify your email address");
        $mail->Body = t("A new \$\$appName\$\$ account has been registered with this email address.", [
                "appName" => Config->getAppName()
            ]) . "\r\n"
            . t("To verify your email address and to complete the registration process, please open the following link:") . "\r\n"
            . $verificationLink . "\r\n"
            . "\r\n"
            . t("If you haven't registered an account at \$\$appName\$\$, you can ignore this email.", [
                "appName" => Config->getAppName()
            ]);
        $mail->addAddress($user->getEmail());
        $mail->send();
    }

    public static function sendPasswordRecoveryInstructions(User $user, string $oneTimePassword): void {
        $recoveryLink = UserPasswordResetService::generateRecoveryLink($user->getId(), $oneTimePassword);

        // Send password recovery mail
        $mail = new \struktal\MailWrapper\MailWrapper();
        $mail->Subject = t("Password recovery");
        $mail->Body = t("You have requested to recover your password for your \$\$appName\$\$ account.", [
                "appName" => Config->getAppName()
            ]) . "\r\n"
            . t("To set a new password, please open the following link:") . "\r\n"
            . $recoveryLink . "\r\n"
            . t("This link is valid for 15 minutes.") . "\r\n"
            . "\r\n"
            . t("If you haven't requested a password recovery for your \$\$appName\$\$ account, you can ignore this email.", [
                "appName" => Config->getAppName()
            ]);
        $mail->addAddress($user->getEmail());
        $mail->send();
    }
}
