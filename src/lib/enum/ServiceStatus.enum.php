<?php

enum ServiceStatus: int {
    case OPERATIONAL = 0;
    case HIGH_RESPONSE_TIME = 100;
    case INTERNAL_ERROR = 200;
    case NOT_RESPONDING = 300;

    public function getDescription(): string {
        return match($this) {
            ServiceStatus::OPERATIONAL => t("The service is operating within normal parameters"),
            ServiceStatus::HIGH_RESPONSE_TIME => t("The service is experiencing high response times"),
            ServiceStatus::INTERNAL_ERROR => t("An internal error has occurred"),
            ServiceStatus::NOT_RESPONDING => t("Service is not responding"),
        };
    }
}
