<?php

class Status extends GenericObject {
    public ?string $serviceId = null;
    public bool $latest = false;
    public ?DateTime $invalidatedAt = null;
    public ?int $outageType = null;
    public ?int $duration = null;
    public ?int $responseCode = null;
    public ?string $incidentId = null;

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

    public function getInvalidatedAt(): ?DateTime {
        return $this->invalidatedAt;
    }

    public function setInvalidatedAt(?DateTime $invalidatedAt): void {
        $this->invalidatedAt = $invalidatedAt;
    }

    public function getOutageType(): ?int {
        return $this->outageType;
    }

    public function setOutageType(?int $outageType): void {
        $this->outageType = $outageType;
    }

    public function getDuration(): ?int {
        return $this->duration;
    }

    public function setDuration(?int $duration): void {
        $this->duration = $duration;
    }

    public function getResponseCode(): ?int {
        return $this->responseCode;
    }

    public function setResponseCode(?int $responseCode): void {
        $this->responseCode = $responseCode;
    }

    public function getIncidentId(): ?string {
        return $this->incidentId;
    }

    public function setIncidentId(?string $incidentId): void {
        $this->incidentId = $incidentId;
    }
}
