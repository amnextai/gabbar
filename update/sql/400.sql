UPDATE `settings` SET `value` = '{\"version\":\"4.0.0\", \"code\":\"400\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add aix_transcriptions_current_month bigint unsigned default 0 after source;

-- SEPARATOR --

CREATE TABLE `transcriptions` (
`transcription_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`project_id` bigint unsigned DEFAULT NULL,
`name` varchar(64) DEFAULT NULL,
`input` text,
`content` text,
`words` int unsigned DEFAULT NULL,
`language` varchar(32) DEFAULT NULL,
`settings` text,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`transcription_id`),
KEY `user_id` (`user_id`),
KEY `project_id` (`project_id`),
CONSTRAINT `transcriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `transcriptions_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
