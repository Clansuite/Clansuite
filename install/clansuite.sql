-- phpMyAdmin SQL Dump
-- version 2.8.0.3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 26. Juli 2006 um 18:22
-- Server Version: 5.0.20
-- PHP-Version: 5.1.2
-- 
-- Datenbank: `clansuite`
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_adminmenu`
-- 

CREATE TABLE `cs_adminmenu` (
  `id` tinyint(3) unsigned NOT NULL default '0',
  `parent` tinyint(3) unsigned NOT NULL default '0',
  `type` varchar(255) character set latin1 collate latin1_german1_ci NOT NULL default '',
  `text` varchar(255) character set latin1 collate latin1_german1_ci NOT NULL default '',
  `href` varchar(255) character set latin1 collate latin1_german1_ci NOT NULL default '',
  `title` varchar(255) character set latin1 collate latin1_german1_ci NOT NULL default '',
  `target` varchar(255) character set latin1 collate latin1_german1_ci NOT NULL default '',
  PRIMARY KEY  (`id`,`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `cs_adminmenu`
-- 

INSERT INTO `cs_adminmenu` VALUES (1, 0, 'button', 'Home', 'index.php', 'Home', '_self');
INSERT INTO `cs_adminmenu` VALUES (2, 0, 'button', 'Module', '', 'Module', '_self');
INSERT INTO `cs_adminmenu` VALUES (3, 2, 'item', 'News', '/index.php?mod=admin&sub=news', 'News', '_self');
INSERT INTO `cs_adminmenu` VALUES (4, 3, 'item', 'Add News', '/index.php?mod=admin&sub=add_news', 'Add News', '_self');
INSERT INTO `cs_adminmenu` VALUES (5, 0, 'button', 'System', '', 'System', '_self');
INSERT INTO `cs_adminmenu` VALUES (6, 5, 'item', 'Menüeditor', '/index.php?mod=admin&sub=menueditor', 'Menüeditor', '_self');
INSERT INTO `cs_adminmenu` VALUES (7, 5, 'item', 'Templateeditor', '/index.php?mod=admin&sub=templateditor', 'Templateeditor', '_self');
INSERT INTO `cs_adminmenu` VALUES (8, 5, 'item', 'Categories', 'admin/categories/index.php', 'Categories', '_self');
INSERT INTO `cs_adminmenu` VALUES (10, 5, 'item', 'Settings', '/index.php?mod=admin&sub=config', 'Settings', '_self');
INSERT INTO `cs_adminmenu` VALUES (11, 0, 'button', 'Users', '', 'Users', '_self');
INSERT INTO `cs_adminmenu` VALUES (9, 11, 'item', 'Show All', '/index.php?mod=admin&sub=users', 'Show All', '_self');
INSERT INTO `cs_adminmenu` VALUES (12, 11, 'item', 'Search', '/index.php?mod=admin&sub=search', 'Search', '_self');
INSERT INTO `cs_adminmenu` VALUES (13, 11, 'item', 'Groups', '', 'Groups', '_self');
INSERT INTO `cs_adminmenu` VALUES (25, 11, 'item', 'Permissions', '', 'Permissions', '_self');
INSERT INTO `cs_adminmenu` VALUES (14, 5, 'button', 'Modulemanager', '', 'Modulemanager', '_self');
INSERT INTO `cs_adminmenu` VALUES (15, 14, 'item', 'Show all modules', '/index.php?mod=admin&sub=modules&action=show_all', 'Show all modules', '_self');
INSERT INTO `cs_adminmenu` VALUES (16, 14, 'item', 'Install new modules', '/index.php?mod=admin&sub=modules&action=install_new', 'Install new modules', '_self');
INSERT INTO `cs_adminmenu` VALUES (17, 14, 'item', 'Export a module', '/index.php?mod=admin&sub=modules&action=export', 'Export a module', '_self');
INSERT INTO `cs_adminmenu` VALUES (18, 14, 'item', 'Import a module', '/index.php?mod=admin&sub=modules&action=import', 'Import a module', '_self');
INSERT INTO `cs_adminmenu` VALUES (19, 14, 'item', 'Create a new module', '/index.php?mod=admin&sub=modules&action=create_new', 'Create a new module', '_self');
INSERT INTO `cs_adminmenu` VALUES (20, 0, 'button', 'Hilfe', '', 'Hilfe', '_self');
INSERT INTO `cs_adminmenu` VALUES (21, 20, 'item', 'Hilfe', 'help.php', 'Hilfe', '_self');
INSERT INTO `cs_adminmenu` VALUES (22, 20, 'item', 'Handbuch', 'manual.php', 'Handbuch', '_self');
INSERT INTO `cs_adminmenu` VALUES (23, 20, 'item', 'Report Bug & Give Feedback', 'bugreport.php', 'Report Bug & Give Feedback', '_self');
INSERT INTO `cs_adminmenu` VALUES (24, 20, 'item', 'Über Clansuite', 'about.php', 'Über Clansuite', '_self');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_category`
-- 

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

INSERT INTO `cs_category` VALUES (1, 'newso', NULL, 'keine', '', 'Diese News sind keiner Kategorie zugeordnet');
INSERT INTO `cs_category` VALUES (2, 'news', NULL, 'Allgemein', '/images/allgemein.gif', 'Allgemein');
INSERT INTO `cs_category` VALUES (3, 'news', NULL, 'Member', '/images/news/member.gif', 'Thema Member');
INSERT INTO `cs_category` VALUES (4, 'news', NULL, 'Page', '/images/news/page.gif', ' Thema Page');
INSERT INTO `cs_category` VALUES (5, 'news', NULL, 'IRC', '/images/news/irc.gif', ' Thema IRC gehÃ¶rt');
INSERT INTO `cs_category` VALUES (6, 'news', NULL, 'Clan-Wars', '/images/news/clanwars.gif', 'Thema Matches');
INSERT INTO `cs_category` VALUES (7, 'news', NULL, 'Sonstiges', '/images/news/sonstiges.gif', 'alles');
INSERT INTO `cs_category` VALUES (8, 'news', NULL, 'LAN', '', 'lan');
INSERT INTO `cs_category` VALUES (10, '/design', NULL, 'good night', NULL, '0');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_groups`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=12 ;

