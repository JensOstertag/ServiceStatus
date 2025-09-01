<?php

$visibleServices = Service::dao()->getObjects([
    "visible" => true
]);

$currentStatuses = [];
$incidents = [];
foreach($visibleServices as $service) {
    $serviceIncidents = ReportService::getIncidentData($service);
    $incidents[$service->getId()] = $serviceIncidents;

    $currentStatus = ReportService::getCurrentStatus($service);
    $currentStatuses[$service->getId()] = $currentStatus;
}

echo Blade->run("index", [
    "services" => $visibleServices,
    "incidents" => $incidents,
    "currentStatuses" => $currentStatuses
]);
