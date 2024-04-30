<?php
use jensostertag\Templify\Templify;
Templify::setConfig("WEBSITE_TITLE", "Home");
Templify::include("header.php");
?>

<h1>Home</h1>
<p>
    Hello, World!
</p>

<?php
Templify::include("footer.php");
?>