-- 
-- Daten für Tabelle `cs_modules`
-- 

INSERT INTO `cs_modules` VALUES (4, 'captcha', 'Captcha Module', 'The captcha module presents a image only humanoids can read.', 'module_captcha', 'captcha.module.php', 'captcha', 1, 'module_captcha.jpg', 0.1, 0.1);
INSERT INTO `cs_modules` VALUES (1, 'index', 'Index Module', 'This is the main site.', 'module_index', 'index.module.php', 'index', 1, 'module_index.jpg', 0.1, 0.1);
INSERT INTO `cs_modules` VALUES (2, 'admin', 'Admin Interface', 'This is the Admin Control Panel', 'module_admin', 'admin.module.php', 'admin', 1, 'module_admin.jpg', 0.1, 0.1);
INSERT INTO `cs_modules` VALUES (3, 'account', 'Account Administration', 'This module handles all necessary account stuff - like login/logout etc.', 'module_account', 'account.module.php', 'account', 1, 'module_account.jpg', 0.1, 0.1);
INSERT INTO `cs_modules` VALUES (11, 'news', 'News', 'News module - your description', 'module_news', 'news.module.php', 'news', 0, 'module_news.jpg', 0.1, 0.1);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_news`
-- 

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

INSERT INTO `cs_news` VALUES (1, 'testeintrag1', 'testbody1\r\n1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n10\r\ntestbody11', 1, 1, NULL, 0);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_news_comments`
-- 

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

