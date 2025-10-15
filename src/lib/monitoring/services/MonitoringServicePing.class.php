<?php

namespace app\monitoring;

use \app\services\MonitoringSettings;

class MonitoringServicePing extends MonitoringService {
    public static function run(MonitoringSettings $settings): MonitoringResult {
        $host = self::parseEndpoint($settings->getEndpoint());

        // Execute ping command
        $command = "ping -4 -c 1 $host";
        exec($command, $output, $status);

        // Check if ping was successful
        if($status !== 0) {
            $result = new MonitoringResult();
            $result->setMonitoringSettingsId($settings->getId());
            $result->setStatus(ServiceStatus::NOT_RESPONDING->value);
            $result->setResponseTime(null);
            $result->setResponseCode(null);
            MonitoringResult::dao()->save($result);
            return $result;
        }

        // Parse output to get response time
        $responseTime = null;
        foreach($output as $line) {
            if(preg_match("/time=([\d\.]+)\s*ms/", $line, $matches)) {
                $responseTime = (float) $matches[1];
                break;
            }
        }

        $serviceStatus = self::validateServiceStatus($settings, $responseTime);

        // Create new response object
        $result = new MonitoringResult();
        $result->setMonitoringSettingsId($settings->getId());
        $result->setStatus($serviceStatus->value);
        $result->setResponseTime($responseTime);
        $result->setResponseCode(null);
        MonitoringResult::dao()->save($result);

        return $result;
    }

    private static function validateServiceStatus(MonitoringSettings $monitoringSettings, float $responseTime): ServiceStatus {
        $expectation = json_decode($monitoringSettings->getExpectation(), true);

        // Check response time thresholds
        if($responseTime > $expectation["maxResponseTime"]) {
            return ServiceStatus::HIGH_RESPONSE_TIME;
        }

        return ServiceStatus::OPERATIONAL;
    }

    private static function parseEndpoint(string $endpoint): string {
        // Check if the endpoint is a hostname or IP address
        if(self::isIP($endpoint)) {
            return escapeshellarg($endpoint);
        }

        // IDN conversion
        if(function_exists("idn_to_ascii")) {
            $idn = idn_to_ascii(
                $endpoint,
                IDNA_DEFAULT,
                INTL_IDNA_VARIANT_UTS46
            );

            $endpoint = $idn !== false ? $idn : $endpoint;
        }

        return escapeshellarg($endpoint);
    }

    private static function isIP(string $endpoint): bool {
        if(filter_var($endpoint, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return true;
        }

        if(filter_var($endpoint, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return true;
        }

        return false;
    }
}
