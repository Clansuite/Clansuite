-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 17. Mai 2011 um 08:59
-- Server Version: 5.1.36
-- PHP-Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `clansuite`
--

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
