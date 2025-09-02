<?php

$visibleServices = Service::dao()->getObjects([
    "visible" => true
], "order");

$currentStatuses = [];
$incidents = [];
foreach($visibleServices as $service) {
    $serviceIncidents = ReportService::getIncidentData($service);
    $incidents[$service->getId()] = $serviceIncidents;

    $currentStatus = ReportService::getCurrentStatus($service);
    $currentStatuses[$service->getId()] = $currentStatus;
}

echo Blade->run("dependencies");
