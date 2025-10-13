<?php

namespace app\services;

class ServiceService {
    public static function getServicesOverview(bool $onlyVisible = true): array {
        $filters = [];
        if($onlyVisible) {
            $filters["visible"] = true;
        }

        return Service::dao()->getObjects($filters, "order");
    }
}
