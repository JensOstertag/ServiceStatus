<?php

$validation = Validation->create()
    ->array()
    ->required()
    ->children([
        "slug" => Validation->create()
            ->string()
            ->minLength(1)
            ->maxLength(256)
            ->build()
    ])
    ->build();

try {
    $get = $validation->getValidatedValue($_GET);
} catch(\struktal\Validation\ValidationException $e) {
    Router->redirect(Router->generate("index"));
}

$service = Service::dao()->getObject([
    "slug" => $get["slug"]
]);

if(!$service instanceof Service) {
    Router->redirect(Router->generate("404"));
}

$currentStatus = ReportService::getCurrentStatus($service);

$uptime = ReportService::getUptime($service);

$monitoringSettings = MonitoringSettings::dao()->getObjects([
    "serviceId" => $service->getId()
]);
$enabledMonitoringTypes = [];
foreach($monitoringSettings as $monitoringSetting) {
    $enabledMonitoringTypes[] = $monitoringSetting->getMonitoringTypeEnum();
}

$reports = [];
foreach(MonitoringType::cases() as $monitoringType) {
    $reports[$monitoringType->value] = ReportService::getReportData($service, $monitoringType);
}

echo Blade->run("pages.services.index", [
    "service" => $service,
    "currentStatus" => $currentStatus,
    "uptime" => $uptime,
    "enabledMonitoringTypes" => $enabledMonitoringTypes,
    "reports" => $reports
]);
