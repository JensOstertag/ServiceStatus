<?php

$user = Auth->enforceLogin(0, Router->generate("auth-login"));

$services = Service::dao()->getObjects([], "order");

$services = array_map(function(Service $service) {
    $array = $service->toArray();
    $array["editHref"] = Router->generate("services-edit", ["service" => $service->getId()]);
    unset($array["id"]);
    unset($array["created"]);
    unset($array["updated"]);
    return $array;
}, $services);

\struktal\API\API::sendJson($services);
