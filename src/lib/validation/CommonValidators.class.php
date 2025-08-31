<?php

use \struktal\validation\internals\Validator;

class CommonValidators {
    public static function service(bool $required = true, array $additionalFilters = [], ?string $errorMessage = null): Validator {
        $validation = Validation->create();
        if($errorMessage !== null) {
            $validation->withErrorMessage($errorMessage);
        }
        $validation->inDatabase(Service::dao(), $additionalFilters);
        if($required) {
            $validation->required();
        }

        return $validation->build();
    }

    public static function checkbox(): Validator {
        return Validation->create()
            ->int(false)
            ->build();
    }

    public static function httpMonitoringSettings(bool $enabled = false): Validator {
        $validation = Validation->create()
            ->array();
        if($enabled) {
            $validation->required();
        }

        $validation->children([
            "enabled" => CommonValidators::checkbox(),
            "endpoint" => Validation->create()
                ->string($enabled)
                ->minLength(1)
                ->maxLength(256)
                ->build(),
            "expectedResponseCode" => Validation->create()
                ->int($enabled)
                ->build(),
            "maxResponseTime" => Validation->create()
                ->int($enabled)
                ->minValue(0)
                ->build()
        ]);

        return $validation->build();
    }

    public static function pingMonitoringSettings(bool $enabled = false): Validator {
        $validation = Validation->create()
            ->array();
        if($enabled) {
            $validation->required();
        }

        $validation->children([
            "enabled" => CommonValidators::checkbox(),
            "endpoint" => Validation->create()
                ->string($enabled)
                ->minLength(1)
                ->maxLength(256)
                ->build(),
            "maxResponseTime" => Validation->create()
                ->int($enabled)
                ->minValue(0)
                ->build()
        ]);

        return $validation->build();
    }
}
