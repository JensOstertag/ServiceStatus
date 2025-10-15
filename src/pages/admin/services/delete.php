<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("auth-login"));

$get = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "service" => CommonValidators::service(true, [], t("The service that should be deleted does not exist."))
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("services"));
    });

$service = $get["service"];

$service->preDelete();
\app\services\Service::dao()->delete($service);

Logger->tag("Services")->info("User {$user->getId()} ({$user->getUsername()}) deleted the service {$service->getId()} ({$service->getName()})");

InfoMessage->success(t("The service has been deleted."));
Router->redirect(Router->generate("services"));
