<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("auth-login"));

$registrationEnabled = \app\settings\SystemSetting::dao()->get("registrationEnabled") === "true";

echo Blade->run("pages.admin.settings.index", [
    "registrationEnabled" => $registrationEnabled,
]);
