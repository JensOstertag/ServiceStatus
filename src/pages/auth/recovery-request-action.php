<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

// Check whether form fields are given
$validation = Validation->create()
    ->withErrorMessage(t("Please enter your account's verified email address."))
    ->array()
    ->required()
    ->children([
        "email" => Validation->create()
            ->string()
            ->email()
            ->withErrorMessage(t("The specified email address is invalid. Please check for spelling errors and try again."))
            ->build()
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    Router->redirect(Router->generate("auth-recovery-request"));
}

$user = User::dao()->getObject([
    "email" => $post["email"],
    "emailVerified" => true
]);

if(!$user instanceof User) {
    Logger->tag("Recovery")->info("Failed to request password recovery for email \"{$post["email"]}\"");
    InfoMessage->error(t("An account with this email could not be found. Please check for spelling errors and try again."));
    Router->redirect(Router->generate("auth-recovery-request"));
}

// Send password recovery mail
$oneTimePassword = User::dao()->generateOneTimePassword();
$now = new DateTimeImmutable();
$oneTimePasswordExpiration = $now->modify("+15 minutes");

$user->setOneTimePassword($oneTimePassword);
$user->setOneTimePasswordExpiration($oneTimePasswordExpiration);
User::dao()->save($user);

$otpIdEncoded = urlencode(base64_encode($user->getId()));
$otpEncoded = urlencode($oneTimePassword);
$verificationLink = Router->generate("auth-recovery-reset", [], true) . "?otpid=" . $otpIdEncoded . "&otp=" . $otpEncoded;
$mail = new \struktal\MailWrapper\MailWrapper();
$mail->Subject = t("Password recovery");
$mail->Body = t("You have requested to recover your password for your \$\$appName\$\$ account.", [
        "appName" => Config->getAppName()
    ]) . "\r\n"
    . t("To set a new password, please open the following link:") . "\r\n"
    . $verificationLink . "\r\n"
    . t("This link is valid for 15 minutes.") . "\r\n"
    . "\r\n"
    . t("If you haven't requested a password recovery for your \$\$appName\$\$ account, you can ignore this email.", [
        "appName" => Config->getAppName()
    ]);
$mail->addAddress($post["email"]);
$mail->send();

Logger->tag("Recovery")->info("Requested password recovery for user with email \"{$post["email"]}\" (User ID \"{$user->getId()}\")");
Router->redirect(Router->generate("auth-recovery-request-complete"));
