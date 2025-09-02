<?php

$user = Auth->enforceLogin(0, Router->generate("auth-login"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "registrationEnabled" => CommonValidators::checkbox(),
    ])
    ->build();

try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    Router->redirect(Router->generate("settings"));
}

$registrationEnabled = $post["registrationEnabled"] === 1;
SystemSetting::dao()->set("registrationEnabled", $registrationEnabled ? "true" : "false");

InfoMessage->success(t("The settings have been saved."));
Router->redirect(Router->generate("settings"));
