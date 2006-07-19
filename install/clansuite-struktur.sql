-- phpMyAdmin SQL Dump
-- version 2.8.0.3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 19. Juli 2006 um 14:42
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
CREATE TABLE IF NOT EXISTS `cs_adminmenu` (
  `id` tinyint(3) unsigned NOT NULL default '0',
  `parent` tinyint(3) unsigned NOT NULL default '0',
  `type` varchar(255) collate latin1_german1_ci NOT NULL default '',
  `text` varchar(255) collate latin1_german1_ci NOT NULL default '',
  `href` varchar(255) collate latin1_german1_ci NOT NULL default '',
  `title` varchar(255) collate latin1_german1_ci NOT NULL default '',
  `target` varchar(255) collate latin1_german1_ci NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_category`
-- 

DROP TABLE IF EXISTS `cs_category`;
CREATE TABLE IF NOT EXISTS `cs_category` (
  `cat_id` tinyint(4) NOT NULL auto_increment,
  `cat_modulname` text character set latin1,
  `cat_sortorder` tinyint(4) default NULL,
  `cat_name` text character set latin1,
  `cat_image_url` varchar(60) character set latin1 default NULL,
  `cat_description` varchar(90) character set latin1 default '0',
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_groups`
-- 

DROP TABLE IF EXISTS `cs_groups`;
CREATE TABLE IF NOT EXISTS `cs_groups` (
  `group_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate latin1_general_ci NOT NULL,
  `description` text collate latin1_general_ci NOT NULL,
  `rights` text collate latin1_general_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_modules`
-- 

DROP TABLE IF EXISTS `cs_modules`;
CREATE TABLE IF NOT EXISTS `cs_modules` (
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

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_news`
-- 

DROP TABLE IF EXISTS `cs_news`;
CREATE TABLE IF NOT EXISTS `cs_news` (
  `news_id` int(11) NOT NULL auto_increment,
  `news_title` varchar(255) collate latin1_general_ci NOT NULL default '',
  `news_body` text collate latin1_general_ci NOT NULL,
  `news_category` tinyint(4) NOT NULL default '0',
  `user_id` int(11) unsigned NOT NULL default '0',
  `news_added` int(11) default NULL,
  `news_hidden` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`news_id`,`news_category`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_news_comments`
-- 

DROP TABLE IF EXISTS `cs_news_comments`;
CREATE TABLE IF NOT EXISTS `cs_news_comments` (
  `news_id` int(11) NOT NULL default '0',
  `comment_id` int(10) unsigned NOT NULL default '0',
  `user_id` int(11) unsigned NOT NULL default '0',
  `body` text character set latin1 NOT NULL,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `pseudo` varchar(25) character set latin1 default NULL,
  `ip` varchar(15) character set latin1 NOT NULL default '',
  `host` varchar(255) character set latin1 NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_accesslog`
-- 

DROP TABLE IF EXISTS `cs_pot_accesslog`;
CREATE TABLE IF NOT EXISTS `cs_pot_accesslog` (
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

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_add_data`
-- 

DROP TABLE IF EXISTS `cs_pot_add_data`;
CREATE TABLE IF NOT EXISTS `cs_pot_add_data` (
  `accesslog_id` int(11) NOT NULL,
  `data_field` varchar(32) collate latin1_general_ci NOT NULL,
  `data_value` varchar(255) collate latin1_general_ci NOT NULL,
  KEY `accesslog_id` (`accesslog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_documents`
-- 

DROP TABLE IF EXISTS `cs_pot_documents`;
CREATE TABLE IF NOT EXISTS `cs_pot_documents` (
  `data_id` int(11) NOT NULL,
  `string` varchar(255) collate latin1_general_ci NOT NULL,
  `document_url` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`data_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_exit_targets`
-- 

DROP TABLE IF EXISTS `cs_pot_exit_targets`;
CREATE TABLE IF NOT EXISTS `cs_pot_exit_targets` (
  `data_id` int(11) NOT NULL,
  `string` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`data_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_hostnames`
-- 

DROP TABLE IF EXISTS `cs_pot_hostnames`;
CREATE TABLE IF NOT EXISTS `cs_pot_hostnames` (
  `data_id` int(11) NOT NULL,
  `string` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`data_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_operating_systems`
-- 

DROP TABLE IF EXISTS `cs_pot_operating_systems`;
CREATE TABLE IF NOT EXISTS `cs_pot_operating_systems` (
  `data_id` int(11) NOT NULL,
  `string` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`data_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_referers`
-- 

DROP TABLE IF EXISTS `cs_pot_referers`;
CREATE TABLE IF NOT EXISTS `cs_pot_referers` (
  `data_id` int(11) NOT NULL,
  `string` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`data_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_user_agents`
-- 

DROP TABLE IF EXISTS `cs_pot_user_agents`;
CREATE TABLE IF NOT EXISTS `cs_pot_user_agents` (
  `data_id` int(11) NOT NULL,
  `string` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`data_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci DELAY_KEY_WRITE=1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_pot_visitors`
-- 

DROP TABLE IF EXISTS `cs_pot_visitors`;
CREATE TABLE IF NOT EXISTS `cs_pot_visitors` (
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

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_rights`
-- 

DROP TABLE IF EXISTS `cs_rights`;
CREATE TABLE IF NOT EXISTS `cs_rights` (
  `right_id` int(11) unsigned NOT NULL default '0',
  `right_name` varchar(150) character set latin1 collate latin1_german1_ci NOT NULL default '',
  PRIMARY KEY  (`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_session`
-- 

DROP TABLE IF EXISTS `cs_session`;
CREATE TABLE IF NOT EXISTS `cs_session` (
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

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_user_rights`
-- 

DROP TABLE IF EXISTS `cs_user_rights`;
CREATE TABLE IF NOT EXISTS `cs_user_rights` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `right_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_user_usergroups`
-- 

DROP TABLE IF EXISTS `cs_user_usergroups`;
CREATE TABLE IF NOT EXISTS `cs_user_usergroups` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `group_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_usergroup_rights`
-- 

DROP TABLE IF EXISTS `cs_usergroup_rights`;
CREATE TABLE IF NOT EXISTS `cs_usergroup_rights` (
  `group_id` int(5) unsigned zerofill NOT NULL default '00000',
  `right_id` int(5) unsigned zerofill NOT NULL default '00000',
  `right_pos` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`group_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_usergroups`
-- 

DROP TABLE IF EXISTS `cs_usergroups`;
CREATE TABLE IF NOT EXISTS `cs_usergroups` (
  `group_id` int(5) unsigned NOT NULL auto_increment,
  `group_pos` tinyint(4) unsigned NOT NULL default '1',
  `group_name` varchar(75) character set latin1 collate latin1_german1_ci default NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `cs_users`
-- 

DROP TABLE IF EXISTS `cs_users`;
CREATE TABLE IF NOT EXISTS `cs_users` (
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
