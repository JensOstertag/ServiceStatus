<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

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
    InfoMessage->error(t("The URL has already been invalidated. Please log in or request a new password recovery email."));
    Router->redirect(Router->generate("auth-login"));
}
if(!password_verify($otp, $user->getOneTimePassword())) {
    Logger->tag("Email-Verification")->info("Attempted to verify an email, but one-time password does not match");
    InfoMessage->error(t("The URL has already been invalidated. Please log in or request a new password recovery email."));
    Router->redirect(Router->generate("auth-login"));
}

// Update the user object in the database
$user->setEmailVerified(true);
$user->setOneTimePassword(null);
$user->setOneTimePasswordExpiration(null);
$user->setUpdated(new DateTimeImmutable());
User::dao()->save($user);

Logger->tag("Email-Verification")->info("The email address \"{$user->getEmail()}\" (User ID \"{$user->getId()}\") has been verified");

Router->redirect(Router->generate("auth-verify-email-complete"));
