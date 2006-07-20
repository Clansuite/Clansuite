-- phpMyAdmin SQL Dump
-- version 2.8.0.3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 20. Juli 2006 um 11:52
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
  `type` varchar(255) collate latin1_german1_ci NOT NULL default '',
  `text` varchar(255) collate latin1_german1_ci NOT NULL default '',
  `href` varchar(255) collate latin1_german1_ci NOT NULL default '',
  `title` varchar(255) collate latin1_german1_ci NOT NULL default '',
  `target` varchar(255) collate latin1_german1_ci NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- 
-- Daten für Tabelle `cs_adminmenu`
-- 

INSERT INTO `cs_adminmenu` (`id`, `parent`, `type`, `text`, `href`, `title`, `target`) VALUES (4, 0, 'button', 'System', '', 'System', '_self'),
(3, 0, 'button', 'Categories', 'admin/categories/index.php', 'Categories', '_self'),
(6, 0, 'button', 'Hilfe', '', 'Hilfe', '_self'),
(1, 0, 'button', 'Home', 'index.php', 'Home', '_self'),
(2, 0, 'button', 'Modules', '', 'Modules', '_self'),
(7, 6, 'item', 'Hilfe', 'help.php', 'Hilfe', '_self'),
(8, 6, 'item', 'Handbuch', 'manual.php', 'Handbuch', '_self'),
(5, 0, 'button', 'Users', 'admin/users/index.php', 'Users', '_self'),
(9, 6, 'item', 'Report Bug & Give Feedback', 'bugreport.php', 'Report Bug & Give Feedback', '_self'),
(10, 6, 'item', 'Über Clansuite', 'about.php', 'Über Clansuite', '_self'),
(13, 4, 'item', 'Menüeditor', 'admin/menueditor.php', 'Menüeditor', '_self'),
(14, 4, 'item', 'Templateeditor', 'admin/templateeditor.php', 'TemplateEditor', '_self'),
(15, 20, 'item', 'Show all modules', '/index.php?mod=admin&sub=admin_modules&action=show_all', 'Show all modules', '_self'),
(16, 20, 'item', 'Install new modules', '/index.php?mod=admin&sub=admin_modules&action=install_new', 'Install new modules', '_self'),
(17, 20, 'item', 'Export a module', '/index.php?mod=admin&sub=admin_modules&action=export', 'Export a module', '_self'),
(18, 20, 'item', 'Import a module', '/index.php?mod=admin&sub=admin_modules&action=import', 'Import a module', '_self'),
(19, 20, 'item', 'Create a new module', '/index.php?mod=admin&sub=admin_modules&action=create_new', 'Create a new module', '_self'),
(20, 4, 'item', 'Modulmanager', '', 'Modulmanager', '_self');

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

INSERT INTO `cs_category` (`cat_id`, `cat_modulname`, `cat_sortorder`, `cat_name`, `cat_image_url`, `cat_description`) VALUES (1, 'newso', NULL, 'keine', '', 'Diese News sind keiner Kategorie zugeordnet'),
(2, 'news', NULL, 'Allgemein', '/images/allgemein.gif', 'Allgemein'),
(3, 'news', NULL, 'Member', '/images/news/member.gif', 'Thema Member'),
(4, 'news', NULL, 'Page', '/images/news/page.gif', ' Thema Page'),
(5, 'news', NULL, 'IRC', '/images/news/irc.gif', ' Thema IRC gehört'),
(6, 'news', NULL, 'Clan-Wars', '/images/news/clanwars.gif', 'Thema Matches'),
(7, 'news', NULL, 'Sonstiges', '/images/news/sonstiges.gif', 'alles'),
(8, 'news', NULL, 'LAN', '', 'lan'),
(10, '/design', NULL, 'good night', NULL, '0');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=9 ;

-- 
-- Daten für Tabelle `cs_modules`
-- 

