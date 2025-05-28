UPDATE `settings` SET `value` = '{\"version\":\"19.0.0\", \"code\":\"1900\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add os_name varchar(16) null after city_name;

-- SEPARATOR --

alter table users add browser_name varchar(32) null after city_name;

-- SEPARATOR --

alter table users add browser_language varchar(32) null after city_name;

-- SEPARATOR --

alter table users add device_type varchar(16) null after city_name;

-- SEPARATOR --

alter table users drop column last_user_agent;

-- SEPARATOR --

alter table users_logs add browser_name varchar(32) null;

-- SEPARATOR --

alter table users_logs add browser_language varchar(32) null;

-- SEPARATOR --

update images set api = 'dall_e_2' where api = 'dall-e-2';

-- SEPARATOR --

alter table chats_messages add image varchar(40) null after content;

