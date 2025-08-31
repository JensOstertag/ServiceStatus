<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

// Check whether a one-time password has been specified
$sessionValidation = Validation->create()
    ->withErrorMessage(t("An error has occurred. Please try again later."))
    ->array()
    ->required()
    ->children([
        "authRecoveryOtpId" => Validation->create()
            ->int()
            ->build(),
        "authRecoveryOtp" => Validation->create()
            ->string()
            ->minLength(1)
            ->build()
    ])
    ->build();
try {
    $session = $sessionValidation->getValidatedValue($_SESSION);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    Router->redirect(Router->generate("auth-login"));
}

$otpId = $session["authRecoveryOtpId"];
$otp = $session["authRecoveryOtp"];

// Clear old session variables
unset($_SESSION["authRecoveryOtpId"]);
unset($_SESSION["authRecoveryOtp"]);

// Generate redirect link for error cases
$otpIdEncoded = urlencode(base64_encode($otpId));
$otpEncoded = urlencode($otp);
$resetLink = Router->generate("auth-recovery-reset") . "?otpid=" . $otpIdEncoded . "&otp=" . $otpEncoded;

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

// Check whether form fields are given
$postValidation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "password" => Validation->create()
            ->string()
            ->minLength(8)
            ->maxLength(256)
            ->build(),
        "password-repeat" => Validation->create()
            ->string()
            ->minLength(8)
            ->maxLength(256)
            ->build()
    ])
    ->build();
try {
    $post = $postValidation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    Router->redirect($resetLink);
}

// Check passwords
if($post["password"] !== $post["password-repeat"]) {
    InfoMessage->error(t("The specified passwords do not match. Please check for spelling errors and try again."));
    Router->redirect($resetLink);
}
if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).{8,}$/", $post["password"])) {
    InfoMessage->error(t("The specified password doesn't fulfill the password requirements. Please choose a safer password."));
    Router->redirect($resetLink);
}

// Change password
$user->setPassword($post["password"]);
$user->setOneTimePassword(null);
$user->setOneTimePasswordExpiration(null);
$user->setUpdated(new DateTimeImmutable());
User::dao()->save($user);

Logger->tag("Recovery")->info("Changed password for user with email \"{$user->getEmail()}\" (User ID \"{$user->getId()}\")");

Router->redirect(Router->generate("auth-recovery-reset-complete"));
