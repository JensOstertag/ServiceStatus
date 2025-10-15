<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("auth-login"));

$post = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "service" => CommonValidators::service(false, [], t("The service that should be edited does not exist.")),
        "name" => Validation->create()
            ->string()
            ->minLength(1)
            ->maxLength(256)
            ->build(),
        "visible" => CommonValidators::checkbox(),
        "order" => Validation->create()
            ->int()
            ->build()
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        if(isset($_POST["service"]) && is_numeric($_POST["service"])) {
            Router->redirect(Router->generate("services-edit", ["service" => intval($_POST["service"])]));
        } else {
            Router->redirect(Router->generate("services-create"));
        }
    });

$service = new \app\services\Service();
if(isset($post["service"])) {
    $service = $post["service"];
}

$service->setName($post["name"]);
$serviceSlug = \app\services\Service::slugify($post["name"]);
$service->setSlug($serviceSlug);
$service->setVisible($post["visible"] === 1);
$service->setOrder($post["order"]);
\app\services\Service::dao()->save($service);

$monitoringSettings = $_POST["monitoringSettings"] ?? [];
foreach(\app\monitoring\MonitoringType::cases() as $monitoringType) {
    if(!isset($monitoringSettings[$monitoringType->name]) || !isset($monitoringSettings[$monitoringType->name]["enabled"]) || $monitoringSettings[$monitoringType->name]["enabled"] != 1) {
        // Monitoring type not set, delete existing settings
        $monitoringSetting = \app\services\MonitoringSettings::dao()->getObject([
            "serviceId" => $service->getId(),
            "monitoringType" => $monitoringType->value
        ]);
        if($monitoringSetting instanceof \app\services\MonitoringSettings) {
            \app\services\MonitoringSettings::dao()->delete($monitoringSetting);
        }

        continue;
    }

    $validator = $monitoringType->getValidator(true);
    try {
        $validatedSettings = $validator->getValidatedValue($monitoringSettings[$monitoringType->name]);
    } catch(\struktal\validation\ValidationException $e) {
        InfoMessage->error(t("Could not save the \$\$monitoringType\$\$ monitoring settings. Please fill out all the required fields.", [
            "monitoringType" => $monitoringType->name,
        ]));
        continue;
    }

    $monitoringSetting = \app\services\MonitoringSettings::dao()->getObject([
        "serviceId" => $service->getId(),
        "monitoringType" => $monitoringType->value
    ]);
    if(!($monitoringSetting instanceof \app\services\MonitoringSettings)) {
        $monitoringSetting = new \app\services\MonitoringSettings();
        $monitoringSetting->setServiceId($service->getId());
        $monitoringSetting->setMonitoringType($monitoringType->value);
    }
    $monitoringSetting->setEndpoint($validatedSettings["endpoint"]);
    $expectation = $monitoringType->parseExpectation($validatedSettings);
    $monitoringSetting->setExpectation($expectation);
    \app\services\MonitoringSettings::dao()->save($monitoringSetting);
}

Logger->tag("Services")->info("User {$user->getId()} ({$user->getUsername()}) saved the service {$service->getId()} ({$service->getName()})");

InfoMessage->success(t("The service has been saved."));
Router->redirect(Router->generate("services"));
