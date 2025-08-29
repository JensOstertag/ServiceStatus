<?php

$user = Auth->enforceLogin(0, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "service" => CommonValidators::service(false, [], t("The service that should be edited does not exist.")),
        "name" => Validation->create()
            ->string()
            ->minLength(1)
            ->maxLength(256)
            ->build(),
        "visible" => CommonValidators::checkbox(),
        "order" => Validation->create()
            ->int()
            ->build(),
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    if(isset($_POST["service"]) && is_numeric($_POST["service"])) {
        Router->redirect(Router->generate("services-edit", ["service" => intval($_POST["service"])]));
    } else {
        Router->redirect(Router->generate("services-create"));
    }
}

$service = new Service();
if(isset($post["service"])) {
    $service = $post["service"];
}

$service->setName($post["name"]);
$serviceSlug = Service::slugify($post["name"]);
$service->setSlug($serviceSlug);
$service->setVisible($post["visible"] === 1);
$service->setOrder($post["order"]);
Service::dao()->save($service);

Logger->tag("Services")->info("User {$user->getId()} ({$user->getUsername()}) saved the service {$service->getId()} ({$service->getName()})");

InfoMessage->success(t("The service has been saved."));
Router->redirect(Router->generate("services"));
