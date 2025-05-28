UPDATE `settings` SET `value` = '{\"version\":\"25.0.0\", \"code\":\"2500\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add extra text null after preferences;
