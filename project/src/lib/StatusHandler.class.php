<?php

class StatusHandler {
    private function getStatus(Service $service): array {
        $curl = new jensostertag\Curl\Curl();
        $curl->setUrl($service->getUrl());
        $curl->setMethod(jensostertag\Curl\Curl::$METHOD_GET);
        $curl->setHeaders([
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3"
        ]);

        $time = microtime(true);
        $curl->execute();
        $duration = (microtime(true) - $time) * 1000;

        $status = ServiceStatus::OPERATIONAL;
        $incident = null;
        if($duration >= $service->getFullOutageThreshold()) {
            $status = ServiceStatus::FULL_OUTAGE;
            $incident = "Extremely high response time";
        } else if($duration >= $service->getPartialOutageThreshold()) {
            $status = ServiceStatus::PARTIAL_OUTAGE;
            $incident = "Higher response time";
        }

        $responseCode = $curl->getHttpCode();
        if($responseCode !== $service->getExpectedResponseCode()) {
            $status = ServiceStatus::PARTIAL_OUTAGE;

            if($responseCode <= 0 || ($responseCode >= 400 && $responseCode <= 599)) {
                $status = ServiceStatus::FULL_OUTAGE;
                if($responseCode <= 0) {
                    $incident = "Service is not responding";
                } else {
                    $incident = "Internal error occurred";
                }
            }
        }

        return [
            "status" => $status,
            "duration" => $duration,
            "responseCode" => $responseCode,
            "incident" => $incident
        ];
    }

    public function updateStatus(Service $service): void {
        $status = $this->getStatus($service);

        $latestStatusObject = Status::dao()->getObject([
            "serviceId" => $service->getId(),
            "latest" => true
        ]);

        $statusObject = new Status();
        if(!($latestStatusObject instanceof Status)) {
            // No latest status object was found, it's being created
            $statusObject->setServiceId($service->getId());
            $statusObject->setLatest(true);
        } else {
            if($latestStatusObject->getOutageType() !== $status["status"]->value) {
                // The status has changed since the last check, invalidate the last status object and create a new one
                $latestStatusObject->setLatest(false);
                $latestStatusObject->setInvalidatedAt(DateFormatter::technicalDateTime(new DateTime()));
                Status::dao()->save($latestStatusObject);

                $statusObject->setServiceId($service->getId());
                $statusObject->setLatest(true);
            } else {
                // The status hasn't changed since the last check
                $statusObject = $latestStatusObject;
            }
        }

        $incident = null;
        if($status["incident"] !== null) {
            $incident = new Incident();
            $incident->setName($status["incident"]);
            $incident->setDate(DateFormatter::technicalDate(new DateTime()));
            Incident::dao()->save($incident);
        }

        $statusObject->setOutageType($status["status"]->value);
        $statusObject->setRtt($status["duration"]);
        $statusObject->setResponseCode($status["responseCode"]);
        $statusObject->setUpdated(new DateTime());
        if($incident !== null) {
            $statusObject->setIncidentId($incident->getId());
        }
        Status::dao()->save($statusObject);
    }
}
