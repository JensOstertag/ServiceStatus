<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

echo Blade->run("auth.message", [
    "messages" => [
        t("Your email address has been verified. You can now log in to your account.")
    ],
    "showLogin" => true
]);
