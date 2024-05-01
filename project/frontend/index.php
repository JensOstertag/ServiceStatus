<?php
use jensostertag\Templify\Templify;
Templify::setConfig("WEBSITE_TITLE", "Home");
Templify::include("header.php");
?>

<section class="container">
    <h1>Service Status</h1>
</section>

<?php
Templify::include("footer.php");
?>
