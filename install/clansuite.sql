-- phpMyAdmin SQL Dump
-- version 2.8.0.3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 17. August 2006 um 00:29
-- Server Version: 5.0.20
-- PHP-Version: 5.1.2
-- 
-- Datenbank: `clansuite` 
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_admin_shortcuts`
-- 

CREATE TABLE `cs_admin_shortcuts` (
  `id` tinyint(4) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `order` tinyint(4) NOT NULL default '30',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- 
-- Daten für Tabelle `cs_admin_shortcuts`
-- 

INSERT INTO `cs_admin_shortcuts` VALUES (1, 'Console', '/index.php?mod=admin&sub=console', 'console.png', 45);
INSERT INTO `cs_admin_shortcuts` VALUES (2, 'Downloads', '/index.php?mod=admin&sub=downloads', 'downloads.png', 30);
INSERT INTO `cs_admin_shortcuts` VALUES (3, 'Articles', '/index.php?mod=admin&sub=articles', 'articles.png', 30);
INSERT INTO `cs_admin_shortcuts` VALUES (4, 'Links', '/index.php?mod=admin&sub=links', 'links.png', 30);
INSERT INTO `cs_admin_shortcuts` VALUES (5, 'Calendar', '/index.php?mod=admin&sub=calendar', 'calendar.png', 30);
INSERT INTO `cs_admin_shortcuts` VALUES (6, 'Time', '/index.php?mod=admin&sub=time', 'time.png', 30);
INSERT INTO `cs_admin_shortcuts` VALUES (7, 'Email', '/index.php?mod=admin&sub=email', 'email.png', 3);
INSERT INTO `cs_admin_shortcuts` VALUES (8, 'Shoutbox', '/index.php?mod=admin&sub=shoutbox', 'shoutbox.png', 30);
INSERT INTO `cs_admin_shortcuts` VALUES (9, 'Help', '/index.php?mod=admin&sub=help', 'help.png', 40);
INSERT INTO `cs_admin_shortcuts` VALUES (10, 'Security', '/index.php?mod=admin&sub=security', 'security.png', 41);
INSERT INTO `cs_admin_shortcuts` VALUES (11, 'Gallery', '/index.php?mod=admin&sub=gallery', 'gallery.png', 30);
INSERT INTO `cs_admin_shortcuts` VALUES (12, 'System', '/index.php?mod=admin&sub=system', 'system.png', 42);
INSERT INTO `cs_admin_shortcuts` VALUES (13, 'Replays', '/index.php?mod=admin&sub=replays', 'replays.png', 30);
INSERT INTO `cs_admin_shortcuts` VALUES (14, 'News', '/index.php?mod=admin&sub=news', 'news.png', 2);
INSERT INTO `cs_admin_shortcuts` VALUES (15, 'Settings', '/index.php?mod=admin&sub=settings', 'settings.png', 43);
INSERT INTO `cs_admin_shortcuts` VALUES (16, 'Users', '/index.php?mod=admin&sub=users', 'users.png', 1);
INSERT INTO `cs_admin_shortcuts` VALUES (17, 'Backup', '/index.php?mod=admin&sub=backup', 'backup.png', 44);
INSERT INTO `cs_admin_shortcuts` VALUES (18, 'Templates', '/index.php?mod=admin&sub=templates', 'templates.png', 4);
INSERT INTO `cs_admin_shortcuts` VALUES (19, 'Groups', '/index.php?mod=admin&sub=groups', 'groups.png', 2);
INSERT INTO `cs_admin_shortcuts` VALUES (20, 'Search', '/index.php?mod=admin&sub=search', 'search.png', 30);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_adminmenu`
-- 