INSERT INTO `cs_modules` (`module_id`, `name`, `title`, `description`, `class_name`, `file_name`, `folder_name`, `enabled`, `image_name`, `version`, `cs_version`) VALUES (1, 'index', 'Index Module', 'This is the main site.', 'module_index', 'index.class.php', 'index', 1, 'module_index.jpg', 0.1, 0.1),
(2, 'admin', 'Admin Interface', 'This is the Admin Control Panel', 'module_admin', 'admin.class.php', 'admin', 1, 'module_admin.jpg', 0.1, 0.1),
(3, 'account', 'Account Administration', 'This module handles all necessary account stuff - like login/logout etc.', 'module_account', 'account.class.php', 'account', 1, 'module_account.jpg', 0.1, 0.1),
(4, 'captcha', 'Captcha Module', 'The captcha module presents a image only humanoids can read.', 'module_captcha', 'captcha.class.php', 'captcha', 1, 'module_captcha.jpg', 0.1, 0.1),
(8, 'news', 'The website news', 'The website news', 'module_news', 'news.class.php', 'news', 0, 'module_news.jpg', 0, 0);

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

INSERT INTO `cs_news` (`news_id`, `news_title`, `news_body`, `news_category`, `user_id`, `news_added`, `news_hidden`) VALUES (1, 'testeintrag1', 'testbody1\r\n1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n10\r\ntestbody11', 1, 1, NULL, 0);

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

INSERT INTO `cs_news_comments` (`news_id`, `comment_id`, `user_id`, `body`, `added`, `pseudo`, `ip`, `host`) VALUES (1, 1, 1, '123', '2005-07-29 13:04:07', '', '127.0.0.1', 'localhost'),
(1, 2, 0, '1234567', '2005-07-29 16:50:08', 'blub', '127.0.0.1', 'localhost'),
(2, 0, 0, 'testeee', '2006-03-04 02:25:42', 'test', '127.0.0.1', 'localhost'),
(2, 0, 0, 'eee', '2006-03-04 02:25:57', 'tester', '127.0.0.1', 'localhost'),
(3, 0, 1, '[center]test[/center]', '2006-05-11 18:30:57', 'test', '127.0.0.1', 'localhost');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_accesslog`
-- 

CREATE TABLE `cs_pot_accesslog` (
  `accesslog_id` int(11) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  `weekday` tinyint(1) unsigned NOT NULL,
  `hour` tinyint(2) unsigned NOT NULL,
  `document_id` int(11) NOT NULL,
  `exit_target_id` int(11) NOT NULL default '0',
  `entry_document` tinyint(1) unsigned NOT NULL,
  KEY `accesslog_id` (`accesslog_id`),
  KEY `timestamp` (`timestamp`),
  KEY `document_id` (`document_id`),
  KEY `exit_target_id` (`exit_target_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=1 DELAY_KEY_WRITE=1;

-- 
-- Daten für Tabelle `cs_pot_accesslog`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_add_data`
-- 

CREATE TABLE `cs_pot_add_data` (
  `accesslog_id` int(11) NOT NULL,
  `data_field` varchar(32) collate latin1_general_ci NOT NULL,
  `data_value` varchar(255) collate latin1_general_ci NOT NULL,
  KEY `accesslog_id` (`accesslog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- 
-- Daten für Tabelle `cs_pot_add_data`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_documents`
-- 

CREATE TABLE `cs_pot_documents` (
  `data_id` int(11) NOT NULL,
  `string` varchar(255) collate latin1_general_ci NOT NULL,
  `document_url` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`data_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- 
-- Daten für Tabelle `cs_pot_documents`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_exit_targets`
-- 

CREATE TABLE `cs_pot_exit_targets` (
  `data_id` int(11) NOT NULL,
  `string` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`data_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- 
-- Daten für Tabelle `cs_pot_exit_targets`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_hostnames`
-- 

CREATE TABLE `cs_pot_hostnames` (
  `data_id` int(11) NOT NULL,
  `string` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`data_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- 
