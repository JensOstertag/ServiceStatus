<?php

class ReportService {
    private static int $DAYS = 60;

    public static function getReportData(Service $service, MonitoringType $monitoringType): array {
        $monitoringSettings = MonitoringSettings::dao()->getObject([
            "serviceId" => $service->getId(),
            "monitoringType" => $monitoringType->value
        ]);
        if(!$monitoringSettings instanceof MonitoringSettings) {
            return [];
        }

        $end = new DateTimeImmutable();
        $todayMidnight = $end->setTime(0, 0, 0, 0);
        $start = $todayMidnight->sub(new DateInterval("P" . self::$DAYS . "D"));

        $rawData = MonitoringResult::dao()->getObjects([
            "monitoringSettingsId" => $monitoringSettings->getId(),
            new \struktal\ORM\DAOFilter(\struktal\ORM\DAOFilterOperator::GREATER_THAN_EQUALS, "created", $start),
            new \struktal\ORM\DAOFilter(\struktal\ORM\DAOFilterOperator::LESS_THAN_EQUALS, "created", $end)
        ], "created");

        $data = [];
        $carryDatetime = $start;
        while($carryDatetime <= $todayMidnight) {
            $data[$carryDatetime->format("Y-m-d")] = [];
            $carryDatetime = $carryDatetime->add(new DateInterval("P1D"));
        }

        foreach($rawData as $monitoringResult) {
            $date = $monitoringResult->getCreated()->format("Y-m-d");
            if(!isset($data[$date])) {
                continue;
            }

            $data[$date][] = $monitoringResult;
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
}
