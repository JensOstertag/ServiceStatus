<?php

$visibleServices = Service::dao()->getObjects([
    "visible" => true
]);

$incidents = [];
foreach($visibleServices as $service) {
    $serviceIncidents = ReportService::getIncidentData($service);
    $incidents[$service->getId()] = $serviceIncidents;
}

echo Blade->run("index", [
    "services" => $visibleServices,
    "incidents" => $incidents
]);
