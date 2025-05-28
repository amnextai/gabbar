UPDATE `settings` SET `value` = '{\"version\":\"13.0.0\", \"code\":\"1300\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('theme', '');

-- SEPARATOR --

alter table images add api varchar(64) null after settings;

-- SEPARATOR --

UPDATE `images` SET `api` = 'openai_dall_e';

-- SEPARATOR --

alter table users add aix_upscaled_images_current_month bigint unsigned default 0 after source;
