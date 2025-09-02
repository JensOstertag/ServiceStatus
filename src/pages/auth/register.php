<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

// Check whether registration is enabled
$registrationEnabled = SystemSetting::dao()->get("registrationEnabled") === "true";
if(!$registrationEnabled) {
    InfoMessage->error(t("Registration is currently disabled."));
    Router->redirect(Router->generate("auth-login"));
}

$data = [];
if(isset($_SESSION["register-username"])) {
    $data["username"] = $_SESSION["register-username"];
    unset($_SESSION["register-username"]);
}
if(isset($_SESSION["register-email"])) {
    $data["email"] = $_SESSION["register-email"];
    unset($_SESSION["register-email"]);
}

echo Blade->run("auth.register", $data);
