<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

// Clear old session variables
unset($_SESSION["authRecoveryOtpId"]);
unset($_SESSION["authRecoveryOtp"]);

// Check whether a one-time password has been specified
$get = Validation->create()
    ->withErrorMessage(t("An error has occurred. Please try again later."))
    ->array()
    ->required()
    ->children([
        "otpid" => \app\users\Validations::urlOtpId(),
        "otp" => \app\users\Validations::otp()
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("auth-login"));
    });

// Find the user from the one-time password
try {
    $user = \app\users\UserPasswordResetService::verifyOtp($get["otpid"], $get["otp"], true);
} catch(\app\users\UserNotFoundException $e) {
    InfoMessage->error(t("The URL has already been invalidated. Please log in or request a new password recovery email."));
    Router->redirect(Router->generate("auth-login"));
}

// Write user details to session
$_SESSION["authRecoveryOtpId"] = $user->getId();
$_SESSION["authRecoveryOtp"] = urldecode($get["otp"]);

Logger->tag("Recovery")->info("Starting password recovery for user with email \"{$user->getEmail()}\" (User ID \"{$user->getId()}\")");

echo Blade->run("pages.auth.recoveryreset");
