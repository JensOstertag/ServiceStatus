# User table
CREATE TABLE IF NOT EXISTS `User` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `emailVerified` tinyint(1) NOT NULL DEFAULT 0,
    `permissionLevel` int(11) NOT NULL,
    `oneTimePassword` VARCHAR(255) NULL,
    `oneTimePasswordExpiration` DATETIME NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
