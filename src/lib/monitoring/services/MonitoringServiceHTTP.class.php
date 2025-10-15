<?php

namespace app\monitoring;

use \app\services\MonitoringSettings;

class MonitoringServiceHTTP extends MonitoringService {
    public static function run(MonitoringSettings $settings): MonitoringResult {
        // Instantiate curl handle
        $curl = new \struktal\Curl\Curl();
        $curl->setUrl($settings->getEndpoint());
        $curl->setMethod(\struktal\Curl\Curl::$METHOD_GET);
        $curl->setHeaders([
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3"
        ]);

        // Execute request and measure time
        $time = microtime(true);
        $curl->execute();
        $duration = (microtime(true) - $time) * 1000;

        $serviceStatus = self::validateServiceStatus($settings, $duration, $curl->getHttpCode());

        // Create new response object
        $result = new MonitoringResult();
        $result->setMonitoringSettingsId($settings->getId());
        $result->setStatus($serviceStatus->value);
        $result->setResponseTime($duration);
        $result->setResponseCode($curl->getHttpCode());
        MonitoringResult::dao()->save($result);

        return $result;
    }

    private static function validateServiceStatus(MonitoringSettings $monitoringSettings, float $responseTime, int $httpCode): ServiceStatus {
        $expectation = json_decode($monitoringSettings->getExpectation(), true);

        // Check non-responding
        if($httpCode <= 0) {
            return ServiceStatus::NOT_RESPONDING;
        }

        // Check unexpected response code
        if($httpCode !== $expectation["expectedResponseCode"]) {
            return ServiceStatus::INTERNAL_ERROR;
        }

        // Check response time thresholds
        if($responseTime > $expectation["maxResponseTime"]) {
            return ServiceStatus::HIGH_RESPONSE_TIME;
        }

        return ServiceStatus::OPERATIONAL;
    }
}
