<?php

require __DIR__ . "/.cronjob-setup.php";

Logger::getLogger("STATUS-UPDATE")->info("Updating service statuses");

$statusHandler = new StatusHandler();
$services = Service::dao()->getObjects();
foreach($services as $service) {
    Logger::getLogger("STATUS-UPDATE")->debug("Checking service status for service {$service->getId()}");
    $statusHandler->updateStatus($service);
    Logger::getLogger("STATUS-UPDATE")->debug("Service status for service {$service->getId()} updated");
}

Logger::getLogger("STATUS-UPDATE")->info("Service statuses updated");
