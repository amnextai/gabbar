UPDATE `settings` SET `value` = '{\"version\":\"13.0.1\", \"code\":\"1301\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

CREATE TABLE `upscaled_images` (
`upscaled_image_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`project_id` bigint unsigned DEFAULT NULL,
`name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`original_image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`upscaled_image` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`original_size` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`upscaled_size` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`scale` tinyint unsigned DEFAULT NULL,
`settings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
`api_response_time` int unsigned DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`upscaled_image_id`),
KEY `user_id` (`user_id`),
KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
