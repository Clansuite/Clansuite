-- phpMyAdmin SQL Dump
-- version 2.8.0.3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 19. Juli 2006 um 14:40
-- Server Version: 5.0.20
-- PHP-Version: 5.1.2
-- 
-- Datenbank: `clansuite`
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_adminmenu`
-- 

DROP TABLE IF EXISTS `cs_adminmenu`;
CREATE TABLE `cs_adminmenu` (
  `id` tinyint(3) unsigned NOT NULL default '0',
  `parent` tinyint(3) unsigned NOT NULL default '0',
  `type` varchar(255) collate latin1_german1_ci NOT NULL default '',
  `text` varchar(255) collate latin1_german1_ci NOT NULL default '',
  `href` varchar(255) collate latin1_german1_ci NOT NULL default '',
  `title` varchar(255) collate latin1_german1_ci NOT NULL default '',
  `target` varchar(255) collate latin1_german1_ci NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- 
-- Daten für Tabelle `cs_adminmenu`
-- 

UPDATE `cs_adminmenu` SET `id` = 4, `parent` = 0, `type` = 'button', `text` = 'System', `href` = '', `title` = 'System', `target` = '_self' WHERE  `id` = 4 AND `parent` = 0 AND CONVERT(`type` USING utf8) = 'button' AND CONVERT(`text` USING utf8) = 'System' AND CONVERT(`href` USING utf8) = '' AND CONVERT(`title` USING utf8) = 'System' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 3, `parent` = 0, `type` = 'button', `text` = 'Categories', `href` = 'admin/categories/index.php', `title` = 'Categories', `target` = '_self' WHERE  `id` = 3 AND `parent` = 0 AND CONVERT(`type` USING utf8) = 'button' AND CONVERT(`text` USING utf8) = 'Categories' AND CONVERT(`href` USING utf8) = 'admin/categories/index.php' AND CONVERT(`title` USING utf8) = 'Categories' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 6, `parent` = 0, `type` = 'button', `text` = 'Hilfe', `href` = '', `title` = 'Hilfe', `target` = '_self' WHERE  `id` = 6 AND `parent` = 0 AND CONVERT(`type` USING utf8) = 'button' AND CONVERT(`text` USING utf8) = 'Hilfe' AND CONVERT(`href` USING utf8) = '' AND CONVERT(`title` USING utf8) = 'Hilfe' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 1, `parent` = 0, `type` = 'button', `text` = 'Home', `href` = 'index.php', `title` = 'Home', `target` = '_self' WHERE  `id` = 1 AND `parent` = 0 AND CONVERT(`type` USING utf8) = 'button' AND CONVERT(`text` USING utf8) = 'Home' AND CONVERT(`href` USING utf8) = 'index.php' AND CONVERT(`title` USING utf8) = 'Home' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 2, `parent` = 0, `type` = 'button', `text` = 'Modules', `href` = '', `title` = 'Modules', `target` = '_self' WHERE  `id` = 2 AND `parent` = 0 AND CONVERT(`type` USING utf8) = 'button' AND CONVERT(`text` USING utf8) = 'Modules' AND CONVERT(`href` USING utf8) = '' AND CONVERT(`title` USING utf8) = 'Modules' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 7, `parent` = 6, `type` = 'item', `text` = 'Hilfe', `href` = 'help.php', `title` = 'Hilfe', `target` = '_self' WHERE  `id` = 7 AND `parent` = 6 AND CONVERT(`type` USING utf8) = 'item' AND CONVERT(`text` USING utf8) = 'Hilfe' AND CONVERT(`href` USING utf8) = 'help.php' AND CONVERT(`title` USING utf8) = 'Hilfe' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 8, `parent` = 6, `type` = 'item', `text` = 'Handbuch', `href` = 'manual.php', `title` = 'Handbuch', `target` = '_self' WHERE  `id` = 8 AND `parent` = 6 AND CONVERT(`type` USING utf8) = 'item' AND CONVERT(`text` USING utf8) = 'Handbuch' AND CONVERT(`href` USING utf8) = 'manual.php' AND CONVERT(`title` USING utf8) = 'Handbuch' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 5, `parent` = 0, `type` = 'button', `text` = 'Users', `href` = 'admin/users/index.php', `title` = 'Users', `target` = '_self' WHERE  `id` = 5 AND `parent` = 0 AND CONVERT(`type` USING utf8) = 'button' AND CONVERT(`text` USING utf8) = 'Users' AND CONVERT(`href` USING utf8) = 'admin/users/index.php' AND CONVERT(`title` USING utf8) = 'Users' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 9, `parent` = 6, `type` = 'item', `text` = 'Report Bug & Give Feedback', `href` = 'bugreport.php', `title` = 'Report Bug & Give Feedback', `target` = '_self' WHERE  `id` = 9 AND `parent` = 6 AND CONVERT(`type` USING utf8) = 'item' AND CONVERT(`text` USING utf8) = 'Report Bug & Give Feedback' AND CONVERT(`href` USING utf8) = 'bugreport.php' AND CONVERT(`title` USING utf8) = 'Report Bug & Give Feedback' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 10, `parent` = 6, `type` = 'item', `text` = 'Über Clansuite', `href` = 'about.php', `title` = 'Über Clansuite', `target` = '_self' WHERE  `id` = 10 AND `parent` = 6 AND CONVERT(`type` USING utf8) = 'item' AND CONVERT(`text` USING utf8) = 'Über Clansuite' AND CONVERT(`href` USING utf8) = 'about.php' AND CONVERT(`title` USING utf8) = 'Über Clansuite' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 13, `parent` = 4, `type` = 'item', `text` = 'Menüeditor', `href` = 'admin/menueditor.php', `title` = 'Menüeditor', `target` = '_self' WHERE  `id` = 13 AND `parent` = 4 AND CONVERT(`type` USING utf8) = 'item' AND CONVERT(`text` USING utf8) = 'Menüeditor' AND CONVERT(`href` USING utf8) = 'admin/menueditor.php' AND CONVERT(`title` USING utf8) = 'Menüeditor' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 14, `parent` = 4, `type` = 'item', `text` = 'Templateeditor', `href` = 'admin/templateeditor.php', `title` = 'TemplateEditor', `target` = '_self' WHERE  `id` = 14 AND `parent` = 4 AND CONVERT(`type` USING utf8) = 'item' AND CONVERT(`text` USING utf8) = 'Templateeditor' AND CONVERT(`href` USING utf8) = 'admin/templateeditor.php' AND CONVERT(`title` USING utf8) = 'TemplateEditor' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 15, `parent` = 20, `type` = 'item', `text` = 'Show all modules', `href` = '/index.php?mod=admin&sub=admin_modules&action=show_all', `title` = 'Show all modules', `target` = '_self' WHERE  `id` = 15 AND `parent` = 20 AND CONVERT(`type` USING utf8) = 'item' AND CONVERT(`text` USING utf8) = 'Show all modules' AND CONVERT(`href` USING utf8) = '/index.php?mod=admin&sub=admin_modules&action=show_all' AND CONVERT(`title` USING utf8) = 'Show all modules' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 16, `parent` = 20, `type` = 'item', `text` = 'Install new modules', `href` = '/index.php?mod=admin&sub=admin_modules&action=install_new', `title` = 'Install new modules', `target` = '_self' WHERE  `id` = 16 AND `parent` = 20 AND CONVERT(`type` USING utf8) = 'item' AND CONVERT(`text` USING utf8) = 'Install new modules' AND CONVERT(`href` USING utf8) = '/index.php?mod=admin&sub=admin_modules&action=install_new' AND CONVERT(`title` USING utf8) = 'Install new modules' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 17, `parent` = 20, `type` = 'item', `text` = 'Export a module', `href` = '/index.php?mod=admin&sub=admin_modules&action=export', `title` = 'Export a module', `target` = '_self' WHERE  `id` = 17 AND `parent` = 20 AND CONVERT(`type` USING utf8) = 'item' AND CONVERT(`text` USING utf8) = 'Export a module' AND CONVERT(`href` USING utf8) = '/index.php?mod=admin&sub=admin_modules&action=export' AND CONVERT(`title` USING utf8) = 'Export a module' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 18, `parent` = 20, `type` = 'item', `text` = 'Import a module', `href` = '/index.php?mod=admin&sub=admin_modules&action=import', `title` = 'Import a module', `target` = '_self' WHERE  `id` = 18 AND `parent` = 20 AND CONVERT(`type` USING utf8) = 'item' AND CONVERT(`text` USING utf8) = 'Import a module' AND CONVERT(`href` USING utf8) = '/index.php?mod=admin&sub=admin_modules&action=import' AND CONVERT(`title` USING utf8) = 'Import a module' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 19, `parent` = 20, `type` = 'item', `text` = 'Create a new module', `href` = '/index.php?mod=admin&sub=admin_modules&action=create_new', `title` = 'Create a new module', `target` = '_self' WHERE  `id` = 19 AND `parent` = 20 AND CONVERT(`type` USING utf8) = 'item' AND CONVERT(`text` USING utf8) = 'Create a new module' AND CONVERT(`href` USING utf8) = '/index.php?mod=admin&sub=admin_modules&action=create_new' AND CONVERT(`title` USING utf8) = 'Create a new module' AND CONVERT(`target` USING utf8) = '_self';
UPDATE `cs_adminmenu` SET `id` = 20, `parent` = 4, `type` = 'item', `text` = 'Modulmanager', `href` = '', `title` = 'Modulmanager', `target` = '_self' WHERE  `id` = 20 AND `parent` = 4 AND CONVERT(`type` USING utf8) = 'item' AND CONVERT(`text` USING utf8) = 'Modulmanager' AND CONVERT(`href` USING utf8) = '' AND CONVERT(`title` USING utf8) = 'Modulmanager' AND CONVERT(`target` USING utf8) = '_self';

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_category`
-- 

