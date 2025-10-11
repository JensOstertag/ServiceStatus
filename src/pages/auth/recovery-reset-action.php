<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

// Check whether a one-time password has been specified
$session = Validation->create()
    ->withErrorMessage(t("An error has occurred. Please try again later."))
    ->array()
    ->required()
    ->children([
        "authRecoveryOtpId" => \app\users\Validations::sessionOtpId(),
        "authRecoveryOtp" => \app\users\Validations::otp()
    ])
    ->validate($_SESSION, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("auth-login"));
    });

$otpId = $session["authRecoveryOtpId"];
$otp = $session["authRecoveryOtp"];

// Clear old session variables
unset($_SESSION["authRecoveryOtpId"]);
unset($_SESSION["authRecoveryOtp"]);

// Generate redirect link for error cases
$recoveryLink = \app\users\UserPasswordResetService::generateRecoveryLink($otpId, $otp);

// Find the user from the one-time password
try {
    $user = \app\users\UserPasswordResetService::verifyOtp($otpId, $otp, false);
} catch(\app\users\UserNotFoundException $e) {
    InfoMessage->error(t("The URL has already been invalidated. Please log in or request a new password recovery email."));
    Router->redirect(Router->generate("auth-login"));
}

// Check whether form fields are given
$post = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "password" => \app\users\Validations::password(),
        "password-repeat" => \app\users\Validations::password()
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) use ($recoveryLink) {
        InfoMessage->error($e->getMessage());
        Router->redirect($recoveryLink);
    });

// Check passwords
try {
    \app\users\Validations::checkTwoPasswords($post["password"], $post["password-repeat"]);
} catch(\app\users\PasswordMismatchException $e) {
    InfoMessage->error(t("The specified passwords do not match. Please check for spelling errors and try again."));
    Router->redirect($recoveryLink);
} catch(\app\users\WeakPasswordException $e) {
    InfoMessage->error(t("The specified password doesn't fulfill the password requirements. Please choose a safer password."));
    Router->redirect($recoveryLink);
}

// Change password
\app\users\UserPasswordResetService::setPassword($user, $post["password"]);

Router->redirect(Router->generate("auth-recovery-reset-complete"));
