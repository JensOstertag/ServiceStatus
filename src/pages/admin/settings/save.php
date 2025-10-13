<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("auth-login"));

$post = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "registrationEnabled" => CommonValidators::checkbox(),
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("settings"));
    });

$registrationEnabled = $post["registrationEnabled"] === 1;
\app\settings\SystemSetting::dao()->set("registrationEnabled", $registrationEnabled ? "true" : "false");

InfoMessage->success(t("The settings have been saved."));
Router->redirect(Router->generate("settings"));
