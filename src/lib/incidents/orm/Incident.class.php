<?php

namespace app\incidents;

use \app\services\Service;
use \app\monitoring\ServiceStatus;

class Incident extends \struktal\ORM\GenericEntity {
    public ?int $serviceId = null;
    public ?int $status = null;
    public ?\DateTimeImmutable $from = null;
    public ?\DateTimeImmutable $until = null;

    private ?Service $service = null;
    public function getService(): ?Service {
        if($this->service === null) {
            $this->service = Service::dao()->getObject([
                "id" => $this->getServiceId()
            ]);
        }

        return $this->service;
    }

    private ?ServiceStatus $serviceStatusEnum = null;
    public function getServiceStatusEnum(): ?ServiceStatus {
        if($this->serviceStatusEnum === null) {
            $this->serviceStatusEnum = ServiceStatus::tryFrom($this->getStatus());
        }

        return $this->serviceStatusEnum;
    }

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

    public function getFrom(): ?\DateTimeImmutable {
        return $this->from;
    }

    public function setFrom(?\DateTimeImmutable $from): void {
        $this->from = $from;
    }

    public function getUntil(): ?\DateTimeImmutable {
        return $this->until;
    }

    public function setUntil(?\DateTimeImmutable $until): void {
        $this->until = $until;
    }
}
