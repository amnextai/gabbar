UPDATE `settings` SET `value` = '{\"version\":\"2.0.0\", \"code\":\"200\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table blog_posts modify url varchar(256) not null;

-- SEPARATOR --

alter table blog_posts modify title varchar(256) not null DEFAULT '';

-- SEPARATOR --

alter table blog_posts modify description varchar(256) DEFAULT NULL;

-- SEPARATOR --

alter table blog_posts_categories modify url varchar(256) not null;

-- SEPARATOR --

alter table blog_posts_categories modify description varchar(256) DEFAULT NULL;

-- SEPARATOR --

alter table blog_posts_categories modify title varchar(256) not null DEFAULT '';

-- SEPARATOR --

alter table pages modify url varchar(256) not null;

-- SEPARATOR --

alter table pages modify title varchar(256) not null default '';

-- SEPARATOR --

alter table pages modify description varchar(256) default null;

-- SEPARATOR --

alter table pages_categories modify url varchar(256) not null;

-- SEPARATOR --

alter table pages_categories modify title varchar(256) not null default '';

-- SEPARATOR --

alter table pages_categories modify description varchar(256) default null;

-- SEPARATOR --

UPDATE `settings` SET `value` = '{"openai_api_key":"","documents_is_enabled":true,"images_is_enabled":true,"input_moderation_is_enabled":true,"available_types":{"summarize":true,"explain_for_a_kid":true,"spinner":true,"keywords_generator":true,"grammar_fixer":true,"social_bio":true,"seo_title":true,"seo_description":true,"seo_keywords":true,"blog_article_idea_and_outline":true,"blog_article_section":true,"video_idea":true,"video_title":true,"video_description":true,"ad_title":true,"ad_description":true,"text_to_emoji":true,"tweet":true,"instagram_caption":true,"name_generator":true,"startup_ideas":true,"viral_ideas":true,"custom":true},"documents_available_languages":["English","Romanian","Italian","French","Spanish"]}' WHERE `key` = 'aix';
