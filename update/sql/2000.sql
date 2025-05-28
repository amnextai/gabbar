UPDATE `settings` SET `value` = '{\"version\":\"20.0.0\", \"code\":\"2000\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('sso', '{}');

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('iyzico', '{}');

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('midtrans', '{}');

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('flutterwave', '{}');

-- SEPARATOR --

alter table plans add prices text null after description;

-- SEPARATOR --

update plans set prices = '{}';

-- SEPARATOR --

alter table users add currency varchar(4) null after language;

-- SEPARATOR --

alter table syntheses add format varchar(16) default 'mp3' after language;

-- SEPARATOR --

alter table chats_assistants modify prompt varchar(2048) default null;

-- EXTENDED SEPARATOR --

alter table payments add total_amount_default_currency float null after total_amount;

-- SEPARATOR --

update payments set total_amount_default_currency = total_amount;
