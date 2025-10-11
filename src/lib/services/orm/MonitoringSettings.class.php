<?php

namespace app\services;

class MonitoringSettings extends \struktal\ORM\GenericEntity {
    public ?int $serviceId = null;
    public ?int $monitoringType = null;
    public ?string $endpoint = null;
    public ?string $expectation = null;

    private ?Service $service = null;
    public function getService(): ?Service {
        if($this->service === null) {
            $this->service = Service::dao()->getObject([
                "id" => $this->getServiceId()
            ]);
        }

        return $this->service;
    }

    private ?MonitoringType $monitoringTypeEnum = null;
    public function getMonitoringTypeEnum(): ?MonitoringType {
        if($this->monitoringTypeEnum === null) {
            $this->monitoringTypeEnum = MonitoringType::tryFrom($this->getMonitoringType());
        }

        return $this->monitoringTypeEnum;
    }

    public function getExpectationFromArray(string $key, mixed $default = null): mixed {
        $expectation = $this->getExpectation();
        if($expectation === null) {
            return $default;
        }

        $expectationArray = json_decode($expectation, true);
        return $expectationArray[$key] ?? $default;
    }

    public function getServiceId(): ?int {
        return $this->serviceId;
    }

    public function setServiceId(?int $serviceId): void {
        $this->serviceId = $serviceId;
    }

    public function getMonitoringType(): ?int {
        return $this->monitoringType;
    }

    public function setMonitoringType(?int $monitoringType): void {
        $this->monitoringType = $monitoringType;
    }

    public function getEndpoint(): ?string {
        return $this->endpoint;
    }

    public function setEndpoint(?string $endpoint): void {
        $this->endpoint = $endpoint;
    }

    public function getExpectation(): ?string {
        return $this->expectation;
    }

    public function setExpectation(?string $expectation): void {
        $this->expectation = $expectation;
    }
}
