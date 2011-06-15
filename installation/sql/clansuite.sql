-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 08. Dezember 2010 um 19:15
-- Server Version: 5.1.36
-- PHP-Version: 5.3.0

-- -------------------------------------------------------------------------
-- Changes
-- -------------------------------------------------------------------------
-- 2010/12/13 (paulbr) update 2010-12-13_upd_permissions_toolbox.sql eingefügt
-- 
-- -------------------------------------------------------------------------

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `clansuite`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_acl_actions`
--
-- Erzeugt am: 20. November 2010 um 12:43
--

DROP TABLE IF EXISTS `cs_acl_actions`;
CREATE TABLE `cs_acl_actions` (
  `action_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `modulname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`action_id`),
  KEY `module_name` (`modulname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_acl_actions`
--

INSERT INTO `cs_acl_actions` VALUES(1, 'about', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(2, 'about', 'action_admin_show');
INSERT INTO `cs_acl_actions` VALUES(3, 'about', 'action_admin_edit_info');
INSERT INTO `cs_acl_actions` VALUES(4, 'about', 'action_admin_edit_menu');
INSERT INTO `cs_acl_actions` VALUES(5, 'account', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(6, 'acl', 'action_admin_show');
INSERT INTO `cs_acl_actions` VALUES(7, 'acl', 'action_admin_edit_info');
INSERT INTO `cs_acl_actions` VALUES(8, 'acl', 'action_admin_edit_permissions');
INSERT INTO `cs_acl_actions` VALUES(9, 'categories', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(10, 'controlcenter', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(11, 'cronjobs', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(12, 'doctrine', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(13, 'forum', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(14, 'guestbook', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(15, 'index', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(16, 'languages', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(17, 'menu', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(18, 'mibbitirc', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(19, 'modulemanager', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(20, 'news', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(21, 'rssreader', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(22, 'settings', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(23, 'staticpages', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(24, 'statistics', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(25, 'systeminfo', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(26, 'teamspeakviewer', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(27, 'templatemanager', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(28, 'thememanager', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(29, 'toolbox', 'action_show');
INSERT INTO `cs_acl_actions` VALUES(30, 'toolbox', 'action_admin_show');
INSERT INTO `cs_acl_actions` VALUES(31, 'toolbox', 'widget_toolbox');
INSERT INTO `cs_acl_actions` VALUES(32, 'users', 'action_show');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_acl_roles`
--
-- Erzeugt am: 20. November 2010 um 12:43
--

