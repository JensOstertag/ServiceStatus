<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

echo Blade->run("pages.auth.message", [
    "messages" => [
        t("You have requested a password recovery."),
        t("Shortly, you will receive an email with a link. Please open that link to set a new password.")
    ],
    "showLogin" => false
]);
