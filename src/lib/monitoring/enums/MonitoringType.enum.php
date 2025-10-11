<?php

namespace app\monitoring;

enum MonitoringType: int {
    case HTTP = 1;
    case PING = 2;

    public function getName(): string {
        return match($this) {
            self::HTTP => t("HTTP monitoring"),
            self::PING => t("Ping monitoring"),
        };
    }

    public function getMonitoringService(): MonitoringService {
        return match($this) {
            self::HTTP => new MonitoringServiceHTTP(),
            self::PING => new MonitoringServicePing(),
        };
    }

    public function getValidator(bool $enabled = false): \struktal\validation\internals\Validator {
        return match($this) {
            self::HTTP => CommonValidators::httpMonitoringSettings($enabled),
            self::PING => CommonValidators::pingMonitoringSettings($enabled),
        };
    }

    public function parseExpectation(array $validatedSettings): string {
        return match($this) {
            self::HTTP => json_encode([
                "expectedResponseCode" => $validatedSettings["expectedResponseCode"],
                "maxResponseTime" => $validatedSettings["maxResponseTime"],
            ]),
            self::PING => json_encode([
                "maxResponseTime" => $validatedSettings["maxResponseTime"],
            ]),
        };
    }
}
