<?php

$user = Auth->enforceLogin(0, Router->generate("auth-login"));

$registrationEnabled = SystemSetting::dao()->get("registrationEnabled") === "true";

echo Blade->run("admin.settings.index", [
    "registrationEnabled" => $registrationEnabled,
]);
