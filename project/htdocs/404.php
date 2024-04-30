<?php

use jensostertag\Templify\Templify;

http_response_code(404);
Templify::display("404.php");
