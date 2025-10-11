<?php

namespace app\settings;

class SettingsService {
    public static function registrationAllowed(): bool {
        $registrationEnabled = SystemSetting::dao()->get("registrationEnabled") === "true";
        return $registrationEnabled;
    }
}
