<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

// Clear old session variables
unset($_SESSION["authRecoveryOtpId"]);
unset($_SESSION["authRecoveryOtp"]);

// Check whether a one-time password has been specified
$validation = Validation->create()
    ->withErrorMessage(t("An error has occurred. Please try again later."))
    ->array()
    ->required()
    ->children([
        "otpid" => Validation->create()
            ->string()
            ->minLength(1)
            ->build(),
        "otp" => Validation->create()
            ->string()
            ->minLength(1)
            ->build()
    ])
    ->build();
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    Router->redirect(Router->generate("auth-login"));
}

$otpId = base64_decode(urldecode($get["otpid"]));
$otp = urldecode($get["otp"]);

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
        new DateTime()
    )
]);
if(!$user instanceof User) {
    Logger->tag("Recovery")->info("Attempted to recover password, but couldn't find user with otpid \"{$otpId}\"");
    InfoMessage->error(t("The URL has already been invalidated. Please log in or request a new password recovery email."));
    Router->redirect(Router->generate("auth-login"));
}
if(!password_verify($otp, $user->getOneTimePassword())) {
    Logger->tag("Recovery")->info("Attempted to recover password, but one-time password does not match");
    InfoMessage->error(t("The URL has already been invalidated. Please log in or request a new password recovery email."));
    Router->redirect(Router->generate("auth-login"));
}

// Write user details to session
$_SESSION["authRecoveryOtpId"] = $user->getId();
$_SESSION["authRecoveryOtp"] = $otp;

Logger->tag("Recovery")->info("Starting password recovery for user with email \"{$user->getEmail()}\" (User ID \"{$user->getId()}\")");

echo Blade->run("auth.recoveryreset");
