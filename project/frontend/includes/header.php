<?php
use jensostertag\Templify\Templify;
?>

<!DOCTYPE html>
<html lang="en">
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
        <link rel="stylesheet" href="<?php output(Router::staticFilePath("css/infomessages.css")); ?>">

        <link rel="stylesheet" href="<?php output(Router::staticFilePath("css/project.css")); ?>">

        <?php // JavaScript ?>
        <script src="<?php output(Router::staticFilePath("js/lib/jquery.min.js")); ?>"></script>

        <script src="<?php output(Router::staticFilePath("js/infomessage.js")); ?>"></script>
    </head>
    <body>
        <header>
            <div class="container">
                <?php if(!empty($breadcrumbs)): ?>
                    <div class="breadcrumbs margin-half">
                        <?php foreach($breadcrumbs as $i => $breadcrumb): ?>
                            <a href="<?php output($breadcrumb["link"]); ?>" <?php if($i >= count($breadcrumbs) - 1): ?>data-last="true"<?php endif; ?>>
                                <?php output($breadcrumb["name"]); ?>
                            </a>
                            <?php if($i < count($breadcrumbs) - 1): ?>
                                <span>/</span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </header>
        <main>
