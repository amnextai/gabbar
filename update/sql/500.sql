UPDATE `settings` SET `value` = '{\"version\":\"5.0.0\", \"code\":\"500\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table images add mood varchar(128) null after image;
