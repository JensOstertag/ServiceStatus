<?php
    use jensostertag\Templify\Templify;
?>

<!DOCTYPE html>
<html>
    <head>
        <?php // Encoding ?>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php // Browser Tab ?>
        <title><?php
            output(
                (
                        Templify::getConfig("WEBSITE_TITLE") !== null ?
                            Templify::getConfig("WEBSITE_TITLE") . " - "
                        :
                            ""
                ) . Config::$PROJECT_SETTINGS["WEBSITE_TITLE"]
            );
        ?></title>
        <link rel="icon" href="<?php output(Config::$PROJECT_SETTINGS["PROJECT_FAVICON"]); ?>" type="image/x-icon">

        <?php // Basic SEO ?>
        <meta name="description" content="<?php output(SEO::getDescription()); ?>">
        <meta name="keywords" content="<?php output(implode(", ", Config::$SEO_SETTINGS["SEO_KEYWORDS"])); ?>">
        <meta name="author" content="<?php output(Config::$PROJECT_SETTINGS["PROJECT_AUTHOR"]); ?>">

        <?php // OpenGraph SEO ?>
        <meta property="og:title" content="<?php
            output(
                (
                    Templify::getConfig("WEBSITE_TITLE") !== null ?
                        Templify::getConfig("WEBSITE_TITLE") . " - "
                    :
                        ""
                ) . Config::$PROJECT_SETTINGS["WEBSITE_TITLE"]
            );
        ?>">
        <meta property="og:description" content="<?php output(SEO::getDescription()); ?>">
        <meta property="og:image" content="<?php output(Config::$SEO_SETTINGS["SEO_IMAGE_PREVIEW"]); ?>">
        <meta property="og:url" content="<?php output(Router::getCalledURL()); ?>">
        <?php if (Config::$SEO_SETTINGS["SEO_OPENGRAPH"]["OPENGRAPH_SITE_NAME"] !== null): ?>
            <meta property="og:site_name" content="<?php output(Config::$SEO_SETTINGS["SEO_OPENGRAPH"]["OPENGRAPH_SITE_NAME"]); ?>">
        <?php endif; ?>
        <meta property="og:type" content="website">

        <?php // Twitter SEO ?>
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="<?php
            output(
                (
                    Templify::getConfig("WEBSITE_TITLE") !== null ?
                        Templify::getConfig("WEBSITE_TITLE") . " - "
                    :
                        ""
                ) . Config::$PROJECT_SETTINGS["WEBSITE_TITLE"]
            );
        ?>">
        <meta name="twitter:description" content="<?php output(SEO::getDescription()); ?>">
        <meta name="twitter:image" content="<?php output(Config::$SEO_SETTINGS["SEO_IMAGE_PREVIEW"]); ?>">
        <meta name="twitter:url" content="<?php output(Router::getCalledURL()); ?>">
        <?php if (Config::$SEO_SETTINGS["SEO_TWITTER"]["TWITTER_SITE"] !== null): ?>
            <meta name="twitter:site" content="<?php output(Config::$SEO_SETTINGS["SEO_TWITTER"]["TWITTER_SITE"]); ?>">
        <?php endif; ?>
        <?php if (Config::$SEO_SETTINGS["SEO_TWITTER"]["TWITTER_CREATOR"] !== null): ?>
            <meta name="twitter:creator" content="<?php output(Config::$SEO_SETTINGS["SEO_TWITTER"]["TWITTER_CREATOR"]); ?>">
        <?php endif; ?>

        <?php // Indexing ?>
        <meta name="robots" content="<?php output(implode(", ", SEO::getRobots())); ?>">
        <meta name="revisit-after" content="<?php output(Config::$SEO_SETTINGS["SEO_REVISIT"]); ?>">

        <?php // CSS ?>
        <link rel="stylesheet" href="<?php output(Router::staticFilePath("css/base.css")); ?>">
        <link rel="stylesheet" href="<?php output(Router::staticFilePath("css/fonts.css")); ?>">

        <link rel="stylesheet" href="<?php output(Router::staticFilePath("css/header.css")); ?>">
        <link rel="stylesheet" href="<?php output(Router::staticFilePath("css/footer.css")); ?>">
        <link rel="stylesheet" href="<?php output(Router::staticFilePath("css/infomessages.css")); ?>">

        <link rel="stylesheet" href="<?php output(Router::staticFilePath("css/project.css")); ?>">

        <script src="<?php output(Router::staticFilePath("js/lib/jquery.min.js")); ?>"></script>

        <?php // JavaScript ?>
        <script src="<?php output(Router::staticFilePath("js/sidebar.js")); ?>"></script>
        <script src="<?php output(Router::staticFilePath("js/infomessage.js")); ?>"></script>
    </head>
    <body>
        <nav>
            <div class="header-logo">
                <a href="<?php output(Router::generate(Config::$PROJECT_SETTINGS["PROJECT_URL"])); ?>">
                    <img src="<?php output(Router::staticFilePath("img/logo.svg")); ?>" alt="Logo">
                    <span>
                        <?php output(Config::$PROJECT_SETTINGS["PROJECT_NAME"]); ?>
                    </span>
                </a>
            </div>

            <ul class="header-navigator header-navigator-default">
                <?php foreach (Config::$MENU_SETTINGS["MENU_SIDEBAR"] as $displayName => $settings): ?>
                    <li>
                        <a href="<?php output($settings["route"]); ?>">
                            <span><?php output($displayName); ?></span>
                        </a>
                    </li>
                    <hr>
                <?php endforeach; ?>
            </ul>

            <div class="header-burger">
                <div class="l1"></div>
                <div class="l2"></div>
                <div class="l3"></div>
            </div>

            <div class="header-dark-background header-dark-background-default"></div>

            <script>
                let sidebar = new Sidebar(".header-navigator", ".header-burger", ".header-dark-background");
            </script>
        </nav>

        <main>
            <?php
                Templify::include("infomessages.php");
            ?>