DROP TABLE IF EXISTS `cs_acl_roles`;
CREATE TABLE `cs_acl_roles` (
  `role_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(48) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` tinyint(4) unsigned NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `sort` smallint(2) NOT NULL COMMENT 'Sort display checkboxen',
  PRIMARY KEY (`role_id`),
  KEY `name` (`name`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_acl_roles`
--

INSERT INTO `cs_acl_roles` VALUES(1, 'root', 0, 'Supervisor', 0);
INSERT INTO `cs_acl_roles` VALUES(2, 'bot', 0, 'Searchengine', 4);
INSERT INTO `cs_acl_roles` VALUES(3, 'guest', 0, 'Guest', 3);
INSERT INTO `cs_acl_roles` VALUES(4, 'member', 3, 'User', 2);
INSERT INTO `cs_acl_roles` VALUES(5, 'admin', 4, 'Administrator', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_acl_rules`
--
-- Erzeugt am: 20. November 2010 um 12:43
--

DROP TABLE IF EXISTS `cs_acl_rules`;
CREATE TABLE `cs_acl_rules` (
  `rule_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` tinyint(4) unsigned NOT NULL,
  `action_id` int(10) unsigned NOT NULL,
  `access` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rule_id`),
  KEY `resource_id` (`action_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_acl_rules`
--

INSERT INTO `cs_acl_rules` VALUES(1, 1, 1, 1);
INSERT INTO `cs_acl_rules` VALUES(2, 2, 1, 1);
INSERT INTO `cs_acl_rules` VALUES(3, 3, 1, 1);
INSERT INTO `cs_acl_rules` VALUES(4, 4, 1, 1);
INSERT INTO `cs_acl_rules` VALUES(5, 5, 1, 1);
INSERT INTO `cs_acl_rules` VALUES(6, 1, 2, 1);
INSERT INTO `cs_acl_rules` VALUES(7, 5, 2, 1);
INSERT INTO `cs_acl_rules` VALUES(8, 1, 3, 1);
INSERT INTO `cs_acl_rules` VALUES(9, 5, 3, 1);
INSERT INTO `cs_acl_rules` VALUES(10, 1, 4, 1);
INSERT INTO `cs_acl_rules` VALUES(11, 5, 4, 1);
INSERT INTO `cs_acl_rules` VALUES(12, 1, 5, 1);
INSERT INTO `cs_acl_rules` VALUES(13, 2, 5, 1);
INSERT INTO `cs_acl_rules` VALUES(14, 3, 5, 1);
INSERT INTO `cs_acl_rules` VALUES(15, 4, 5, 1);
INSERT INTO `cs_acl_rules` VALUES(16, 5, 5, 1);
INSERT INTO `cs_acl_rules` VALUES(17, 1, 6, 1);
INSERT INTO `cs_acl_rules` VALUES(18, 5, 6, 1);
INSERT INTO `cs_acl_rules` VALUES(19, 1, 7, 1);
INSERT INTO `cs_acl_rules` VALUES(20, 5, 7, 1);
INSERT INTO `cs_acl_rules` VALUES(21, 1, 8, 1);
INSERT INTO `cs_acl_rules` VALUES(22, 5, 8, 1);
INSERT INTO `cs_acl_rules` VALUES(23, 1, 9, 1);
INSERT INTO `cs_acl_rules` VALUES(24, 2, 9, 1);
INSERT INTO `cs_acl_rules` VALUES(25, 3, 9, 1);
INSERT INTO `cs_acl_rules` VALUES(26, 4, 9, 1);
INSERT INTO `cs_acl_rules` VALUES(27, 5, 9, 1);
INSERT INTO `cs_acl_rules` VALUES(28, 1, 10, 1);
INSERT INTO `cs_acl_rules` VALUES(29, 2, 10, 1);
INSERT INTO `cs_acl_rules` VALUES(30, 3, 10, 1);
INSERT INTO `cs_acl_rules` VALUES(31, 4, 10, 1);
INSERT INTO `cs_acl_rules` VALUES(32, 5, 10, 1);
INSERT INTO `cs_acl_rules` VALUES(33, 1, 11, 1);
INSERT INTO `cs_acl_rules` VALUES(34, 2, 11, 1);
INSERT INTO `cs_acl_rules` VALUES(35, 3, 11, 1);
INSERT INTO `cs_acl_rules` VALUES(36, 4, 11, 1);
INSERT INTO `cs_acl_rules` VALUES(37, 5, 11, 1);
INSERT INTO `cs_acl_rules` VALUES(38, 1, 12, 1);
INSERT INTO `cs_acl_rules` VALUES(39, 2, 12, 1);
INSERT INTO `cs_acl_rules` VALUES(40, 3, 12, 1);
INSERT INTO `cs_acl_rules` VALUES(41, 4, 12, 1);
INSERT INTO `cs_acl_rules` VALUES(42, 5, 12, 1);
INSERT INTO `cs_acl_rules` VALUES(43, 1, 13, 1);
INSERT INTO `cs_acl_rules` VALUES(44, 2, 13, 1);
INSERT INTO `cs_acl_rules` VALUES(45, 3, 13, 1);
INSERT INTO `cs_acl_rules` VALUES(46, 4, 13, 1);
INSERT INTO `cs_acl_rules` VALUES(47, 5, 13, 1);
INSERT INTO `cs_acl_rules` VALUES(48, 1, 14, 1);
INSERT INTO `cs_acl_rules` VALUES(49, 2, 14, 1);
INSERT INTO `cs_acl_rules` VALUES(50, 3, 14, 1);
INSERT INTO `cs_acl_rules` VALUES(51, 4, 14, 1);
INSERT INTO `cs_acl_rules` VALUES(52, 5, 14, 1);
INSERT INTO `cs_acl_rules` VALUES(53, 1, 15, 1);
INSERT INTO `cs_acl_rules` VALUES(54, 2, 15, 1);
INSERT INTO `cs_acl_rules` VALUES(55, 3, 15, 1);
INSERT INTO `cs_acl_rules` VALUES(56, 4, 15, 1);
INSERT INTO `cs_acl_rules` VALUES(57, 5, 15, 1);
INSERT INTO `cs_acl_rules` VALUES(58, 1, 16, 1);
INSERT INTO `cs_acl_rules` VALUES(59, 2, 16, 1);
INSERT INTO `cs_acl_rules` VALUES(60, 3, 16, 1);
INSERT INTO `cs_acl_rules` VALUES(61, 4, 16, 1);
INSERT INTO `cs_acl_rules` VALUES(62, 5, 16, 1);
INSERT INTO `cs_acl_rules` VALUES(63, 1, 17, 1);
INSERT INTO `cs_acl_rules` VALUES(64, 2, 17, 1);
INSERT INTO `cs_acl_rules` VALUES(65, 3, 17, 1);
INSERT INTO `cs_acl_rules` VALUES(66, 4, 17, 1);
INSERT INTO `cs_acl_rules` VALUES(67, 5, 17, 1);
INSERT INTO `cs_acl_rules` VALUES(68, 1, 18, 1);
INSERT INTO `cs_acl_rules` VALUES(69, 2, 18, 1);
INSERT INTO `cs_acl_rules` VALUES(70, 3, 18, 1);
INSERT INTO `cs_acl_rules` VALUES(71, 4, 18, 1);
INSERT INTO `cs_acl_rules` VALUES(72, 5, 18, 1);
INSERT INTO `cs_acl_rules` VALUES(73, 1, 19, 1);
INSERT INTO `cs_acl_rules` VALUES(74, 2, 19, 1);
INSERT INTO `cs_acl_rules` VALUES(75, 3, 19, 1);
INSERT INTO `cs_acl_rules` VALUES(76, 4, 19, 1);
INSERT INTO `cs_acl_rules` VALUES(77, 5, 19, 1);
INSERT INTO `cs_acl_rules` VALUES(78, 1, 20, 1);
INSERT INTO `cs_acl_rules` VALUES(79, 2, 20, 1);
INSERT INTO `cs_acl_rules` VALUES(80, 3, 20, 1);
INSERT INTO `cs_acl_rules` VALUES(81, 4, 20, 1);
INSERT INTO `cs_acl_rules` VALUES(82, 5, 20, 1);
INSERT INTO `cs_acl_rules` VALUES(83, 1, 21, 1);
INSERT INTO `cs_acl_rules` VALUES(84, 2, 21, 1);
INSERT INTO `cs_acl_rules` VALUES(85, 3, 21, 1);
INSERT INTO `cs_acl_rules` VALUES(86, 4, 21, 1);
INSERT INTO `cs_acl_rules` VALUES(87, 5, 21, 1);
INSERT INTO `cs_acl_rules` VALUES(88, 1, 22, 1);
INSERT INTO `cs_acl_rules` VALUES(89, 2, 22, 1);
INSERT INTO `cs_acl_rules` VALUES(90, 3, 22, 1);
INSERT INTO `cs_acl_rules` VALUES(91, 4, 22, 1);
INSERT INTO `cs_acl_rules` VALUES(92, 5, 22, 1);
INSERT INTO `cs_acl_rules` VALUES(93, 1, 23, 1);
INSERT INTO `cs_acl_rules` VALUES(94, 2, 23, 1);
INSERT INTO `cs_acl_rules` VALUES(95, 3, 23, 1);
INSERT INTO `cs_acl_rules` VALUES(96, 4, 23, 1);
INSERT INTO `cs_acl_rules` VALUES(97, 5, 23, 1);
INSERT INTO `cs_acl_rules` VALUES(98, 1, 24, 1);
INSERT INTO `cs_acl_rules` VALUES(99, 2, 24, 1);
INSERT INTO `cs_acl_rules` VALUES(100, 3, 24, 1);
INSERT INTO `cs_acl_rules` VALUES(101, 4, 24, 1);
INSERT INTO `cs_acl_rules` VALUES(102, 5, 24, 1);
INSERT INTO `cs_acl_rules` VALUES(103, 1, 25, 1);
INSERT INTO `cs_acl_rules` VALUES(104, 2, 25, 1);
INSERT INTO `cs_acl_rules` VALUES(105, 3, 25, 1);
INSERT INTO `cs_acl_rules` VALUES(106, 4, 25, 1);
INSERT INTO `cs_acl_rules` VALUES(107, 5, 25, 1);
INSERT INTO `cs_acl_rules` VALUES(108, 1, 26, 1);
INSERT INTO `cs_acl_rules` VALUES(109, 2, 26, 1);
INSERT INTO `cs_acl_rules` VALUES(110, 3, 26, 1);
INSERT INTO `cs_acl_rules` VALUES(111, 4, 26, 1);
INSERT INTO `cs_acl_rules` VALUES(112, 5, 26, 1);
INSERT INTO `cs_acl_rules` VALUES(113, 1, 27, 1);
INSERT INTO `cs_acl_rules` VALUES(114, 2, 27, 1);
INSERT INTO `cs_acl_rules` VALUES(115, 3, 27, 1);
INSERT INTO `cs_acl_rules` VALUES(116, 4, 27, 1);
INSERT INTO `cs_acl_rules` VALUES(117, 5, 27, 1);
INSERT INTO `cs_acl_rules` VALUES(118, 1, 28, 1);
INSERT INTO `cs_acl_rules` VALUES(119, 2, 28, 1);
INSERT INTO `cs_acl_rules` VALUES(120, 3, 28, 1);
INSERT INTO `cs_acl_rules` VALUES(121, 4, 28, 1);
INSERT INTO `cs_acl_rules` VALUES(122, 5, 28, 1);
INSERT INTO `cs_acl_rules` VALUES(123, 1, 29, 1);
INSERT INTO `cs_acl_rules` VALUES(124, 5, 29, 1);
INSERT INTO `cs_acl_rules` VALUES(125, 1, 30, 1);
INSERT INTO `cs_acl_rules` VALUES(126, 5, 30, 1);
INSERT INTO `cs_acl_rules` VALUES(127, 1, 31, 1);
INSERT INTO `cs_acl_rules` VALUES(128, 5, 31, 1);
INSERT INTO `cs_acl_rules` VALUES(129, 1, 32, 1);
INSERT INTO `cs_acl_rules` VALUES(130, 2, 32, 1);
INSERT INTO `cs_acl_rules` VALUES(131, 3, 32, 1);
INSERT INTO `cs_acl_rules` VALUES(132, 4, 32, 1);
INSERT INTO `cs_acl_rules` VALUES(133, 5, 32, 1);
INSERT INTO `cs_acl_rules` VALUES(134, 2, 31, 1);
INSERT INTO `cs_acl_rules` VALUES(135, 3, 31, 1);
INSERT INTO `cs_acl_rules` VALUES(136, 4, 31, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_adminmenu`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_adminmenu`;
CREATE TABLE `cs_adminmenu` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `href` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `target` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sortorder` tinyint(4) NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permission` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`,`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_adminmenu`
--

INSERT INTO `cs_adminmenu` VALUES(1, 0, 'folder', 'Control Center', '?mod=controlcenter', 'Clansuite Control Center', '_self', 0, 'layout_header.png', 'admin');
INSERT INTO `cs_adminmenu` VALUES(2, 0, 'folder', 'Redaktion', '', 'Redaktion', '_self', 1, 'page_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(3, 2, 'folder', 'News', '', 'News', '_self', 0, 'page_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(4, 3, 'item', 'Manage News', 'index.php?mod=news&amp;sub=admin', 'Manage News', '_self', 0, 'application_form_edit.png', 'cc_edit_news');
INSERT INTO `cs_adminmenu` VALUES(5, 3, 'item', 'Create news', 'index.php?mod=news&amp;sub=admin&amp;action=create', 'Create news', '_self', 1, 'add.png', 'cc_create_news');
INSERT INTO `cs_adminmenu` VALUES(6, 2, 'folder', 'Blog', '', 'Blog', '_self', 1, 'book_open.png', '');
INSERT INTO `cs_adminmenu` VALUES(7, 6, 'item', 'Manage Blog', 'index.php?mod=blog&amp;sub=admin', 'Manage Blog', '_self', 0, 'pencil.png', '');
INSERT INTO `cs_adminmenu` VALUES(8, 2, 'folder', 'Articles', '', 'Articles', '_self', 2, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(9, 8, 'item', 'Manage Articles', 'index.php?mod=articles&amp;sub=admin', 'Manage Articles', '_self', 0, 'bricks_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(10, 2, 'folder', 'Reviews', '', 'Reviews', '_self', 3, 'pencil.png', '');
INSERT INTO `cs_adminmenu` VALUES(11, 10, 'item', 'Manage Reviews', 'index.php?mod=reviews&amp;sub=admin', 'Manage Reviews', '_self', 0, 'report.png', '');
INSERT INTO `cs_adminmenu` VALUES(12, 2, 'folder', 'Coverages', '', 'Coverages', '_self', 4, 'package.png', '');
INSERT INTO `cs_adminmenu` VALUES(13, 12, 'item', 'Manage Coverages', 'index.php?mod=coverages&amp;sub=admin', 'Manage Coverages', '_self', 0, 'application_view_list.png', '');
INSERT INTO `cs_adminmenu` VALUES(14, 0, 'folder', 'Community', '', 'Community', '_self', 2, 'bricks.png', '');
INSERT INTO `cs_adminmenu` VALUES(15, 14, 'folder', 'Forum', '', 'Forum', '_self', 0, 'application_view_list.png', '');
INSERT INTO `cs_adminmenu` VALUES(16, 15, 'item', 'Manage Forum', 'index.php?mod=forum&amp;sub=admin', 'Manage Forum', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(17, 14, 'folder', 'Guestbook', 'index.php?mod=guestbook&amp;action=show', 'Guestbook', '_self', 1, 'book_open.png', '');
INSERT INTO `cs_adminmenu` VALUES(18, 17, 'item', 'Manage Guestbook', 'index.php?mod=guestbook&amp;sub=admin', 'Manage Guestbook', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(19, 14, 'folder', 'Shoutbox', '', 'Shoutbox', '_self', 2, 'comment.png', '');
INSERT INTO `cs_adminmenu` VALUES(20, 19, 'item', 'Manage Shoutbox', 'index.php?mod=shoutbox&amp;sub=admin', 'Manage Shoutbox', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(21, 0, 'folder', 'Clanverwaltung', '', 'Clanverwaltung', '_self', 3, 'group.png', '');
INSERT INTO `cs_adminmenu` VALUES(22, 21, 'folder', 'Clankasse', '', 'Clankasse', '_self', 0, 'book_open.png', '');
INSERT INTO `cs_adminmenu` VALUES(23, 22, 'item', 'Manage Clancash', 'index.php?mod=clancash&amp;sub=admin', 'Manage Clancash', '_self', 0, 'money_dollar.png', '');
INSERT INTO `cs_adminmenu` VALUES(24, 21, 'folder', 'Teams', '', 'Teams', '_self', 1, 'user_suit.png', '');
INSERT INTO `cs_adminmenu` VALUES(25, 24, 'item', 'Manage Teams', 'index.php?mod=teams&amp;sub=admin', 'Manage Teams', '_self', 0, 'group.png', '');
INSERT INTO `cs_adminmenu` VALUES(26, 21, 'folder', 'Matches', 'index.php?mod=matches&amp;action=show', 'Matches', '_self', 2, 'database_go.png', '');
INSERT INTO `cs_adminmenu` VALUES(27, 26, 'item', 'Manage Matches', 'index.php?mod=matches&amp;sub=admin', 'Manage Matches', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(28, 21, 'folder', 'Replays', '', 'Replays', '_self', 3, 'film.png', '');
INSERT INTO `cs_adminmenu` VALUES(29, 28, 'item', 'Manage Replays', 'index.php?mod=replays&amp;sub=admin', 'Manage Replays', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(30, 21, 'folder', 'Serverlist', '', 'Serverlist', '_self', 4, 'table.png', '');
INSERT INTO `cs_adminmenu` VALUES(31, 30, 'item', 'Show Servers', 'index.php?mod=serverlist&amp;sub=admin&amp;action=show', 'Show Servers', '_self', 0, 'application_view_list.png', '');
INSERT INTO `cs_adminmenu` VALUES(32, 30, 'item', 'Add Server', 'index.php?mod=serverlist&amp;sub=admin&amp;action=create', 'Add Server', '_self', 1, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(33, 0, 'folder', 'Mediacenter', '', 'Mediacenter', '_self', 4, 'film.png', '');
INSERT INTO `cs_adminmenu` VALUES(34, 33, 'folder', 'Downloads', '', 'Downloads', '_self', 0, 'disk.png', '');
INSERT INTO `cs_adminmenu` VALUES(35, 34, 'item', 'Manage Downloads', 'index.php?mod=downloads&amp;sub=admin', 'Manage Downloads', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(36, 33, 'folder', 'Gallery', '', 'Gallery', '_self', 1, 'map_go.png', '');
INSERT INTO `cs_adminmenu` VALUES(37, 36, 'item', 'Manage Gallery', 'index.php?mod=gallery&amp;sub=admin', 'Manage Gallery', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(38, 33, 'folder', 'Videos', '', 'Videos', '_self', 2, 'film.png', '');
INSERT INTO `cs_adminmenu` VALUES(39, 38, 'item', 'Manage Videos', 'index.php?mod=quotes&amp;sub=admin', 'Manage Videos', '_self', 0, 'film.png', '');
INSERT INTO `cs_adminmenu` VALUES(40, 0, 'folder', 'Website', '', 'Website', '_self', 5, 'table.png', '');
INSERT INTO `cs_adminmenu` VALUES(41, 40, 'folder', 'Slideshow', '', 'Slideshow', '_self', 0, 'comment.png', '');
INSERT INTO `cs_adminmenu` VALUES(42, 40, 'folder', 'Quotes', '', 'Quotes', '_self', 1, 'report.png', '');
INSERT INTO `cs_adminmenu` VALUES(43, 42, 'item', 'Manage Quotes', 'index.php?mod=quotes&amp;sub=admin', 'Manage Quotes', '_self', 0, 'report.png', '');
INSERT INTO `cs_adminmenu` VALUES(44, 40, 'folder', 'Static Pages', '', 'Static Pages', '_self', 2, 'html.png', '');
INSERT INTO `cs_adminmenu` VALUES(45, 44, 'item', 'Manage Static Pages', 'index.php?mod=staticpages&amp;sub=admin', 'Show Static Pages', '_self', 0, 'pencil.png', '');
INSERT INTO `cs_adminmenu` VALUES(46, 44, 'item', 'Create new Static Page', 'index.php?mod=staticpages&amp;sub=admin&amp;action=create', 'Create new Static Page', '_self', 1, 'add.png', '');
INSERT INTO `cs_adminmenu` VALUES(47, 0, 'folder', 'Users', '', 'Users', '_self', 6, 'user_suit.png', '');
INSERT INTO `cs_adminmenu` VALUES(48, 47, 'folder', 'Users', '', 'Users', '_self', 0, 'user_suit.png', '');
INSERT INTO `cs_adminmenu` VALUES(49, 48, 'item', 'Manage Users', 'index.php?mod=users&amp;sub=admin', 'Manage users', '_self', 0, 'table.png', 'cc_show_users');
INSERT INTO `cs_adminmenu` VALUES(50, 48, 'item', 'Search User', 'index.php?mod=users&amp;sub=admin&amp;action=search', 'Search a User', '_self', 1, 'magnifier.png', 'cc_search_users');
INSERT INTO `cs_adminmenu` VALUES(51, 47, 'folder', 'Groups', '', 'Groups', '_self', 1, 'group.png', '');
INSERT INTO `cs_adminmenu` VALUES(52, 51, 'item', 'Manage Groups', 'index.php?mod=groups&amp;sub=admin', 'Show all Groups', '_self', 0, 'table.png', '');
INSERT INTO `cs_adminmenu` VALUES(53, 51, 'item', 'Create Group', 'index.php?mod=groups&amp;sub=admin&amp;action=create', 'Create a group', '_self', 1, 'add.png', '');
INSERT INTO `cs_adminmenu` VALUES(54, 47, 'folder', 'Permissions', '', 'Permissions', '_self', 2, 'key.png', '');
INSERT INTO `cs_adminmenu` VALUES(55, 54, 'item', 'Manage Permissions', 'index.php?mod=permissions&amp;sub=admin', 'Manage permissions', '_self', 0, 'add.png', '');
INSERT INTO `cs_adminmenu` VALUES(56, 0, 'folder', 'System', '', 'System', '_self', 7, 'computer.png', '');
INSERT INTO `cs_adminmenu` VALUES(57, 56, 'item', 'Settings', 'index.php?mod=settings&amp;sub=admin', 'Settings', '_self', 0, 'settings.png', '');
INSERT INTO `cs_adminmenu` VALUES(58, 56, 'item', 'Categories', 'index.php?mod=categories&amp;sub=admin', 'Categories', '_self', 1, 'spellcheck.png', '');
INSERT INTO `cs_adminmenu` VALUES(59, 56, 'item', 'Games', 'index.php?mod=games&amp;sub=admin', 'Games', '_self', 2, 'module-inactive.gif', '');
INSERT INTO `cs_adminmenu` VALUES(60, 56, 'item', 'Cache', 'index.php?mod=cache&amp;sub=admin', 'Cache', 'self', 3, 'bullet_disk.png', '');
INSERT INTO `cs_adminmenu` VALUES(61, 56, 'folder', 'Wartung', '', 'Wartung', '_self', 4, 'warning.png', '');
INSERT INTO `cs_adminmenu` VALUES(62, 61, 'item', 'Cronjobs', 'index.php?mod=cronjobs&amp;sub=admin', 'Cronjobs', 'self', 0, 'settings.png', '');
INSERT INTO `cs_adminmenu` VALUES(63, 61, 'folder', 'Hooks & Events', '', 'Hooks & Events', 'self', 1, 'settings.png', '');
INSERT INTO `cs_adminmenu` VALUES(64, 63, 'item', 'Bridges', 'index.php?mod=bridges&amp;sub=admin', 'Bridges', '_self', 0, 'application_view_list.png', '');
INSERT INTO `cs_adminmenu` VALUES(65, 61, 'folder', 'Paket-Management', '', 'Paket-Mangement', '_self', 2, 'bricks.png', '');
INSERT INTO `cs_adminmenu` VALUES(66, 65, 'item', 'Search Pakets', 'index.php?mod=paketmanager&amp;sub=admin', 'Search Pakets', 'self', 0, 'package.png', '');
INSERT INTO `cs_adminmenu` VALUES(67, 65, 'item', 'Search Module', 'Search%20Modules', 'Search Module', 'self', 1, 'package.png', '');
INSERT INTO `cs_adminmenu` VALUES(68, 65, 'item', 'Search Themes', 'index.php?mod=paketmanager&amp;sub=admin', 'Search Themes', 'self', 2, 'package.png', '');
INSERT INTO `cs_adminmenu` VALUES(69, 61, 'folder', 'Database', '', 'Database', '_self', 3, 'database_gear.png', '');
INSERT INTO `cs_adminmenu` VALUES(70, 69, 'item', 'Import', '', 'Import', '_self', 0, 'database_go.png', '');
INSERT INTO `cs_adminmenu` VALUES(71, 69, 'item', 'Optimize', 'index.php?mod=database&amp;action=optimize', 'Optimize', '_self', 1, 'database_gear.png', '');
INSERT INTO `cs_adminmenu` VALUES(72, 69, 'item', 'Export & Backup', 'index.php?mod=database&amp;action=backup', 'Export & Backup', '_self', 2, 'database_key.png', '');
INSERT INTO `cs_adminmenu` VALUES(73, 56, 'folder', 'Layout', '', 'Layout', '_self', 5, 'layout_header.png', '');
INSERT INTO `cs_adminmenu` VALUES(74, 73, 'item', 'BB Codes', 'index.php?mod=admin&amp;sub=bbcode', 'BB Code Editor', '_self', 0, 'text_bold.png', '');
INSERT INTO `cs_adminmenu` VALUES(75, 73, 'item', 'Menueditor', 'index.php?mod=menu&amp;sub=admin&amp;action=menueditor', 'Menueditor', '_self', 1, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(76, 73, 'item', 'Templates', 'index.php?mod=templatemanager&amp;sub=admin', 'Template Editor', '_self', 2, 'layout_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(77, 73, 'item', 'Themes', 'index.php?mod=thememanager&amp;sub=admin', 'Themes Manager', '_self', 3, 'layout_edit.png', 'cc_edit_themes');
INSERT INTO `cs_adminmenu` VALUES(78, 56, 'folder', 'Modules', '', 'Modules', '_self', 6, 'bricks.png', '');
INSERT INTO `cs_adminmenu` VALUES(79, 78, 'item', 'Install Modules', 'index.php?mod=modulemanager&amp;sub=admin&amp;action=install', 'Install new modules', '_self', 0, 'package.png', '');
INSERT INTO `cs_adminmenu` VALUES(80, 78, 'item', 'Create Module', 'index.php?mod=modulemanager&amp;sub=admin&amp;action=builder', 'Create a module', '_self', 1, 'add.png', '');
INSERT INTO `cs_adminmenu` VALUES(81, 78, 'item', 'Manage modules', 'index.php?mod=modulemanager&amp;sub=admin&amp;action=show', 'Manage modules', '_self', 2, 'bricks_edit.png', '');
INSERT INTO `cs_adminmenu` VALUES(82, 78, 'item', 'Export a module', 'index.php?mod=modulemanager&amp;sub=admin&amp;action=export', 'Export a module', '_self', 3, 'compress.png', '');
INSERT INTO `cs_adminmenu` VALUES(83, 56, 'folder', 'Language', '', 'Language', '_self', 7, 'spellcheck.png', '');
INSERT INTO `cs_adminmenu` VALUES(84, 83, 'item', 'Language Editor', 'index.php?mod=language&amp;sub=admin', 'Language Editor', '_self', 0, 'spellcheck.png', '');
INSERT INTO `cs_adminmenu` VALUES(85, 56, 'folder', 'Logs', 'index.php?mod=logs&amp;sub=admin', 'Logs', 'self', 8, 'table.png', '');
INSERT INTO `cs_adminmenu` VALUES(86, 85, 'item', 'Overview', 'index.php?mod=logs&amp;ampsub=admin', 'Overview', 'self', 0, 'table.png', '');
INSERT INTO `cs_adminmenu` VALUES(87, 85, 'item', 'Error Log', 'index.php?mod=logs&amp;sub=admin&amp;action=errorlog', 'Error Log', 'self', 1, 'table.png', '');
INSERT INTO `cs_adminmenu` VALUES(88, 85, 'item', 'Moderator Log', 'index.php?mod=logs&amp;sub=admin&amp;action=', 'Moderator Log', 'self', 2, 'table.png', '');
INSERT INTO `cs_adminmenu` VALUES(89, 56, 'item', 'Statistics', 'index.php?mod=statistics&amp;sub=admin', 'Statistics', '_self', 9, 'table.png', '');
INSERT INTO `cs_adminmenu` VALUES(90, 56, 'item', 'Systeminformation', 'index.php?mod=systeminfo&amp;sub=admin', 'Systeminformation', '_self', 10, 'information.png', '');
INSERT INTO `cs_adminmenu` VALUES(91, 0, 'folder', 'Help', '', 'Help', '_self', 8, 'help.png', '');
INSERT INTO `cs_adminmenu` VALUES(92, 91, 'item', 'Help & Support', 'index.php?mod=controlcenter&amp;&amp;action=show&amp;action=supportlinks', 'Help', '_self', 0, 'help.png', '');
INSERT INTO `cs_adminmenu` VALUES(93, 91, 'item', 'Online-Manuals', 'http://www.clansuite.com/documentation', 'Manual', '_blank', 1, 'book_open.png', '');
INSERT INTO `cs_adminmenu` VALUES(94, 91, 'item', 'Report Bugs & Issues', 'index.php?mod=controlcenter&amp;action=bugs', 'Report Bugs & Issues', '_self', 2, 'error.png', '');
INSERT INTO `cs_adminmenu` VALUES(95, 91, 'item', 'About Clansuite', 'index.php?mod=controlcenter&amp;action=about', 'About Clansuite', '_self', 3, 'information.png', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_adminmenu_backup`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_adminmenu_backup`;
CREATE TABLE `cs_adminmenu_backup` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `href` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `target` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sortorder` tinyint(4) NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permission` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`,`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_adminmenu_backup`
--

INSERT INTO `cs_adminmenu_backup` VALUES(1, 0, 'folder', 'Control Center', '?mod=controlcenter', 'Clansuite Control Center', '_self', 0, 'layout_header.png', 'admin');
INSERT INTO `cs_adminmenu_backup` VALUES(2, 0, 'folder', 'Redaktion', '', 'Redaktion', '_self', 1, 'page_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(3, 2, 'folder', 'News', '', 'News', '_self', 0, 'page_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(4, 3, 'item', 'Manage News', 'index.php?mod=news&amp;sub=admin', 'Manage News', '_self', 0, 'application_form_edit.png', 'cc_edit_news');
INSERT INTO `cs_adminmenu_backup` VALUES(5, 3, 'item', 'Create news', 'index.php?mod=news&amp;sub=admin&amp;action=create', 'Create news', '_self', 1, 'add.png', 'cc_create_news');
INSERT INTO `cs_adminmenu_backup` VALUES(6, 2, 'folder', 'Blog', '', 'Blog', '_self', 1, 'book_open.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(7, 6, 'item', 'Manage Blog', 'index.php?mod=blog&amp;sub=admin', 'Manage Blog', '_self', 0, 'pencil.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(8, 2, 'folder', 'Articles', '', 'Articles', '_self', 2, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(9, 8, 'item', 'Manage Articles', 'index.php?mod=articles&amp;sub=admin', 'Manage Articles', '_self', 0, 'bricks_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(10, 2, 'folder', 'Reviews', '', 'Reviews', '_self', 3, 'pencil.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(11, 10, 'item', 'Manage Reviews', 'index.php?mod=reviews&amp;sub=admin', 'Manage Reviews', '_self', 0, 'report.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(12, 2, 'folder', 'Coverages', '', 'Coverages', '_self', 4, 'package.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(13, 12, 'item', 'Manage Coverages', 'index.php?mod=coverages&amp;sub=admin', 'Manage Coverages', '_self', 0, 'application_view_list.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(14, 0, 'folder', 'Community', '', 'Community', '_self', 2, 'bricks.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(15, 14, 'folder', 'Forum', '', 'Forum', '_self', 0, 'application_view_list.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(16, 15, 'item', 'Manage Forum', 'index.php?mod=forum&amp;sub=admin', 'Manage Forum', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(17, 14, 'folder', 'Guestbook', 'index.php?mod=guestbook&amp;action=show', 'Guestbook', '_self', 1, 'book_open.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(18, 17, 'item', 'Manage Guestbook', 'index.php?mod=guestbook&amp;sub=admin', 'Manage Guestbook', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(19, 14, 'folder', 'Shoutbox', '', 'Shoutbox', '_self', 2, 'comment.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(20, 19, 'item', 'Manage Shoutbox', 'index.php?mod=shoutbox&amp;sub=admin', 'Manage Shoutbox', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(21, 0, 'folder', 'Clanverwaltung', '', 'Clanverwaltung', '_self', 3, 'group.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(22, 21, 'folder', 'Clankasse', '', 'Clankasse', '_self', 0, 'book_open.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(23, 22, 'item', 'Manage Clancash', 'index.php?mod=clancash&amp;sub=admin', 'Manage Clancash', '_self', 0, 'money_dollar.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(24, 21, 'folder', 'Teams', '', 'Teams', '_self', 1, 'user_suit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(25, 24, 'item', 'Manage Teams', 'index.php?mod=teams&amp;sub=admin', 'Manage Teams', '_self', 0, 'group.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(26, 21, 'folder', 'Matches', 'index.php?mod=matches&amp;action=show', 'Matches', '_self', 2, 'database_go.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(27, 26, 'item', 'Manage Matches', 'index.php?mod=matches&amp;sub=admin', 'Manage Matches', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(28, 21, 'folder', 'Replays', '', 'Replays', '_self', 3, 'film.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(29, 28, 'item', 'Manage Replays', 'index.php?mod=replays&amp;sub=admin', 'Manage Replays', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(30, 21, 'folder', 'Serverlist', '', 'Serverlist', '_self', 4, 'table.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(31, 30, 'item', 'Show Servers', 'index.php?mod=serverlist&amp;sub=admin&amp;action=show', 'Show Servers', '_self', 0, 'application_view_list.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(32, 30, 'item', 'Add Server', 'index.php?mod=serverlist&amp;sub=admin&amp;action=create', 'Add Server', '_self', 1, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(33, 0, 'folder', 'Mediacenter', '', 'Mediacenter', '_self', 4, 'film.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(34, 33, 'folder', 'Downloads', '', 'Downloads', '_self', 0, 'disk.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(35, 34, 'item', 'Manage Downloads', 'index.php?mod=downloads&amp;sub=admin', 'Manage Downloads', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(36, 33, 'folder', 'Gallery', '', 'Gallery', '_self', 1, 'map_go.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(37, 36, 'item', 'Manage Gallery', 'index.php?mod=gallery&amp;sub=admin', 'Manage Gallery', '_self', 0, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(38, 33, 'folder', 'Videos', '', 'Videos', '_self', 2, 'film.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(39, 38, 'item', 'Manage Videos', 'index.php?mod=quotes&amp;sub=admin', 'Manage Videos', '_self', 0, 'film.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(40, 0, 'folder', 'Website', '', 'Website', '_self', 5, 'table.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(41, 40, 'folder', 'Slideshow', '', 'Slideshow', '_self', 0, 'comment.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(42, 40, 'folder', 'Quotes', '', 'Quotes', '_self', 1, 'report.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(43, 42, 'item', 'Manage Quotes', 'index.php?mod=quotes&amp;sub=admin', 'Manage Quotes', '_self', 0, 'report.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(44, 40, 'folder', 'Static Pages', '', 'Static Pages', '_self', 2, 'html.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(45, 44, 'item', 'Manage Static Pages', 'index.php?mod=staticpages&amp;sub=admin', 'Show Static Pages', '_self', 0, 'pencil.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(46, 44, 'item', 'Create new Static Page', 'index.php?mod=staticpages&amp;sub=admin&amp;action=create', 'Create new Static Page', '_self', 1, 'add.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(47, 0, 'folder', 'Users', '', 'Users', '_self', 6, 'user_suit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(48, 47, 'folder', 'Users', '', 'Users', '_self', 0, 'user_suit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(49, 48, 'item', 'Manage Users', 'index.php?mod=users&amp;sub=admin', 'Manage users', '_self', 0, 'table.png', 'cc_show_users');
INSERT INTO `cs_adminmenu_backup` VALUES(50, 48, 'item', 'Search User', 'index.php?mod=users&amp;sub=admin&amp;action=search', 'Search a User', '_self', 1, 'magnifier.png', 'cc_search_users');
INSERT INTO `cs_adminmenu_backup` VALUES(51, 47, 'folder', 'Groups', '', 'Groups', '_self', 1, 'group.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(52, 51, 'item', 'Manage Groups', 'index.php?mod=groups&amp;sub=admin', 'Show all Groups', '_self', 0, 'table.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(53, 51, 'item', 'Create Group', 'index.php?mod=groups&amp;sub=admin&amp;action=create', 'Create a group', '_self', 1, 'add.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(54, 47, 'folder', 'Permissions', '', 'Permissions', '_self', 2, 'key.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(55, 54, 'item', 'Manage Permissions', 'index.php?mod=permissions&amp;sub=admin', 'Manage permissions', '_self', 0, 'add.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(56, 0, 'folder', 'System', '', 'System', '_self', 7, 'computer.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(57, 56, 'item', 'Settings', 'index.php?mod=settings&amp;sub=admin', 'Settings', '_self', 0, 'settings.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(58, 56, 'item', 'Categories', 'index.php?mod=categories&amp;sub=admin', 'Categories', '_self', 1, 'spellcheck.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(59, 56, 'item', 'Games', 'index.php?mod=games&amp;sub=admin', 'Games', '_self', 2, 'module-inactive.gif', '');
INSERT INTO `cs_adminmenu_backup` VALUES(60, 56, 'item', 'Cache', 'index.php?mod=cache&amp;sub=admin', 'Cache', 'self', 3, 'bullet_disk.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(61, 56, 'folder', 'Wartung', '', 'Wartung', '_self', 4, 'warning.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(62, 61, 'item', 'Cronjobs', 'index.php?mod=cronjobs&amp;sub=admin', 'Cronjobs', 'self', 0, 'settings.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(63, 61, 'folder', 'Hooks & Events', '', 'Hooks & Events', 'self', 1, 'settings.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(64, 63, 'item', 'Bridges', 'index.php?mod=bridges&amp;sub=admin', 'Bridges', '_self', 0, 'application_view_list.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(65, 61, 'folder', 'Paket-Management', '', 'Paket-Mangement', '_self', 2, 'bricks.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(66, 65, 'item', 'Search Pakets', 'index.php?mod=paketmanager&amp;sub=admin', 'Search Pakets', 'self', 0, 'package.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(67, 65, 'item', 'Search Module', 'Search%20Modules', 'Search Module', 'self', 1, 'package.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(68, 65, 'item', 'Search Themes', 'index.php?mod=paketmanager&amp;sub=admin', 'Search Themes', 'self', 2, 'package.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(69, 61, 'folder', 'Database', '', 'Database', '_self', 3, 'database_gear.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(70, 69, 'item', 'Import', '', 'Import', '_self', 0, 'database_go.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(71, 69, 'item', 'Optimize', 'index.php?mod=database&amp;action=optimize', 'Optimize', '_self', 1, 'database_gear.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(72, 69, 'item', 'Export & Backup', 'index.php?mod=database&amp;action=backup', 'Export & Backup', '_self', 2, 'database_key.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(73, 56, 'folder', 'Layout', '', 'Layout', '_self', 5, 'layout_header.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(74, 73, 'item', 'BB Codes', 'index.php?mod=admin&amp;sub=bbcode', 'BB Code Editor', '_self', 0, 'text_bold.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(75, 73, 'item', 'Adminmenu', 'index.php?mod=menu&amp;sub=admin&amp;action=menueditor', 'Adminmenu Editor', '_self', 1, 'application_form_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(76, 73, 'item', 'Templates', 'index.php?mod=templatemanager&amp;sub=admin', 'Template Editor', '_self', 2, 'layout_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(77, 73, 'item', 'Themes', 'index.php?mod=thememanager&amp;sub=admin', 'Themes Manager', '_self', 3, 'layout_edit.png', 'cc_edit_themes');
INSERT INTO `cs_adminmenu_backup` VALUES(78, 56, 'folder', 'Modules', '', 'Modules', '_self', 6, 'bricks.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(79, 78, 'item', 'Install Modules', 'index.php?mod=modulemanager&amp;sub=admin&amp;action=install', 'Install new modules', '_self', 0, 'package.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(80, 78, 'item', 'Create Module', 'index.php?mod=modulemanager&amp;sub=admin&amp;action=builder', 'Create a module', '_self', 1, 'add.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(81, 78, 'item', 'Manage modules', 'index.php?mod=modulemanager&amp;sub=admin&amp;action=show', 'Manage modules', '_self', 2, 'bricks_edit.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(82, 78, 'item', 'Export a module', 'index.php?mod=modulemanager&amp;sub=admin&amp;action=export', 'Export a module', '_self', 3, 'compress.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(83, 56, 'folder', 'Language', '', 'Language', '_self', 7, 'spellcheck.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(84, 83, 'item', 'Language Editor', 'index.php?mod=language&amp;sub=admin', 'Language Editor', '_self', 0, 'spellcheck.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(85, 56, 'folder', 'Logs', 'index.php?mod=logs&amp;sub=admin', 'Logs', 'self', 8, 'table.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(86, 85, 'item', 'Overview', 'index.php?mod=logs&amp;ampsub=admin', 'Overview', 'self', 0, 'table.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(87, 85, 'item', 'Error Log', 'index.php?mod=logs&amp;sub=admin&amp;action=errorlog', 'Error Log', 'self', 1, 'table.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(88, 85, 'item', 'Moderator Log', 'index.php?mod=logs&amp;sub=admin&amp;action=', 'Moderator Log', 'self', 2, 'table.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(89, 56, 'item', 'Statistics', 'index.php?mod=statistics&amp;sub=admin', 'Statistics', '_self', 9, 'table.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(90, 56, 'item', 'Systeminformation', 'index.php?mod=systeminfo&amp;sub=admin', 'Systeminformation', '_self', 10, 'information.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(91, 0, 'folder', 'Help', '', 'Help', '_self', 8, 'help.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(92, 91, 'item', 'Help & Support', 'index.php?mod=controlcenter&amp;&amp;action=show&amp;action=supportlinks', 'Help', '_self', 0, 'help.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(93, 91, 'item', 'Online-Manuals', 'http://www.clansuite.com/documentation', 'Manual', '_blank', 1, 'book_open.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(94, 91, 'item', 'Report Bugs & Issues', 'index.php?mod=controlcenter&amp;action=bugs', 'Report Bugs & Issues', '_self', 2, 'error.png', '');
INSERT INTO `cs_adminmenu_backup` VALUES(95, 91, 'item', 'About Clansuite', 'index.php?mod=controlcenter&amp;action=about', 'About Clansuite', '_self', 3, 'information.png', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_adminmenu_shortcuts`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_adminmenu_shortcuts`;
CREATE TABLE `cs_adminmenu_shortcuts` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `href` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` tinyint(4) NOT NULL DEFAULT '30',
  `cat` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_adminmenu_shortcuts`
--

INSERT INTO `cs_adminmenu_shortcuts` VALUES(1, 'Console', 'index.php?mod=admin&amp;sub=console', 'console.png', 45, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(2, 'Downloads', 'index.php?mod=admin&amp;sub=downloads', 'downloads.png', 30, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(3, 'Articles', 'index.php?mod=admin&amp;sub=articles', 'articles.png', 30, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(4, 'Links', 'index.php?mod=admin&amp;sub=links', 'links.png', 30, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(5, 'Calendar', 'index.php?mod=admin&amp;sub=calendar', 'calendar.png', 30, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(6, 'Time', 'index.php?mod=admin&amp;sub=time', 'time.png', 30, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(7, 'Email', 'index.php?mod=admin&amp;sub=email', 'email.png', 3, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(8, 'Shoutbox', 'index.php?mod=admin&amp;sub=shoutbox', 'shoutbox.png', 30, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(9, 'Help', 'index.php?mod=admin&amp;sub=help', 'help.png', 40, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(10, 'Security', 'index.php?mod=admin&amp;sub=security', 'security.png', 41, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(11, 'Gallery', 'index.php?mod=admin&amp;sub=gallery', 'gallery.png', 30, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(12, 'System', 'index.php?mod=admin&amp;sub=system', 'system.png', 42, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(13, 'Replays', 'index.php?mod=admin&amp;sub=replays', 'replays.png', 30, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(14, 'News', 'index.php?mod=admin&amp;sub=news', 'news.png', 2, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(15, 'Settings', 'index.php?mod=admin&amp;sub=settings', 'settings.png', 43, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(16, 'Users', 'index.php?mod=admin&amp;sub=users', 'users.png', 1, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(17, 'Backup', 'index.php?mod=admin&amp;sub=backup', 'backup.png', 44, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(18, 'Templates', 'index.php?mod=admin&amp;sub=templates', 'templates.png', 4, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(19, 'Groups', 'index.php?mod=admin&amp;sub=groups', 'groups.png', 2, '');
INSERT INTO `cs_adminmenu_shortcuts` VALUES(20, 'Search', 'index.php?mod=admin&amp;sub=search', 'search.png', 30, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_bb_code`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_bb_code`;
CREATE TABLE `cs_bb_code` (
  `bb_code_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `end_tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `allowed_in` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `not_allowed_in` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`bb_code_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_bb_code`
--

INSERT INTO `cs_bb_code` VALUES(1, 'b', '<b>', '</b>', 'block', 'listitem,block,inline,link', '');
INSERT INTO `cs_bb_code` VALUES(2, 'i', '<i>', '</i>', 'block', 'listitem,block,inline,link', '');
INSERT INTO `cs_bb_code` VALUES(3, 'center', '<center>', '</center>', 'block', 'listitem,block,inline,link', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_calendar`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_calendar`;
CREATE TABLE `cs_calendar` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` smallint(2) NOT NULL,
  `day` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `month` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `year` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `eventname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`event_id`,`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_calendar`
--

INSERT INTO `cs_calendar` VALUES(1, 1, '19', '4', '2007', 'badday', 'badday', 'lalal');
INSERT INTO `cs_calendar` VALUES(2, 1, '19', '4', '2007', 'badday 2nd event', 'badday 2nd event', 'asdhfkjashdkfjaasd');
INSERT INTO `cs_calendar` VALUES(3, 1, '14', '4', '2007', '123', '123', '123');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_categories`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_categories`;
CREATE TABLE `cs_categories` (
  `cat_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `module_id` tinyint(4) DEFAULT NULL,
  `sortorder` tinyint(4) DEFAULT '0',
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT 'New Category',
  `description` text COLLATE utf8_unicode_ci,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `cat_id` (`cat_id`),
  KEY `modul_id` (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_categories`
--

INSERT INTO `cs_categories` VALUES(1, 7, 1, '-keine-', 'Diese News sind keiner Kategorie zugeordnet', 'themes/core/images/nopreview.jpg', 'themes/core/images/nopreview.jpg', '#000000');
INSERT INTO `cs_categories` VALUES(2, 7, 2, 'Allgemein', 'Thema Allgemein', 'http://www.clansuite-dev.com/uploads/images/gallery/kunst.jpg', 'http://www.clansuite-dev.com/uploads/images/gallery/raetsel_1.jpg', '#000000');
INSERT INTO `cs_categories` VALUES(3, 7, 3, 'Member', 'Thema Members', 'themes/core/images/nopreview.jpg', 'themes/core/images/nopreview.jpg', '#3366CC');
INSERT INTO `cs_categories` VALUES(4, 7, 4, 'Page', 'Thema Page', 'themes/core/images/nopreview.jpg', 'themes/core/images/nopreview.jpg', '#000000');
INSERT INTO `cs_categories` VALUES(5, 7, 5, 'IRC', 'Thema IRC', 'themes/core/images/nopreview.jpg', 'themes/core/images/nopreview.jpg', '#000000');
INSERT INTO `cs_categories` VALUES(6, 7, 6, 'Clan-Wars', 'Thema Matches', 'themes/core/images/nopreview.jpg', 'themes/core/images/nopreview.jpg', '#000000');
INSERT INTO `cs_categories` VALUES(7, 7, 7, 'Sonstiges', 'Thema Hardware', '', '', '#000000');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_comments`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_comments`;
CREATE TABLE `cs_comments` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pseudo` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_comments`
--

INSERT INTO `cs_comments` VALUES(1, 1, '', '123', '2005-07-29 13:04:07', '', '127.0.0.1', 'localhost');
INSERT INTO `cs_comments` VALUES(2, 2, '', '1234567', '2005-07-29 16:50:08', 'blub', '127.0.0.1', 'localhost');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_downloads`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_downloads`;
CREATE TABLE `cs_downloads` (
  `download_id` int(20) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `filename` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `filepath` text COLLATE utf8_unicode_ci NOT NULL,
  `added_date` datetime NOT NULL,
  PRIMARY KEY (`download_id`),
  UNIQUE KEY `download_id` (`download_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_downloads`
--

INSERT INTO `cs_downloads` VALUES(1, 'test', 'testfile1', 'this is a testdescription for testfile1', '/uploads/downloads/testfile1.zip', '2009-06-20 00:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_forum_boards`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_forum_boards`;
CREATE TABLE `cs_forum_boards` (
  `board_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `child_level` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  `post_id_last` int(10) NOT NULL DEFAULT '0',
  `post_id_updated` int(10) NOT NULL DEFAULT '0',
  `groups` varchar(255) NOT NULL DEFAULT '-1,0',
  `profile_id` tinyint(5) unsigned NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `num_topics` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `num_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `count_posts` tinyint(4) NOT NULL DEFAULT '0',
  `unapproved_posts` tinyint(5) NOT NULL DEFAULT '0',
  `unapproved_topics` tinyint(5) NOT NULL DEFAULT '0',
  `redirect` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`board_id`),
  UNIQUE KEY `categories` (`cat_id`,`board_id`),
  KEY `parent_id` (`parent_id`),
  KEY `post_id_updated` (`post_id_updated`),
  KEY `groups` (`groups`(48))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cs_forum_boards`
--

INSERT INTO `cs_forum_boards` VALUES(1, 1, 0, 0, 0, 0, 0, '-1,0', 1, 'Ankündigungen | Announcements', 'Allgemeine Informationen und Entwicklertagebuch zu Clansuite. Read Only!', 0, 0, 0, 0, 0, '');
INSERT INTO `cs_forum_boards` VALUES(2, 1, 0, 0, 0, 0, 0, '-1,0', 1, 'Entwicklerecke', 'Bastelstube oder auch Clansuite Coder''''s Corner (CCC) genannt :)', 0, 0, 0, 0, 0, '');
INSERT INTO `cs_forum_boards` VALUES(3, 1, 0, 0, 0, 0, 0, '-1,0', 1, 'Anwenderforum | Usersforum', 'Fragen und Antworten zur Anwendung, Bedienung und Anpassung des Systems.', 0, 0, 0, 0, 0, '');
INSERT INTO `cs_forum_boards` VALUES(4, 1, 0, 0, 0, 0, 0, '-1,0', 1, 'Hilfe | Support & Troubleshooting', 'Allgemeines Support- und Problemlösungsforum.', 0, 0, 0, 0, 0, '');
INSERT INTO `cs_forum_boards` VALUES(5, 1, 0, 0, 0, 0, 0, '-1,0', 1, 'Wünsche | Feature Requests', 'Anregungen und Vorschläge für neue Funktionalitäten, Module oder Plugins gehören in dieses Forum.', 0, 0, 0, 0, 0, '');
INSERT INTO `cs_forum_boards` VALUES(6, 1, 0, 0, 0, 0, 0, '-1,0', 1, 'Fehler | Bugs & Issues', 'Für Diskussionen über Fehlermeldungen und Bug-Reports.', 0, 0, 0, 0, 0, '');
INSERT INTO `cs_forum_boards` VALUES(7, 1, 0, 0, 0, 0, 0, '-1,0', 1, 'Designs & Themes, Templates', 'Bereich für Design, Grafik, Icons und Logos sowie Template-Angelegenheiten.', 0, 0, 0, 0, 0, '');
INSERT INTO `cs_forum_boards` VALUES(8, 1, 0, 0, 0, 0, 0, '-1,0', 1, 'Show Room | Schaufenster', 'Stelle Deine mit Clansuite erstellte Website vor.', 0, 0, 0, 0, 0, '');
INSERT INTO `cs_forum_boards` VALUES(9, 1, 0, 0, 0, 0, 0, '-1,0', 1, 'Nachrichten', '', 0, 0, 0, 0, 0, '');
INSERT INTO `cs_forum_boards` VALUES(10, 1, 0, 0, 0, 0, 0, '-1,0', 1, 'Fun Forum', 'Spaß und gute Laune', 0, 0, 0, 0, 0, '');
INSERT INTO `cs_forum_boards` VALUES(11, 1, 0, 1, 0, 0, 0, '-1,0', 1, ' Termine und Events', 'Anstehende Termine', 0, 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_forum_boards_moderator`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_forum_boards_moderator`;
CREATE TABLE `cs_forum_boards_moderator` (
  `board_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`board_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cs_forum_boards_moderator`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_forum_category`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_forum_category`;
CREATE TABLE `cs_forum_category` (
  `cat_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` tinyint(4) unsigned DEFAULT NULL,
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cs_forum_category`
--

INSERT INTO `cs_forum_category` VALUES(1, NULL, 0, 'Clansuite', 'default kategorie');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_forum_config`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_forum_config`;
CREATE TABLE `cs_forum_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cs_forum_config`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_forum_groups`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_forum_groups`;
CREATE TABLE `cs_forum_groups` (
  `group_id` smallint(2) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(80) NOT NULL DEFAULT '',
  `description` tinytext NOT NULL,
  `online_color` varchar(20) NOT NULL DEFAULT '',
  `min_posts` mediumint(9) NOT NULL DEFAULT '-1',
  `max_messages` smallint(5) unsigned NOT NULL DEFAULT '0',
  `stars` varchar(255) NOT NULL DEFAULT '',
  `group_type` smallint(2) NOT NULL DEFAULT '0',
  `hidden` smallint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`),
  KEY `min_posts` (`min_posts`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cs_forum_groups`
--

INSERT INTO `cs_forum_groups` VALUES(1, 'Administrator', '', '#FF0000', -1, 0, '5#staradmin.gif', 1, 0);
INSERT INTO `cs_forum_groups` VALUES(2, 'Moderator', '', '', -1, 0, '5#starmod.gif', 0, 0);
INSERT INTO `cs_forum_groups` VALUES(3, 'Gold', '', '', -1, 0, '4#star.gif', 0, 0);
INSERT INTO `cs_forum_groups` VALUES(4, 'Silber', '', '', -1, 0, '3#star.gif', 0, 0);
INSERT INTO `cs_forum_groups` VALUES(5, 'Bronze', '', '', -1, 0, '2#star.gif', 0, 0);
INSERT INTO `cs_forum_groups` VALUES(6, 'Newbie', '', '', 0, 0, '1#star.gif', 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_forum_icons`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_forum_icons`;
CREATE TABLE `cs_forum_icons` (
  `icon_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL DEFAULT '',
  `filename` varchar(80) NOT NULL DEFAULT '',
  `board_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `icon_order` smallint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`icon_id`),
  KEY `board_id` (`board_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cs_forum_icons`
--

INSERT INTO `cs_forum_icons` VALUES(1, 'Standard', 'xx', 0, 0);
INSERT INTO `cs_forum_icons` VALUES(2, 'Thumb Up', 'thumbup', 0, 1);
INSERT INTO `cs_forum_icons` VALUES(3, 'Thumb Down', 'thumbdown', 0, 2);
INSERT INTO `cs_forum_icons` VALUES(4, 'Exclamation point', 'exclamation', 0, 3);
INSERT INTO `cs_forum_icons` VALUES(5, 'Question mark', 'question', 0, 4);
INSERT INTO `cs_forum_icons` VALUES(6, 'Lamp', 'lamp', 0, 5);
INSERT INTO `cs_forum_icons` VALUES(7, 'Smiley', 'smiley', 0, 6);
INSERT INTO `cs_forum_icons` VALUES(8, 'Angry', 'angry', 0, 7);
INSERT INTO `cs_forum_icons` VALUES(9, 'Cheesy', 'cheesy', 0, 8);
INSERT INTO `cs_forum_icons` VALUES(10, 'Grin', 'grin', 0, 9);
INSERT INTO `cs_forum_icons` VALUES(11, 'Sad', 'sad', 0, 10);
INSERT INTO `cs_forum_icons` VALUES(12, 'Wink', 'wink', 0, 11);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_forum_permission`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_forum_permission`;
CREATE TABLE `cs_forum_permission` (
  `group_id` smallint(2) NOT NULL DEFAULT '0',
  `profile_id` smallint(2) unsigned NOT NULL DEFAULT '0',
  `permission` varchar(30) NOT NULL DEFAULT '',
  `deny` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`group_id`,`profile_id`,`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cs_forum_permission`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_forum_permission_profile`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_forum_permission_profile`;
CREATE TABLE `cs_forum_permission_profile` (
  `profile_id` smallint(2) NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`profile_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cs_forum_permission_profile`
--

INSERT INTO `cs_forum_permission_profile` VALUES(1, 'default');
INSERT INTO `cs_forum_permission_profile` VALUES(2, 'no_polls');
INSERT INTO `cs_forum_permission_profile` VALUES(3, 'reply_only');
INSERT INTO `cs_forum_permission_profile` VALUES(4, 'read_only');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_forum_posts`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_forum_posts`;
CREATE TABLE `cs_forum_posts` (
  `post_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) unsigned NOT NULL DEFAULT '0',
  `board_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `poster_time` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `msg_modified_id` int(10) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `poster_name` varchar(255) NOT NULL DEFAULT '',
  `poster_email` varchar(255) NOT NULL DEFAULT '',
  `poster_ip` varchar(255) NOT NULL DEFAULT '',
  `smileys_enabled` smallint(1) NOT NULL DEFAULT '1',
  `modified_time` int(10) unsigned NOT NULL DEFAULT '0',
  `modified_name` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `icon` varchar(20) NOT NULL DEFAULT 'xx',
  `approved` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`post_id`),
  UNIQUE KEY `topic` (`topic_id`,`post_id`),
  UNIQUE KEY `board_id` (`board_id`,`post_id`),
  UNIQUE KEY `user_id` (`user_id`,`post_id`),
  KEY `approved` (`approved`),
  KEY `ip_index` (`poster_ip`(15),`topic_id`),
  KEY `participation` (`user_id`,`topic_id`),
  KEY `show_posts` (`user_id`,`board_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_post_id` (`user_id`,`approved`,`post_id`),
  KEY `current_topic` (`topic_id`,`post_id`,`user_id`,`approved`),
  KEY `related_ip` (`user_id`,`poster_ip`,`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cs_forum_posts`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_forum_profiles`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_forum_profiles`;
CREATE TABLE `cs_forum_profiles` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `group_id` smallint(2) unsigned NOT NULL DEFAULT '0',
  `mod_prefs` varchar(20) NOT NULL DEFAULT '',
  `personal_text` varchar(255) NOT NULL DEFAULT '',
  `hide_email` smallint(1) NOT NULL DEFAULT '0',
  `show_online` smallint(1) NOT NULL DEFAULT '1',
  `signature` tinytext NOT NULL,
  `time_offset` float NOT NULL DEFAULT '0',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `usertitle` varchar(255) NOT NULL DEFAULT '',
  `notify_announcements` smallint(1) NOT NULL DEFAULT '1',
  `notify_regularity` smallint(1) NOT NULL DEFAULT '1',
  `notify_send_body` smallint(1) NOT NULL DEFAULT '0',
  `notify_types` tinyint(4) NOT NULL DEFAULT '2',
  `is_activated` smallint(1) unsigned NOT NULL DEFAULT '1',
  `validation_code` varchar(10) NOT NULL DEFAULT '',
  `post_id_last_visit` int(10) unsigned NOT NULL DEFAULT '0',
  `additional_groups` varchar(255) NOT NULL DEFAULT '',
  `smiley_set` varchar(48) NOT NULL DEFAULT '',
  `group_post_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ignore_boards` text NOT NULL,
  `warning` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `group_id` (`group_id`),
  KEY `posts` (`posts`),
  KEY `warning` (`warning`),
  KEY `group_post_id` (`group_post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cs_forum_profiles`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_forum_topics`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_forum_topics`;
CREATE TABLE `cs_forum_topics` (
  `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_sticky` smallint(1) NOT NULL DEFAULT '0',
  `board_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `first_post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `last_post_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id_started` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id_updated` int(11) unsigned NOT NULL DEFAULT '0',
  `poll_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `previous_board_id` mediumint(8) NOT NULL DEFAULT '0',
  `previous_topic_id` int(11) NOT NULL DEFAULT '0',
  `num_replies` int(10) unsigned NOT NULL DEFAULT '0',
  `num_views` int(10) unsigned NOT NULL DEFAULT '0',
  `locked` smallint(1) NOT NULL DEFAULT '0',
  `unapproved_posts` smallint(5) NOT NULL DEFAULT '0',
  `approved` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`topic_id`),
  UNIQUE KEY `poll` (`poll_id`,`topic_id`),
  UNIQUE KEY `last_post` (`last_post_id`,`board_id`),
  UNIQUE KEY `first_post` (`first_post_id`,`board_id`),
  KEY `is_sticky` (`is_sticky`),
  KEY `approved` (`approved`),
  KEY `board_id` (`board_id`),
  KEY `board_news` (`board_id`,`first_post_id`),
  KEY `last_post_sticky` (`board_id`,`is_sticky`,`last_post_id`),
  KEY `user_started` (`user_id_started`,`board_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cs_forum_topics`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_groups`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_groups`;
CREATE TABLE `cs_groups` (
  `group_id` tinyint(5) unsigned NOT NULL AUTO_INCREMENT,
  `sortorder` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` tinyint(4) unsigned NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_groups`
--

INSERT INTO `cs_groups` VALUES(1, 1, 'Guest', 'The non-registered users.', '', '', '#000000', 3);
INSERT INTO `cs_groups` VALUES(2, 2, 'Normal users', 'The users are forced into this group after registration.', '', '', '#006600', 4);
INSERT INTO `cs_groups` VALUES(3, 3, 'Administrators', 'The website administrator with access to the control center (cc)', '', '', '#FF0000', 5);
INSERT INTO `cs_groups` VALUES(4, 4, 'Bot', 'Searchengine Bots', '', '', '#9900CC', 2);
INSERT INTO `cs_groups` VALUES(5, 5, 'root', 'The website superadministrator with all access', '', '', '#FF0000', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_guestbook`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_guestbook`;
CREATE TABLE `cs_guestbook` (
  `gb_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `gb_added` int(12) DEFAULT NULL,
  `gb_nick` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gb_email` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gb_icq` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gb_website` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gb_town` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gb_text` text COLLATE utf8_unicode_ci,
  `gb_ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gb_comment` text COLLATE utf8_unicode_ci,
  `image_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gb_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_guestbook`
--

INSERT INTO `cs_guestbook` VALUES(1, 0, 1003200322, 'nick', 'jakoch@web.de', '123124', 'www.skdjf.de', 'sdfsfas', 'sadfasdfasdfasdfasdf', '1231231231', 'mir nur ganz alleine :) [b]fu[/b] asdfasdf asdfffffffffffffffffasd asdfsadfsafsafsadf asdfsadfsfsafsafddddddddddddddddddddddddddddd  asdfsfsdfsdfsdfddddddddddddddddddddddddd dddddasdfsdfsdfsdfsaf assadfsaf ', 3);
INSERT INTO `cs_guestbook` VALUES(2, 0, 1175392043, 'nick1', 'vain@clansuite.com', '123', '', '123', '123', '0', '', 0);
INSERT INTO `cs_guestbook` VALUES(3, 0, 1175919684, 'nester tester', 'vain@clansuite.com', '1234567', 'http://www.test.de', 'blablubb', 'asafsdfd [b]test[/b]', '127.0.0.1', NULL, 0);
INSERT INTO `cs_guestbook` VALUES(4, 0, 1175924624, 'nickname', 'email@email.de', '32452345', 'faffa', 'fafafa', 'faafaffaaf', '127.0.0.1', NULL, 3);
INSERT INTO `cs_guestbook` VALUES(5, 0, 1175928797, 'name', 'email@email.de', 'sdfasdf', 'sdfasdf', '1234234', '21efsdfasdfasdf', '127.0.0.1', NULL, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_help`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_help`;
CREATE TABLE `cs_help` (
  `help_id` int(11) NOT NULL AUTO_INCREMENT,
  `mod` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sub` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `helptext` text COLLATE utf8_unicode_ci NOT NULL,
  `related_links` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`help_id`),
  UNIQUE KEY `help_id` (`help_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_help`
--

INSERT INTO `cs_help` VALUES(1, 'admin', '', 'show', '[b]BOLD: admin show helptext[/b] [i]ITALICS: Italiener sind Spagettifresser![/i]\n[s]STRANGETEST: not defined bbcode[/s]\n\n[code=php]\n<?php\necho ''test'';\n?>\n[/code]', 'www123');
INSERT INTO `cs_help` VALUES(2, 'admin', 'modules', 'export', 'test', '');
INSERT INTO `cs_help` VALUES(3, 'admin', 'bbcode', 'show', '[b]asdfsadf[/b]\n\n[i]help[/i]', '');
INSERT INTO `cs_help` VALUES(7, 'admin', 'users', 'show', '[s]not defined[/s]', '[url]http://www.clansuite.com/users[/url]');
INSERT INTO `cs_help` VALUES(9, 'admin', 'groups', 'show', '[b]wow[/b]', '');
INSERT INTO `cs_help` VALUES(10, 'admin', 'settings', 'show', '[b]ficken[/b]\n\n\nasdf', '[url]www.google.de[/url]\n[url]www.clansuite.com[/url]');
INSERT INTO `cs_help` VALUES(11, 'admin', 'modules', 'install_new', '[b]fuuuuuu[/b]\n\n\n[url]http:/hhhhasdfsadfas\n', 'asdfsdf');
INSERT INTO `cs_help` VALUES(12, 'serverlist', 'admin', 'show', '', '[url]http://www.google.de[/url]');
INSERT INTO `cs_help` VALUES(13, 'admin', 'modules', 'show_all', 'sadfsaf', '');
INSERT INTO `cs_help` VALUES(14, 'guestbook', 'admin', 'show', '', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_images`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_images`;
CREATE TABLE `cs_images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_images`
--

INSERT INTO `cs_images` VALUES(3, 1, 'upload', 'images/avatars/1.jpg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_messages`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_messages`;
CREATE TABLE `cs_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `headline` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `read` int(1) NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `from` (`from`,`to`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_messages`
--

INSERT INTO `cs_messages` VALUES(10, 1, 1, 'uschi', 'furuzzz', 1171204602, 1);
INSERT INTO `cs_messages` VALUES(11, 1, 1, 'uschi', 'furuzzz', 1171204602, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_modules`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_modules`;
CREATE TABLE `cs_modules` (
  `module_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` tinyint(4) NOT NULL DEFAULT '0',
  `section_id` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `enabled` smallint(1) NOT NULL,
  `core` smallint(1) NOT NULL DEFAULT '0',
  `module_version` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `clansuite_version` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `license` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `homepage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `copyright` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `name` (`name`),
  KEY `section_id` (`section_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_modules`
--

INSERT INTO `cs_modules` VALUES(1, 0, 0, 'about', 'About', '', 1, 0, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_about.jpg');
INSERT INTO `cs_modules` VALUES(2, 0, 0, 'account', 'Account', '', 1, 1, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', '', 'http://www.clansuite.com', '', 'module_account.jpg');
INSERT INTO `cs_modules` VALUES(3, 0, 0, 'acl', 'Acl', '', 1, 0, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Paul Brand', 'http://www.clansuite.com', 'Paul Brand', 'module_acl.jpg');
INSERT INTO `cs_modules` VALUES(4, 0, 0, 'categories', 'Categories', '', 1, 1, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_categories.jpg');
INSERT INTO `cs_modules` VALUES(5, 0, 0, 'controlcenter', 'Controlcenter', '', 1, 1, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_controlcenter.jpg');
INSERT INTO `cs_modules` VALUES(6, 0, 0, 'cronjobs', 'Cronjobs', '', 1, 0, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Daniel Winterfeldt, Jens-André Koch', 'http://www.clansuite.com', 'Daniel Winterfeldt, Jens-André Koch', 'module_cronjobs.jpg');
INSERT INTO `cs_modules` VALUES(7, 0, 0, 'doctrine', 'Doctrine', '', 1, 1, '0.2-0.x-dev', '0.2', 'GPLv2', 'Daniel Winterfeldt, Jens-André Koch', '', 'Daniel Winterfeldt, Jens-André Koch', 'module_doctrine.jpg');
INSERT INTO `cs_modules` VALUES(8, 0, 0, 'forum', 'Forum', '', 1, 0, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_forum.jpg');
INSERT INTO `cs_modules` VALUES(9, 0, 0, 'guestbook', 'Guestbook', '', 1, 0, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_guestbook.jpg');
INSERT INTO `cs_modules` VALUES(10, 0, 0, 'index', 'Index', '', 1, 0, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_index.jpg');
INSERT INTO `cs_modules` VALUES(11, 0, 0, 'languages', 'Languages', '', 1, 0, '0.2', '0.2', 'GPLv2', 'Jens-André Koch', '', 'Jens-André Koch', 'module_languages.jpg');
INSERT INTO `cs_modules` VALUES(12, 0, 0, 'menu', 'Menu', '', 1, 1, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_menu.jpg');
INSERT INTO `cs_modules` VALUES(13, 0, 0, 'mibbitirc', 'Mibbitirc', '', 1, 0, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_mibbitirc.jpg');
INSERT INTO `cs_modules` VALUES(14, 0, 0, 'modulemanager', 'Modulemanager', '', 1, 1, '0.2-0.x-dev', '0.2', 'GPL v2 or any later', 'Florian Wolf, Jens-André Koch', 'http://www.clansuite.com', 'Florian Wolf, Jens-André Koch', 'module_modulemanager.jpg');
INSERT INTO `cs_modules` VALUES(15, 0, 0, 'news', 'News', '', 1, 0, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_news.jpg');
INSERT INTO `cs_modules` VALUES(16, 0, 0, 'rssreader', 'Rssreader', '', 1, 0, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_rssreader.jpg');
INSERT INTO `cs_modules` VALUES(17, 0, 0, 'settings', 'Settings', '', 1, 1, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_settings.jpg');
INSERT INTO `cs_modules` VALUES(18, 0, 0, 'staticpages', 'Staticpages', '', 1, 0, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_staticpages.jpg');
INSERT INTO `cs_modules` VALUES(19, 0, 0, 'statistics', 'Statistics', '', 1, 0, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_statistics.jpg');
INSERT INTO `cs_modules` VALUES(20, 0, 0, 'systeminfo', 'Systeminfo', '', 1, 1, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_systeminfo.jpg');
INSERT INTO `cs_modules` VALUES(21, 0, 0, 'teamspeakviewer', 'Teamspeakviewer', '', 1, 0, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_teamspeakviewer.jpg');
INSERT INTO `cs_modules` VALUES(22, 0, 0, 'templatemanager', 'Templatemanager', '', 1, 1, '0.2-0.x-dev', '0.2', 'GPLv2', 'Jens-André Koch', '', 'Jens-André Koch', 'module_templatemanager.jpg');
INSERT INTO `cs_modules` VALUES(23, 0, 0, 'thememanager', 'Thememanager', '', 1, 1, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_thememanager.jpg');
INSERT INTO `cs_modules` VALUES(24, 0, 0, 'toolbox', 'Toolbox', '', 1, 0, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_toolbox.jpg');
INSERT INTO `cs_modules` VALUES(25, 0, 0, 'users', 'Users', '', 1, 1, '0.2-0.x-dev', '0.2', 'GPLv2 or any later', 'Jens-André Koch', 'http://www.clansuite.com', 'Jens-André Koch', 'module_users.jpg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_modules_section`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_modules_section`;
CREATE TABLE `cs_modules_section` (
  `section_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`section_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_modules_section`
--

INSERT INTO `cs_modules_section` VALUES(1, 'Users & Groups', 'User & Group Management');
INSERT INTO `cs_modules_section` VALUES(2, 'System', 'System Module');
INSERT INTO `cs_modules_section` VALUES(3, 'Plugins', '');
INSERT INTO `cs_modules_section` VALUES(4, 'Content', 'Content Modules');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_news`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_news`;
CREATE TABLE `cs_news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `news_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `news_body` text COLLATE utf8_unicode_ci NOT NULL,
  `cat_id` tinyint(4) NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `news_status` int(11) NOT NULL,
  PRIMARY KEY (`news_id`,`cat_id`,`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_news`
--

INSERT INTO `cs_news` VALUES(21, 'Testeintrag 1 - Lore Ipsum ', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sed vestibulum nulla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ut felis scelerisque nibh rutrum dapibus eu et felis. Pellentesque eget nulla risus, at blandit lectus. Praesent venenatis tortor non neque molestie quis congue neque ullamcorper. Quisque at tellus sapien, molestie tempor lacus. Proin non lacus id justo dapibus feugiat ut sed nisi. Cras in purus tincidunt orci tincidunt dignissim sagittis non justo. Integer felis urna, sodales nec pharetra ac, tristique sit amet felis. Nam eget augue felis, sed sodales mauris. Aliquam molestie odio nec eros elementum quis lobortis felis fringilla. In semper sem id tellus pharetra id congue erat aliquet.\r\n', 1, 2, '2009-10-01 23:00:24', '2010-05-13 19:01:49', 4);
INSERT INTO `cs_news` VALUES(22, 'Testeintrag 2 - Lore Ipsum ', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sed vestibulum nulla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ut felis scelerisque nibh rutrum dapibus eu et felis. Pellentesque eget nulla risus, at blandit lectus. Praesent venenatis tortor non neque molestie quis congue neque ullamcorper. Quisque at tellus sapien, molestie tempor lacus. Proin non lacus id justo dapibus feugiat ut sed nisi. Cras in purus tincidunt orci tincidunt dignissim sagittis non justo. Integer felis urna, sodales nec pharetra ac, tristique sit amet felis. Nam eget augue felis, sed sodales mauris. Aliquam molestie odio nec eros elementum quis lobortis felis fringilla. In semper sem id tellus pharetra id congue erat aliquet.\r\n', 1, 2, '2009-10-01 23:00:24', '2010-05-13 19:02:02', 4);
INSERT INTO `cs_news` VALUES(23, 'Testeintrag 3 - Lore Ipsum ', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque imperdiet tristique justo ac pretium. Fusce odio nisl, dictum sed vestibulum in, tristique malesuada lacus. Suspendisse potenti. Proin bibendum placerat neque ut placerat. Nunc felis ligula, ullamcorper sed congue ut, pulvinar ut sapien. Nullam sem purus, adipiscing ut consectetur at, ornare eget magna. Vivamus ut eros vitae neque aliquam vulputate et molestie arcu. Etiam et metus id risus pulvinar pretium? Sed vulputate venenatis consectetur. Mauris id odio risus. Maecenas lacinia iaculis nisi vel egestas. Suspendisse potenti. Quisque vel dui in lectus ultrices vehicula. Donec convallis tortor et leo vehicula non placerat mi varius. Morbi ac pharetra lorem. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce cursus pulvinar nisl. Nunc a nisi vel purus sagittis bibendum!\r\n', 2, 2, '2009-10-01 23:00:24', '2010-05-13 19:02:04', 4);
INSERT INTO `cs_news` VALUES(24, 'Testeintrag 4 - Lore Ipsum ', '<span style="font-weight: bold;">Lorem ipsum</span> <span style="font-style: italic;">dolor sit amet</span>, <span style="text-decoration: underline;">consectetur adipiscing elit</span>. <span style="font-family: georgia;"></span><h2><br></h2><span style="font-family: georgia;">Pellentesque imperdiet tristique justo ac pretium.</span> <font size="5">Fusce odio nisl, dictum sed vestibulum in, tristique malesuada lacus.</font> Suspendisse potenti. Proin bibendum placerat neque ut placerat. Nunc felis ligula, ullamcorper sed congue ut, pulvinar ut sapien. Nullam sem purus, adipiscing ut consectetur at, ornare eget magna. Vivamus ut eros vitae neque aliquam vulputate et molestie arcu. Etiam et metus id risus pulvinar pretium? Sed vulputate venenatis consectetur. Mauris id odio risus. Maecenas lacinia iaculis nisi vel egestas. Suspendisse potenti. Quisque vel dui in lectus ultrices vehicula. Donec convallis tortor et leo vehicula non placerat mi varius. Morbi ac pharetra lorem. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce cursus pulvinar nisl. Nunc a nisi vel purus sagittis bibendum!<br>', 2, 2, '2009-10-01 23:00:24', '2010-05-13 19:06:47', 4);
INSERT INTO `cs_news` VALUES(71, 'Willkommen auf Ihrer neuen Clansuite-Website!', 'Bitte folgen Sie diesen Schritten um mit Ihrer Website zu beginnen:\r\n\r\n   1. Konfigurieren Sie Ihre Website Wenn Sie angemeldet sind, besuchen Sie den Administrationsbereich, in dem Sie alle Aspekte Ihrer Website anpassen und konfigurieren kÃƒÂ¶nnen.\r\n   2. Aktivieren Sie zusÃƒÂ¤tzliche Funktionen Als nÃƒÂ¤chstes kÃƒÂ¶nnen Sie in der ModulÃƒÂ¼bersicht die Funktionen aktivieren, die Ihren speziellen Anforderungen entsprechen. Sie finden zusÃƒÂ¤tzliche Module im Downloadbereich fÃƒÂ¼r Clansuite-Module.\r\n   3. Passen Sie das Design Ihrer Website an Um das Aussehen Ihrer Website zu verÃƒÂ¤ndern, besuchen Sie den Theme-Bereich. Sie kÃƒÂ¶nnen aus den mitgelieferten Themes auswÃƒÂ¤hlen oder zusÃƒÂ¤tzliche Themes aus dem Downloadbereich fÃƒÂ¼r Clansuite-Themes herunterladen.\r\n   4. Beginnen Sie, Inhalte zu schreiben SchlieÃƒÅ¸lich kÃƒÂ¶nnen Sie fÃƒÂ¼r Ihre Website Inhalte erstellen. Dieser Informationstext verschwindet, sobald der erste Beitrag erscheint.\r\n\r\nNÃƒÂ¤here Informationen finden sich im Hilfe-Bereich oder online in den Clansuite-HandbÃƒÂ¼chern. Sie kÃƒÂ¶nnen auch einen Beitrag im Clansuite-Forum schreiben oder aus den vielfÃƒÂ¤ltigen anderen verfÃƒÂ¼gbaren Support-Angeboten auswÃƒÂ¤hlen.', 2, 0, '2009-12-02 23:00:24', '2009-12-09 12:17:19', 0);
INSERT INTO `cs_news` VALUES(98, 'cyrillic (manual sql insert)', 'ёЁйЙцЦуУкКеЕнНгГшШщЩзЗхХъЪфФыЫвВаАпПрРоОлЛдДжЖэЭяЯчЧсСмМиИтТьЬбБюЮ', 0, 0, NULL, '2010-05-13 21:38:25', 0);
INSERT INTO `cs_news` VALUES(99, 'cyrillic (by backend) ёЁйЙцЦ', '<font size="3"><span style="font-weight: bold;">ёЁйЙц</span><span style="font-style: italic;">ЦуУкКеЕ</span><span style="text-decoration: underline;">нНгГшШ</span>щЩзЗхХъЪфФыЫвВаАпПрРоОлЛдДжЖэЭяЯчЧсСмМиИтТьЬбБюЮ<br><br>What is this?<br>a) The text is cyrillic.<br>b) It''s formatted with a wysiwyg editor. So there are html span tags applied to the text.<br></font>', 1, 0, '2010-05-13 21:39:37', '2010-05-13 21:47:27', 0);
INSERT INTO `cs_news` VALUES(100, 'german specialchars', 'ä 	ä\r\nÄ 	Ä\r\nö 	ö\r\nÖ 	Ö\r\nü 	ü\r\nÜ 	Ü\r\nß 	ß<br><br><h5><span style="font-weight: bold;">ä 	ä\r\n<span style="font-style: italic;">Ä 	Ä</span>\r\nö 	ö\r\n<span style="text-decoration: underline;">Ö 	Ö</span>\r\n</span>ü 	ü\r\nÜ 	Ü\r\nß 	ß</h5>', 1, 0, '2010-05-13 21:51:14', '2010-05-13 21:53:04', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_news_comments`
--

CREATE TABLE `cs_news_comments` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` int(10) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pseudo` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_news_comments`
--

INSERT INTO `cs_news_comments` VALUES(1, 21, 1, '', '123', '2005-07-29 13:04:07', '', '127.0.0.1', 'localhost');
INSERT INTO `cs_news_comments` VALUES(2, 22, 2, '', '1234567', '2005-07-29 16:50:08', 'blub', '127.0.0.1', 'localhost');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_news_index`
--
-- Erzeugt am: 20. November 2010 um 12:33
--

DROP TABLE IF EXISTS `cs_news_index`;
CREATE TABLE `cs_news_index` (
  `user_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `news_title` text COLLATE utf8_unicode_ci NOT NULL,
  `news_body` text COLLATE utf8_unicode_ci NOT NULL,
  `keyword` text COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL,
  `field` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_news_index`
--

INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'aliquam', 103, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'neque', 102, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'vitae', 101, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'eros', 100, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ut', 99, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'vivamus', 98, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'magna', 97, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'eget', 96, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ornare', 95, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'consectetur', 93, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ut', 92, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'adipiscing', 91, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'purus', 90, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'sem', 89, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'nullam', 88, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'sapien', 87, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ut', 86, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'pulvinar', 85, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ut', 84, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'congue', 83, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'sed', 82, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ullamcorper', 81, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ligula', 80, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'felis', 79, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'nunc', 78, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'placerat', 77, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ut', 76, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'neque', 75, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'placerat', 74, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'bibendum', 73, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'proin', 72, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'potenti', 71, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'suspendisse', 70, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'font', 69, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'lacus', 67, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'malesuada', 66, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'tristique', 65, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'vestibulum', 63, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'sed', 62, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'dictum', 61, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'nisl', 60, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'odio', 59, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'fusce', 58, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', '5', 57, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'font', 55, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'span', 53, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'pretium', 51, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ac', 50, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'justo', 49, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'tristique', 48, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'imperdiet', 47, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'pellentesque', 46, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'georgia', 45, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'family', 44, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'font', 43, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'style', 42, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'span', 41, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'h2', 40, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'br', 38, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'h2', 37, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'span', 36, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'georgia', 34, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'family', 33, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'font', 32, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'style', 31, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'span', 30, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'span', 28, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'elit', 27, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'adipiscing', 26, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'consectetur', 25, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'underline', 24, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'decoration', 23, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'text', 22, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'style', 21, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'span', 20, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'span', 18, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'amet', 17, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'sit', 16, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'dolor', 15, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'italic', 14, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'style', 13, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'font', 12, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'style', 11, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'span', 10, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'span', 8, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ipsum', 7, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'lorem', 6, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'bold', 5, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'weight', 4, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'font', 3, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'style', 2, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'span', 1, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ipsum', 4, 'news_title');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'lore', 3, 'news_title');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', '4', 1, 'news_title');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'testeintrag', 0, 'news_title');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'br', 175, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'bibendum', 174, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'sagittis', 173, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'purus', 172, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'vel', 171, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'nisi', 170, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'nunc', 168, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'nisl', 167, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'pulvinar', 166, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'cursus', 165, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'fusce', 164, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'mus', 163, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ridiculus', 162, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'nascetur', 161, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'montes', 160, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'parturient', 159, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'dis', 158, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'magnis', 157, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'et', 156, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'penatibus', 155, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'natoque', 154, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'sociis', 153, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'cum', 152, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'lorem', 151, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'pharetra', 150, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ac', 149, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'morbi', 148, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'varius', 147, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'mi', 146, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'placerat', 145, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'non', 144, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'vehicula', 143, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'leo', 142, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'et', 141, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'tortor', 140, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'convallis', 139, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'donec', 138, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'vehicula', 137, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'ultrices', 136, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'lectus', 135, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'vel', 132, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'dui', 133, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'quisque', 131, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'potenti', 130, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'suspendisse', 129, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'egestas', 128, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'vel', 127, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'nisi', 126, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'iaculis', 125, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'lacinia', 124, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'maecenas', 123, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'risus', 122, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'odio', 121, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'id', 120, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'mauris', 119, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'consectetur', 118, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'venenatis', 117, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'vulputate', 116, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'sed', 115, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'pretium', 114, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'pulvinar', 113, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'risus', 112, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'id', 111, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'metus', 110, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'et', 109, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'etiam', 108, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'arcu', 107, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'molestie', 106, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'et', 105, 'news_body');
INSERT INTO `cs_news_index` VALUES(2, 2, 24, '', '', 'vulputate', 104, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'auswahlen', 164, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'angeboten', 163, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'support', 162, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'verfugbaren', 161, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'anderen', 160, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'vielfaltigen', 159, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'den', 158, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'aus', 157, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'oder', 156, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'schreiben', 155, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'forum', 154, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'clansuite', 153, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'im', 152, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'beitrag', 151, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'einen', 150, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'auch', 149, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'konnen', 148, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 147, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'handbuchern', 146, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'clansuite', 145, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'den', 144, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'online', 142, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'oder', 141, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'bereich', 140, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'hilfe', 139, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'im', 138, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sich', 137, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'finden', 136, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'informationen', 135, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'nahere', 134, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'erscheint', 131, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'beitrag', 130, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'erste', 129, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'der', 128, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sobald', 127, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'verschwindet', 126, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'informationstext', 125, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'dieser', 124, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'erstellen', 123, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'inhalte', 122, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'website', 121, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'ihre', 120, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'fur', 119, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 118, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'konnen', 117, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'schliesslich', 116, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'schreiben', 115, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'zu', 114, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'inhalte', 113, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 112, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'beginnen', 111, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', '4', 110, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'herunterladen', 107, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'themes', 106, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'clansuite', 105, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'fur', 104, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'downloadbereich', 103, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'dem', 102, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'aus', 101, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'themes', 100, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'zusatzliche', 99, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'oder', 98, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'auswahlen', 97, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'themes', 96, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'mitgelieferten', 95, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'den', 94, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'aus', 93, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'konnen', 92, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 91, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'bereich', 90, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'theme', 89, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'den', 88, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 87, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'besuchen', 86, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'verandern', 85, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'zu', 84, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'website', 83, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'ihrer', 82, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'aussehen', 81, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'das', 80, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'um', 79, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'website', 77, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'ihrer', 76, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'design', 75, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'das', 74, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 73, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'passen', 72, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', '3', 71, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'module', 68, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'clansuite', 67, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'fur', 66, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'downloadbereich', 65, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'im', 64, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'module', 63, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'zusatzliche', 62, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'finden', 61, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 60, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'entsprechen', 59, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'anforderungen', 58, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'speziellen', 57, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'ihren', 56, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'die', 55, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'aktivieren', 54, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'funktionen', 53, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'die', 52, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'modulubersicht', 51, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'der', 50, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 48, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'konnen', 47, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'nachstes', 46, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'als', 45, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'funktionen', 44, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'zusatzliche', 43, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 42, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'aktivieren', 41, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', '2', 40, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'konnen', 37, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'konfigurieren', 36, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'und', 35, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'anpassen', 34, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'website', 33, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'ihrer', 32, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'aspekte', 31, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'alle', 30, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 29, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'dem', 28, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'administrationsbereich', 26, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'den', 25, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 24, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'besuchen', 23, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sind', 22, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'angemeldet', 21, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 20, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'wenn', 19, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'website', 18, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'ihre', 17, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 16, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'konfigurieren', 15, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', '1', 14, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'beginnen', 10, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'zu', 9, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'website', 8, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'ihrer', 7, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'mit', 6, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'um', 5, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'schritten', 4, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'diesen', 3, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'sie', 2, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'folgen', 1, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'bitte', 0, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'color', 8, 'news_title');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'live', 5, 'news_title');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'tests', 3, 'news_title');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'mapping', 2, 'news_title');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'status', 1, 'news_title');
INSERT INTO `cs_news_index` VALUES(0, 2, 71, '', '', 'perfect', 0, 'news_title');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dhdh', 59, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 58, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dhzdh', 57, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 56, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dhynedh', 55, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 54, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 53, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 52, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 51, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 50, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 49, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'n', 48, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 47, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'n', 46, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dhynsdh', 45, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'n', 44, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 43, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 42, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 41, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'n', 40, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'span', 39, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 37, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'n', 36, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 35, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 34, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 33, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 32, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'underline', 31, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'decoration', 30, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'text', 29, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'style', 28, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'span', 27, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'span', 26, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dhsdhudh', 24, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 23, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'nfdh', 22, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 21, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'italic', 20, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'style', 19, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'font', 18, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'style', 17, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'span', 16, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'span', 15, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'n', 13, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 12, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 11, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 10, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'n', 9, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'bold', 8, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'weight', 7, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'font', 6, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'style', 5, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'span', 4, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', '3', 3, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'font', 1, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'backend', 2, 'news_title');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'cyrillic', 0, 'news_title');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 60, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 61, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'n', 62, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 63, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'n', 64, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 65, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'n', 66, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 67, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'n', 68, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 69, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 70, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dhoedh', 71, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 72, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'n', 73, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dhcnoedh', 74, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 75, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'dh', 76, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'nzdh', 77, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'br', 78, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'br', 79, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'br', 83, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'text', 86, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'cyrillic', 88, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'br', 89, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'b', 90, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'its', 91, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'formatted', 92, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'wysiwyg', 95, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'editor', 96, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'html', 100, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'span', 101, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'tags', 102, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'applied', 103, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'text', 106, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'br', 107, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 99, '', '', 'font', 109, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'german', 0, 'news_title');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'specialchars', 1, 'news_title');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ae', 0, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ae', 1, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ae', 2, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ae', 3, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'oe', 4, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'oe', 5, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'oe', 6, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'oe', 7, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ue', 8, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ue', 9, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ue', 10, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ue', 11, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ss', 12, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ss', 13, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'br', 14, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'br', 15, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'h5', 16, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'span', 17, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'style', 18, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'font', 19, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'weight', 20, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'bold', 21, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ae', 22, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ae', 23, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'span', 25, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'style', 26, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'font', 27, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'style', 28, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'italic', 29, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ae', 30, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ae', 31, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'span', 32, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'oe', 34, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'oe', 35, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'span', 37, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'style', 38, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'text', 39, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'decoration', 40, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'underline', 41, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'oe', 42, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'oe', 43, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'span', 44, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'span', 47, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ue', 48, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ue', 49, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ue', 50, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ue', 51, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ss', 52, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'ss', 53, 'news_body');
INSERT INTO `cs_news_index` VALUES(0, 1, 100, '', '', 'h5', 54, 'news_body');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_opponents`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_opponents`;
CREATE TABLE `cs_opponents` (
  `opponent_id` int(3) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `clantag` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `websiteurl` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `ircchannel` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `image_id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_opponents`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_options`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_options`;
CREATE TABLE `cs_options` (
  `option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_id` int(10) unsigned NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`option_id`,`name_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_options`
--

INSERT INTO `cs_options` VALUES(1, 1, 'drahtgitter');
INSERT INTO `cs_options` VALUES(2, 2, 'en');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_profiles`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_profiles`;
CREATE TABLE `cs_profiles` (
  `profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` int(11) NOT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `height` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `zipcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `homepage` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `icq` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `msn` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `skype` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `custom_text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`profile_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_profiles`
--

INSERT INTO `cs_profiles` VALUES(1, 1, 1175369474, 'Florian', 'Wolf', 496274400, 'male', 178, 'MÃ¼hlenstr. 65', '78126', 'Jena', 'DE', 'http://www.clansuite.com', '163164530', '-', '-', '-', '-', '[b]bla[/b]');
INSERT INTO `cs_profiles` VALUES(2, 3, 1172510321, '', '', 0, '-', 0, '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_profiles_computer`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_profiles_computer`;
CREATE TABLE `cs_profiles_computer` (
  `computer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cpu` text COLLATE utf8_unicode_ci NOT NULL,
  `ram` text COLLATE utf8_unicode_ci NOT NULL,
  `gpu` text COLLATE utf8_unicode_ci NOT NULL,
  `sound` text COLLATE utf8_unicode_ci NOT NULL,
  `hdd` text COLLATE utf8_unicode_ci NOT NULL,
  `cdrom` text COLLATE utf8_unicode_ci NOT NULL,
  `monitor` text COLLATE utf8_unicode_ci NOT NULL,
  `devices` text COLLATE utf8_unicode_ci NOT NULL,
  `network` text COLLATE utf8_unicode_ci NOT NULL,
  `other` text COLLATE utf8_unicode_ci NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`computer_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_profiles_computer`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_profiles_general`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_profiles_general`;
CREATE TABLE `cs_profiles_general` (
  `general_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` int(11) NOT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `height` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `zipcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `homepage` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `icq` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `msn` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `skype` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-',
  `custom_text` text COLLATE utf8_unicode_ci NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`general_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_profiles_general`
--

INSERT INTO `cs_profiles_general` VALUES(1, 1, 1175292000, 'Florian', 'Wolf', 496274400, '', 178, 'MÃ¼hlenstr. 65', '78126', 'Jena', 'DDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDD', '', 'http://www.clansuite.com', '163164530', '', '', '', '', '[b]bla[/b]', 3);
INSERT INTO `cs_profiles_general` VALUES(2, 3, 1172510321, '', '', 0, '-', 0, '-', '-', '-', '', '-', '-', '-', '-', '-', '-', '-', '', 0);
INSERT INTO `cs_profiles_general` VALUES(3, 2, 1175635148, '', '', 0, '-', 0, '-', '-', '-', '', '-', '-', '-', '-', '-', '-', '-', '', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_profiles_guestbook`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_profiles_guestbook`;
CREATE TABLE `cs_profiles_guestbook` (
  `gb_id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL DEFAULT '0',
  `to` int(11) NOT NULL,
  `gb_added` int(12) DEFAULT NULL,
  `gb_nick` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gb_email` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gb_icq` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gb_website` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gb_town` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gb_text` text COLLATE utf8_unicode_ci,
  `gb_ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gb_comment` text COLLATE utf8_unicode_ci,
  `image_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gb_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_profiles_guestbook`
--

INSERT INTO `cs_profiles_guestbook` VALUES(1, 0, 0, 1003200322, 'nick', 'email', '123124', 'www.skdjf.de', 'sdfsfas', 'sadfasdfasdfasdfasdf', '1231231231', 'mir nur ganz alleine :) [b]fu[/b] asdfasdf asdfffffffffffffffffasd asdfsadfsafsafsadf asdfsadfsfsafsafddddddddddddddddddddddddddddd  asdfsfsdfsdfsdfddddddddddddddddddddddddd dddddasdfsdfsdfsdfsaf assadfsaf ', 3);
INSERT INTO `cs_profiles_guestbook` VALUES(2, 0, 0, 1175392043, '123', '123', '123', '', '123', '123', '0', '', 0);
INSERT INTO `cs_profiles_guestbook` VALUES(3, 0, 0, 1175919684, 'nester tester', 'asdf', '1234---1234', 'http://www.uschi.de', 'blablubb', 'asafsdfd [b]/uschi[/b]', '127.0.0.1', NULL, 0);
INSERT INTO `cs_profiles_guestbook` VALUES(4, 0, 0, 1175924624, 'asdf', 'asdfafafaf', 'afaffa', 'faffa', 'fafafa', 'faafaffaaf', '127.0.0.1', NULL, 3);
INSERT INTO `cs_profiles_guestbook` VALUES(5, 0, 0, 1175928797, 'asdfsadf', 'sdfas', 'sdfasdf', 'sdfasdf', '1234234', '21efsdfasdfasdf', '127.0.0.1', NULL, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_rel_news_comments`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_rel_news_comments`;
CREATE TABLE `cs_rel_news_comments` (
  `news_id` int(10) unsigned NOT NULL,
  `comment_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`news_id`,`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_rel_news_comments`
--

INSERT INTO `cs_rel_news_comments` VALUES(16, 2);
INSERT INTO `cs_rel_news_comments` VALUES(91, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_rel_option_name`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_rel_option_name`;
CREATE TABLE `cs_rel_option_name` (
  `option_id` int(10) unsigned NOT NULL,
  `name_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`option_id`,`name_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_rel_option_name`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_rel_user_options`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_rel_user_options`;
CREATE TABLE `cs_rel_user_options` (
  `option_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`option_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_rel_user_options`
--

INSERT INTO `cs_rel_user_options` VALUES(1, 1);
INSERT INTO `cs_rel_user_options` VALUES(2, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_rel_user_profile`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_rel_user_profile`;
CREATE TABLE `cs_rel_user_profile` (
  `user_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_rel_user_profile`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_session`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_session`;
CREATE TABLE `cs_session` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `session_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `session_data` text COLLATE utf8_unicode_ci NOT NULL,
  `session_name` text COLLATE utf8_unicode_ci NOT NULL,
  `session_starttime` int(11) NOT NULL DEFAULT '0',
  `session_visibility` tinyint(4) NOT NULL DEFAULT '0',
  `session_where` text COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `session_id` (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_session`
--

INSERT INTO `cs_session` VALUES(0, 'a7t5gi2fomdtnsg5bkkhj9gg26', 'user|a:14:{s:8:"language";s:2:"de";s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:5:"Guest";s:12:"passwordhash";s:0:"";s:5:"email";s:0:"";s:8:"disabled";i:0;s:9:"activated";i:0;s:16:"language_via_url";i:1;s:14:"frontend_theme";s:8:"standard";s:13:"backend_theme";s:5:"admin";s:5:"group";i:1;s:4:"role";i:3;s:6:"rights";s:0:"";}', 'suiteSID', 1291835494, 1, 'sessionstart');
INSERT INTO `cs_session` VALUES(0, 'e81s9j41urd24cnqes6obs5ul3', 'user|a:14:{s:8:"language";s:2:"de";s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:5:"Guest";s:12:"passwordhash";s:0:"";s:5:"email";s:0:"";s:8:"disabled";i:0;s:9:"activated";i:0;s:16:"language_via_url";i:1;s:14:"frontend_theme";s:8:"standard";s:13:"backend_theme";s:5:"admin";s:5:"group";i:1;s:4:"role";i:3;s:6:"rights";s:0:"";}', 'suiteSID', 1291834832, 1, 'sessionstart');
INSERT INTO `cs_session` VALUES(0, 'ebm7j7m9vbent0ess05vnl0hp3', 'user|a:14:{s:8:"language";s:2:"de";s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:5:"Guest";s:12:"passwordhash";s:0:"";s:5:"email";s:0:"";s:8:"disabled";i:0;s:9:"activated";i:0;s:16:"language_via_url";i:1;s:14:"frontend_theme";s:8:"standard";s:13:"backend_theme";s:5:"admin";s:5:"group";i:1;s:4:"role";i:3;s:6:"rights";s:0:"";}', 'suiteSID', 1291834587, 1, 'sessionstart');
INSERT INTO `cs_session` VALUES(0, 'h6n4sdvagtb5ovtfoaofepkf65', 'user|a:14:{s:8:"language";s:2:"de";s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:5:"Guest";s:12:"passwordhash";s:0:"";s:5:"email";s:0:"";s:8:"disabled";i:0;s:9:"activated";i:0;s:16:"language_via_url";i:1;s:14:"frontend_theme";s:8:"standard";s:13:"backend_theme";s:5:"admin";s:5:"group";i:1;s:4:"role";i:3;s:6:"rights";s:0:"";}', 'suiteSID', 1291835563, 1, 'sessionstart');
INSERT INTO `cs_session` VALUES(0, 'igpkm3l4njpgr0h1knrkubvba0', 'user|a:14:{s:8:"language";s:2:"de";s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:5:"Guest";s:12:"passwordhash";s:0:"";s:5:"email";s:0:"";s:8:"disabled";i:0;s:9:"activated";i:0;s:16:"language_via_url";i:1;s:14:"frontend_theme";s:8:"standard";s:13:"backend_theme";s:5:"admin";s:5:"group";i:1;s:4:"role";i:3;s:6:"rights";s:0:"";}', 'suiteSID', 1291835326, 1, 'sessionstart');
INSERT INTO `cs_session` VALUES(0, 'kg7ufplb1tr505fe9igeev7iv5', 'user|a:14:{s:8:"language";s:2:"de";s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:5:"Guest";s:12:"passwordhash";s:0:"";s:5:"email";s:0:"";s:8:"disabled";i:0;s:9:"activated";i:0;s:16:"language_via_url";i:1;s:14:"frontend_theme";s:8:"standard";s:13:"backend_theme";s:5:"admin";s:5:"group";i:1;s:4:"role";i:3;s:6:"rights";s:0:"";}', 'suiteSID', 1291835152, 1, 'sessionstart');
INSERT INTO `cs_session` VALUES(0, 'ng8k038n8aic819nmtc9l9b772', 'user|a:14:{s:8:"language";s:2:"de";s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:5:"Guest";s:12:"passwordhash";s:0:"";s:5:"email";s:0:"";s:8:"disabled";i:0;s:9:"activated";i:0;s:16:"language_via_url";i:1;s:14:"frontend_theme";s:8:"standard";s:13:"backend_theme";s:5:"admin";s:5:"group";i:1;s:4:"role";i:3;s:6:"rights";s:0:"";}', 'suiteSID', 1291833724, 1, 'sessionstart');
INSERT INTO `cs_session` VALUES(0, 'o0hk34n4pslae2u5m0ap34g884', 'user|a:14:{s:8:"language";s:2:"de";s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:5:"Guest";s:12:"passwordhash";s:0:"";s:5:"email";s:0:"";s:8:"disabled";i:0;s:9:"activated";i:0;s:16:"language_via_url";i:1;s:14:"frontend_theme";s:8:"standard";s:13:"backend_theme";s:5:"admin";s:5:"group";i:1;s:4:"role";i:3;s:6:"rights";s:0:"";}', 'suiteSID', 1291834945, 1, 'sessionstart');
INSERT INTO `cs_session` VALUES(0, 'r41prjb94eqh16juqqnga4cvn4', 'user|a:14:{s:8:"language";s:2:"de";s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:5:"Guest";s:12:"passwordhash";s:0:"";s:5:"email";s:0:"";s:8:"disabled";i:0;s:9:"activated";i:0;s:16:"language_via_url";i:1;s:14:"frontend_theme";s:8:"standard";s:13:"backend_theme";s:5:"admin";s:5:"group";i:1;s:4:"role";i:3;s:6:"rights";s:0:"";}', 'suiteSID', 1291834759, 1, 'sessionstart');
INSERT INTO `cs_session` VALUES(0, 'sht387k5uqusf32cstnpamif00', 'user|a:14:{s:8:"language";s:2:"de";s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:5:"Guest";s:12:"passwordhash";s:0:"";s:5:"email";s:0:"";s:8:"disabled";i:0;s:9:"activated";i:0;s:16:"language_via_url";i:1;s:14:"frontend_theme";s:8:"standard";s:13:"backend_theme";s:5:"admin";s:5:"group";i:1;s:4:"role";i:3;s:6:"rights";s:0:"";}', 'suiteSID', 1291834659, 1, 'sessionstart');
INSERT INTO `cs_session` VALUES(0, 'sqspdvvn54c2rvupbpe6p8kfu4', 'user|a:14:{s:8:"language";s:2:"de";s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:5:"Guest";s:12:"passwordhash";s:0:"";s:5:"email";s:0:"";s:8:"disabled";i:0;s:9:"activated";i:0;s:16:"language_via_url";i:1;s:14:"frontend_theme";s:8:"standard";s:13:"backend_theme";s:5:"admin";s:5:"group";i:1;s:4:"role";i:3;s:6:"rights";s:0:"";}', 'suiteSID', 1291835120, 1, 'sessionstart');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_settings`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_settings`;
CREATE TABLE `cs_settings` (
  `option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_id` int(10) unsigned NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`option_id`,`name_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_settings`
--

INSERT INTO `cs_settings` VALUES(1, 1, 'drahtgitter');
INSERT INTO `cs_settings` VALUES(2, 2, 'en');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_static_pages`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_static_pages`;
CREATE TABLE `cs_static_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `html` text COLLATE utf8_unicode_ci NOT NULL,
  `iframe` tinyint(1) NOT NULL DEFAULT '0',
  `iframe_height` int(11) NOT NULL DEFAULT '300',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_static_pages`
--

INSERT INTO `cs_static_pages` VALUES(1, 'Credits', 'Without their brains Clansuite would not be - Thanks alot!', '', '<u><strong>Clansuite - Credits </strong></u>\r\n<br />\r\n<br />\r\n<br />\r\n<table width="691" height="393" cellspacing="1" cellpadding="1" border="1" align="" summary="">\r\n    <tbody>\r\n        <tr>\r\n            <td align="center">Class</td>\r\n            <td align="center">Author<br />\r\n            </td>\r\n            <td align="center">&nbsp;Licence</td>\r\n        </tr>\r\n        <tr>\r\n            <td>tar.class.php</td>\r\n            <td>Vincent Blavet &lt;vincent@phpconcept.net&gt;<br />\r\n            Copyright (c) 1997-2003 The PHP Group <br />\r\n            </td>\r\n            <td>PHP license v3</td>\r\n        </tr>\r\n        <tr>\r\n            <td>PEAR, the PHP Extension and Application Repository</td>\r\n            <td>Sterling Hughes &lt;sterling@php.net&gt;<br />\r\n            Stig Bakken &lt;ssb@php.net&gt;<br />\r\n            Tomas V.V.Cox &lt;cox@idecnet.com&gt;<br />\r\n            Greg Beaver &lt;cellog@php.net&gt;<br />\r\n            &nbsp;Copyright&nbsp; 1997-2006 The PHP Group</td>\r\n            <td>PHP license v3</td>\r\n        </tr>\r\n        <tr>\r\n            <td>Swift Mailer: A Flexible PHP Mailer Class</td>\r\n            <td>&quot;Chris Corbyn&quot; &lt;chris@w3style.co.uk&gt;<br />\r\n            Copyright 2006 Chris Corbyn</td>\r\n            <td>LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign="top">Smarty: the PHP compiling template engine</td>\r\n            <td valign="top">Monte Ohrt &lt;monte at ohrt dot com&gt;<br />\r\n            Andrei Zmievski &lt;andrei@php.net&gt;<br />\r\n            Copyright 2001-2005 New Digital Group, Inc.</td>\r\n            <td valign="top">LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign="top">Sajax : cross-platform, cross-browser web scripting toolkit</td>\r\n            <td valign="top">Copyright 2005-2006 modernmethod</td>\r\n            <td valign="top">BSD</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign="top">Imagemanger</td>\r\n            <td valign="top">Xiang Wei ZHUO &lt;wei@zhuo.org&gt;</td>\r\n            <td valign="top">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign="top">DHTML Calendar Javascript</td>\r\n            <td valign="top">Copyright Mihai Bazon, 2002-2005</td>\r\n            <td valign="top">LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign="top">Tab Pane Javascript</td>\r\n            <td valign="top">Copyright (c) 2002, 2003, 2006 Erik Arvidsson</td>\r\n            <td valign="top">Apache License v2</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign="top"><a href="http://www.fckeditor.net/">FCKEditor</a>- WYSIWYG</td>\r\n            <td valign="top">&nbsp;</td>\r\n            <td valign="top">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign="top">Icons by <a href="http://www.famfamfam.com/lab/icons/">famfamfam</a></td>\r\n            <td valign="top">&nbsp;</td>\r\n            <td valign="top">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign="top">mygosumenu''s</td>\r\n            <td valign="top">Copyright 2003,2004 Cezary Tomczak</td>\r\n            <td valign="top">BSD</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign="top">Bitstream Vera Fonts </td>\r\n            <td valign="top">Copyright (c) 2003 by Bitstream, Inc.</td>\r\n            <td valign="top">own</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign="top">&nbsp;</td>\r\n            <td valign="top">&nbsp;</td>\r\n            <td valign="top">&nbsp;</td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />', 1, 300);
INSERT INTO `cs_static_pages` VALUES(2, 'Google', 'Google', 'http://www.google.de', '', 1, 500);
INSERT INTO `cs_static_pages` VALUES(3, 'Help', 'The help for ClanSuite', '', '<strong><font size="4">Help</font><br />\r\n<br />\r\n</strong><strong> - gogo<br />\r\n- gogogogo<br />\r\n- gogogogogogo</strong>', 1, 300);
INSERT INTO `cs_static_pages` VALUES(4, 'Manual', 'The Manual', '', '<font size="4">Manual</font><br />\r\n<br />\r\n- some content', 1, 300);
INSERT INTO `cs_static_pages` VALUES(5, 'About', 'About ClanSuite', '', '<font size="4">About</font><br />\r\n<br />\r\n- some content', 1, 300);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_statistic`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_statistic`;
CREATE TABLE `cs_statistic` (
  `id` int(11) NOT NULL,
  `hits` int(20) NOT NULL DEFAULT '0',
  `views` int(50) NOT NULL DEFAULT '0',
  `online` int(14) NOT NULL DEFAULT '0',
  `maxonline` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_statistic`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_statistic_ip`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_statistic_ip`;
CREATE TABLE `cs_statistic_ip` (
  `id` int(11) NOT NULL,
  `dates` int(11) NOT NULL,
  `del` int(11) NOT NULL,
  `ip` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_statistic_ip`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_statistic_stats`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_statistic_stats`;
CREATE TABLE `cs_statistic_stats` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `dates` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `count` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_statistic_stats`
--

INSERT INTO `cs_statistic_stats` VALUES(1, '10.12.2009', 1);
INSERT INTO `cs_statistic_stats` VALUES(2, '11.12.2009', 1);
INSERT INTO `cs_statistic_stats` VALUES(3, '12.12.2009', 1);
INSERT INTO `cs_statistic_stats` VALUES(4, '20.12.2009', 1);
INSERT INTO `cs_statistic_stats` VALUES(5, '20.12.2009', 1);
INSERT INTO `cs_statistic_stats` VALUES(6, '21.12.2009', 1);
INSERT INTO `cs_statistic_stats` VALUES(7, '22.12.2009', 1);
INSERT INTO `cs_statistic_stats` VALUES(8, '23.12.2009', 1);
INSERT INTO `cs_statistic_stats` VALUES(9, '26.01.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(10, '27.01.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(11, '28.01.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(12, '29.01.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(13, '30.01.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(14, '31.01.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(15, '01.02.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(16, '02.02.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(17, '03.02.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(18, '04.02.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(19, '07.02.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(20, '15.02.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(21, '20.02.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(22, '22.02.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(23, '26.02.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(24, '27.02.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(25, '28.02.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(26, '03.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(27, '04.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(28, '05.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(29, '06.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(30, '08.03.2010', 2);
INSERT INTO `cs_statistic_stats` VALUES(31, '11.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(32, '13.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(33, '14.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(34, '15.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(35, '16.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(36, '17.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(37, '18.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(38, '19.03.2010', 2);
INSERT INTO `cs_statistic_stats` VALUES(39, '20.03.2010', 2);
INSERT INTO `cs_statistic_stats` VALUES(40, '21.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(41, '24.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(42, '25.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(43, '26.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(44, '27.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(45, '28.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(46, '29.03.2010', 1);
INSERT INTO `cs_statistic_stats` VALUES(47, '13.05.2010', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_users`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_users`;
CREATE TABLE `cs_users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nick` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `passwordhash` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `new_passwordhash` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `new_salt` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `activation_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `joined` int(11) NOT NULL DEFAULT '0',
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `country` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `timezone` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `theme` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `group_id` tinyint(5) unsigned NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `email` (`email`),
  KEY `nick` (`nick`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_users`
--

INSERT INTO `cs_users` VALUES(1, 'jakoch@web.de', 'user1', 'd033e22ae348aeb5660fc2140aec35850c4da997', '', '', '', '', 1215754325, 0, 0, 1, 0, 'de', 'de_DE', 'UTC1', 'standard', 3);
INSERT INTO `cs_users` VALUES(2, 'user2@clansuite.com', 'user2', 'd1ca11799e222d429424d47b424047002ea72d44', '', '', '', '', 1215763325, 0, 0, 1, 0, 'de', 'de_DE', 'UTC1', 'standard', 0);
INSERT INTO `cs_users` VALUES(8, 'user3@clansuite.com', 'user3', 'e5292e82b58ec55069d178b092ad25ee97f1917d', '', 'G1vmXy', '', '', 1215764325, 0, 0, 1, 0, '', '', NULL, '', 3);
INSERT INTO `cs_users` VALUES(9, 'user4@clansuite.com', 'user4', '90b525e43d877ee890e3cd800584fbddd7cd6668', '', 'eVH0Jx', '', '', 1215768110, 0, 0, 1, 0, '', '', NULL, '', 0);
INSERT INTO `cs_users` VALUES(10, 'user5@clansuite.com', 'user5', 'ff4e167734b0cc1c61fb9ca064a18d85045aea80', '', 'AxOD.2', '', '', 1215984499, 0, 0, 1, 0, '', '', NULL, '', 0);
INSERT INTO `cs_users` VALUES(11, 'admin@email.com', 'admin', '3979339f2a534fea635cc6df254eb2a616490653', '', 'CPEr2Z', '', '', 1229294500, 0, 0, 1, 0, '', 'german', NULL, '', 0);
INSERT INTO `cs_users` VALUES(12, 'admin@email.com', 'admin', '06dc00bbadde6fee4c3c0e15ba1358bcb6542c64', '', 'z36ZGW', '', '', 1231801464, 0, 0, 1, 0, '', 'english', NULL, '', 0);
INSERT INTO `cs_users` VALUES(13, 'admin@email.com', 'admin', '7f0ce5783e1aeb2ae60b697c8311f784f83e0f81', '', 'y7UQ0', '', '', 1258237342, 0, 0, 1, 0, '', 'german', NULL, '', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_users_options`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_users_options`;
CREATE TABLE `cs_users_options` (
  `option_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `language` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `theme` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`option_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_users_options`
--

INSERT INTO `cs_users_options` VALUES(0, 1, 'en', 'accessible');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_whoisonline`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_whoisonline`;
CREATE TABLE `cs_whoisonline` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `time` int(14) NOT NULL DEFAULT '0',
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `userID` int(11) DEFAULT '0',
  `site` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_whoisonline`
--

INSERT INTO `cs_whoisonline` VALUES(72, 1273783967, '127.0.0.1', NULL, '/index.php?mod=news&action=show&page=1', '13.05.2010');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cs_whowasonline`
--
-- Erzeugt am: 20. November 2010 um 12:35
--

DROP TABLE IF EXISTS `cs_whowasonline`;
CREATE TABLE `cs_whowasonline` (
  `id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `ip` text COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `site` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cs_whowasonline`
--

