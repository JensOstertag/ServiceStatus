<?php

namespace app\incidents;

use \app\services\Service;
use \app\monitoring\ServiceStatus;

class IncidentService {
    public static function open(Service $service, ServiceStatus $status): Incident {
        $incident = new Incident();
        $incident->setServiceId($service->getId());
        $incident->setStatus($status->value);
        $incident->setFrom(new \DateTimeImmutable());
        Incident::dao()->save($incident);

        // TODO: Send notifications

        return $incident;
    }

    public static function close(Incident $incident): void {
        $incident->setUntil(new \DateTimeImmutable());
        Incident::dao()->save($incident);

        // TODO: Send notifications
    }
}
