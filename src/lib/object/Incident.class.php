<?php

class Incident extends \struktal\ORM\GenericObject {
    public ?int $serviceId = null;
    public ?int $status = null;
    public ?DateTime $from = null;
    public ?DateTime $until = null;

    public function getServiceId(): ?int {
        return $this->serviceId;
    }

    public function setServiceId(?int $serviceId): void {
        $this->serviceId = $serviceId;
    }

    public function getStatus(): ?int {
        return $this->status;
    }

    public function setStatus(?int $status): void {
        $this->status = $status;
    }

    public function getFrom(): ?DateTime {
        return $this->from;
    }

    public function setFrom(?DateTime $from): void {
        $this->from = $from;
    }

    public function getUntil(): ?DateTime {
        return $this->until;
    }

    public function setUntil(?DateTime $until): void {
        $this->until = $until;
    }
}
