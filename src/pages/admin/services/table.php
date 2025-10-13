<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("auth-login"));

$services = \app\services\ServiceService::getServicesOverview(false);

$services = array_map(function(\app\services\Service $service) {
    $array = $service->toArray();
    $array["editHref"] = Router->generate("services-edit", ["service" => $service->getId()]);
    unset($array["id"]);
    unset($array["created"]);
    unset($array["updated"]);
    return $array;
}, $services);

\struktal\API\API::sendJson($services);
