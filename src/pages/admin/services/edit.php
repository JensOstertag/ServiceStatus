<?php

$user = Auth->enforceLogin(0, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "service" => CommonValidators::service(false, [], t("The service that should be edited does not exist."))
    ])
    ->build();
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    Router->redirect(Router->generate("services"));
}

$service = $get["service"];
if($service instanceof Service) {
    $monitoringSettings = MonitoringSettings::dao()->getObjects([
        "serviceId" => $service->getId()
    ]);
    $reindexedMonitoringSettings = [];
    foreach($monitoringSettings as $setting) {
        $reindexedMonitoringSettings[$setting->getMonitoringTypeEnum()->name] = $setting;
    }
}

echo Blade->run("admin.services.edit", [
    "service" => $service ?? null,
    "monitoringSettings" => $reindexedMonitoringSettings ?? []
]);
