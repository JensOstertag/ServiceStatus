<?php
use jensostertag\Templify\Templify;
Templify::setConfig("WEBSITE_TITLE", "404");
Templify::include("header.php");
?>

<h1>404</h1>
<p>
    The requested resource could not be found.
</p>

<?php
Templify::include("footer.php");
?>
