-- phpMyAdmin SQL Dump
-- version 2.8.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 14. Juli 2006 um 14:04
-- Server Version: 5.0.21
-- PHP-Version: 5.1.4
-- 
-- Datenbank: `clansuite`
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
