<?php

// Project directory: The directory where the project is located in the filesystem
chdir("../");
const __APP_DIR__ = __DIR__ . "/..";

require_once(__APP_DIR__ . "/framework/framework.php");

session_start();

// Start the Router
$router = new Router();
$router->startRouter();
