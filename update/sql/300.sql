UPDATE `settings` SET `value` = '{\"version\":\"3.0.0\", \"code\":\"300\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table images add variants_ids text null after project_id;

-- SEPARATOR --

alter table images add artist varchar(128) null after image;

-- SEPARATOR --

alter table images add lighting varchar(128) null after image;

-- SEPARATOR --

alter table images add style varchar(128) null after image;
