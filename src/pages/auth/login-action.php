<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

// Check whether form fields are given
$validation = Validation->create()
    ->withErrorMessage(t("Please log in with your account's credentials."))
    ->array()
    ->required()
    ->children([
        "username" => Validation->create()
            ->string()
            ->minLength(5)
            ->maxLength(256)
            ->build(),
        "password" => Validation->create()
            ->string()
            ->minLength(8)
            ->maxLength(256)
            ->build()
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    Router->redirect(Router->generate("auth-login"));
}

$user = User::dao()->login($post["username"], false, $post["password"]);

if($user instanceof \struktal\Auth\LoginError) {
    if($user === \struktal\Auth\LoginError::EMAIL_NOT_VERIFIED) {
        $message = t("Before logging in, please verify your account's email address.");
    } else {
        $message = t("An account with these credentials could not be found. Please check for spelling errors and try again.");
    }

    Logger->tag("Login")->info("User \"{$post["username"]}\" failed to log in: " . ($user === 0 ? "User not found" : ($user === 1 ? "Password incorrect" : "Email not verified")));
    InfoMessage->error($message);
    Router->redirect(Router->generate("auth-login"));
}

// Reset possibly existing one-time password
$user->setOneTimePassword(null);
$user->setOneTimePasswordExpiration(null);
User::dao()->save($user);

Logger->tag("Login")->info("User \"{$post["username"]}\" has logged in (User ID {$user->getId()})");
Auth->login($user);
Router->redirect(Router->generate("index"));
