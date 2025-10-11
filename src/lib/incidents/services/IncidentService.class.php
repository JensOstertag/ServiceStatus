<?php

namespace app\incidents;

class IncidentService {
    public static function open(Service $service, ServiceStatus $status): Incident {
        $incident = new Incident();
        $incident->setServiceId($service->getId());
        $incident->setStatus($status->value);
        $incident->setFrom(new DateTime());
        Incident::dao()->save($incident);

        // TODO: Send notifications

        return $incident;
    }

    public static function close(Incident $incident): void {
        $incident->setUntil(new DateTime());
        Incident::dao()->save($incident);

        // TODO: Send notifications
    }
}
