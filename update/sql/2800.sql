UPDATE `settings` SET `value` = '{\"version\":\"28.0.0\", \"code\":\"2800\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table blog_posts add image_description varchar(256) null after description;
