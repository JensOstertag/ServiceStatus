<?php

$user = Auth->enforceLogin(0, Router->generate("index"));

$validation = Validation->create()
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
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    if(isset($_POST["service"]) && is_numeric($_POST["service"])) {
        Router->redirect(Router->generate("services-edit", ["service" => intval($_POST["service"])]));
    } else {
        Router->redirect(Router->generate("services-create"));
    }
}

$service = new Service();
if(isset($post["service"])) {
    $service = $post["service"];
}

$service->setName($post["name"]);
$serviceSlug = Service::slugify($post["name"]);
$service->setSlug($serviceSlug);
$service->setVisible($post["visible"] === 1);
$service->setOrder($post["order"]);
Service::dao()->save($service);

$monitoringSettings = $_POST["monitoringSettings"] ?? [];
foreach(MonitoringType::cases() as $monitoringType) {
    if(!isset($monitoringSettings[$monitoringType->name]) || !isset($monitoringSettings[$monitoringType->name]["enabled"]) || $monitoringSettings[$monitoringType->name]["enabled"] != 1) {
        // Monitoring type not set, delete existing settings
        $monitoringSetting = MonitoringSettings::dao()->getObject([
            "serviceId" => $service->getId(),
            "monitoringType" => $monitoringType->value
        ]);
        if($monitoringSetting instanceof MonitoringSettings) {
            MonitoringSettings::dao()->delete($monitoringSetting);
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

    $monitoringSetting = MonitoringSettings::dao()->getObject([
        "serviceId" => $service->getId(),
        "monitoringType" => $monitoringType->value
    ]);
    if(!($monitoringSetting instanceof MonitoringSettings)) {
        $monitoringSetting = new MonitoringSettings();
        $monitoringSetting->setServiceId($service->getId());
        $monitoringSetting->setMonitoringType($monitoringType->value);
    }
    $monitoringSetting->setEndpoint($validatedSettings["endpoint"]);
    $expectation = $monitoringType->parseExpectation($validatedSettings);
    $monitoringSetting->setExpectation($expectation);
    MonitoringSettings::dao()->save($monitoringSetting);
}

Logger->tag("Services")->info("User {$user->getId()} ({$user->getUsername()}) saved the service {$service->getId()} ({$service->getName()})");

InfoMessage->success(t("The service has been saved."));
Router->redirect(Router->generate("services"));
