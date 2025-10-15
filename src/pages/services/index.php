<?php

$get = Validation->create()
    ->array()
    ->required()
    ->children([
        "slug" => Validation->create()
            ->string()
            ->minLength(1)
            ->maxLength(256)
            ->build()
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        Router->redirect(Router->generate("index"));
    });

$service = \app\services\Service::dao()->getObject([
    "slug" => $get["slug"]
]);

if(!$service instanceof \app\services\Service) {
    Router->redirect(Router->generate("404"));
}

$currentStatus = \app\monitoring\ReportService::getCurrentStatus($service);

$uptime = \app\monitoring\ReportService::getUptime($service);

$monitoringSettings = \app\services\MonitoringSettings::dao()->getObjects([
    "serviceId" => $service->getId()
]);
$enabledMonitoringTypes = [];
foreach($monitoringSettings as $monitoringSetting) {
    $enabledMonitoringTypes[] = $monitoringSetting->getMonitoringTypeEnum();
}

$reports = [];
foreach(\app\monitoring\MonitoringType::cases() as $monitoringType) {
    $reports[$monitoringType->value] = \app\monitoring\ReportService::getReportData($service, $monitoringType);
}

echo Blade->run("pages.services.index", [
    "service" => $service,
    "currentStatus" => $currentStatus,
    "uptime" => $uptime,
    "enabledMonitoringTypes" => $enabledMonitoringTypes,
    "reports" => $reports
]);
