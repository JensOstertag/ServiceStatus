<?php

$user = Auth->enforceLogin(0, Router->generate("index"));

$services = Service::dao()->getObjects();

$services = array_map(function(Service $service) {
    $array = $service->toArray();
    $array["editHref"] = Router->generate("services-edit", ["service" => $service->getId()]);
    unset($array["id"]);
    unset($array["created"]);
    unset($array["updated"]);
    return $array;
}, $services);

\struktal\API\API::sendJson($services);
