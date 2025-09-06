<?php

$user = Auth->enforceLogin(0, Router->generate("auth-login"));

echo Blade->run("pages.admin.notifications.index");
