<?php

class SystemSettingDAO extends \struktal\ORM\GenericObjectDAO {
    public function get(string $key): ?string {
        $object = $this->getObject(["key" => $key]);
        if($object instanceof SystemSetting) {
            return $object->getValue();
        }

        return null;
    }

    public function set(string $key, string $value): void {
        $object = $this->getObject(["key" => $key]);
        if(!$object instanceof SystemSetting) {
            $object = new SystemSetting();
            $object->setKey($key);
        }
        $object->setValue($value);
        $this->save($object);
    }
}
