CREATE TABLE `users` (
`user_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`email` varchar(320) NOT NULL,
`password` varchar(128) DEFAULT NULL,
`name` varchar(64) NOT NULL,
`billing` text,
`api_key` varchar(32) DEFAULT NULL,
`token_code` varchar(32) DEFAULT NULL,
`twofa_secret` varchar(16) DEFAULT NULL,
`anti_phishing_code` varchar(8) DEFAULT NULL,
`one_time_login_code` varchar(32) DEFAULT NULL,
`pending_email` varchar(128) DEFAULT NULL,
`email_activation_code` varchar(32) DEFAULT NULL,
`lost_password_code` varchar(32) DEFAULT NULL,
`type` tinyint NOT NULL DEFAULT '0',
`status` tinyint NOT NULL DEFAULT '0',
`is_newsletter_subscribed` tinyint NOT NULL DEFAULT '0',
`has_pending_internal_notifications` tinyint NOT NULL DEFAULT '0',
`plan_id` varchar(16) NOT NULL DEFAULT '',
`plan_expiration_date` datetime DEFAULT NULL,
`plan_settings` text,
`plan_trial_done` tinyint DEFAULT '0',
`plan_expiry_reminder` tinyint DEFAULT '0',
`payment_subscription_id` varchar(64) DEFAULT NULL,
`payment_processor` varchar(16) DEFAULT NULL,
`payment_total_amount` float DEFAULT NULL,
`payment_currency` varchar(4) DEFAULT NULL,
`referral_key` varchar(32) DEFAULT NULL,
`referred_by` varchar(32) DEFAULT NULL,
`referred_by_has_converted` tinyint DEFAULT '0',
`language` varchar(32) DEFAULT 'english',
`currency` varchar(4) DEFAULT NULL,
`timezone` varchar(32) DEFAULT 'UTC',
`preferences` text,
`extra` text,
`datetime` datetime DEFAULT NULL,
`next_cleanup_datetime` datetime DEFAULT CURRENT_TIMESTAMP,
`ip` varchar(64) DEFAULT NULL,
`continent_code` varchar(8) DEFAULT NULL,
`country` varchar(8) DEFAULT NULL,
`city_name` varchar(32) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`browser_language` varchar(32) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`last_activity` datetime DEFAULT NULL,
`total_logins` int DEFAULT '0',
`user_deletion_reminder` tinyint(4) DEFAULT '0',
`source` varchar(32) DEFAULT 'direct',
`aix_documents_current_month` bigint unsigned DEFAULT '0',
`aix_words_current_month` bigint unsigned DEFAULT '0',
`aix_images_current_month` bigint unsigned DEFAULT '0',
`aix_upscaled_images_current_month` bigint unsigned DEFAULT '0',
`aix_removed_background_images_current_month` bigint unsigned DEFAULT '0',
`aix_replaced_background_images_current_month` bigint unsigned DEFAULT '0',
`aix_transcriptions_current_month` bigint unsigned DEFAULT '0',
`aix_chats_current_month` bigint unsigned DEFAULT '0',
`aix_syntheses_current_month` bigint unsigned DEFAULT '0',
`aix_synthesized_characters_current_month` bigint unsigned DEFAULT '0',
PRIMARY KEY (`user_id`),
KEY `plan_id` (`plan_id`),
KEY `api_key` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `users` (`user_id`, `email`, `password`, `api_key`, `referral_key`, `name`, `type`, `status`, `plan_id`, `plan_expiration_date`, `plan_settings`, `datetime`, `ip`, `last_activity`, `preferences`)
VALUES (1,'admin','$2y$10$uFNO0pQKEHSFcus1zSFlveiPCB3EvG9ZlES7XKgJFTAl5JbRGFCWy', md5(rand()), md5(rand()), 'AltumCode',1,1,'custom','2030-01-01 12:00:00', '{"documents_model":"gpt-3.5-turbo","documents_per_month_limit":-1,"words_per_month_limit":-1,"images_api":"dall-e-2","images_per_month_limit":-1,"upscaled_images_per_month_limit":-1,"upscaled_images_file_size_limit":2,"transcriptions_per_month_limit":-1,"transcriptions_file_size_limit":2,"chats_model":"gpt-3.5-turbo","chats_per_month_limit":-1,"chat_messages_per_chat_limit":-1,"syntheses_per_month_limit":-1,"synthesized_characters_per_month_limit":-1,"projects_limit":-1,"teams_limit":0,"team_members_limit":0,"api_is_enabled":true,"affiliate_commission_percentage":0,"no_ads":true}', NOW(),'',NOW(), '{"default_results_per_page":100,"default_order_type":"DESC","openai_api_key":"","clipdrop_api_key":"","aws_access_key":"","aws_secret_access_key":"","aws_region":"ap-east-1"}');

-- SEPARATOR --

CREATE TABLE `users_logs` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`type` varchar(64) DEFAULT NULL,
`ip` varchar(64) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`continent_code` varchar(8) DEFAULT NULL,
`country_code` varchar(8) DEFAULT NULL,
`city_name` varchar(32) DEFAULT NULL,
`browser_language` varchar(32) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `users_logs_user_id` (`user_id`),
KEY `users_logs_ip_type_datetime_index` (`ip`,`type`,`datetime`),
CONSTRAINT `users_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `plans` (
`plan_id` int unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(64) NOT NULL DEFAULT '',
`description` varchar(256) NOT NULL DEFAULT '',
`translations` text NOT NULL,
`prices` text NOT NULL,
`trial_days` int unsigned NOT NULL DEFAULT '0',
`settings` longtext NOT NULL,
`taxes_ids` text,
`color` varchar(16) DEFAULT NULL,
`status` tinyint NOT NULL,
`order` int unsigned DEFAULT '0',
`datetime` datetime NOT NULL,
PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `pages_categories` (
`pages_category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`icon` varchar(32) DEFAULT NULL,
`order` int NOT NULL DEFAULT '0',
`language` varchar(32) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`pages_category_id`),
KEY `url` (`url`),
KEY `pages_categories_url_language_index` (`url`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `pages` (
`page_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`pages_category_id` bigint unsigned DEFAULT NULL,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`icon` varchar(32) DEFAULT NULL,
`keywords` varchar(256) CHARACTER SET utf8mb4 DEFAULT NULL,
`editor` varchar(16) DEFAULT NULL,
`content` longtext,
`type` varchar(16) DEFAULT '',
`position` varchar(16) NOT NULL DEFAULT '',
`language` varchar(32) DEFAULT NULL,
`open_in_new_tab` tinyint DEFAULT '1',
`order` int DEFAULT '0',
`total_views` bigint unsigned DEFAULT '0',
`is_published` tinyint DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`page_id`),
KEY `pages_pages_category_id_index` (`pages_category_id`),
KEY `pages_url_index` (`url`),
KEY `pages_is_published_index` (`is_published`),
KEY `pages_language_index` (`language`),
CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`pages_category_id`) REFERENCES `pages_categories` (`pages_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `pages` (`pages_category_id`, `url`, `title`, `description`, `content`, `type`, `position`, `order`, `total_views`, `datetime`, `last_datetime`) VALUES
(NULL, 'https://amnext.tech/', 'Made by American Nexus T', '', '', 'external', 'bottom', 1, 0, NOW(), NOW()),
(NULL, 'https://amnext.network/', 'ProLink', '', '', 'external', 'bottom', 0, 0, NOW(), NOW());

-- SEPARATOR --

CREATE TABLE `blog_posts_categories` (
`blog_posts_category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`order` int NOT NULL DEFAULT '0',
`language` varchar(32) DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`blog_posts_category_id`),
KEY `url` (`url`),
KEY `blog_posts_categories_url_language_index` (`url`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `blog_posts` (
`blog_post_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`blog_posts_category_id` bigint unsigned DEFAULT NULL,
`url` varchar(256) NOT NULL,
`title` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) DEFAULT NULL,
`keywords` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`image_description` varchar(256) DEFAULT NULL,
`editor` varchar(16) DEFAULT NULL,
`content` longtext,
`language` varchar(32) DEFAULT NULL,
`total_views` bigint unsigned DEFAULT '0',
`is_published` tinyint DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`blog_post_id`),
KEY `blog_post_id_index` (`blog_post_id`),
KEY `blog_post_url_index` (`url`),
KEY `blog_posts_category_id` (`blog_posts_category_id`),
KEY `blog_posts_is_published_index` (`is_published`),
KEY `blog_posts_language_index` (`language`),
CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`blog_posts_category_id`) REFERENCES `blog_posts_categories` (`blog_posts_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `broadcasts` (
`broadcast_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(64) DEFAULT NULL,
`subject` varchar(128) DEFAULT NULL,
`content` text,
`segment` varchar(64) DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`users_ids` longtext CHARACTER SET utf8mb4,
`sent_users_ids` longtext,
`sent_emails` int unsigned DEFAULT '0',
`total_emails` int unsigned DEFAULT '0',
`status` varchar(16) DEFAULT NULL,
`views` bigint unsigned DEFAULT '0',
`clicks` bigint unsigned DEFAULT '0',
`last_sent_email_datetime` datetime DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`broadcast_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `broadcasts_statistics` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`broadcast_id` bigint unsigned DEFAULT NULL,
`type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`target` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `broadcast_id` (`broadcast_id`),
KEY `broadcasts_statistics_user_id_broadcast_id_type_index` (`broadcast_id`,`user_id`,`type`),
CONSTRAINT `broadcasts_statistics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `broadcasts_statistics_ibfk_2` FOREIGN KEY (`broadcast_id`) REFERENCES `broadcasts` (`broadcast_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `internal_notifications` (
`internal_notification_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`for_who` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`from_who` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`icon` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`description` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`url` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`is_read` tinyint unsigned DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`read_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`internal_notification_id`),
KEY `user_id` (`user_id`),
KEY `users_notifications_for_who_idx` (`for_who`) USING BTREE,
CONSTRAINT `internal_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `settings` (
`id` int NOT NULL AUTO_INCREMENT,
`key` varchar(64) NOT NULL DEFAULT '',
`value` longtext NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

SET @cron_key = MD5(RAND());

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES
('main', '{"title":"Your title","default_language":"english","default_theme_style":"light","default_timezone":"UTC","index_url":"","terms_and_conditions_url":"","privacy_policy_url":"","not_found_url":"","ai_scraping_is_allowed":true,"se_indexing":true,"display_index_plans":true,"display_index_testimonials":true,"display_index_faq":true,"display_index_latest_blog_posts":true,"default_results_per_page":100,"default_order_type":"DESC","auto_language_detection_is_enabled":true,"blog_is_enabled":false,"api_is_enabled":true,"theme_style_change_is_enabled":true,"logo_light":"","logo_dark":"","logo_email":"","opengraph":"","favicon":"","openai_api_key":"","openai_model":"gpt-4o","force_https_is_enabled":false,"broadcasts_statistics_is_enabled":true,"breadcrumbs_is_enabled":true,"display_pagination_when_no_pages":false,"chart_cache":12,"chart_days":30}'),
('languages', '{"english":{"status":"active"}}'),
('users', '{"email_confirmation":false,"welcome_email_is_enabled":false,"register_is_enabled":true,"register_only_social_logins":false,"register_social_login_require_password":false,"register_display_newsletter_checkbox":false,"login_rememberme_checkbox_is_checked":true,"login_rememberme_cookie_days":90,"auto_delete_unconfirmed_users":3,"auto_delete_inactive_users":30,"user_deletion_reminder":0,"blacklisted_domains":[],"blacklisted_countries":[],"login_lockout_is_enabled":true,"login_lockout_max_retries":3,"login_lockout_time":10,"lost_password_lockout_is_enabled":true,"lost_password_lockout_max_retries":3,"lost_password_lockout_time":10,"resend_activation_lockout_is_enabled":true,"resend_activation_lockout_max_retries":3,"resend_activation_lockout_time":10,"register_lockout_is_enabled":true,"register_lockout_max_registrations":3,"register_lockout_time":10}'),
('ads', '{"ad_blocker_detector_is_enabled":true,"ad_blocker_detector_lock_is_enabled":false,"ad_blocker_detector_delay":5,"header":"","footer":"","header_biolink":"","footer_biolink":"","header_splash":"","footer_splash":""}'),
('captcha', '{\"type\":\"basic\",\"recaptcha_public_key\":\"\",\"recaptcha_private_key\":\"\",\"login_is_enabled\":0,\"register_is_enabled\":0,\"lost_password_is_enabled\":0,\"resend_activation_is_enabled\":0}'),
('cron', concat('{\"key\":\"', @cron_key, '\"}')),
('email_notifications', '{"emails":"","new_user":false,"delete_user":false,"new_payment":false,"new_domain":false,"new_affiliate_withdrawal":false,"contact":false}'),
('internal_notifications', '{"users_is_enabled":true,"admins_is_enabled":true,"new_user":true,"delete_user":true,"new_newsletter_subscriber":true,"new_payment":true,"new_affiliate_withdrawal":true}'),
('content', '{"blog_is_enabled":true,"blog_share_is_enabled":true,"blog_search_widget_is_enabled":false,"blog_categories_widget_is_enabled":true,"blog_popular_widget_is_enabled":true,"blog_views_is_enabled":true,"pages_is_enabled":true,"pages_share_is_enabled":true,"pages_popular_widget_is_enabled":true,"pages_views_is_enabled":true}'),
('sso', '{"is_enabled":true,"display_menu_items":true,"websites":{}}'),
('facebook', '{"is_enabled":false,"app_id":"","app_secret":""}'),
('google', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('twitter', '{"is_enabled":false,"consumer_api_key":"","consumer_api_secret":""}'),
('discord', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('linkedin', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('microsoft', '{"is_enabled":false,"client_id":"","client_secret":""}'),
('plan_custom', '{"plan_id":"custom","name":"Custom","description":"Contact us for enterprise pricing.","price":"Custom","custom_button_url":"mailto:sample@example.com","color":null,"status":2,"settings":{}}'),
('plan_free', '{"plan_id":"free","name":"Free","description":"Free, forever","price":"Free","color":"#135ac4","status":1,"settings":{"documents_model":"gpt-3.5-turbo","documents_per_month_limit":10,"words_per_month_limit":1000,"images_api":"dall-e-2","images_per_month_limit":10,"upscaled_images_per_month_limit":10,"transcriptions_per_month_limit":10,"transcriptions_file_size_limit":2,"chats_model":"gpt-3.5-turbo","chats_per_month_limit":10,"chat_messages_per_chat_limit":100,"syntheses_per_month_limit":10,"synthesized_characters_per_month_limit":1000,"projects_limit":10,"teams_limit":0,"team_members_limit":0,"api_is_enabled":true,"affiliate_commission_percentage":0,"no_ads":true}}'),
('payment', '{"is_enabled":false,"type":"both","default_payment_frequency":"monthly","currencies":{"USD":{"code":"USD","symbol":"$","default_payment_processor":"offline_payment"}},"default_currency":"USD","codes_is_enabled":true,"taxes_and_billing_is_enabled":true,"invoice_is_enabled":true,"user_plan_expiry_reminder":0,"user_plan_expiry_checker_is_enabled":0,"currency_exchange_api_key":""}'),
('paypal', '{\"is_enabled\":\"0\",\"mode\":\"sandbox\",\"client_id\":\"\",\"secret\":\"\"}'),
('stripe', '{\"is_enabled\":\"0\",\"publishable_key\":\"\",\"secret_key\":\"\",\"webhook_secret\":\"\"}'),
('offline_payment', '{\"is_enabled\":\"0\",\"instructions\":\"Your offline payment instructions go here..\"}'),
('coinbase', '{"is_enabled":false,"api_key":"","webhook_secret":"","currencies":["USD"]}'),
('payu', '{"is_enabled":false,"mode":"sandbox","merchant_pos_id":"","signature_key":"","oauth_client_id":"","oauth_client_secret":"","currencies":["USD"]}'),
('iyzico', '{"is_enabled":false,"mode":"live","api_key":"","secret_key":"","currencies":["USD"]}'),
('paystack', '{"is_enabled":false,"public_key":"","secret_key":"","currencies":["USD"]}'),
('razorpay', '{"is_enabled":false,"key_id":"","key_secret":"","webhook_secret":"","currencies":["USD"]}'),
('mollie', '{"is_enabled":false,"api_key":""}'),
('yookassa', '{"is_enabled":false,"shop_id":"","secret_key":""}'),
('crypto_com', '{"is_enabled":false,"publishable_key":"","secret_key":"","webhook_secret":""}'),
('paddle', '{"is_enabled":false,"mode":"sandbox","vendor_id":"","api_key":"","public_key":"","currencies":["USD"]}'),
('mercadopago', '{"is_enabled":false,"access_token":"","currencies":["USD"]}'),
('midtrans', '{"is_enabled":false,"server_key":"","mode":"sandbox","currencies":["USD"]}'),
('flutterwave', '{"is_enabled":false,"secret_key":"","currencies":["USD"]}'),
('smtp', '{"from_name":"AltumCode","from":"","reply_to_name":"","reply_to":"","cc":"","bcc":"","host":"","encryption":"tls","port":"","auth":0,"username":"","password":"","display_socials":false,"company_details":""}'),
('theme', '{"light_is_enabled": false, "dark_is_enabled": false}'),
('custom', '{"body_content":"","head_js":"","head_css":""}'),
('socials', '{"threads":"","youtube":"","facebook":"","x":"","instagram":"","tiktok":"","linkedin":"","whatsapp":"","email":""}'),
('announcements', '{"guests_is_enabled":0,"guests_id":"035cc337f6de075434bc24807b7ad9af","guests_content":"","guests_text_color":"#000000","guests_background_color":"#000000","users_is_enabled":0,"users_id":"035cc337f6de075434bc24807b7ad9af","users_content":"","users_text_color":"#000000","users_background_color":"#000000","translations":{"english":{"guests_content":"","users_content":""}}}'),
('business', '{\"brand_name\":\"66aix\",\"invoice_nr_prefix\":\"INVOICE-\",\"name\":\"\",\"address\":\"\",\"city\":\"\",\"county\":\"\",\"zip\":\"\",\"country\":\"AF\",\"email\":\"\",\"phone\":\"\",\"tax_type\":\"\",\"tax_id\":\"\",\"custom_key_one\":\"\",\"custom_value_one\":\"\",\"custom_key_two\":\"\",\"custom_value_two\":\"\"}'),
('webhooks', '{"user_new":"","user_delete":"","payment_new":"","code_redeemed":"","contact":"","cron_start":"","cron_end":"","domain_new":"","domain_update":""}'),
('cookie_consent', '{"is_enabled":false,"logging_is_enabled":false,"necessary_is_enabled":true,"analytics_is_enabled":true,"targeting_is_enabled":true,"layout":"bar","position_y":"middle","position_x":"center"}'),
('aix', '{"openai_api_key":"","documents_is_enabled":true,"images_is_enabled":true,"transcriptions_is_enabled":true,"images_display_latest_on_index":true,"input_moderation_is_enabled":null,"documents_available_languages":["English","Chinese","Hindi","Spanish","French","Arabic","Bengali","Russian","Portuguese","Indonesian","Urdu","German","Japanese","Punjabi","Javanese","Telugu","Turkish","Marathi","Hungarian","Romanian","Italian","Ukrainian","Polish","Greek","Swedish","Czech","Serbian","Bulgarian","Croatian","Hebrew"],"images_available_artists":["Leonardo da Vinci","Vincent van Gogh","Pablo Picasso","Salvador Dali","Banksy","Takashi Murakami","George Condo","Tim Burton","Normal Rockwell","Andy Warhol","Claude Monet"],"chats_is_enabled":"on","chats_assistant_name":"66aix bot","chats_avatar":"","access_key":"","secret_access_key":"","region":"eu-central-1","syntheses_is_enabled":true}'),
('license', '{\"license\":\"weadown.com\",\"type\":\"Extended License\"}'),
('product_info', '{\"version\":\"30.0.0\", \"code\":\"3000\"}');

-- SEPARATOR --

CREATE TABLE `projects` (
`project_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`name` varchar(128) DEFAULT NULL,
`color` varchar(16) DEFAULT '#000',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`project_id`),
UNIQUE KEY `project_id` (`project_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

CREATE TABLE `templates_categories` (
`template_category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(128) DEFAULT NULL,
`settings` text,
`icon` varchar(32) DEFAULT NULL,
`emoji` varchar(32) DEFAULT NULL,
`color` varchar(16) DEFAULT NULL,
`background` varchar(16) DEFAULT NULL,
`order` int DEFAULT NULL,
`is_enabled` tinyint unsigned DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`template_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `templates_categories` (`template_category_id`, `name`, `settings`, `icon`, `emoji`, `color`, `background`, `order`, `is_enabled`, `datetime`, `last_datetime`) VALUES
(1, 'Text', '{\"translations\":{\"english\":{\"name\":\"Text\"}}}', 'fas fa-paragraph', '📝', '#14b8a6', '#f0fdfa', 1, 1, '2023-03-25 17:33:19', NULL),
(2, 'Website', '{\"translations\":{\"english\":{\"name\":\"Website\"}}}', 'fas fa-globe', '🌐', '#0ea5e9', '#f0f9ff', 1, 1, '2023-03-25 17:33:19', NULL),
(3, 'Social Media', '{\"translations\":{\"english\":{\"name\":\"Social Media\"}}}', 'fas fa-hashtag', '🕊️', '#3b82f6', '#eff6ff', 1, 1, '2023-03-25 17:33:19', NULL),
(4, 'Others', '{\"translations\":{\"english\":{\"name\":\"Others\"}}}', 'fas fa-fire', '🔥', '#6366f1', '#eef2ff', 1, 1, '2023-03-25 17:33:19', NULL),
(5, 'Developers', '{\"translations\":{\"english\":{\"name\":\"Developers\"}}}', 'fas fa-code', '💻', '#DB00FF', '#FCE9FF', 1, 1, '2023-04-19 20:00:55', NULL);

-- SEPARATOR --

CREATE TABLE `templates` (
`template_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`template_category_id` bigint unsigned DEFAULT NULL,
`name` varchar(128) DEFAULT NULL,
`prompt` text,
`settings` text,
`icon` varchar(32) DEFAULT NULL,
`order` int DEFAULT NULL,
`total_usage` bigint unsigned DEFAULT '0',
`is_enabled` tinyint unsigned DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`template_id`),
KEY `template_category_id` (`template_category_id`),
CONSTRAINT `templates_ibfk_1` FOREIGN KEY (`template_category_id`) REFERENCES `templates_categories` (`template_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `templates` (`template_category_id`, `name`, `prompt`, `settings`, `icon`, `order`, `total_usage`, `is_enabled`, `datetime`, `last_datetime`) VALUES
(1, 'Summarize', 'Summarize the following text: {text}', '{\"translations\":{\"english\":{\"name\":\"Summarize\",\"description\":\"Get a quick summary of a long piece of text, only the important parts.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to summarize\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-align-left', 1, 1, 1, '2023-03-25 23:28:59', NULL),
(1, 'Explain like I am 5', 'Explain & summarize the following text like I am 5: {text}', '{\"translations\":{\"english\":{\"name\":\"Explain like I am 5\",\"description\":\"Get a better understanding on a topic, subject or piece of text.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or concept to explain\",\"placeholder\":\"How does a rocket go into space?\",\"help\":null}}}}}', 'fas fa-child', 2, 1, 1, '2023-03-25 23:28:59', NULL),
(1, 'Text spinner/rewriter', 'Rewrite the following text in a different manner: {text}', '{\"translations\":{\"english\":{\"name\":\"Text spinner/rewriter\",\"description\":\"Rewrite a piece of text in another unique way, using different words.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to rewrite\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-sync', 3, 1, 1, '2023-03-25 23:28:59', NULL),
(1, 'Keywords generator', 'Extract important keywords from the following text: {text}', '{\"translations\":{\"english\":{\"name\":\"Keywords generator\",\"description\":\"Extract important keywords from a piece of text.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to extract keywords from\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-key', 4, 1, 1, '2023-03-25 23:28:59', NULL),
(1, 'Grammar fixer', 'Fix the grammar on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Grammar fixer\",\"description\":\"Make sure your text is written correctly with no spelling or grammar errors.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to grammar fix\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-spell-check', 5, 1, 1, '2023-03-25 23:28:59', NULL),
(1, 'Text to Emoji', 'Transform the following text into emojis: {text}', '{\"translations\":{\"english\":{\"name\":\"Text to Emoji\",\"description\":\"Convert the meaning of a piece of text to fun emojis.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to convert\",\"placeholder\":\"The pirates of the Caribbean\",\"help\":null}}}}}', 'fas fa-smile-wink', 6, 1, 1, '2023-03-25 23:28:59', NULL),
(1, 'Blog Article Idea', 'Write multiple blog article ideas based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Blog Article Idea\",\"description\":\"Generate interesting blog article ideas based on the topics that you want.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":\"Best places to travel as a couple\",\"help\":null}}}}}', 'fas fa-lightbulb', 7, 1, 1, '2023-03-25 23:29:00', NULL),
(1, 'Blog Article Intro', 'Write a good intro for a blog article, based on the title of the blog post: {text}', '{\"translations\":{\"english\":{\"name\":\"Blog Article Intro\",\"description\":\"Generate a creative intro section for your blog article.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title of the blog article\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-keyboard', 8, 1, 1, '2023-03-25 23:29:00', NULL),
(1, 'Blog Article Idea & Outline', 'Write ideas for a blog article title and outline, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Blog Article Idea & Outline\",\"description\":\"Generate unlimited blog article ideas and structure with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-blog', 9, 1, 1, '2023-03-25 23:29:00', NULL),
(1, 'Blog Article Section', 'Write a blog sections about \"{title}\" using the \"{keywords}\" keywords', '{\"translations\":{\"english\":{\"name\":\"Blog Article Section\",\"description\":\"Generate a full and unique section/paragraph for your blog article.\"}},\"inputs\":{\"title\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title\",\"placeholder\":\"Best traveling tips and tricks\",\"help\":null}}},\"keywords\":{\"icon\":\"fas fa-file-word\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Keywords\",\"placeholder\":\"Airport luggage, Car rentals, Quality Airbnb stays\",\"help\":null}}}}}', 'fas fa-rss', 10, 1, 1, '2023-03-25 23:29:00', NULL),
(1, 'Blog Article', 'Write a long article / blog post on \"{title}\" with the \"{keywords}\" keywords and the following sections \"{sections}\"', '{\"translations\":{\"english\":{\"name\":\"Blog Article\",\"description\":\"Generate a simple and creative article / blog post for your website.\"}},\"inputs\":{\"title\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title\",\"placeholder\":\"Places you must visit in winter\",\"help\":null}}},\"keywords\":{\"icon\":\"fas fa-file-word\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Keywords\",\"placeholder\":\"Winter, Hotel, Jacuzzi, Spa, Ski\",\"help\":null}}},\"sections\":{\"icon\":\"fas fa-feather\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Sections\",\"placeholder\":\"Austria, Italy, Switzerland\",\"help\":null}}}}}', 'fas fa-feather', 11, 1, 1, '2023-03-25 23:29:00', NULL),
(1, 'Blog Article Outro', 'Write a blog article outro based on the blog title \"{title}\" and the \"{description}\" description', '{\"translations\":{\"english\":{\"name\":\"Blog Article Outro\",\"description\":\"Generate the conclusion section of your blog article.\"}},\"inputs\":{\"title\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Title\",\"placeholder\":\"Warm places to visit in December\",\"help\":null}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"Describe what your blog article is about\",\"help\":null}}}}}', 'fas fa-pen-nib', 12, 1, 1, '2023-03-25 23:29:00', NULL),
(1, 'Reviews', 'Write a review or testimonial about \"{name}\" using the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Reviews\",\"description\":\"Generate creative reviews / testimonials for your service or product.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"Wandering Agency: Travel with confidence\",\"help\":null}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"We plan and set up your perfect traveling experience to the most exotic places, from start to finish.\",\"help\":null}}}}}', 'fas fa-star', 13, 1, 1, '2023-03-25 23:29:00', NULL),
(1, 'Translate', 'Translate the following text: {text}', '{\"translations\":{\"english\":{\"name\":\"Translate\",\"description\":\"Translate a piece of text to another language with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-language', 14, 1, 1, '2023-03-25 23:29:00', NULL),
(3, 'Social media bio', 'Write a short social media bio profile description based on those keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Social media bio\",\"description\":\"Generate Twitter, Instagram, TikTok bio for your account.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-file-word\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Keywords to be used\",\"placeholder\":\"Yacht traveling, Boat charter, Summer, Sailing\",\"help\":null}}}}}', 'fas fa-share-alt', 15, 1, 1, '2023-03-25 23:29:00', NULL),
(3, 'Social media hashtags', 'Generate hashtags for a social media post based on the following description: {text}', '{\"translations\":{\"english\":{\"name\":\"Social media hashtags\",\"description\":\"Generate hashtags for your social media posts.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to extract hashtags from\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-hashtag', 16, 1, 1, '2023-03-25 23:29:00', NULL),
(3, 'Video Idea', 'Write ideas for a video scenario, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Video Idea\",\"description\":\"Generate a random video idea based on the topics that you want.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-video', 17, 1, 1, '2023-03-25 23:29:00', NULL),
(3, 'Video Title', 'Write a video title, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Video Title\",\"description\":\"Generate a catchy video title for your video.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-play', 18, 1, 1, '2023-03-25 23:29:00', NULL),
(3, 'Video Description', 'Write a video description, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Video Description\",\"description\":\"Generate a brief and quality video description.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Title or Keywords\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-film', 19, 1, 1, '2023-03-25 23:29:00', NULL),
(3, 'Tweet generator', 'Generate a tweet based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Tweet generator\",\"description\":\"Generate tweets based on your ideas/topics/keywords.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fab fa-twitter', 20, 1, 1, '2023-03-25 23:29:00', NULL),
(3, 'Instagram caption', 'Generate an instagram caption for a post based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Instagram caption\",\"description\":\"Generate an instagram post caption based on text or keywords.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fab fa-instagram', 21, 1, 1, '2023-03-25 23:29:00', NULL),
(2, 'Website Headline', 'Write a website short headline for the \"{name}\" product with the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Website Headline\",\"description\":\"Generate creative, catchy and unique headlines for your website.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"Sunset Agents: Best summer destinations\",\"help\":null}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"Our blog helps you find and plan your next summer vacation.\",\"help\":null}}}}}', 'fas fa-feather', 22, 1, 1, '2023-03-25 23:29:00', NULL),
(2, 'SEO Title', 'Write an SEO Title for a web page based on those keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"SEO Title\",\"description\":\"Generate high quality & SEO ready titles for your web pages.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-file-word\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Keywords to be used\",\"placeholder\":\"Traveling, Summer, Beach, Pool\",\"help\":null}}}}}', 'fas fa-heading', 23, 1, 1, '2023-03-25 23:29:00', NULL),
(2, 'SEO Description', 'Write an SEO description, maximum 160 characters, for a web page based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"SEO Description\",\"description\":\"Generate proper descriptions for your web pages to help you rank better\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-pen', 24, 1, 1, '2023-03-25 23:29:00', NULL),
(2, 'SEO Keywords', 'Write SEO meta keywords, maximum 160 characters, for a web page based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"SEO Keywords\",\"description\":\"Extract and generate meaningful and quality keywords for your website.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text to extract keywords from\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-file-word', 25, 1, 1, '2023-03-25 23:29:00', NULL),
(2, 'Ad Title', 'Write a short ad title, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Ad Title\",\"description\":\"Generate a short & good title copy for any of your ads.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-money-check-alt', 26, 1, 1, '2023-03-25 23:29:00', NULL),
(2, 'Ad Description', 'Write a short ad description, based on the following: {text}', '{\"translations\":{\"english\":{\"name\":\"Ad Description\",\"description\":\"Generate the description for an ad campaign.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-th-list', 27, 1, 1, '2023-03-25 23:29:00', NULL),
(4, 'Name generator', 'Generate multiple & relevant product names based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Name generator\",\"description\":\"Generate interesting product names for your project.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-file-signature', 28, 1, 1, '2023-03-25 23:29:00', NULL),
(4, 'Startup ideas', 'Generate multiple & relevant startup business ideas based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Startup ideas\",\"description\":\"Generate startup ideas based on your topic inputs.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-user-tie', 29, 1, 1, '2023-03-25 23:29:00', NULL),
(4, 'Viral ideas', 'Generate a viral idea based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Viral ideas\",\"description\":\"Generate highly viral probability ideas based on your topics or keywords.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":null,\"help\":null}}}}}', 'fas fa-bolt', 30, 1, 1, '2023-03-25 23:29:01', NULL),
(4, 'Custom prompt', '{text}', '{\"translations\":{\"english\":{\"name\":\"Custom prompt\",\"description\":\"Ask our AI for anything & he will do it is best to give you quality content.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Question or task\",\"placeholder\":\"What are the top 5 most tourist friendly destinations?\",\"help\":null}}}}}', 'fas fa-star', 31, 1, 1, '2023-03-25 23:29:23', NULL),
(5, 'PHP snippet', 'You are a PHP programmer, answer the following request with a PHP snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"PHP snippet\",\"description\":\"Generate PHP code snippets with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that connects to a MySQL database in procedural style\",\"help\":\"Ask the AI what PHP code you want to receive \\/ get help with.\"}}}}}', 'fab fa-php', 32, 1, 1, '2023-04-19 20:18:43', NULL),
(5, 'SQL query', 'You are a SQL programmer, answer the following request with an SQL query:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"SQL query\",\"description\":\"Generate helpful SQL queries with the help of AI.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested query\",\"placeholder\":\"Code that calculates the average from 3 columns\",\"help\":\"Ask the AI what SQL query you want to receive \\/ get help with.\"}}}}}', 'fas fa-database', 33, 1, 1, '2023-04-19 21:06:04', '2023-04-19 21:10:50'),
(5, 'JS snippet', 'You are a JS programmer, answer the following request with a JS snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"JS snippet\",\"description\":\"Generate quick & helpful Javascript code snippets.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that helps trigger and catch custom events\",\"help\":\"Ask the AI what JS code you want to receive \\/ get help with.\"}}}}}', 'fab fa-js', 34, 0, 1, '2023-04-19 21:31:37', NULL),
(5, 'HTML snippet', 'You are a HTML programmer, answer the following request with a HTML snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"HTML snippet\",\"description\":\"Generate simple and fast HTML pieces of code.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that generates a blank HTML page\",\"help\":\"Ask the AI what HTML code you want to receive \\/ get help with.\"}}}}}', 'fab fa-html5', 35, 0, 1, '2023-04-19 22:00:58', NULL),
(5, 'CSS snippet', 'You are a CSS programmer, answer the following request with a CSS snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"CSS snippet\",\"description\":\"Generate CSS classes & code snippets with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that generates a gradient background class\",\"help\":\"Ask the AI what CSS code you want to receive \\/ get help with.\"}}}}}', 'fab fa-css3', 36, 0, 1, '2023-04-19 22:03:16', NULL),
(5, 'Python snippet', 'You are a python programmer, answer the following request with a python snippet:\r\n\r\n{text}', '{\"translations\":{\"english\":{\"name\":\"Python snippet\",\"description\":\"Generate Python code pieces with the help of AI.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Requested code\",\"placeholder\":\"Code that sends an external HTTP request\",\"help\":\"Ask the AI what Python code you want to receive \\/ get help with.\"}}}}}', 'fab fa-python', 37, 0, 1, '2023-04-19 22:05:03', NULL),
(1, 'Quote generator', 'Generate a random quote on the following topic: {topic}', '{\"translations\":{\"english\":{\"name\":\"Quote generator\",\"description\":\"Get random quotes based on the topic you wish.\"}},\"inputs\":{\"topic\":{\"icon\":\"fas fa-pen\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Topic\",\"placeholder\":\"Motivational\",\"help\":\"Input the type of quote you wish to generate.\"}}}}}', 'fas fa-bolt', 1, 1, 1, '2023-03-28 20:32:15', '2023-05-13 21:08:06'),
(3, 'LinkedIn post', 'Generate a LinkedIn post based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"LinkedIn post\",\"description\":\"Generate a great LinkedIn post based on text or keywords.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":\"\",\"help\":\"\"}}}}}', 'fab fa-linkedin', 22, 0, 1, '2023-05-13 19:41:14', NULL),
(3, 'Twitter thread generator', 'Generate a full Twitter thread based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Twitter thread generator\",\"description\":\"Generate a full thread based on any topic or idea.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":\"\",\"help\":\"\"}}}}}', 'fab fa-twitter', 23, 0, 1, '2023-05-13 19:49:32', NULL),
(3, 'Pinterest caption', 'Generate a Pinterest caption for a pin based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"Pinterest caption\",\"description\":\"Generate a caption for your pins based on your keywords.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":\"\",\"help\":\"\"}}}}}', 'fab fa-pinterest', 24, 0, 1, '2023-05-13 20:40:38', NULL),
(3, 'TikTok video caption', 'Generate a TikTok video caption based on the following text/keywords: {text}', '{\"translations\":{\"english\":{\"name\":\"TikTok video caption\",\"description\":\"Generate quick & trending captions for your TikTok content with ease.\"}},\"inputs\":{\"text\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Text or keywords to be used\",\"placeholder\":\"\",\"help\":\"\"}}}}}', 'fab fa-tiktok', 25, 0, 1, '2023-05-13 20:42:07', NULL),
(3, 'TikTok video idea', 'Generate a random TikTok video idea in the following niche: {niche}', '{\"translations\":{\"english\":{\"name\":\"TikTok video idea\",\"description\":\"Generate quick & trending video idea your TikTok account.\"}},\"inputs\":{\"niche\":{\"icon\":\"fas fa-pen\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Niche or Category\",\"placeholder\":\"Breakdance tutorials, Interior design principles, Places to visit in New York\",\"help\":\"Input the niche of the idea that you want to get.\"}}}}}', 'fab fa-tiktok', 26, 0, 1, '2023-05-13 20:55:57', '2023-05-13 21:04:22'),
(1, 'Song lyrics', 'Generate song lyrics based the following:\r\n\r\nGenre: {genre}\r\n\r\nTopic: {topic}', '{\"translations\":{\"english\":{\"name\":\"Song lyrics\",\"description\":\"Generate high quality lyrics based for any genre.\"}},\"inputs\":{\"topic\":{\"icon\":\"fas fa-pen\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Topic\",\"placeholder\":\"Heartbreak, Love, Motivational, Dynamic\",\"help\":\"Input the topic of the lyrics you wish to generate.\"}}},\"genre\":{\"icon\":\"fas fa-music\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Genre\",\"placeholder\":\"Rap, Hip Hop, Pop, Rock\",\"help\":\"Input the genre of the lyrics you wish to generate.\"}}}}}', 'fas fa-music', 2, 1, 1, '2023-05-13 21:09:05', '2023-05-13 21:12:38'),
(1, 'Joke generator', 'Generate a random funny joke on the following topic: {topic}', '{\"translations\":{\"english\":{\"name\":\"Joke generator\",\"description\":\"Get random and funny jokes based on the topic you wish.\"}},\"inputs\":{\"topic\":{\"icon\":\"fas fa-pen\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Topic\",\"placeholder\":\"Edgy, Cringe, Modern, Dark humor\",\"help\":\"Input the type of joke you wish to generate.\"}}}}}', 'fas fa-laugh-beam', 2, 0, 1, '2023-05-13 21:17:22', '2023-05-13 21:18:55'),
(2, 'Welcome email', 'Write a welcome email subject and body &#34;{name}&#34; product/service with the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Welcome email\",\"description\":\"Generate great engaging emails for your new users.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"OpenAI\",\"help\":\"\"}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"Our web platform helps users get start with AI with ease.\",\"help\":\"\"}}}}}', 'fas fa-envelope-open', 23, 1, 1, '2023-05-14 09:54:39', '2023-05-14 10:59:45'),
(2, 'Outreach email', 'Write a cold outreach email subject and body &#34;{name}&#34; product/service with the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Outreach email\",\"description\":\"Generate great emails for cold outreach to get more leads.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"OpenAI\",\"help\":\"\"}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"Our web platform helps users get start with AI with ease.\",\"help\":\"\"}}}}}', 'fas fa-envelope', 24, 0, 1, '2023-05-14 10:56:37', '2023-05-14 10:59:51'),
(2, 'Facebook advertisement', 'Generate a Facebook ad copy for the &#34;{name}&#34; product/service with the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Facebook advertisement\",\"description\":\"Generate Facebook optimized ad copy details for a product or service.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"Booking.com\",\"help\":\"\"}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"The largest and most trusted online booking and traveling agencies.\",\"help\":\"\"}}}}}', 'fab fa-facebook', 25, 0, 1, '2023-05-14 11:29:22', '2023-05-14 11:39:04'),
(2, 'Google advertisement', 'Generate a Google ad copy for the &#34;{name}&#34; product/service with the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"Google advertisement\",\"description\":\"Generate Google optimized ad copy details for a product or service.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"Booking.com\",\"help\":\"\"}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"The largest and most trusted online booking and traveling agencies.\",\"help\":\"\"}}}}}', 'fab fa-google', 26, 0, 1, '2023-05-14 11:39:14', '2023-05-14 11:39:51'),
(2, 'LinkedIn advertisement', 'Generate a LinkedIn ad copy for the &#34;{name}&#34; product/service with the following description: {description}', '{\"translations\":{\"english\":{\"name\":\"LinkedIn advertisement\",\"description\":\"Generate LinkedIn optimized ad copy details for a product or service.\"}},\"inputs\":{\"name\":{\"icon\":\"fas fa-heading\",\"type\":\"input\",\"translations\":{\"english\":{\"name\":\"Product or service name\",\"placeholder\":\"Booking.com\",\"help\":\"\"}}},\"description\":{\"icon\":\"fas fa-paragraph\",\"type\":\"textarea\",\"translations\":{\"english\":{\"name\":\"Description\",\"placeholder\":\"The largest and most trusted online booking and traveling agencies.\",\"help\":\"\"}}}}}', 'fab fa-linkedin', 27, 0, 1, '2023-05-14 11:40:12', '2023-05-14 11:40:37');



-- SEPARATOR --

CREATE TABLE `documents` (
`document_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`project_id` bigint unsigned DEFAULT NULL,
`template_id` bigint unsigned DEFAULT NULL,
`template_category_id` bigint unsigned DEFAULT NULL,
`name` varchar(64) DEFAULT NULL,
`type` varchar(32) DEFAULT NULL,
`input` text,
`content` text,
`words` int unsigned DEFAULT NULL,
`settings` text,
`model` varchar(64) DEFAULT NULL,
`api_response_time` int unsigned DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`document_id`),
KEY `user_id` (`user_id`),
KEY `project_id` (`project_id`),
KEY `documents_templates_template_id_fk` (`template_id`),
KEY `documents_templates_categories_template_category_id_fk` (`template_category_id`),
CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `documents_templates_categories_template_category_id_fk` FOREIGN KEY (`template_category_id`) REFERENCES `templates_categories` (`template_category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `documents_templates_template_id_fk` FOREIGN KEY (`template_id`) REFERENCES `templates` (`template_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `images` (
`image_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`project_id` bigint unsigned DEFAULT NULL,
`variants_ids` text,
`name` varchar(64) DEFAULT NULL,
`input` text,
`image` varchar(40) DEFAULT NULL,
`style` varchar(128) DEFAULT NULL,
`artist` varchar(128) DEFAULT NULL,
`lighting` varchar(128) DEFAULT NULL,
`mood` varchar(128) DEFAULT NULL,
`size` varchar(16) DEFAULT NULL,
`settings` text,
`api` varchar(64) DEFAULT NULL,
`api_response_time` int unsigned DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`image_id`),
KEY `user_id` (`user_id`),
KEY `project_id` (`project_id`),
CONSTRAINT `images_projects_project_id_fk` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `images_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `upscaled_images` (
`upscaled_image_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`project_id` bigint unsigned DEFAULT NULL,
`name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`original_image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`upscaled_image` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`original_size` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`upscaled_size` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`scale` tinyint unsigned DEFAULT NULL,
`settings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
`api_response_time` int unsigned DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`upscaled_image_id`),
KEY `user_id` (`user_id`),
KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --


CREATE TABLE `removed_background_images` (
`removed_background_image_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`project_id` bigint unsigned DEFAULT NULL,
`name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`original_image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`removed_background_image` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
`api_response_time` int unsigned DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`removed_background_image_id`),
KEY `user_id` (`user_id`),
KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `replaced_background_images` (
`replaced_background_image_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`project_id` bigint unsigned DEFAULT NULL,
`name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`input` text,
`original_image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`replaced_background_image` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
`api_response_time` int unsigned DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`replaced_background_image_id`),
KEY `user_id` (`user_id`),
KEY `project_id` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `transcriptions` (
`transcription_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`project_id` bigint unsigned DEFAULT NULL,
`name` varchar(64) DEFAULT NULL,
`input` text,
`content` text,
`words` int unsigned DEFAULT NULL,
`language` varchar(32) DEFAULT NULL,
`settings` text,
`api_response_time` int unsigned DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`transcription_id`),
KEY `user_id` (`user_id`),
KEY `project_id` (`project_id`),
CONSTRAINT `transcriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `transcriptions_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `syntheses` (
`synthesis_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`project_id` bigint unsigned DEFAULT NULL,
`name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`input` text COLLATE utf8mb4_unicode_ci,
`file` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`language` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`format` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

CREATE TABLE `chats_assistants` (
`chat_assistant_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`prompt` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`image` varchar(404) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`order` int DEFAULT NULL,
`total_usage` bigint unsigned DEFAULT '0',
`is_enabled` tinyint unsigned DEFAULT '1',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`chat_assistant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `chats` (
`chat_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`user_id` bigint unsigned DEFAULT NULL,
`chat_assistant_id` bigint unsigned DEFAULT NULL,
`name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`settings` text COLLATE utf8mb4_unicode_ci,
`total_messages` int unsigned DEFAULT '0',
`used_tokens` int unsigned DEFAULT '0',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`chat_id`),
KEY `user_id` (`user_id`),
KEY `chats_chats_assistants_chat_assistant_id_fk` (`chat_assistant_id`),
CONSTRAINT `chats_chats_assistants_chat_assistant_id_fk` FOREIGN KEY (`chat_assistant_id`) REFERENCES `chats_assistants` (`chat_assistant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `chats_messages` (
`chat_message_id` bigint unsigned NOT NULL AUTO_INCREMENT,
`chat_id` bigint unsigned DEFAULT NULL,
`user_id` bigint unsigned DEFAULT NULL,
`role` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`content` text COLLATE utf8mb4_unicode_ci,
`image` varchar(40) DEFAULT NULL,
`model` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`api_response_time` int unsigned DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`chat_message_id`),
KEY `chat_id` (`chat_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `chats_messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`chat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `chats_messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `chats_assistants` (`chat_assistant_id`, `name`, `prompt`, `settings`, `image`, `order`, `total_usage`, `is_enabled`, `last_datetime`, `datetime`) VALUES (1, 'General Assistant', 'You are a general assistant that can help with anything.', '{\"translations\":{\"english\":{\"name\":\"General Assistant\",\"description\":\"I can help you with any general task or question.\"}}}', 'de618ff8b13d6aa0b7df3b91b16cb452.png', 0, 0, 1, null, NOW());

-- SEPARATOR --

CREATE TABLE `codes` (
  `code_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) DEFAULT NULL,
  `type` VARCHAR(16) DEFAULT NULL,
  `days` INT(11) UNSIGNED NULL COMMENT 'only applicable if type is redeemable',
  `code` VARCHAR(32) NOT NULL DEFAULT '',
  `discount` INT(11) UNSIGNED NOT NULL,
  `quantity` INT(11) UNSIGNED DEFAULT 1 NOT NULL,
  `redeemed` INT(11) UNSIGNED DEFAULT 0 NOT NULL,
  `plans_ids` TEXT NULL,
  `datetime` DATETIME NOT NULL,
  PRIMARY KEY (`code_id`),
  KEY `type` (`type`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `payments` (
  `id` INT(11) UNSIGNED NOT NULL,
  `user_id` INT(11) DEFAULT NULL,
  `plan_id` INT(11) DEFAULT NULL,
  `processor` VARCHAR(16) DEFAULT NULL,
  `type` VARCHAR(16) DEFAULT NULL,
  `frequency` VARCHAR(16) DEFAULT NULL,
  `payment_id` VARCHAR(128) DEFAULT NULL,
  `email` VARCHAR(256) DEFAULT NULL,
  `name` VARCHAR(256) DEFAULT NULL,
  `plan` TEXT DEFAULT NULL,
  `billing` TEXT DEFAULT NULL,
  `business` TEXT DEFAULT NULL,
  `taxes_ids` TEXT DEFAULT NULL,
  `base_amount` FLOAT DEFAULT NULL,
  `total_amount` FLOAT DEFAULT NULL,
  `total_amount_default_currency` FLOAT NULL,
  `code` VARCHAR(32) DEFAULT NULL,
  `discount_amount` FLOAT DEFAULT NULL,
  `currency` VARCHAR(4) DEFAULT NULL,
  `payment_proof` VARCHAR(40) DEFAULT NULL,
  `status` TINYINT(4) DEFAULT 1,
  `datetime` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `redeemed_codes` (
  `id` INT(11) NOT NULL,
  `code_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `datetime` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `taxes` (
  `tax_id` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(64) DEFAULT NULL,
  `description` VARCHAR(256) DEFAULT NULL,
  `value` INT(11) DEFAULT NULL,
  `value_type` ENUM('percentage','fixed') DEFAULT NULL,
  `type` ENUM('inclusive','exclusive') DEFAULT NULL,
  `billing_type` ENUM('personal','business','both') DEFAULT NULL,
  `countries` TEXT DEFAULT NULL,
  `datetime` DATETIME DEFAULT NULL,
  PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;