-- Daten für Tabelle `cs_pot_hostnames`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_operating_systems`
-- 

CREATE TABLE `cs_pot_operating_systems` (
  `data_id` int(11) NOT NULL,
  `string` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`data_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- 
-- Daten für Tabelle `cs_pot_operating_systems`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_referers`
-- 

CREATE TABLE `cs_pot_referers` (
  `data_id` int(11) NOT NULL,
  `string` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`data_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- 
-- Daten für Tabelle `cs_pot_referers`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_user_agents`
-- 

CREATE TABLE `cs_pot_user_agents` (
  `data_id` int(11) NOT NULL,
  `string` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`data_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- 
-- Daten für Tabelle `cs_pot_user_agents`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_visitors`
-- 

CREATE TABLE `cs_pot_visitors` (
  `accesslog_id` int(11) NOT NULL,
  `visitor_id` int(11) NOT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `operating_system_id` int(11) NOT NULL,
  `user_agent_id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `referer_id` int(11) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL,
  `weekday` tinyint(1) unsigned NOT NULL,
  `hour` tinyint(2) unsigned NOT NULL,
  `returning_visitor` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`accesslog_id`),
  KEY `client_time` (`client_id`,`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- 
-- Daten für Tabelle `cs_pot_visitors`
-- 


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

INSERT INTO `cs_rights` (`right_id`, `right_name`) VALUES (1, 'Settings-Edit'),
(2, 'News-Edit'),
(3, 'User-Add');

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

INSERT INTO `cs_session` (`user_id`, `session_id`, `session_data`, `session_name`, `session_expire`, `session_visibility`, `session_where`) VALUES (0, '49c1e1639419413a3bddf7b0f44d8e41', 'client_ip|s:9:"127.0.0.1";client_browser|s:87:"Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8.0.4) Gecko/20060508 Firefox/1.5.0.4";client_host|s:9:"localhost";suiteSID|s:32:"49c1e1639419413a3bddf7b0f44d8e41";user|a:9:{s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:4:"Gast";s:8:"password";s:0:"";s:5:"email";s:0:"";s:10:"first_name";s:7:"Vorname";s:9:"last_name";s:8:"Nachname";s:8:"disabled";s:0:"";s:9:"activated";s:0:"";}', 'suiteSID', 1153388569, 1, 'admin');

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

INSERT INTO `cs_user_rights` (`user_id`, `right_id`) VALUES (1, 1),
(1, 3),
(2, 2),
(2, 3);

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

INSERT INTO `cs_user_usergroups` (`user_id`, `group_id`) VALUES (1, 1),
(1, 2),
(2, 3);

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

INSERT INTO `cs_usergroup_rights` (`group_id`, `right_id`, `right_pos`) VALUES (00001, 00001, 1),
(00002, 00002, 1);

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

INSERT INTO `cs_usergroups` (`group_id`, `group_pos`, `group_name`) VALUES (1, 1, 'Administrator'),
(2, 2, 'Newsgruppe'),
(3, 3, 'Gästebuchgruppe');

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

INSERT INTO `cs_users` (`user_id`, `email`, `nick`, `password`, `new_password`, `code`, `joined`, `timestamp`, `first_name`, `last_name`, `infotext`, `disabled`, `activated`) VALUES (19, 'admin@localhost.de', 'admin', '26a1102e42022f67a17add9ab0e74c9440efa7d2', '26a1102e42022f67a17add9ab0e74c9440efa7d2', '9dd90802013c886ccdd04d524adf3446', 1152190495, 0, '', '', '', 0, 1),
(21, 'asdf2@bla.de', 'bla', '27b276d6221741f11b727e0c24979470f2a7b90a', '', '66a147b49d97ad7df250b0dd91f6d930', 1152208688, 0, '', '', '', 0, 1);
