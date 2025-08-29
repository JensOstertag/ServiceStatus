<?php

class MonitoringService {
    private static function dispatch(MonitoringSettings $settings): MonitoringService {
        $monitoringType = $settings->getMonitoringTypeEnum();
        return $monitoringType->getMonitoringService();
    }

    public static function run(MonitoringSettings $settings): MonitoringResult {
        return self::dispatch($settings)::run($settings);
    }

    public static function handleResult(MonitoringSettings $settings, MonitoringResult $result): void {
        $serviceStatus = $result->getServiceStatusEnum();

        // Update incidents
        if($serviceStatus === ServiceStatus::OPERATIONAL) {
            // If service is operational, close all current incidents
            $currentIncidents = Incident::dao()->getObjects([
                "serviceId" => $settings->getServiceId(),
                "until" => null
            ]);
            foreach($currentIncidents as $incident) {
                IncidentService::close($incident);
            }
        } else {
            // If service is not operational, check if there is already an open incident
            $currentIncident = Incident::dao()->getObject([
                "serviceId" => $settings->getServiceId(),
                "serviceStatus" => $serviceStatus->value,
                "until" => null
            ]);
            if(!($currentIncident instanceof Incident)) {
                // No open incident found, create a new one
                IncidentService::open($settings->getService(), $serviceStatus);
            }

            // Close all other open incidents with higher status
            $otherIncidents = Incident::dao()->getObjects([
                "serviceId" => $settings->getServiceId(),
                new \struktal\ORM\DAOFilter(\struktal\ORM\DAOFilterOperator::GREATER_THAN, "serviceStatus", $serviceStatus->value),
                "until" => null
            ]);
            foreach($otherIncidents as $incident) {
                IncidentService::close($incident);
            }
        }
    }
}
