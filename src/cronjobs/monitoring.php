<?php

require __DIR__ . "/.cronjob-setup.php";

Logger->tag("Monitoring")->info("Starting monitoring cronjob");

$monitoringSettings = MonitoringSettings::dao()->getObjects();

foreach($monitoringSettings as $monitoringSetting) {
    Logger->tag("Monitoring")->trace("Running monitoring for service ID " . $monitoringSetting->getServiceId() . " with type " . $monitoringSetting->getMonitoringTypeEnum()->name);
    $monitoringResult = MonitoringService::run($monitoringSetting);
    MonitoringService::handleResult($monitoringSetting, $monitoringResult);
    Logger->tag("Monitoring")->trace("Finished monitoring for service ID " . $monitoringSetting->getServiceId());
    usleep(500000); // Sleep for 0.5 seconds to avoid overloading the system
}

Logger->tag("Monitoring")->info("Finished monitoring cronjob");
