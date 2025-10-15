<?php

$services = \app\services\ServiceService::getServicesOverview();

$currentStatuses = [];
$incidents = [];
foreach($services as $service) {
    $serviceIncidents = \app\monitoring\ReportService::getIncidentData($service);
    $incidents[$service->getId()] = $serviceIncidents;

    $currentStatus = \app\monitoring\ReportService::getCurrentStatus($service);
    $currentStatuses[$service->getId()] = $currentStatus;
}

echo Blade->run("pages.index", [
    "services" => $services,
    "incidents" => $incidents,
    "currentStatuses" => $currentStatuses
]);
