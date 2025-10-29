<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

// Check whether form fields are given
$post = Validation->create()
    ->withErrorMessage(t("Please log in with your account's credentials."))
    ->array()
    ->required()
    ->children([
        "username" => \app\users\Validations::username(),
        "password" => \app\users\Validations::password()
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("auth-login"));
    });

try {
    $user = \app\users\UserService::login($post["username"], false, $post["password"]);
} catch(\app\users\UserNotFoundException | \app\users\InvalidPasswordException $e) {
    InfoMessage->error(t("An account with these credentials could not be found. Please check for spelling errors and try again."));
    Router->redirect(Router->generate("auth-login"));
} catch(\app\users\EmailNotVerifiedException $e) {
    InfoMessage->error(t("Before logging in, please verify your account's email address."));
    Router->redirect(Router->generate("auth-login"));
}

Logger->tag("Login")->info("User \"{$post["username"]}\" has logged in (User ID {$user->getId()})");
Auth->sessionLogin($user);
Router->redirect(Router->generate("admin"));
