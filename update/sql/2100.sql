UPDATE `settings` SET `value` = '{\"version\":\"21.0.0\", \"code\":\"2100\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table users add preferences text after timezone;

-- EXTENDED SEPARATOR --

-- X --
update payments set total_amount_default_currency = total_amount;


