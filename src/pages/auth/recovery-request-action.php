<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

// Check whether form fields are given
$post = Validation->create()
    ->withErrorMessage(t("Please enter your account's verified email address."))
    ->array()
    ->required()
    ->children([
        "email" => \app\users\Validations::email(t("The specified email address is invalid. Please check for spelling errors and try again."))
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("auth-recovery-request"));
    });

$user = \app\users\User::dao()->getObject([
    "email" => $post["email"],
    "emailVerified" => true
]);

if(!$user instanceof \app\users\User) {
    Logger->tag("Recovery")->info("Failed to request password recovery for email \"{$post["email"]}\"");
    InfoMessage->error(t("An account with this email could not be found. Please check for spelling errors and try again."));
    Router->redirect(Router->generate("auth-recovery-request"));
}

// Send password recovery mail
try {
    \app\users\UserPasswordResetService::requestRecovery($user);
} catch(\Exception $e) {
    Logger->tag("Recovery")->error("Failed to send password recovery email to user with email \"{$post["email"]}\" (User ID \"{$user->getId()}\")", ["exception" => $e]);
    InfoMessage->error(t("An error has occurred. Please try again later."));
    Router->redirect(Router->generate("auth-recovery-request"));
}

Router->redirect(Router->generate("auth-recovery-request-complete"));
