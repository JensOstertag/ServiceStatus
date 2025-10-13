<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("auth-login"));

echo Blade->run("pages.admin.services.index");
