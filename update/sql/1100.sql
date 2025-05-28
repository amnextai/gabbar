UPDATE `settings` SET `value` = '{\"version\":\"11.0.0\", \"code\":\"1100\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add aix_documents_current_month bigint unsigned default 0 after aix_words_current_month;

-- SEPARATOR --

alter table chats add chat_assistant_id bigint unsigned null after user_id;

-- SEPARATOR --

CREATE TABLE `chats_assistants` (
`chat_assistant_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`prompt` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`image` varchar(404) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`order` int DEFAULT NULL,
`total_usage` bigint unsigned DEFAULT '0',
`is_enabled` tinyint unsigned DEFAULT '1',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`chat_assistant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `chats_assistants` (`chat_assistant_id`, `name`, `prompt`, `settings`, `image`, `order`, `total_usage`, `is_enabled`, `last_datetime`, `datetime`) VALUES (1, 'General Assistant', 'You are a general assistant that can help with anything.', '{\"translations\":{\"english\":{\"name\":\"General Assistant\",\"description\":\"I can help you with any general task or question.\"}}}', 'de618ff8b13d6aa0b7df3b91b16cb452.png', 0, 0, 1, null, NOW());

-- SEPARATOR --

alter table chats add constraint chats_chats_assistants_chat_assistant_id_fk foreign key (chat_assistant_id) references chats_assistants (chat_assistant_id) on update cascade on delete cascade;

-- SEPARATOR --

update chats set chat_assistant_id = 1;
