UPDATE `settings` SET `value` = '{\"version\":\"17.0.0\", \"code\":\"1700\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add aix_removed_background_images_current_month bigint unsigned default 0 after source;

-- SEPARATOR --

alter table users add aix_replaced_background_images_current_month bigint unsigned default 0 after source;

-- SEPARATOR --

CREATE TABLE `removed_background_images` (
`removed_background_image_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`project_id` bigint unsigned DEFAULT NULL,
`name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`original_image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`removed_background_image` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
`api_response_time` int unsigned DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`removed_background_image_id`),
KEY `user_id` (`user_id`),
KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `replaced_background_images` (
`replaced_background_image_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`project_id` bigint unsigned DEFAULT NULL,
`name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`input` text,
`original_image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`replaced_background_image` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
`api_response_time` int unsigned DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`replaced_background_image_id`),
KEY `user_id` (`user_id`),
KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('languages', '{}');
