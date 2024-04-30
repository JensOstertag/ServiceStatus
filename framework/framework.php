<?php

// Autoload Composer libraries
require_once(__APP_DIR__ . "/project/src/lib/vendor/autoload.php");

// Setup Composer libraries
use jensostertag\Templify\Templify;
Templify::setConfig("TEMPLATE_BASE_DIR", __APP_DIR__ . "/project/frontend");

// ClassLoader
require_once(__APP_DIR__ . "/framework/src/ClassLoader.class.php");
$classLoader = ClassLoader::getInstance();

// Load Logger
$classLoader->loadClass(__APP_DIR__ . "/framework/src/Logger.class.php");

// Load Comm
$classLoader->loadClass(__APP_DIR__ . "/framework/src/Comm.class.php");

// Load Router
$classLoader->loadClass(__APP_DIR__ . "/framework/src/Router.class.php");

// Configuration files
require_once(__APP_DIR__ . "/framework/config/Config.class.php");
Config::init();
require_once(__APP_DIR__ . "/project/config/app-config.php");

// Initialize routes
require_once(__APP_DIR__ . "/project/config/app-routes.php");

// Load enums
$classLoader->loadEnums(__APP_DIR__ . "/framework/src/enum/");

// Load libraries
$classLoader->loadClasses(__APP_DIR__ . "/framework/src/lib/");
$classLoader->load(__APP_DIR__ . "/framework/src/lib/methods.php");

// Load objects
$classLoader->loadClasses(__APP_DIR__ . "/framework/src/object/");

// Load DAOs
$classLoader->loadClasses(__APP_DIR__ . "/framework/src/dao/");

// Load extra enums and classes
foreach(Config::$CLASS_LOADER_SETTINGS["CLASS_LOADER_IMPORT_PATHS"] as $path) {
    $classLoader->loadEnums($path);
    $classLoader->loadClasses($path);
}
