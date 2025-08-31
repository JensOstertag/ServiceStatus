<?php

$user = Auth->enforceLogin(0, Router->generate("index"));

echo Blade->run("admin.notifications.index");