INSERT INTO `cs_news_comments` VALUES (1, 1, 1, '123', '2005-07-29 13:04:07', '', '127.0.0.1', 'localhost');
INSERT INTO `cs_news_comments` VALUES (1, 2, 0, '1234567', '2005-07-29 16:50:08', 'blub', '127.0.0.1', 'localhost');
INSERT INTO `cs_news_comments` VALUES (2, 0, 0, 'testeee', '2006-03-04 02:25:42', 'test', '127.0.0.1', 'localhost');
INSERT INTO `cs_news_comments` VALUES (2, 0, 0, 'eee', '2006-03-04 02:25:57', 'tester', '127.0.0.1', 'localhost');
INSERT INTO `cs_news_comments` VALUES (3, 0, 1, '[center]test[/center]', '2006-05-11 18:30:57', 'test', '127.0.0.1', 'localhost');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_rights`
-- 

CREATE TABLE `cs_rights` (
  `right_id` int(11) unsigned NOT NULL default '0',
  `right_name` varchar(150) character set latin1 collate latin1_german1_ci NOT NULL default '',
  PRIMARY KEY  (`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `cs_rights`
-- 

INSERT INTO `cs_rights` VALUES (1, 'Settings-Edit');
INSERT INTO `cs_rights` VALUES (2, 'News-Edit');
INSERT INTO `cs_rights` VALUES (3, 'User-Add');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_session`
-- 

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

INSERT INTO `cs_session` VALUES (0, '841edfd0c16affef78accfa541fb42f0', 'client_ip|s:9:"127.0.0.1";client_browser|s:87:"Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8.0.4) Gecko/20060508 Firefox/1.5.0.4";client_host|s:9:"localhost";suiteSID|s:32:"841edfd0c16affef78accfa541fb42f0";user|a:9:{s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:4:"Gast";s:8:"password";s:0:"";s:5:"email";s:0:"";s:10:"first_name";s:7:"Vorname";s:9:"last_name";s:8:"Nachname";s:8:"disabled";s:0:"";s:9:"activated";s:0:"";}', 'suiteSID', 1153830277, 1, 'admin');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_user_rights`
-- 

CREATE TABLE `cs_user_rights` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `right_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Daten für Tabelle `cs_user_rights`
-- 

INSERT INTO `cs_user_rights` VALUES (1, 1);
INSERT INTO `cs_user_rights` VALUES (1, 3);
INSERT INTO `cs_user_rights` VALUES (2, 2);
INSERT INTO `cs_user_rights` VALUES (2, 3);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_user_usergroups`
-- 

CREATE TABLE `cs_user_usergroups` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `group_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `cs_user_usergroups`
-- 

INSERT INTO `cs_user_usergroups` VALUES (1, 1);
INSERT INTO `cs_user_usergroups` VALUES (1, 2);
INSERT INTO `cs_user_usergroups` VALUES (2, 3);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_usergroup_rights`
-- 

CREATE TABLE `cs_usergroup_rights` (
  `group_id` int(5) unsigned zerofill NOT NULL default '00000',
  `right_id` int(5) unsigned zerofill NOT NULL default '00000',
  `right_pos` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`group_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Daten für Tabelle `cs_usergroup_rights`
-- 

INSERT INTO `cs_usergroup_rights` VALUES (00001, 00001, 1);
INSERT INTO `cs_usergroup_rights` VALUES (00002, 00002, 1);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_usergroups`
-- 

CREATE TABLE `cs_usergroups` (
  `group_id` int(5) unsigned NOT NULL auto_increment,
  `group_pos` tinyint(4) unsigned NOT NULL default '1',
  `group_name` varchar(75) character set latin1 collate latin1_german1_ci default NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Daten für Tabelle `cs_usergroups`
-- 

INSERT INTO `cs_usergroups` VALUES (1, 1, 'Administrator');
INSERT INTO `cs_usergroups` VALUES (2, 2, 'Newsgruppe');
INSERT INTO `cs_usergroups` VALUES (3, 3, 'GÃ¤stebuchgruppe');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_users`
-- 

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

INSERT INTO `cs_users` VALUES (19, 'admin@localhost.de', 'admin', '26a1102e42022f67a17add9ab0e74c9440efa7d2', '26a1102e42022f67a17add9ab0e74c9440efa7d2', '9dd90802013c886ccdd04d524adf3446', 1152190495, 0, '', '', '', 0, 1);
INSERT INTO `cs_users` VALUES (21, 'asdf2@bla.de', 'bla', '27b276d6221741f11b727e0c24979470f2a7b90a', '', '66a147b49d97ad7df250b0dd91f6d930', 1152208688, 0, '', '', '', 0, 1);
