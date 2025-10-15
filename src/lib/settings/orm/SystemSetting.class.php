<?php

namespace app\settings;

class SystemSetting extends \struktal\ORM\GenericRecord {
    public mixed $key = null;
    public mixed $value = null;

    public function getKey(): mixed {
        return $this->key;
    }

    public function setKey(mixed $key): void {
        $this->key = $key;
    }

    public function getValue(): mixed {
        return $this->value;
    }

    public function setValue(mixed $value): void {
        $this->value = $value;
    }
}
