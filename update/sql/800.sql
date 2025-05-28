UPDATE `settings` SET `value` = '{\"version\":\"8.0.0\", \"code\":\"800\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add aix_chats_current_month bigint unsigned default 0 after aix_transcriptions_current_month;

-- SEPARATOR --

CREATE TABLE `chats` (
`chat_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`total_messages` int unsigned DEFAULT '0',
`used_tokens` int unsigned DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`chat_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `chats_messages` (
`chat_message_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`chat_id` bigint unsigned DEFAULT NULL,
`user_id` bigint unsigned DEFAULT NULL,
`role` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`content` text COLLATE utf8mb4_unicode_ci,
`model` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`api_response_time` int unsigned DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`chat_message_id`),
KEY `chat_id` (`chat_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `chats_messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`chat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `chats_messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
