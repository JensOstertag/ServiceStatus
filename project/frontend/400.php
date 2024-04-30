<?php
use jensostertag\Templify\Templify;
Templify::setConfig("WEBSITE_TITLE", "400");
Templify::include("header.php");
?>

<h1>400</h1>
<p>
    There was an error with your request.
</p>

<?php
Templify::include("footer.php");
?>
