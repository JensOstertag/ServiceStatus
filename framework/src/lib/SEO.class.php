<?php

class SEO {
    private static ?string $description = null;
    private static bool $unlisted = false;

    /**
     * Sets the website description
     * @param string $description
     * @return void
     */
    public static function setDescription(string $description): void {
        self::$description = $description;
    }

    /**
     * Returns the website description
     * @return string
     */
    public static function getDescription(): string {
        if(self::$description !== null) {
            return self::$description;
        } else {
            return Config::$SEO_SETTINGS["SEO_DEFAULT_DESCRIPTION"];
        }
    }

    /**
     * Hides the page from search engines
     * @return void
     */
    public static function setUnlisted(): void {
        self::$unlisted = true;
    }

    /**
     * Returns the values for the robots meta tag
     * @return array
     */
    public static function getRobots(): array {
        if(self::$unlisted) {
            return ["noindex", "nofollow"];
        } else {
            return Config::$SEO_SETTINGS["SEO_ROBOTS"];
        }
    }
}
