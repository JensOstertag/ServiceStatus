<?php

namespace app\monitoring;

use \app\services\Service;
use \app\services\MonitoringSettings;
use \app\incidents\Incident;

class ReportService {
    public static int $DAYS = 60;

    private static array $reportDataCache = [];
    public static function getReportData(Service $service, MonitoringType $monitoringType): array {
        $cacheKey = $service->getId() . "_" . $monitoringType->value;
        if(isset(self::$reportDataCache[$cacheKey])) {
            return self::$reportDataCache[$cacheKey];
        }

        $monitoringSettings = MonitoringSettings::dao()->getObject([
            "serviceId" => $service->getId(),
            "monitoringType" => $monitoringType->value
        ]);
        if(!$monitoringSettings instanceof MonitoringSettings) {
            return [];
        }

        $end = new \DateTimeImmutable();
        $todayMidnight = $end->setTime(0, 0, 0, 0);
        $start = $todayMidnight->sub(new \DateInterval("P" . (self::$DAYS - 1) . "D"));

        $rawData = MonitoringResult::dao()->getObjects([
            "monitoringSettingsId" => $monitoringSettings->getId(),
            new \struktal\ORM\DAOFilter(\struktal\ORM\DAOFilterOperator::GREATER_THAN_EQUALS, "created", $start),
            new \struktal\ORM\DAOFilter(\struktal\ORM\DAOFilterOperator::LESS_THAN_EQUALS, "created", $end)
        ], "created");

        $data = [];
        $carryDatetime = $start;
        while($carryDatetime <= $todayMidnight) {
            $data[$carryDatetime->format("Y-m-d")] = [];
            $carryDatetime = $carryDatetime->add(new \DateInterval("P1D"));
        }

        foreach($rawData as $monitoringResult) {
            $date = $monitoringResult->getCreated()->format("Y-m-d");
            if(!isset($data[$date])) {
                continue;
            }

            $data[$date][] = $monitoringResult;
        }

        self::$reportDataCache[$cacheKey] = $data;

        return $data;
    }

    public static function getIncidentData(Service $service): array {
        $end = new \DateTimeImmutable();
        $todayMidnight = $end->setTime(0, 0, 0, 0);
        $start = $todayMidnight->sub(new \DateInterval("P" . (self::$DAYS - 1) . "D"));

        $incidents = Incident::dao()->getObjects([
            "serviceId" => $service->getId(),
            new \struktal\ORM\DAOFilter(\struktal\ORM\DAOFilterOperator::GREATER_THAN_EQUALS, "until", $start),
            new \struktal\ORM\DAOFilter(\struktal\ORM\DAOFilterOperator::LESS_THAN_EQUALS, "from", $end)
        ], "created");

        $data = [];
        $carryDatetime = $start;
        while($carryDatetime <= $todayMidnight) {
            $data[$carryDatetime->format("Y-m-d")] = [];
            $carryDatetime = $carryDatetime->add(new \DateInterval("P1D"));
        }

        /** @var Incident $incident */
        foreach($incidents as $incident) {
            $start = $incident->getFrom();
            $end = new \DateTimeImmutable();
            if($incident->getUntil() !== null) {
                $end = $incident->getUntil();
            }
            $carryDatetime = $start->setTime(0, 0, 0);

            while($carryDatetime <= $end) {
                $date = $carryDatetime->format("Y-m-d");
                if(isset($data[$date])) {
                    $data[$date][] = $incident;
                }

                $carryDatetime = $carryDatetime->add(new \DateInterval("P1D"));
            }
        }

        return $data;
    }

    public static function getHighestStatus(array $monitoringResults): ?ServiceStatus {
        if(count($monitoringResults) === 0) {
            return null;
        }

        $highestStatus = ServiceStatus::OPERATIONAL;
        foreach($monitoringResults as $monitoringResult) {
            if($monitoringResult->getServiceStatusEnum()->value > $highestStatus->value) {
                $highestStatus = $monitoringResult->getServiceStatusEnum();
            }
        }

        return $highestStatus;
    }

    public static function getHighestIncidentStatus(array $incidentData): ?ServiceStatus {
        if(count($incidentData) === 0) {
            return null;
        }

        $highestStatus = ServiceStatus::OPERATIONAL;
        foreach($incidentData as $incident) {
            if($incident->getServiceStatusEnum()->value > $highestStatus->value) {
                $highestStatus = $incident->getServiceStatusEnum();
            }
        }

        return $highestStatus;
    }

    public static function getUptime(Service $service, ?MonitoringType $monitoringType = null): float {
        $monitoringSettingsFilter = [
            "serviceId" => $service->getId()
        ];
        if($monitoringType !== null) {
            $monitoringSettingsFilter["monitoringType"] = $monitoringType->value;
        }

        $monitoringSettings = MonitoringSettings::dao()->getObjects($monitoringSettingsFilter);
        if(count($monitoringSettings) === 0) {
            return 1.0;
        }

        $successful = 0;
        $total = 0;

        foreach($monitoringSettings as $monitoringSetting) {
            $reportData = self::getReportData($service, $monitoringSetting->getMonitoringTypeEnum());
            foreach($reportData as $date => $dayData) {
                foreach($dayData as $monitoringResult) {
                    if($monitoringResult->getServiceStatusEnum() === ServiceStatus::OPERATIONAL) {
                        $successful++;
                    }
                    $total++;
                }
            }
        }

        if($total === 0) {
            return 1.0;
        }

        return $successful / $total;
    }

    public static function getCurrentStatus(Service $service): ServiceStatus {
        $currentIncidents = Incident::dao()->getObjects([
            "serviceId" => $service->getId(),
            "until" => null
        ]);
        return self::getHighestIncidentStatus($currentIncidents) ?? ServiceStatus::OPERATIONAL;
    }
}
