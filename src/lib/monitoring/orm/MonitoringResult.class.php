<?php

namespace app\monitoring;

use \app\services\Service;
use \app\services\MonitoringSettings;

class MonitoringResult extends \struktal\ORM\GenericEntity {
    public ?int $monitoringSettingsId = null;
    public ?int $status = null;
    public ?float $responseTime = null;
    public ?int $responseCode = null;
    public ?string $additionalInfo = null;

    private ?MonitoringSettings $monitoringSettings = null;
    public function getMonitoringSettings(): ?MonitoringSettings {
        if($this->monitoringSettings === null) {
            $this->monitoringSettings = MonitoringSettings::dao()->getObject([
                "id" => $this->getMonitoringSettingsId()
            ]);
        }

        return $this->monitoringSettings;
    }

    private ?Service $service = null;
    public function getService(): ?Service {
        if($this->service === null) {
            $settings = $this->getMonitoringSettings();
            if($settings !== null) {
                $this->service = $settings->getService();
            }
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

    public function getMonitoringSettingsId(): ?int {
        return $this->monitoringSettingsId;
    }

    public function setMonitoringSettingsId(?int $monitoringSettingsId): void {
        $this->monitoringSettingsId = $monitoringSettingsId;
    }

    public function getStatus(): ?int {
        return $this->status;
    }

    public function setStatus(?int $status): void {
        $this->status = $status;
    }

    public function getResponseTime(): ?float {
        return $this->responseTime;
    }

    public function setResponseTime(?float $responseTime): void {
        $this->responseTime = $responseTime;
    }

    public function getResponseCode(): ?int {
        return $this->responseCode;
    }

    public function setResponseCode(?int $responseCode): void {
        $this->responseCode = $responseCode;
    }

    public function getAdditionalInfo(): ?string {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo(?string $additionalInfo): void {
        $this->additionalInfo = $additionalInfo;
    }
}
