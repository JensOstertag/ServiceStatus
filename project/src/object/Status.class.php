<?php

class Status extends GenericObject {
    public ?string $serviceId = null;
    public bool $latest = false;
    public ?string $invalidatedAt = null;
    public ?int $outageType = null;
    public ?int $rtt = null;
    public ?int $responseCode = null;
    public ?int $incidentId = null;

    private ?Incident $incident = null;

    public function getServiceId(): ?string {
        return $this->serviceId;
    }

    public function setServiceId(?string $serviceId): void {
        $this->serviceId = $serviceId;
    }

    public function isLatest(): bool {
        return $this->latest;
    }

    public function setLatest(bool $latest): void {
        $this->latest = $latest;
    }

    public function getInvalidatedAt(): ?string {
        return $this->invalidatedAt;
    }

    public function setInvalidatedAt(?string $invalidatedAt): void {
        $this->invalidatedAt = $invalidatedAt;
    }

    public function getOutageType(): ?int {
        return $this->outageType;
    }

    public function setOutageType(?int $outageType): void {
        $this->outageType = $outageType;
    }

    public function getRtt(): ?int {
        return $this->rtt;
    }

    public function setRtt(?int $rtt): void {
        $this->rtt = $rtt;
    }

    public function getResponseCode(): ?int {
        return $this->responseCode;
    }

    public function setResponseCode(?int $responseCode): void {
        $this->responseCode = $responseCode;
    }

    public function getIncidentId(): ?int {
        return $this->incidentId;
    }

    public function setIncidentId(?int $incidentId): void {
        $this->incidentId = $incidentId;
    }

    public function getStartDate(?DateTime $day = null): DateTime {
        if($day === null) {
            return $this->getCreated();
        }

        $createdMidnight = clone $this->getCreated();
        $createdMidnight->modify("midnight");
        $dayMidnight = clone $day;
        $dayMidnight->modify("midnight");
        if($createdMidnight == $dayMidnight) {
            return $this->getCreated();
        } else {
            return $dayMidnight;
        }
    }

    public function getEndDate(?DateTime $day = null): ?DateTime {
        if($this->getInvalidatedAt() === null) {
            return null;
        }

        $invalidated = DateFormatter::parseTechnicalDateTime($this->getInvalidatedAt());

        if($day === null) {
            return DateFormatter::parseTechnicalDateTime($this->getInvalidatedAt());
        }

        $invalidatedMidnight = clone $invalidated;
        $invalidatedMidnight->modify("midnight");
        $dayMidnight = clone $day;
        $dayMidnight->modify("midnight");
        if($invalidatedMidnight == $dayMidnight) {
            return $invalidated;
        } else {
            return $dayMidnight->modify("23:59:59");
        }
    }

    public function getDuration(?DateTime $day = null): ?string {
        $startDate = $this->getStartDate($day);
        $endDate = $this->getEndDate($day);
        if($endDate === null) {
            return null;
        }
        $timeDiff = $startDate->diff($endDate);

        $minutes = $timeDiff->i;
        $hours = $timeDiff->h;
        $days = $timeDiff->d;
        $months = $timeDiff->m;
        $years = $timeDiff->y;
        if($timeDiff->s >= 30) {
            $minutes++;
        }
        if($minutes >= 60) {
            $hours += floor($minutes / 60);
            $minutes = $minutes % 60;
        }
        if($hours >= 24) {
            $days += floor($hours / 24);
            $hours = $hours % 24;
        }
        if($days >= 30) {
            $months += floor($days / 30);
            $days = $days % 30;
        }
        if($months >= 12) {
            $years += floor($months / 12);
            $months = $months % 12;
        }

        $durationString = $years > 0 ? $years . "y " : "";
        $durationString .= $months > 0 ? $months . "m " : "";
        $durationString .= $days > 0 ? $days . "d " : "";
        $durationString .= $hours > 0 ? $hours . "h " : "";
        $durationString .= $minutes > 0 ? $minutes . "min" : "";

        if($durationString === "") {
            $durationString = "0min";
        }

        return $durationString;
    }

    public function getIncident(): ?Incident {
        if($this->incidentId === null) {
            return null;
        }

        if(!isset($this->incident)) {
            $this->incident = Incident::dao()->getObject(["id" => $this->incidentId]);
        }

        return $this->incident;
    }

    public static function filterDowntimes(array $statuses): array {
        return array_filter($statuses, function($status) {
            return !in_array($status->getOutageType(), [ServiceStatus::OPERATIONAL->value, ServiceStatus::UNKNOWN->value]);
        });
    }
}
