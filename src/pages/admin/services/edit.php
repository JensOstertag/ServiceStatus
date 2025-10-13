<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("auth-login"));

$get = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "service" => CommonValidators::service(false, [], t("The service that should be edited does not exist."))
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("services"));
    });

$service = $get["service"];
if($service instanceof \app\services\Service) {
    $monitoringSettings = \app\services\MonitoringSettings::dao()->getObjects([
        "serviceId" => $service->getId()
    ]);
    $reindexedMonitoringSettings = [];
    foreach($monitoringSettings as $setting) {
        $reindexedMonitoringSettings[$setting->getMonitoringTypeEnum()->value] = $setting;
    }
}

echo Blade->run("pages.admin.services.edit", [
    "service" => $service ?? null,
    "monitoringSettings" => $reindexedMonitoringSettings ?? []
]);
