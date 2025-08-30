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

$uptime = ReportService::getUptime($service);

$httpReports = ReportService::getReportData($service, MonitoringType::HTTP);
$pingReports = ReportService::getReportData($service, MonitoringType::PING);

echo Blade->run("services.index", [
    "service" => $service,
    "uptime" => $uptime,
    "httpReports" => $httpReports,
    "pingReports" => $pingReports
]);