CREATE TABLE `cs_adminmenu` (
  `id` tinyint(3) unsigned NOT NULL default '0',
  `parent` tinyint(3) unsigned NOT NULL default '0',
  `type` varchar(255) character set utf8 NOT NULL,
  `text` varchar(255) character set utf8 NOT NULL,
  `href` varchar(255) character set utf8 NOT NULL,
  `title` varchar(255) character set utf8 NOT NULL,
  `target` varchar(255) character set utf8 NOT NULL,
  `order` tinyint(4) NOT NULL,
  `icon` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`,`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Daten für Tabelle `cs_adminmenu`
-- 

INSERT INTO `cs_adminmenu` VALUES (1, 0, 'folder', 'Home', '/index.php?mod=admin', 'Home', '_self', 0, '');
INSERT INTO `cs_adminmenu` VALUES (2, 0, 'folder', 'Modules', '', 'Modules', '_self', 1, '');
INSERT INTO `cs_adminmenu` VALUES (3, 2, 'folder', 'Articles', '', 'Articles', '_self', 0, 'report.png');
INSERT INTO `cs_adminmenu` VALUES (4, 2, 'folder', 'Downloads', '', 'Downloads', '_self', 1, 'disk.png');
INSERT INTO `cs_adminmenu` VALUES (5, 2, 'folder', 'eMail', '', 'eMail', '_self', 2, 'email_open_image.png');
INSERT INTO `cs_adminmenu` VALUES (6, 2, 'folder', 'Forum', '', 'Forum', '_self', 3, 'application_view_list.png');
INSERT INTO `cs_adminmenu` VALUES (7, 2, 'folder', 'Gallery', '', 'Gallery', '_self', 4, 'map_go.png');
INSERT INTO `cs_adminmenu` VALUES (8, 2, 'folder', 'News', '', 'News', '_self', 5, 'page_edit.png');
INSERT INTO `cs_adminmenu` VALUES (9, 2, 'folder', 'Replays', '', 'Replays', '_self', 6, 'film.png');
INSERT INTO `cs_adminmenu` VALUES (10, 2, 'folder', 'Shoutbox', '', 'Shoutbox', '_self', 7, 'comment.png');
INSERT INTO `cs_adminmenu` VALUES (11, 2, 'folder', 'Static Pages', '', 'Static Pages', '_self', 8, 'html.png');
INSERT INTO `cs_adminmenu` VALUES (12, 11, 'item', 'Create', '/index.php?mod=admin&sub=static&action=create', 'Create', '_self', 0, 'add.png');
INSERT INTO `cs_adminmenu` VALUES (13, 11, 'item', 'Edit', '/index.php?mod=admin&sub=static&action=list_all', 'Edit', '_self', 1, 'pencil.png');
INSERT INTO `cs_adminmenu` VALUES (14, 0, 'folder', 'System', '', 'System', '_self', 2, '');
INSERT INTO `cs_adminmenu` VALUES (15, 14, 'item', 'Settings', '/index.php?mod=admin&sub=config', 'Settings', '_self', 0, 'settings.png');
INSERT INTO `cs_adminmenu` VALUES (16, 14, 'folder', 'Modules', '', 'Modules', '_self', 1, 'bricks.png');
INSERT INTO `cs_adminmenu` VALUES (17, 16, 'item', 'Install new modules', '/index.php?mod=admin&sub=modules&action=install_new', 'Install new modules', '_self', 0, 'package.png');
INSERT INTO `cs_adminmenu` VALUES (18, 16, 'folder', 'Development', '', 'Development', '_self', 1, 'application_xp_terminal.png');
INSERT INTO `cs_adminmenu` VALUES (19, 18, 'item', 'Create a module', '/index.php?mod=admin&sub=modules&action=create_new', 'Create a new module', '_self', 0, 'add.png');
INSERT INTO `cs_adminmenu` VALUES (20, 18, 'item', 'Export a module', '/index.php?mod=admin&sub=modules&action=export', 'Export a module', '_self', 1, 'compress.png');
INSERT INTO `cs_adminmenu` VALUES (21, 18, 'item', 'Edit modules', '/index.php?mod=admin&sub=modules&action=show_all', 'Edit modules', '_self', 2, 'bricks_edit.png');
INSERT INTO `cs_adminmenu` VALUES (22, 14, 'folder', 'Development', '', 'Development', '_self', 2, 'application_xp_terminal.png');
INSERT INTO `cs_adminmenu` VALUES (23, 22, 'item', 'Template Editor', '/index.php?mod=admin&sub=templateditor', 'Template Editor', '_self', 0, 'layout_edit.png');
INSERT INTO `cs_adminmenu` VALUES (24, 22, 'item', 'Adminmenu Editor', '/index.php?mod=admin&sub=menueditor', 'Adminmenu Editor', '_self', 1, 'application_form_edit.png');
INSERT INTO `cs_adminmenu` VALUES (25, 0, 'folder', 'Administration', '', 'Administration', '_self', 3, '');
INSERT INTO `cs_adminmenu` VALUES (26, 25, 'folder', 'Users', '', 'Users', '_self', 0, 'user_suit.png');
INSERT INTO `cs_adminmenu` VALUES (27, 26, 'item', 'Show All', '/index.php?mod=admin&sub=users', 'Show All', '_self', 0, 'table.png');
INSERT INTO `cs_adminmenu` VALUES (28, 26, 'item', 'Search', '/index.php?mod=admin&sub=search', 'Search', '_self', 1, 'magnifier.png');
INSERT INTO `cs_adminmenu` VALUES (29, 25, 'folder', 'Groups', '', 'Groups', '_self', 1, 'group.png');
INSERT INTO `cs_adminmenu` VALUES (30, 25, 'folder', 'Permissions', '', 'Permissions', '_self', 2, 'key.png');
INSERT INTO `cs_adminmenu` VALUES (31, 25, 'folder', 'Templates', '', 'Templates', '_self', 3, 'layout_header.png');
INSERT INTO `cs_adminmenu` VALUES (32, 0, 'folder', 'Help', '', 'Help', '_self', 4, '');
INSERT INTO `cs_adminmenu` VALUES (33, 32, 'item', 'Help', '/index.php?mod=admin&sub=static&action=show&page=help', 'Help', '_self', 0, 'help.png');
INSERT INTO `cs_adminmenu` VALUES (34, 32, 'item', 'Manual', '/index.php?mod=admin&sub=static&action=show&page=manual', 'Manual', '_self', 1, 'book_open.png');
INSERT INTO `cs_adminmenu` VALUES (35, 32, 'item', 'Report Bugs & Give Feedback', '/index.php?mod=admin&sub=bugs', 'Report Bugs & Give Feedback', '_self', 2, 'error.png');
INSERT INTO `cs_adminmenu` VALUES (36, 32, 'item', 'About Clansuite', '/index.php?mod=admin&sub=static&action=show&page=about', 'About Clansuite', '_self', 3, 'information.png');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_adminmenu_old`
-- 

CREATE TABLE `cs_adminmenu_old` (
  `id` tinyint(3) unsigned NOT NULL default '0',
  `parent` tinyint(3) unsigned NOT NULL default '0',
  `type` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `order` tinyint(4) NOT NULL,
  `icon` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`,`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Daten für Tabelle `cs_adminmenu_old`
-- 

INSERT INTO `cs_adminmenu_old` VALUES (1, 0, 'folder', 'Home', '/index.php?mod=admin', 'Home', '_self', 0, '');
INSERT INTO `cs_adminmenu_old` VALUES (2, 0, 'folder', 'Modules', '', 'Modules', '_self', 1, '');
INSERT INTO `cs_adminmenu_old` VALUES (3, 2, 'folder', 'Articles', '', 'Articles', '_self', 0, 'report.png');
INSERT INTO `cs_adminmenu_old` VALUES (4, 2, 'folder', 'Downloads', '', 'Downloads', '_self', 1, 'disk.png');
INSERT INTO `cs_adminmenu_old` VALUES (5, 2, 'folder', 'eMail', '', 'eMail', '_self', 2, 'email_open_image.png');
INSERT INTO `cs_adminmenu_old` VALUES (6, 2, 'folder', 'Forum', '', 'Forum', '_self', 3, 'application_view_list.png');
INSERT INTO `cs_adminmenu_old` VALUES (7, 2, 'folder', 'Gallery', '', 'Gallery', '_self', 4, 'map_go.png');
INSERT INTO `cs_adminmenu_old` VALUES (8, 2, 'folder', 'News', '', 'News', '_self', 5, 'page_edit.png');
INSERT INTO `cs_adminmenu_old` VALUES (9, 2, 'folder', 'Replays', '', 'Replays', '_self', 6, 'film.png');
INSERT INTO `cs_adminmenu_old` VALUES (10, 2, 'folder', 'Shoutbox', '', 'Shoutbox', '_self', 7, 'comment.png');
INSERT INTO `cs_adminmenu_old` VALUES (11, 2, 'folder', 'Static Pages', '', 'Static Pages', '_self', 8, 'html.png');
INSERT INTO `cs_adminmenu_old` VALUES (12, 11, 'item', 'Create', '/index.php?mod=admin&sub=static&action=create', 'Create', '_self', 0, 'add.png');
INSERT INTO `cs_adminmenu_old` VALUES (13, 11, 'item', 'Edit', '/index.php?mod=admin&sub=static&action=list_all', 'Edit', '_self', 1, 'pencil.png');
INSERT INTO `cs_adminmenu_old` VALUES (14, 0, 'folder', 'System', '', 'System', '_self', 2, '');
INSERT INTO `cs_adminmenu_old` VALUES (15, 14, 'item', 'Settings', '/index.php?mod=admin&sub=config', 'Settings', '_self', 0, 'settings.png');
INSERT INTO `cs_adminmenu_old` VALUES (16, 14, 'folder', 'Modules', '', 'Modules', '_self', 1, 'bricks.png');
INSERT INTO `cs_adminmenu_old` VALUES (17, 16, 'item', 'Install new modules', '/index.php?mod=admin&sub=modules&action=install_new', 'Install new modules', '_self', 0, 'package.png');
INSERT INTO `cs_adminmenu_old` VALUES (18, 16, 'folder', 'Development', '', 'Development', '_self', 1, 'application_xp_terminal.png');
INSERT INTO `cs_adminmenu_old` VALUES (19, 18, 'item', 'Create a module', '/index.php?mod=admin&sub=modules&action=create_new', 'Create a new module', '_self', 0, 'add.png');
INSERT INTO `cs_adminmenu_old` VALUES (20, 18, 'item', 'Export a module', '/index.php?mod=admin&sub=modules&action=export', 'Export a module', '_self', 1, 'compress.png');
INSERT INTO `cs_adminmenu_old` VALUES (21, 18, 'item', 'Edit modules', '/index.php?mod=admin&sub=modules&action=show_all', 'Edit modules', '_self', 2, 'bricks_edit.png');
INSERT INTO `cs_adminmenu_old` VALUES (22, 14, 'folder', 'Development', '', 'Development', '_self', 2, 'application_xp_terminal.png');
INSERT INTO `cs_adminmenu_old` VALUES (23, 22, 'item', 'Template Editor', '/index.php?mod=admin&sub=templateditor', 'Template Editor', '_self', 0, 'layout_edit.png');
INSERT INTO `cs_adminmenu_old` VALUES (24, 22, 'item', 'Adminmenu Editor', '/index.php?mod=admin&sub=menueditor', 'Adminmenu Editor', '_self', 1, 'application_form_edit.png');
INSERT INTO `cs_adminmenu_old` VALUES (25, 0, 'folder', 'Administration', '', 'Administration', '_self', 3, '');
INSERT INTO `cs_adminmenu_old` VALUES (26, 25, 'folder', 'Users', '', 'Users', '_self', 0, 'user_suit.png');
INSERT INTO `cs_adminmenu_old` VALUES (27, 26, 'item', 'Show All', '/index.php?mod=admin&sub=users', 'Show All', '_self', 0, 'table.png');
INSERT INTO `cs_adminmenu_old` VALUES (28, 26, 'item', 'Search', '/index.php?mod=admin&sub=search', 'Search', '_self', 1, 'magnifier.png');
INSERT INTO `cs_adminmenu_old` VALUES (29, 25, 'folder', 'Groups', '', 'Groups', '_self', 1, 'group.png');
INSERT INTO `cs_adminmenu_old` VALUES (30, 25, 'folder', 'Permissions', '', 'Permissions', '_self', 2, 'key.png');
INSERT INTO `cs_adminmenu_old` VALUES (31, 25, 'folder', 'Templates', '', 'Templates', '_self', 3, 'layout_header.png');
INSERT INTO `cs_adminmenu_old` VALUES (32, 0, 'folder', 'Help', '', 'Help', '_self', 4, '');
INSERT INTO `cs_adminmenu_old` VALUES (33, 32, 'item', 'Help', '/index.php?mod=admin&sub=static&action=show&page=help', 'Help', '_self', 0, 'help.png');
INSERT INTO `cs_adminmenu_old` VALUES (34, 32, 'item', 'Manual', '/index.php?mod=admin&sub=manual', 'Manual', '_self', 1, 'book_open.png');
INSERT INTO `cs_adminmenu_old` VALUES (35, 32, 'item', 'Report Bugs & Give Feedback', '/index.php?mod=admin&sub=bugs', 'Report Bugs & Give Feedback', '_self', 2, 'error.png');
INSERT INTO `cs_adminmenu_old` VALUES (36, 32, 'item', 'About Clansuite', '/index.php?mod=admin&sub=about', 'About Clansuite', '_self', 3, 'information.png');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_category`
-- 

CREATE TABLE `cs_category` (
  `cat_id` tinyint(4) NOT NULL auto_increment,
  `cat_modulname` text,
  `cat_sortorder` tinyint(4) default NULL,
  `cat_name` text,
  `cat_image_url` varchar(60) default NULL,
  `cat_description` varchar(90) default '0',
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

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
-- Tabellenstruktur für Tabelle `cs_modules`
-- 

CREATE TABLE `cs_modules` (
  `module_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `homepage` varchar(255) NOT NULL,
  `license` varchar(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `folder_name` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `version` float NOT NULL,
  `cs_version` float NOT NULL,
  `core` tinyint(4) NOT NULL default '0',
  `subs` text NOT NULL,
  PRIMARY KEY  (`module_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=87 ;

-- 
-- Daten für Tabelle `cs_modules`
-- 

INSERT INTO `cs_modules` VALUES (77, 'account', 'Jens-André Koch, Florian Wolf', 'http://www.clansuite.com', 'GPL v2', 'Clansuite Group', 'Account Administration', 'This module handles all necessary account stuff - like login/logout etc.', 'module_account', 'account.module.php', 'account', 0, 'module_account.jpg', 0.1, 0, 1, 's:0:"";');
INSERT INTO `cs_modules` VALUES (85, 'news', 'Jens-André Koch, Florian Wolf', 'http://www.clansuite.com', 'GPL v2', 'Clansuite Group', 'News', 'News module', 'module_news', 'news.module.php', 'news', 0, 'module_news.jpg', 0.1, 0, 0, 's:0:"";');
INSERT INTO `cs_modules` VALUES (79, 'captcha', 'Jens-André Koch, Florian Wolf', 'http://www.clansuite.com', 'GPL v2', 'Clansuite Group', 'Captcha Module', 'The captcha module presents a image only humanoids can read.', 'module_captcha', 'captcha.module.php', 'captcha', 0, 'module_captcha.jpg', 0.1, 0, 1, 's:0:"";');
INSERT INTO `cs_modules` VALUES (80, 'index', 'Jens-André Koch, Florian Wolf', 'http://www.clansuite.com', 'GPL v2', 'Clansuite Group', 'Index Module', 'This is the main site.', 'module_index', 'index.module.php', 'index', 0, 'module_index.jpg', 0.1, 0, 1, 's:0:"";');
INSERT INTO `cs_modules` VALUES (78, 'admin', 'Jens-André Koch, Florian Wolf', 'http://www.clansuite.com', 'GPL v2', 'Clansuite Group', 'Admin Interface', 'This is the Admin Control Panel', 'module_admin', 'admin.module.php', 'admin', 0, 'module_admin.jpg', 0.1, 0, 1, 'a:8:{s:7:"configs";a:2:{i:0;s:18:"configs.module.php";i:1;s:20:"module_admin_configs";}s:7:"modules";a:2:{i:0;s:18:"modules.module.php";i:1;s:20:"module_admin_modules";}s:5:"users";a:2:{i:0;s:16:"users.module.php";i:1;s:18:"module_admin_users";}s:10:"usercenter";a:2:{i:0;s:21:"usercenter.module.php";i:1;s:23:"module_admin_usercenter";}s:6:"groups";a:2:{i:0;s:17:"groups.module.php";i:1;s:19:"module_admin_groups";}s:11:"permissions";a:2:{i:0;s:16:"perms.module.php";i:1;s:24:"module_admin_permissions";}s:10:"menueditor";a:2:{i:0;s:21:"menueditor.module.php";i:1;s:23:"module_admin_menueditor";}s:6:"static";a:2:{i:0;s:17:"static.module.php";i:1;s:19:"module_admin_static";}}');
INSERT INTO `cs_modules` VALUES (83, 'static', 'Jens-André Koch,Florian Wolf', 'http://www.clansuite.com', 'GPL v2', 'Clansuite Group', 'Static Pages', 'Static Pages store HTML content', 'module_static', 'static.module.php', 'static', 0, 'module_static.jpg', 0.1, 0, 0, 's:0:"";');
INSERT INTO `cs_modules` VALUES (86, 'shoutbox', 'Björn Spiegel', 'http://www.clansuite.com', 'GPL v2', 'Clansuite Group', 'Shoutbox Modul', 'This module displays a shoutbox. You can do entries and administrate it ...', 'module_shoutbox', 'shoutbox.module.php', 'shoutbox', 0, 'module_shoutbox.jpg', 0.1, 0, 0, 's:0:"";');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_news`
-- 

CREATE TABLE `cs_news` (
  `news_id` int(11) NOT NULL auto_increment,
  `news_title` varchar(255) NOT NULL,
  `news_body` text NOT NULL,
  `news_category` tinyint(4) NOT NULL default '0',
  `user_id` int(11) unsigned NOT NULL default '0',
  `news_added` int(11) default NULL,
  `news_hidden` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`news_id`,`news_category`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

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
  `body` text NOT NULL,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `pseudo` varchar(25) default NULL,
  `ip` varchar(15) NOT NULL,
  `host` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `right_name` varchar(150) NOT NULL,
  PRIMARY KEY  (`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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

INSERT INTO `cs_session` VALUES (0, '021b9bd455a70353a537f85fa226723f', 'client_ip|s:9:"127.0.0.1";client_browser|s:79:"Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8) Gecko/20051111 Firefox/1.5";client_host|s:9:"localhost";suiteSID|s:32:"021b9bd455a70353a537f85fa226723f";user|a:9:{s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:4:"Gast";s:8:"password";s:0:"";s:5:"email";s:0:"";s:10:"first_name";s:7:"Vorname";s:9:"last_name";s:8:"Nachname";s:8:"disabled";s:0:"";s:9:"activated";s:0:"";}', 'suiteSID', 1155716103, 1, 'admin');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_shoutbox`
-- 

CREATE TABLE `cs_shoutbox` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) collate latin1_general_ci NOT NULL,
  `mail` varchar(100) collate latin1_general_ci NOT NULL,
  `msg` tinytext collate latin1_general_ci NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `ip` varchar(15) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `cs_shoutbox`
-- 
INSERT INTO `cs_shoutbox` VALUES (1, '12345', '123test@test.com', 'texttext', 1155898254, '127.0.0.1');
INSERT INTO `cs_shoutbox` VALUES (2, '109876', '123@123.123', 'shoutboxtesttest', 1155898254, '127.0.0.1');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_static_pages`
-- 

CREATE TABLE `cs_static_pages` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `html` text NOT NULL,
  `iframe` tinyint(1) NOT NULL default '0',
  `iframe_height` int(11) NOT NULL default '300',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Daten für Tabelle `cs_static_pages`
-- 

INSERT INTO `cs_static_pages` VALUES (1, 'credits', 'Those are the people who helped', '', '<u><strong>WYSIWYG Editor:</strong></u><br />\r\n<a href="http://www.fckeditor.net/">FCKEditor</a><br />\r\n<br />\r\n<u><strong>Icons:</strong></u><br />\r\n<a href="http://www.famfamfam.com/lab/icons/">famfamfam</a><br />\r\n<br />\r\n<br />\r\nand more...', 1, 300);
INSERT INTO `cs_static_pages` VALUES (2, 'google', 'Google', 'http://www.google.de', '', 1, 500);
INSERT INTO `cs_static_pages` VALUES (3, 'help', 'The help for ClanSuite', '', '<strong><font size="4">Help</font><br />\r\n<br />\r\n</strong><strong> - gogo<br />\r\n- gogogogo<br />\r\n- gogogogogogo</strong>', 1, 300);
INSERT INTO `cs_static_pages` VALUES (4, 'manual', 'The Manual', '', '<font size="4">Manual</font><br />\r\n<br />\r\n- some content', 1, 300);
INSERT INTO `cs_static_pages` VALUES (5, 'about', 'About ClanSuite', '', '<font size="4">About</font><br />\r\n<br />\r\n- some content', 1, 300);

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `pos` tinyint(4) unsigned NOT NULL default '1',
  `name` varchar(75) default NULL,
  `icon` varchar(255) default NULL,
  `colour` varchar(10) NOT NULL,
  `posts` tinyint(5) default NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Daten für Tabelle `cs_usergroups`
-- 

INSERT INTO `cs_usergroups` VALUES (1, 1, 'Administrator', NULL, '0000FF', NULL);
INSERT INTO `cs_usergroups` VALUES (2, 2, 'Newsgruppe', NULL, '00FF00', NULL);
INSERT INTO `cs_usergroups` VALUES (3, 3, 'Gästebuchgruppe', NULL, '', NULL);
INSERT INTO `cs_usergroups` VALUES (4, 0, 'Newbie', NULL, '', 50);
INSERT INTO `cs_usergroups` VALUES (5, 0, 'Advanced Newbie', NULL, '', 100);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_users`
-- 

CREATE TABLE `cs_users` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(150) NOT NULL,
  `nick` varchar(25) NOT NULL,
  `password` varchar(40) NOT NULL,
  `new_password` varchar(40) NOT NULL,
  `code` varchar(255) NOT NULL,
  `joined` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `infotext` text NOT NULL,
  `disabled` tinyint(1) NOT NULL default '0',
  `activated` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`user_id`),
  KEY `email` (`email`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- 
-- Daten für Tabelle `cs_users`
-- 

INSERT INTO `cs_users` VALUES (19, 'admin@localhost.de', 'admin', '26a1102e42022f67a17add9ab0e74c9440efa7d2', '26a1102e42022f67a17add9ab0e74c9440efa7d2', '9dd90802013c886ccdd04d524adf3446', 1152190495, 0, '', '', '', 0, 1);
INSERT INTO `cs_users` VALUES (21, 'asdf2@bla.de', 'bla', '27b276d6221741f11b727e0c24979470f2a7b90a', '', '66a147b49d97ad7df250b0dd91f6d930', 1152208688, 0, '', '', '', 0, 1);
