UPDATE `settings` SET `value` = '{\"version\":\"10.0.0\", \"code\":\"1000\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add aix_syntheses_current_month bigint unsigned default 0 after aix_chats_current_month;

-- SEPARATOR --

alter table users add aix_synthesized_characters_current_month bigint unsigned default 0 after aix_transcriptions_current_month;

-- SEPARATOR --

CREATE TABLE `syntheses` (
`synthesis_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`project_id` bigint unsigned DEFAULT NULL,
`name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`input` text COLLATE utf8mb4_unicode_ci,
`file` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`language` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`voice_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`voice_engine` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`voice_gender` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`characters` int unsigned DEFAULT '0',
`api_response_time` int unsigned DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`synthesis_id`),
KEY `user_id` (`user_id`),
KEY `project_id` (`project_id`),
CONSTRAINT `syntheses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `syntheses_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `templates_categories` (`template_category_id`, `name`, `settings`, `icon`, `emoji`, `color`, `background`, `order`, `is_enabled`, `datetime`, `last_datetime`) VALUES
(1111, 'Developers', '{\"translations\":{\"english\":{\"name\":\"Developers\"}}}', 'fas fa-code', '💻', '#DB00FF', '#FCE9FF', 1, 1, '2023-04-19 20:00:55', NULL);

-- SEPARATOR --

INSERT INTO `templates` (`template_category_id`, `name`, `prompt`, `settings`, `icon`, `order`, `total_usage`, `is_enabled`, `datetime`, `last_datetime`) VALUES
(1111, 'PHP snippet', 'You are a PHP programmer, answer the following request with a PHP snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"PHP snippet\",\"description\":\"Generate PHP code snippets with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that connects to a MySQL database in procedural style\",\"help\":\"Ask the AI what PHP code you want to receive \\/ get help with.\"}}}}}', 'fab fa-php', 0, 1, 1, '2023-04-19 20:18:43', NULL),
(1111, 'SQL query', 'You are a SQL programmer, answer the following request with an SQL query:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"SQL query\",\"description\":\"Generate helpful SQL queries with the help of AI.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested query\",\"placeholder\":\"Code that calculates the average from 3 columns\",\"help\":\"Ask the AI what SQL query you want to receive \\/ get help with.\"}}}}}', 'fas fa-database', 0, 1, 1, '2023-04-19 21:06:04', '2023-04-19 21:10:50'),
(1111, 'JS snippet', 'You are a JS programmer, answer the following request with a JS snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"JS snippet\",\"description\":\"Generate quick & helpful Javascript code snippets.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that helps trigger and catch custom events\",\"help\":\"Ask the AI what JS code you want to receive \\/ get help with.\"}}}}}', 'fab fa-js', 0, 0, 1, '2023-04-19 21:31:37', NULL),
(1111, 'HTML snippet', 'You are a HTML programmer, answer the following request with a HTML snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"HTML snippet\",\"description\":\"Generate simple and fast HTML pieces of code.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that generates a blank HTML page\",\"help\":\"Ask the AI what HTML code you want to receive \\/ get help with.\"}}}}}', 'fab fa-html5', 0, 0, 1, '2023-04-19 22:00:58', NULL),
(1111, 'CSS snippet', 'You are a CSS programmer, answer the following request with a CSS snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"CSS snippet\",\"description\":\"Generate CSS classes & code snippets with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that generates a gradient background class\",\"help\":\"Ask the AI what CSS code you want to receive \\/ get help with.\"}}}}}', 'fab fa-css3', 0, 0, 1, '2023-04-19 22:03:16', NULL),
(1111, 'Python snippet', 'You are a python programmer, answer the following request with a python snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"Python snippet\",\"description\":\"Generate Python code pieces with the help of AI.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that sends an external HTTP request\",\"help\":\"Ask the AI what Python code you want to receive \\/ get help with.\"}}}}}', 'fab fa-python', 0, 0, 1, '2023-04-19 22:05:03', NULL);