DROP TABLE IF EXISTS `cs_category`;
CREATE TABLE `cs_category` (
  `cat_id` tinyint(4) NOT NULL auto_increment,
  `cat_modulname` text character set latin1,
  `cat_sortorder` tinyint(4) default NULL,
  `cat_name` text character set latin1,
  `cat_image_url` varchar(60) character set latin1 default NULL,
  `cat_description` varchar(90) character set latin1 default '0',
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=11 ;

-- 
-- Daten für Tabelle `cs_category`
-- 

UPDATE `cs_category` SET `cat_id` = 1, `cat_modulname` = 'newso', `cat_sortorder` = NULL, `cat_name` = 'keine', `cat_image_url` = '', `cat_description` = 'Diese News sind keiner Kategorie zugeordnet' WHERE  `cat_id` = 1;
UPDATE `cs_category` SET `cat_id` = 2, `cat_modulname` = 'news', `cat_sortorder` = NULL, `cat_name` = 'Allgemein', `cat_image_url` = '/images/allgemein.gif', `cat_description` = 'Allgemein' WHERE  `cat_id` = 2;
UPDATE `cs_category` SET `cat_id` = 3, `cat_modulname` = 'news', `cat_sortorder` = NULL, `cat_name` = 'Member', `cat_image_url` = '/images/news/member.gif', `cat_description` = 'Thema Member' WHERE  `cat_id` = 3;
UPDATE `cs_category` SET `cat_id` = 4, `cat_modulname` = 'news', `cat_sortorder` = NULL, `cat_name` = 'Page', `cat_image_url` = '/images/news/page.gif', `cat_description` = ' Thema Page' WHERE  `cat_id` = 4;
UPDATE `cs_category` SET `cat_id` = 5, `cat_modulname` = 'news', `cat_sortorder` = NULL, `cat_name` = 'IRC', `cat_image_url` = '/images/news/irc.gif', `cat_description` = ' Thema IRC gehört' WHERE  `cat_id` = 5;
UPDATE `cs_category` SET `cat_id` = 6, `cat_modulname` = 'news', `cat_sortorder` = NULL, `cat_name` = 'Clan-Wars', `cat_image_url` = '/images/news/clanwars.gif', `cat_description` = 'Thema Matches' WHERE  `cat_id` = 6;
UPDATE `cs_category` SET `cat_id` = 7, `cat_modulname` = 'news', `cat_sortorder` = NULL, `cat_name` = 'Sonstiges', `cat_image_url` = '/images/news/sonstiges.gif', `cat_description` = 'alles' WHERE  `cat_id` = 7;
UPDATE `cs_category` SET `cat_id` = 8, `cat_modulname` = 'news', `cat_sortorder` = NULL, `cat_name` = 'LAN', `cat_image_url` = '', `cat_description` = 'lan' WHERE  `cat_id` = 8;
UPDATE `cs_category` SET `cat_id` = 10, `cat_modulname` = '/design', `cat_sortorder` = NULL, `cat_name` = 'good night', `cat_image_url` = NULL, `cat_description` = '0' WHERE  `cat_id` = 10;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_groups`
-- 

DROP TABLE IF EXISTS `cs_groups`;
CREATE TABLE `cs_groups` (
  `group_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate latin1_general_ci NOT NULL,
  `description` text collate latin1_general_ci NOT NULL,
  `rights` text collate latin1_general_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `cs_groups`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_modules`
-- 

DROP TABLE IF EXISTS `cs_modules`;
CREATE TABLE `cs_modules` (
  `module_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate latin1_general_ci NOT NULL,
  `title` varchar(255) collate latin1_general_ci NOT NULL,
  `description` text collate latin1_general_ci NOT NULL,
  `class_name` varchar(255) collate latin1_general_ci NOT NULL,
  `file_name` varchar(255) collate latin1_general_ci NOT NULL,
  `folder_name` varchar(255) collate latin1_general_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `image_name` varchar(255) collate latin1_general_ci NOT NULL,
  `version` float NOT NULL,
  `cs_version` float NOT NULL,
  PRIMARY KEY  (`module_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=9 ;

-- 
-- Daten für Tabelle `cs_modules`
-- 

UPDATE `cs_modules` SET `module_id` = 1, `name` = 'index', `title` = 'Index Module', `description` = 'This is the main site.', `class_name` = 'module_index', `file_name` = 'index.class.php', `folder_name` = 'index', `enabled` = 1, `image_name` = 'module_index.jpg', `version` = 0.1, `cs_version` = 0.1 WHERE  `module_id` = 1;
UPDATE `cs_modules` SET `module_id` = 2, `name` = 'admin', `title` = 'Admin Interface', `description` = 'This is the Admin Control Panel', `class_name` = 'module_admin', `file_name` = 'admin.class.php', `folder_name` = 'admin', `enabled` = 1, `image_name` = 'module_admin.jpg', `version` = 0.1, `cs_version` = 0.1 WHERE  `module_id` = 2;
UPDATE `cs_modules` SET `module_id` = 3, `name` = 'account', `title` = 'Account Administration', `description` = 'This module handles all necessary account stuff - like login/logout etc.', `class_name` = 'module_account', `file_name` = 'account.class.php', `folder_name` = 'account', `enabled` = 1, `image_name` = 'module_account.jpg', `version` = 0.1, `cs_version` = 0.1 WHERE  `module_id` = 3;
UPDATE `cs_modules` SET `module_id` = 4, `name` = 'captcha', `title` = 'Captcha Module', `description` = 'The captcha module presents a image only humanoids can read.', `class_name` = 'module_captcha', `file_name` = 'captcha.class.php', `folder_name` = 'captcha', `enabled` = 1, `image_name` = 'module_captcha.jpg', `version` = 0.1, `cs_version` = 0.1 WHERE  `module_id` = 4;
UPDATE `cs_modules` SET `module_id` = 8, `name` = 'news', `title` = 'The website news', `description` = 'The website news', `class_name` = 'module_news', `file_name` = 'news.class.php', `folder_name` = 'news', `enabled` = 0, `image_name` = 'module_news.jpg', `version` = 0, `cs_version` = 0 WHERE  `module_id` = 8;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_news`
-- 

DROP TABLE IF EXISTS `cs_news`;
CREATE TABLE `cs_news` (
  `news_id` int(11) NOT NULL auto_increment,
  `news_title` varchar(255) collate latin1_general_ci NOT NULL default '',
  `news_body` text collate latin1_general_ci NOT NULL,
  `news_category` tinyint(4) NOT NULL default '0',
  `user_id` int(11) unsigned NOT NULL default '0',
  `news_added` int(11) default NULL,
  `news_hidden` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`news_id`,`news_category`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

-- 
-- Daten für Tabelle `cs_news`
-- 

UPDATE `cs_news` SET `news_id` = 1, `news_title` = 'testeintrag1', `news_body` = 'testbody1\r\n1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n10\r\ntestbody11', `news_category` = 1, `user_id` = 1, `news_added` = NULL, `news_hidden` = 0 WHERE  `news_id` = 1 AND `news_category` = 1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_news_comments`
-- 

DROP TABLE IF EXISTS `cs_news_comments`;
CREATE TABLE `cs_news_comments` (
  `news_id` int(11) NOT NULL default '0',
  `comment_id` int(10) unsigned NOT NULL default '0',
  `user_id` int(11) unsigned NOT NULL default '0',
  `body` text character set latin1 NOT NULL,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `pseudo` varchar(25) character set latin1 default NULL,
  `ip` varchar(15) character set latin1 NOT NULL default '',
  `host` varchar(255) character set latin1 NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- 
-- Daten für Tabelle `cs_news_comments`
-- 

UPDATE `cs_news_comments` SET `news_id` = 1, `comment_id` = 1, `user_id` = 1, `body` = '123', `added` = '2005-07-29 13:04:07', `pseudo` = '', `ip` = '127.0.0.1', `host` = 'localhost' WHERE  `news_id` = 1 AND `comment_id` = 1 AND `user_id` = 1 AND CONVERT(`body` USING utf8) = '123' AND `added` = '2005-07-29 13:04:07' AND CONVERT(`pseudo` USING utf8) = '' AND CONVERT(`ip` USING utf8) = '127.0.0.1' AND CONVERT(`host` USING utf8) = 'localhost';
UPDATE `cs_news_comments` SET `news_id` = 1, `comment_id` = 2, `user_id` = 0, `body` = '1234567', `added` = '2005-07-29 16:50:08', `pseudo` = 'blub', `ip` = '127.0.0.1', `host` = 'localhost' WHERE  `news_id` = 1 AND `comment_id` = 2 AND `user_id` = 0 AND CONVERT(`body` USING utf8) = '1234567' AND `added` = '2005-07-29 16:50:08' AND CONVERT(`pseudo` USING utf8) = 'blub' AND CONVERT(`ip` USING utf8) = '127.0.0.1' AND CONVERT(`host` USING utf8) = 'localhost';
UPDATE `cs_news_comments` SET `news_id` = 2, `comment_id` = 0, `user_id` = 0, `body` = 'testeee', `added` = '2006-03-04 02:25:42', `pseudo` = 'test', `ip` = '127.0.0.1', `host` = 'localhost' WHERE  `news_id` = 2 AND `comment_id` = 0 AND `user_id` = 0 AND CONVERT(`body` USING utf8) = 'testeee' AND `added` = '2006-03-04 02:25:42' AND CONVERT(`pseudo` USING utf8) = 'test' AND CONVERT(`ip` USING utf8) = '127.0.0.1' AND CONVERT(`host` USING utf8) = 'localhost';
UPDATE `cs_news_comments` SET `news_id` = 2, `comment_id` = 0, `user_id` = 0, `body` = 'eee', `added` = '2006-03-04 02:25:57', `pseudo` = 'tester', `ip` = '127.0.0.1', `host` = 'localhost' WHERE  `news_id` = 2 AND `comment_id` = 0 AND `user_id` = 0 AND CONVERT(`body` USING utf8) = 'eee' AND `added` = '2006-03-04 02:25:57' AND CONVERT(`pseudo` USING utf8) = 'tester' AND CONVERT(`ip` USING utf8) = '127.0.0.1' AND CONVERT(`host` USING utf8) = 'localhost';
UPDATE `cs_news_comments` SET `news_id` = 3, `comment_id` = 0, `user_id` = 1, `body` = '[center]test[/center]', `added` = '2006-05-11 18:30:57', `pseudo` = 'test', `ip` = '127.0.0.1', `host` = 'localhost' WHERE  `news_id` = 3 AND `comment_id` = 0 AND `user_id` = 1 AND CONVERT(`body` USING utf8) = '[center]test[/center]' AND `added` = '2006-05-11 18:30:57' AND CONVERT(`pseudo` USING utf8) = 'test' AND CONVERT(`ip` USING utf8) = '127.0.0.1' AND CONVERT(`host` USING utf8) = 'localhost';

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_rights`
-- 

DROP TABLE IF EXISTS `cs_rights`;
CREATE TABLE `cs_rights` (
  `right_id` int(11) unsigned NOT NULL default '0',
  `right_name` varchar(150) character set latin1 collate latin1_german1_ci NOT NULL default '',
  PRIMARY KEY  (`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `cs_rights`
-- 

UPDATE `cs_rights` SET `right_id` = 1, `right_name` = 'Settings-Edit' WHERE  `right_id` = 1;
UPDATE `cs_rights` SET `right_id` = 2, `right_name` = 'News-Edit' WHERE  `right_id` = 2;
UPDATE `cs_rights` SET `right_id` = 3, `right_name` = 'User-Add' WHERE  `right_id` = 3;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_session`
-- 

DROP TABLE IF EXISTS `cs_session`;
CREATE TABLE `cs_session` (
  `user_id` int(11) NOT NULL default '0',
  `session_id` varchar(32) NOT NULL,
  `session_data` text NOT NULL,
  `session_name` text NOT NULL,
  `session_expire` int(11) NOT NULL default '0',
  `session_visibility` tinyint(4) NOT NULL default '0',
  `session_where` text NOT NULL,
  PRIMARY KEY  (`session_id`),
  UNIQUE KEY `session_id` (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Daten für Tabelle `cs_session`
-- 

UPDATE `cs_session` SET `user_id` = 0, `session_id` = '513531264d8c5bbcae3a47c0e851d1bc', `session_data` = 'client_ip|s:9:"127.0.0.1";client_browser|s:87:"Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8.0.4) Gecko/20060508 Firefox/1.5.0.4";client_host|s:9:"localhost";suiteSID|s:32:"513531264d8c5bbcae3a47c0e851d1bc";user|a:9:{s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:4:"Gast";s:8:"password";s:0:"";s:5:"email";s:0:"";s:10:"first_name";s:7:"Vorname";s:9:"last_name";s:8:"Nachname";s:8:"disabled";s:0:"";s:9:"activated";s:0:"";}_phpOpenTracker_Container|a:23:{s:13:"first_request";b:0;s:9:"client_id";i:1;s:12:"accesslog_id";i:-1563964108;s:10:"ip_address";s:9:"127.0.0.1";s:9:"host_orig";s:9:"localhost";s:4:"host";s:9:"localhost";s:15:"user_agent_orig";s:87:"Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8.0.4) Gecko/20060508 Firefox/1.5.0.4";s:16:"operating_system";s:10:"Windows XP";s:10:"user_agent";s:15:"Firefox/1.5.0.4";s:7:"host_id";i:-1631451101;s:19:"operating_system_id";i:-114077417;s:13:"user_agent_id";i:404955051;s:12:"referer_orig";s:0:"";s:7:"referer";s:0:"";s:10:"referer_id";i:0;s:12:"document_url";s:20:"/index.php?mod=admin";s:8:"document";s:20:"/index.php?mod=admin";s:11:"document_id";i:1894171266;s:11:"plugin_data";a:0:{}s:9:"timestamp";i:1153309770;s:10:"visitor_id";i:-1563964108;s:17:"returning_visitor";b:0;s:13:"last_document";s:20:"/index.php?mod=admin";}', `session_name` = 'suiteSID', `session_expire` = 1153310371, `session_visibility` = 1, `session_where` = 'admin' WHERE  CONVERT(`session_id` USING utf8) = '513531264d8c5bbcae3a47c0e851d1bc';

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_user_rights`
-- 

DROP TABLE IF EXISTS `cs_user_rights`;
CREATE TABLE `cs_user_rights` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `right_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Daten für Tabelle `cs_user_rights`
-- 

UPDATE `cs_user_rights` SET `user_id` = 1, `right_id` = 1 WHERE  `user_id` = 1 AND `right_id` = 1;
UPDATE `cs_user_rights` SET `user_id` = 1, `right_id` = 3 WHERE  `user_id` = 1 AND `right_id` = 3;
UPDATE `cs_user_rights` SET `user_id` = 2, `right_id` = 2 WHERE  `user_id` = 2 AND `right_id` = 2;
UPDATE `cs_user_rights` SET `user_id` = 2, `right_id` = 3 WHERE  `user_id` = 2 AND `right_id` = 3;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_user_usergroups`
-- 

DROP TABLE IF EXISTS `cs_user_usergroups`;
CREATE TABLE `cs_user_usergroups` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `group_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `cs_user_usergroups`
-- 

UPDATE `cs_user_usergroups` SET `user_id` = 1, `group_id` = 1 WHERE  `user_id` = 1 AND `group_id` = 1;
UPDATE `cs_user_usergroups` SET `user_id` = 1, `group_id` = 2 WHERE  `user_id` = 1 AND `group_id` = 2;
UPDATE `cs_user_usergroups` SET `user_id` = 2, `group_id` = 3 WHERE  `user_id` = 2 AND `group_id` = 3;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_usergroup_rights`
-- 

DROP TABLE IF EXISTS `cs_usergroup_rights`;
CREATE TABLE `cs_usergroup_rights` (
  `group_id` int(5) unsigned zerofill NOT NULL default '00000',
  `right_id` int(5) unsigned zerofill NOT NULL default '00000',
  `right_pos` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`group_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `cs_usergroup_rights`
-- 

UPDATE `cs_usergroup_rights` SET `group_id` = 00001, `right_id` = 00001, `right_pos` = 1 WHERE  `group_id` = 00001 AND `right_id` = 00001;
UPDATE `cs_usergroup_rights` SET `group_id` = 00002, `right_id` = 00002, `right_pos` = 1 WHERE  `group_id` = 00002 AND `right_id` = 00002;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_usergroups`
-- 

DROP TABLE IF EXISTS `cs_usergroups`;
CREATE TABLE `cs_usergroups` (
  `group_id` int(5) unsigned NOT NULL auto_increment,
  `group_pos` tinyint(4) unsigned NOT NULL default '1',
  `group_name` varchar(75) character set latin1 collate latin1_german1_ci default NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Daten für Tabelle `cs_usergroups`
-- 

UPDATE `cs_usergroups` SET `group_id` = 1, `group_pos` = 1, `group_name` = 'Administrator' WHERE  `group_id` = 1;
UPDATE `cs_usergroups` SET `group_id` = 2, `group_pos` = 2, `group_name` = 'Newsgruppe' WHERE  `group_id` = 2;
UPDATE `cs_usergroups` SET `group_id` = 3, `group_pos` = 3, `group_name` = 'Gästebuchgruppe' WHERE  `group_id` = 3;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_users`
-- 

DROP TABLE IF EXISTS `cs_users`;
CREATE TABLE `cs_users` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(150) collate latin1_general_ci NOT NULL default '',
  `nick` varchar(25) collate latin1_general_ci NOT NULL default '',
  `password` varchar(40) collate latin1_general_ci NOT NULL,
  `new_password` varchar(40) collate latin1_general_ci NOT NULL default '',
  `code` varchar(255) collate latin1_general_ci NOT NULL,
  `joined` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `first_name` varchar(25) collate latin1_general_ci NOT NULL default '',
  `last_name` varchar(25) collate latin1_general_ci NOT NULL default '',
  `infotext` text collate latin1_general_ci NOT NULL,
  `disabled` tinyint(1) NOT NULL default '0',
  `activated` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`user_id`),
  KEY `email` (`email`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=22 ;

-- 
-- Daten für Tabelle `cs_users`
-- 

UPDATE `cs_users` SET `user_id` = 19, `email` = 'admin@localhost.de', `nick` = 'admin', `password` = '26a1102e42022f67a17add9ab0e74c9440efa7d2', `new_password` = '26a1102e42022f67a17add9ab0e74c9440efa7d2', `code` = '9dd90802013c886ccdd04d524adf3446', `joined` = 1152190495, `timestamp` = 0, `first_name` = '', `last_name` = '', `infotext` = '', `disabled` = 0, `activated` = 1 WHERE  `user_id` = 19;
UPDATE `cs_users` SET `user_id` = 21, `email` = 'asdf2@bla.de', `nick` = 'bla', `password` = '27b276d6221741f11b727e0c24979470f2a7b90a', `new_password` = '', `code` = '66a147b49d97ad7df250b0dd91f6d930', `joined` = 1152208688, `timestamp` = 0, `first_name` = '', `last_name` = '', `infotext` = '', `disabled` = 0, `activated` = 1 WHERE  `user_id` = 21;
