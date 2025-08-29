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
}
