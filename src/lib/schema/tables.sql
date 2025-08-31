# User table
CREATE TABLE IF NOT EXISTS `User` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `emailVerified` TINYINT(1) NOT NULL DEFAULT 0,
    `permissionLevel` INT NOT NULL,
    `oneTimePassword` VARCHAR(255) NULL,
    `oneTimePasswordExpiration` DATETIME(3) NULL,
    `created` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    `updated` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`),
    UNIQUE KEY (`username`),
    UNIQUE KEY (`email`),
    UNIQUE KEY (`oneTimePassword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Services table
CREATE TABLE IF NOT EXISTS `Service` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(256) NOT NULL,
    `slug` VARCHAR(256) NOT NULL,
    `visible` TINYINT NOT NULL,
    `order` INT NOT NULL,
    `created` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    `updated` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`),
    UNIQUE KEY (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Monitoring settings table
CREATE TABLE IF NOT EXISTS `MonitoringSettings` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `serviceId` INT NOT NULL,
    `monitoringType` INT NOT NULL,
    `endpoint` VARCHAR(256) NOT NULL,
    `expectation` VARCHAR(4096) NOT NULL,
    `created` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    `updated` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`serviceId`) REFERENCES `Service`(`id`) ON DELETE CASCADE,
    UNIQUE KEY (`serviceId`, `monitoringType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Monitoring results table
CREATE TABLE IF NOT EXISTS `MonitoringResult` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `monitoringSettingsId` INT NOT NULL,
    `status` INT NOT NULL,
    `responseTime` FLOAT NULL,
    `responseCode` INT NULL,
    `additionalInfo` VARCHAR(4096) NULL,
    `created` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    `updated` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`monitoringSettingsId`) REFERENCES `MonitoringSettings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Incidents table
CREATE TABLE IF NOT EXISTS `Incident` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `serviceId` INT NOT NULL,
    `status` INT NOT NULL,
    `from` DATETIME(3) NOT NULL,
    `until` DATETIME(3) NULL,
    `created` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    `updated` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`serviceId`) REFERENCES `Service`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
