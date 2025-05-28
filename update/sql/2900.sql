UPDATE `settings` SET `value` = '{\"version\":\"29.0.0\", \"code\":\"2900\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

UPDATE settings SET `value` = JSON_SET(`value`, '$.blacklisted_domains', JSON_ARRAY()) WHERE `key` = 'users';
