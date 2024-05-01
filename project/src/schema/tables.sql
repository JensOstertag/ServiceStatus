# User table
CREATE TABLE IF NOT EXISTS `User` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `emailVerified` TINYINT(1) NOT NULL DEFAULT 0,
    `permissionLevel` INT NOT NULL,
    `oneTimePassword` VARCHAR(255) NULL,
    `oneTimePasswordExpiration` DATETIME NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Service table
CREATE TABLE IF NOT EXISTS `Service` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `url` VARCHAR(255) NOT NULL,
    `partialOutageThreshold` INT NULL,
    `fullOutageThreshold` INT NULL,
    `expectedResponseCode` INT NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Incident table
CREATE TABLE IF NOT EXISTS `Incident` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `date` DATETIME NOT NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Status table
CREATE TABLE IF NOT EXISTS `Status` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `serviceId` INT NOT NULL,
    `latest` TINYINT NOT NULL DEFAULT 0,
    `invalidatedAt` DATETIME NULL,
    `outageType` SMALLINT NOT NULL,
    `duration` INT NULL,
    `responseCode` INT NULL,
    `incidentId` INT NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    KEY `serviceId_latest` (`serviceId`, `latest`),
    FOREIGN KEY (`serviceId`) REFERENCES `Service`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`incidentId`) REFERENCES `Incident`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
