-- MySQL dump 10.10
--
-- Host: localhost    Database: clansuite
-- ------------------------------------------------------
-- Server version	5.0.21-community-nt

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cs_admin_shortcuts`
--

DROP TABLE IF EXISTS `cs_admin_shortcuts`;
CREATE TABLE `cs_admin_shortcuts` (
  `id` tinyint(4) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `order` tinyint(4) NOT NULL default '30',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_admin_shortcuts`
--


/*!40000 ALTER TABLE `cs_admin_shortcuts` DISABLE KEYS */;
INSERT INTO `cs_admin_shortcuts` VALUES (1,'Console','index.php?mod=admin&sub=console','console.png',45),(2,'Downloads','index.php?mod=admin&sub=downloads','downloads.png',30),(3,'Articles','index.php?mod=admin&sub=articles','articles.png',30),(4,'Links','index.php?mod=admin&sub=links','links.png',30),(5,'Calendar','index.php?mod=admin&sub=calendar','calendar.png',30),(6,'Time','index.php?mod=admin&sub=time','time.png',30),(7,'Email','index.php?mod=admin&sub=email','email.png',3),(8,'Shoutbox','index.php?mod=admin&sub=shoutbox','shoutbox.png',30),(9,'Help','index.php?mod=admin&sub=help','help.png',40),(10,'Security','index.php?mod=admin&sub=security','security.png',41),(11,'Gallery','index.php?mod=admin&sub=gallery','gallery.png',30),(12,'System','index.php?mod=admin&sub=system','system.png',42),(13,'Replays','index.php?mod=admin&sub=replays','replays.png',30),(14,'News','index.php?mod=admin&sub=news','news.png',2),(15,'Settings','index.php?mod=admin&sub=settings','settings.png',43),(16,'Users','index.php?mod=admin&sub=users','users.png',1),(17,'Backup','index.php?mod=admin&sub=backup','backup.png',44),(18,'Templates','index.php?mod=admin&sub=templates','templates.png',4),(19,'Groups','index.php?mod=admin&sub=groups','groups.png',2),(20,'Search','index.php?mod=admin&sub=search','search.png',30);
/*!40000 ALTER TABLE `cs_admin_shortcuts` ENABLE KEYS */;

--
-- Table structure for table `cs_adminmenu`
--

DROP TABLE IF EXISTS `cs_adminmenu`;
CREATE TABLE `cs_adminmenu` (
  `id` tinyint(3) unsigned NOT NULL default '0',
  `parent` tinyint(3) unsigned NOT NULL default '0',
  `type` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `order` tinyint(4) NOT NULL,
  `icon` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`,`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_adminmenu`
--


/*!40000 ALTER TABLE `cs_adminmenu` DISABLE KEYS */;
INSERT INTO `cs_adminmenu` VALUES (1,0,'folder','Home','index.php?mod=admin','Home','_self',0,''),(2,0,'folder','Modules','','Modules','_self',1,''),(3,2,'folder','News','','News','_self',0,'page_edit.png'),(4,2,'folder','Articles','','Articles','_self',1,'report.png'),(5,2,'folder','Static Pages','','Static Pages','_self',2,'html.png'),(6,5,'item','Create new Static Page','index.php?mod=admin&sub=static&action=create','Create new Static Page','_self',0,'add.png'),(7,5,'item','Show Static Pages','index.php?mod=admin&sub=static&action=show','Show Static Pages','_self',1,'pencil.png'),(8,2,'folder','Forum','','Forum','_self',3,'application_view_list.png'),(9,2,'folder','Guestbook','index.php?mod=guestbook&action=show','Guestbook','_self',4,'book_open.png'),(10,2,'folder','Matches','index.php?mod=matches&action=show','Matches','_self',5,'database_go.png'),(11,2,'folder','Serverlist','','Serverlist','_self',6,'table.png'),(12,11,'item','Show Servers','index.php?mod=serverlist&action=show','Show Servers','_self',0,'application_view_list.png'),(13,11,'item','Add Server','index.php?mod=serverlist&action=show','Add Server','_self',1,'application_form_edit.png'),(14,2,'folder','Shoutbox','','Shoutbox','_self',7,'comment.png'),(15,2,'folder','Downloads','','Downloads','_self',8,'disk.png'),(16,2,'folder','Gallery','','Gallery','_self',9,'map_go.png'),(17,2,'folder','Replays','','Replays','_self',10,'film.png'),(18,2,'folder','eMail','','eMail','_self',11,'email_open_image.png'),(19,0,'folder','System','','System','_self',2,''),(20,19,'item','Settings','index.php?mod=admin&sub=config','Settings','_self',0,'settings.png'),(21,19,'folder','Database','','Database','_self',1,'database_gear.png'),(22,21,'item','Optimize','index.php?mod=database&action=optimize','Optimize','_self',0,'database_go.png'),(23,21,'item','Backup','index.php?mod=database&action=backup','Backup','_self',1,'database_key.png'),(24,19,'folder','Modules','','Modules','_self',2,'bricks.png'),(25,24,'item','Install new modules','index.php?mod=admin&sub=modules&action=install_new','Install new modules','_self',0,'package.png'),(26,24,'item','Create a module/submodule','index.php?mod=admin&sub=modules&action=create_new','Create a new module/submodule','_self',1,'add.png'),(27,24,'item','Show and edit modules','index.php?mod=admin&sub=modules&action=show_all','Show and edit modules','_self',2,'bricks_edit.png'),(28,24,'item','Export a module','index.php?mod=admin&sub=modules&action=export','Export a module','_self',3,'compress.png'),(29,19,'folder','Language','','Language','_self',3,'spellcheck.png'),(30,29,'item','Language Editor','index.php?mod=language&sub=editor','Language Editor','_self',0,'spellcheck.png'),(31,0,'folder','Administration','','Administration','_self',3,''),(32,31,'folder','Users','','Users','_self',0,'user_suit.png'),(33,32,'item','Show all Users','index.php?mod=admin&sub=users','Show all Users','_self',0,'table.png'),(34,32,'item','Create a user','index.php?mod=admin&sub=users&action=create','Create a user','_self',1,'add.png'),(35,32,'item','Search a User','index.php?mod=admin&sub=users&action=search','Search a User','_self',2,'magnifier.png'),(36,31,'folder','Groups','','Groups','_self',1,'group.png'),(37,36,'item','Show all Groups','index.php?mod=admin&sub=groups','Show all Groups','_self',0,'table.png'),(38,36,'item','Create a group','index.php?mod=admin&sub=groups&action=create','Create a group','_self',1,'add.png'),(39,31,'folder','Permissions','','Permissions','_self',2,'key.png'),(40,39,'item','Show all Permissions','index.php?mod=admin&sub=permissions','Show all Permissions','_self',0,'table.png'),(41,31,'item','Categories','index.php?mod=admin&sub=categories','Categories','_self',3,'spellcheck.png'),(42,31,'folder','Layout & Styles','','Layout & Styles','_self',4,'layout_header.png'),(43,42,'item','Adminmenu Editor','index.php?mod=admin&sub=menueditor','Adminmenu Editor','_self',0,'application_form_edit.png'),(44,42,'item','Template Editor','index.php?mod=admin&sub=templates','Template Editor','_self',1,'layout_edit.png'),(45,0,'folder','Help','','Help','_self',4,''),(46,45,'item','Help','index.php?mod=admin&sub=static&action=show&page=help','Help','_self',0,'help.png'),(47,45,'item','Manual','index.php?mod=admin&sub=manual','Manual','_self',1,'book_open.png'),(48,45,'item','Report Bugs & Give Feedback','index.php?mod=admin&sub=bugs','Report Bugs & Give Feedback','_self',2,'error.png'),(49,45,'item','About Clansuite','index.php?mod=admin&sub=static&action=show&page=about','About Clansuite','_self',3,'information.png');
/*!40000 ALTER TABLE `cs_adminmenu` ENABLE KEYS */;

--
-- Table structure for table `cs_adminmenu_old`
--

DROP TABLE IF EXISTS `cs_adminmenu_old`;
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
-- Dumping data for table `cs_adminmenu_old`
--


/*!40000 ALTER TABLE `cs_adminmenu_old` DISABLE KEYS */;
INSERT INTO `cs_adminmenu_old` VALUES (1,0,'folder','Home','index.php?mod=admin','Home','_self',0,''),(2,0,'folder','Modules','','Modules','_self',1,''),(3,2,'folder','News','','News','_self',0,'page_edit.png'),(4,2,'folder','Articles','','Articles','_self',1,'report.png'),(5,2,'folder','Static Pages','','Static Pages','_self',2,'html.png'),(6,5,'item','Create new Static Page','index.php?mod=admin&sub=static&action=create','Create new Static Page','_self',0,'add.png'),(7,5,'item','Show Static Pages','index.php?mod=admin&sub=static&action=show','Show Static Pages','_self',1,'pencil.png'),(8,2,'folder','Forum','','Forum','_self',3,'application_view_list.png'),(9,2,'folder','Guestbook','index.php?mod=guestbook&action=show','Guestbook','_self',4,'book_open.png'),(10,2,'folder','Matches','index.php?mod=matches&action=show','Matches','_self',5,'database_go.png'),(11,2,'folder','Serverlist','','Serverlist','_self',6,'table.png'),(12,11,'item','Show Servers','index.php?mod=serverlist&action=show','Show Servers','_self',0,'application_view_list.png'),(13,11,'item','Add Server','index.php?mod=serverlist&action=show','Add Server','_self',1,'application_form_edit.png'),(14,2,'folder','Shoutbox','','Shoutbox','_self',7,'comment.png'),(15,2,'folder','Downloads','','Downloads','_self',8,'disk.png'),(16,2,'folder','Gallery','','Gallery','_self',9,'map_go.png'),(17,2,'folder','Replays','','Replays','_self',10,'film.png'),(18,2,'folder','eMail','','eMail','_self',11,'email_open_image.png'),(19,0,'folder','System','','System','_self',2,''),(20,19,'item','Settings','index.php?mod=admin&sub=config','Settings','_self',0,'settings.png'),(21,19,'folder','Database','','Database','_self',1,'database_gear.png'),(22,21,'item','Optimize','index.php?mod=database&action=optimize','Optimize','_self',0,'database_go.png'),(23,21,'item','Backup','index.php?mod=database&action=backup','Backup','_self',1,'database_key.png'),(24,19,'folder','Modules','','Modules','_self',2,'bricks.png'),(25,24,'item','Install new modules','index.php?mod=admin&sub=modules&action=install_new','Install new modules','_self',0,'package.png'),(26,24,'item','Create a module/submodule','index.php?mod=admin&sub=modules&action=create_new','Create a new module/submodule','_self',1,'add.png'),(28,24,'item','Show and edit modules','index.php?mod=admin&sub=modules&action=show_all','Show and edit modules','_self',2,'bricks_edit.png'),(29,24,'item','Export a module','index.php?mod=admin&sub=modules&action=export','Export a module','_self',3,'compress.png'),(30,19,'folder','Language','','Language','_self',3,'spellcheck.png'),(31,30,'item','Language Editor','index.php?mod=language&sub=editor','Language Editor','_self',0,'spellcheck.png'),(32,0,'folder','Administration','','Administration','_self',3,''),(33,32,'folder','Users','','Users','_self',0,'user_suit.png'),(34,33,'item','Show all Users','index.php?mod=admin&sub=users','Show all Users','_self',0,'table.png'),(35,33,'item','Create a user','index.php?mod=admin&sub=users&action=create','Create a user','_self',1,'add.png'),(36,33,'item','Search a User','index.php?mod=admin&sub=users&action=search','Search a User','_self',2,'magnifier.png'),(37,32,'folder','Groups','','Groups','_self',1,'group.png'),(38,37,'item','Show all Groups','index.php?mod=admin&sub=groups','Show all Groups','_self',0,'table.png'),(39,37,'item','Create a group','index.php?mod=admin&sub=groups&action=create','Create a group','_self',1,'add.png'),(40,32,'folder','Permissions','','Permissions','_self',2,'key.png'),(41,40,'item','Show all Permissions','index.php?mod=admin&sub=permissions','Show all Permissions','_self',0,'table.png'),(42,32,'item','Categories','index.php?mod=admin&sub=categories','Categories','_self',3,'spellcheck.png'),(43,32,'folder','Layout & Styles','','Layout & Styles','_self',4,'layout_header.png'),(44,43,'item','Adminmenu Editor','index.php?mod=admin&sub=menueditor','Adminmenu Editor','_self',0,'application_form_edit.png'),(45,43,'item','Template Editor','index.php?mod=admin&sub=templates','Template Editor','_self',1,'layout_edit.png'),(46,0,'folder','Help','','Help','_self',4,''),(47,46,'item','Help','index.php?mod=admin&sub=static&action=show&page=help','Help','_self',0,'help.png'),(48,46,'item','Manual','index.php?mod=admin&sub=manual','Manual','_self',1,'book_open.png'),(49,46,'item','Report Bugs & Give Feedback','index.php?mod=admin&sub=bugs','Report Bugs & Give Feedback','_self',2,'error.png'),(50,46,'item','About Clansuite','index.php?mod=admin&sub=static&action=show&page=about','About Clansuite','_self',3,'information.png');
/*!40000 ALTER TABLE `cs_adminmenu_old` ENABLE KEYS */;

--
-- Table structure for table `cs_areas`
--

DROP TABLE IF EXISTS `cs_areas`;
CREATE TABLE `cs_areas` (
  `area_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default 'New Area',
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_areas`
--


/*!40000 ALTER TABLE `cs_areas` DISABLE KEYS */;
INSERT INTO `cs_areas` VALUES (5,'Shoutbox','Rights for the shoutbox'),(4,'ACP','Admin Control Panel'),(6,'News','The area for the news module'),(7,'Filebrowser','The filebrowser module area');
/*!40000 ALTER TABLE `cs_areas` ENABLE KEYS */;

--
-- Table structure for table `cs_categories`
--

DROP TABLE IF EXISTS `cs_categories`;
CREATE TABLE `cs_categories` (
  `cat_id` tinyint(4) NOT NULL auto_increment,
  `module_id` tinyint(4) default NULL,
  `sortorder` tinyint(4) default '0',
  `name` varchar(200) default 'New Category',
  `description` text,
  `image` varchar(60) default NULL,
  `icon` varchar(60) default NULL,
  `color` varchar(7) default NULL,
  PRIMARY KEY  (`cat_id`),
  UNIQUE KEY `cat_id` (`cat_id`),
  KEY `modul_id` (`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_categories`
--


/*!40000 ALTER TABLE `cs_categories` DISABLE KEYS */;
INSERT INTO `cs_categories` VALUES (1,88,1,'-keine-','Diese News sind keiner Kategorie zugeordnet','','','#000000'),(2,88,2,'Allgemein','Thema Allgemein','','','#000000'),(3,88,3,'Member','Thema Members','','','#000000'),(4,88,4,'Page','Thema Page','','','#000000'),(5,88,5,'IRC','Thema IRC','','','#000000'),(6,88,6,'Clan-Wars','Thema Matches','','','#000000'),(7,88,7,'Sonstiges','Thema Hardware','','','#000000');
/*!40000 ALTER TABLE `cs_categories` ENABLE KEYS */;

--
-- Table structure for table `cs_category`
--

DROP TABLE IF EXISTS `cs_category`;
CREATE TABLE `cs_category` (
  `cat_id` tinyint(4) NOT NULL auto_increment,
  `cat_modulname` text,
  `cat_sortorder` tinyint(4) default NULL,
  `cat_name` text,
  `cat_image_url` varchar(60) default NULL,
  `cat_description` varchar(90) default '0',
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_category`
--


/*!40000 ALTER TABLE `cs_category` DISABLE KEYS */;
INSERT INTO `cs_category` VALUES (1,'newso',NULL,'keine','','Diese News sind keiner Kategorie zugeordnet'),(2,'news',NULL,'Allgemein','/images/allgemein.gif','Allgemein'),(3,'news',NULL,'Member','/images/news/member.gif','Thema Member'),(4,'news',NULL,'Page','/images/news/page.gif',' Thema Page'),(5,'news',NULL,'IRC','/images/news/irc.gif',' Thema IRC gehört'),(6,'news',NULL,'Clan-Wars','/images/news/clanwars.gif','Thema Matches'),(7,'news',NULL,'Sonstiges','/images/news/sonstiges.gif','alles'),(8,'news',NULL,'LAN','','lan'),(10,'/design',NULL,'good night',NULL,'0');
/*!40000 ALTER TABLE `cs_category` ENABLE KEYS */;

--
-- Table structure for table `cs_group_right`
--

DROP TABLE IF EXISTS `cs_group_right`;
CREATE TABLE `cs_group_right` (
  `group_id` int(11) NOT NULL default '0',
  `right_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_group_right`
--


/*!40000 ALTER TABLE `cs_group_right` DISABLE KEYS */;
INSERT INTO `cs_group_right` VALUES (1,10),(1,11),(1,12),(1,13),(3,1),(3,3),(3,4),(3,5);
/*!40000 ALTER TABLE `cs_group_right` ENABLE KEYS */;

--
-- Table structure for table `cs_group_rights`
--

DROP TABLE IF EXISTS `cs_group_rights`;
CREATE TABLE `cs_group_rights` (
  `group_id` int(11) NOT NULL default '0',
  `right_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_group_rights`
--


/*!40000 ALTER TABLE `cs_group_rights` DISABLE KEYS */;
INSERT INTO `cs_group_rights` VALUES (1,1),(1,2),(1,3);
/*!40000 ALTER TABLE `cs_group_rights` ENABLE KEYS */;

--
-- Table structure for table `cs_groups`
--

DROP TABLE IF EXISTS `cs_groups`;
CREATE TABLE `cs_groups` (
  `group_id` int(5) unsigned NOT NULL auto_increment,
  `sortorder` int(4) unsigned NOT NULL default '0',
  `name` varchar(80) default 'New Group',
  `description` varchar(255) NOT NULL,
  `icon` varchar(255) default NULL,
  `image` varchar(255) NOT NULL,
  `color` varchar(7) NOT NULL,
  PRIMARY KEY  (`group_id`),
  UNIQUE KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_groups`
--


/*!40000 ALTER TABLE `cs_groups` DISABLE KEYS */;
INSERT INTO `cs_groups` VALUES (1,1,'Administrator','The Administrator Group','','','#666600');
/*!40000 ALTER TABLE `cs_groups` ENABLE KEYS */;

--
-- Table structure for table `cs_help`
--

DROP TABLE IF EXISTS `cs_help`;
CREATE TABLE `cs_help` (
  `help_id` int(11) NOT NULL auto_increment,
  `mod` varchar(255) NOT NULL,
  `sub` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `helptext` text NOT NULL,
  `related_links` text NOT NULL,
  PRIMARY KEY  (`help_id`),
  UNIQUE KEY `help_id` (`help_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_help`
--


/*!40000 ALTER TABLE `cs_help` DISABLE KEYS */;
INSERT INTO `cs_help` VALUES (1,'admin','','show','test',''),(2,'admin','modules','export','test','');
/*!40000 ALTER TABLE `cs_help` ENABLE KEYS */;

--
-- Table structure for table `cs_mod_rel_sub`
--

DROP TABLE IF EXISTS `cs_mod_rel_sub`;
CREATE TABLE `cs_mod_rel_sub` (
  `module_id` int(11) NOT NULL,
  `submodule_id` int(11) NOT NULL,
  PRIMARY KEY  (`module_id`,`submodule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_mod_rel_sub`
--


/*!40000 ALTER TABLE `cs_mod_rel_sub` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_mod_rel_sub` ENABLE KEYS */;

--
-- Table structure for table `cs_modules`
--

DROP TABLE IF EXISTS `cs_modules`;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_modules`
--


/*!40000 ALTER TABLE `cs_modules` DISABLE KEYS */;
INSERT INTO `cs_modules` VALUES (77,'account','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Account Administration','This module handles all necessary account stuff - like login/logout etc.','module_account','account.module.php','account',1,'module_account.jpg',0.1,0,1,'s:0:\"\";'),(79,'captcha','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Captcha Module','The captcha module presents a image only humanoids can read.','module_captcha','captcha.module.php','captcha',1,'module_captcha.jpg',0.1,0,1,'s:0:\"\";'),(80,'index','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Index Module','This is the main site.','module_index','index.module.php','index',1,'module_index.jpg',0.1,0,1,'s:0:\"\";'),(78,'admin','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Admin Interface','This is the Admin Control Panel','module_admin','admin.module.php','admin',1,'module_admin.jpg',0.1,0,1,'a:14:{s:7:\"configs\";a:2:{i:0;s:18:\"configs.module.php\";i:1;s:20:\"module_admin_configs\";}s:7:\"modules\";a:2:{i:0;s:18:\"modules.module.php\";i:1;s:20:\"module_admin_modules\";}s:5:\"users\";a:2:{i:0;s:16:\"users.module.php\";i:1;s:18:\"module_admin_users\";}s:10:\"usercenter\";a:2:{i:0;s:21:\"usercenter.module.php\";i:1;s:23:\"module_admin_usercenter\";}s:6:\"groups\";a:2:{i:0;s:17:\"groups.module.php\";i:1;s:19:\"module_admin_groups\";}s:11:\"permissions\";a:2:{i:0;s:16:\"perms.module.php\";i:1;s:24:\"module_admin_permissions\";}s:10:\"menueditor\";a:2:{i:0;s:21:\"menueditor.module.php\";i:1;s:23:\"module_admin_menueditor\";}s:6:\"static\";a:2:{i:0;s:17:\"static.module.php\";i:1;s:19:\"module_admin_static\";}s:4:\"bugs\";a:2:{i:0;s:15:\"bugs.module.php\";i:1;s:17:\"module_admin_bugs\";}s:6:\"manual\";a:2:{i:0;s:17:\"manual.module.php\";i:1;s:19:\"module_admin_manual\";}s:9:\"templates\";a:2:{i:0;s:20:\"templates.module.php\";i:1;s:22:\"module_admin_templates\";}s:8:\"settings\";a:2:{i:0;s:19:\"settings.module.php\";i:1;s:21:\"module_admin_settings\";}s:10:\"categories\";a:2:{i:0;s:21:\"categories.module.php\";i:1;s:23:\"module_admin_categories\";}s:4:\"help\";a:2:{i:0;s:15:\"help.module.php\";i:1;s:17:\"module_admin_help\";}}'),(83,'static','Jens-André Koch,Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Static Pages','Static Pages store HTML content','module_static','static.module.php','static',1,'module_static.jpg',0.1,0,0,'s:0:\"\";'),(86,'shoutbox','Björn Spiegel, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Shoutbox Modul','This module displays a shoutbox. You can do entries and administrate it ...','module_shoutbox','shoutbox.module.php','shoutbox',1,'module_shoutbox.jpg',0.1,0,0,'s:0:\"\";'),(88,'news','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','News','News module','module_news','news.module.php','news',1,'module_news.jpg',0.1,0,0,'s:0:\"\";'),(92,'filebrowser','Florian Wolf, Jens-Andrè Koch','http://www.clansuite.com','GPL v2','clansuite group','Filebrowser','The filebrwoser of clansuite','module_filebrowser','filebrowser.module.php','filebrowser',1,'module_filebrowser.jpg',0.1,0,0,'a:1:{s:5:\"admin\";a:2:{i:0;s:21:\"filebrowser.admin.php\";i:1;s:24:\"module_filebrowser_admin\";}}'),(93,'serverlist','Jens Andre Koch','','BSD','Clansuite Group','Serverlist','List Gameservers','module_serverlist','serverlist.module.php','serverlist',1,'module_serverlist.jpg',0.1,0.1,0,'s:0:\"\";');
/*!40000 ALTER TABLE `cs_modules` ENABLE KEYS */;

--
-- Table structure for table `cs_news`
--

DROP TABLE IF EXISTS `cs_news`;
CREATE TABLE `cs_news` (
  `news_id` int(11) NOT NULL auto_increment,
  `news_title` varchar(255) NOT NULL,
  `news_body` text NOT NULL,
  `cat_id` tinyint(4) NOT NULL default '0',
  `user_id` int(11) unsigned NOT NULL default '0',
  `news_added` int(11) default NULL,
  `news_hidden` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`news_id`,`cat_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_news`
--


/*!40000 ALTER TABLE `cs_news` DISABLE KEYS */;
INSERT INTO `cs_news` VALUES (1,'testeintrag1','testbody1\r\n1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n10\r\ntestbody11',2,1,NULL,0);
/*!40000 ALTER TABLE `cs_news` ENABLE KEYS */;

--
-- Table structure for table `cs_news_comments`
--

DROP TABLE IF EXISTS `cs_news_comments`;
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
-- Dumping data for table `cs_news_comments`
--


/*!40000 ALTER TABLE `cs_news_comments` DISABLE KEYS */;
INSERT INTO `cs_news_comments` VALUES (1,1,1,'123','2005-07-29 13:04:07','','127.0.0.1','localhost'),(1,2,0,'1234567','2005-07-29 16:50:08','blub','127.0.0.1','localhost'),(2,0,0,'testeee','2006-03-04 02:25:42','test','127.0.0.1','localhost'),(2,0,0,'eee','2006-03-04 02:25:57','tester','127.0.0.1','localhost'),(3,0,1,'[center]test[/center]','2006-05-11 18:30:57','test','127.0.0.1','localhost');
/*!40000 ALTER TABLE `cs_news_comments` ENABLE KEYS */;

--
-- Table structure for table `cs_rights`
--

DROP TABLE IF EXISTS `cs_rights`;
CREATE TABLE `cs_rights` (
  `right_id` int(11) unsigned NOT NULL auto_increment,
  `area_id` int(11) NOT NULL default '0',
  `name` varchar(150) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`right_id`,`area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_rights`
--


/*!40000 ALTER TABLE `cs_rights` DISABLE KEYS */;
INSERT INTO `cs_rights` VALUES (11,5,'shoutbox_post','The right to post into the shoutbox'),(10,4,'access_acp','The right to access the ACP'),(12,6,'create_news','Add a News'),(13,7,'access_filebrowser','Access the filebrowser');
/*!40000 ALTER TABLE `cs_rights` ENABLE KEYS */;

--
-- Table structure for table `cs_serverlist`
--

DROP TABLE IF EXISTS `cs_serverlist`;
CREATE TABLE `cs_serverlist` (
  `server_id` int(5) default NULL,
  `ip` varchar(15) default NULL,
  `port` varchar(5) default NULL,
  `name` varchar(250) default NULL,
  `gametype` varchar(60) default NULL,
  `country` varchar(60) default NULL,
  UNIQUE KEY `server_id` (`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_serverlist`
--


/*!40000 ALTER TABLE `cs_serverlist` DISABLE KEYS */;
INSERT INTO `cs_serverlist` VALUES (1,'team-n1.com','27339','knd-squad DEATHMATCH powered by CLANSUITE DOT COM ','hl','germany'),(2,'212.227.132.154','28015',' .:[Berliner Gemetzel]:.:[CS 1.6]:.','hl','germany');
/*!40000 ALTER TABLE `cs_serverlist` ENABLE KEYS */;

--
-- Table structure for table `cs_session`
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
-- Dumping data for table `cs_session`
--


/*!40000 ALTER TABLE `cs_session` DISABLE KEYS */;
INSERT INTO `cs_session` VALUES (1,'f19052d0c74f342b8cceb408bf3c177b','client_ip|s:9:\"127.0.0.1\";client_browser|s:79:\"Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8) Gecko/20051111 Firefox/1.5\";client_host|s:9:\"localhost\";suiteSID|s:32:\"f19052d0c74f342b8cceb408bf3c177b\";user|a:11:{s:6:\"authed\";i:1;s:7:\"user_id\";s:1:\"1\";s:4:\"nick\";s:5:\"admin\";s:8:\"password\";s:40:\"d1ca11799e222d429424d47b424047002ea72d44\";s:5:\"email\";s:21:\"support@clansuite.com\";s:10:\"first_name\";s:13:\"Administrator\";s:9:\"last_name\";s:13:\"Administrator\";s:8:\"disabled\";s:1:\"0\";s:9:\"activated\";s:1:\"1\";s:6:\"groups\";a:1:{i:0;s:1:\"1\";}s:6:\"rights\";a:4:{s:10:\"access_acp\";i:1;s:13:\"shoutbox_post\";i:1;s:11:\"create_news\";i:1;s:18:\"access_filebrowser\";i:1;}}','suiteSID',1159349639,1,'admin');
/*!40000 ALTER TABLE `cs_session` ENABLE KEYS */;

--
-- Table structure for table `cs_shoutbox`
--

DROP TABLE IF EXISTS `cs_shoutbox`;
CREATE TABLE `cs_shoutbox` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `msg` tinytext NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_shoutbox`
--


/*!40000 ALTER TABLE `cs_shoutbox` DISABLE KEYS */;
INSERT INTO `cs_shoutbox` VALUES (1,'12345','123test@test.com','texttext',1155898254,'127.0.0.1'),(2,'109876','123@123.123','shoutboxtesttest',1155898254,'127.0.0.1'),(3,'asdfasdfs','asdfasdfasdfasdf@asdf.de','dafasdghafg',1156304492,'127.0.0.1'),(4,'asdfasdfs','asdfasdfasdfasdf@asdf.de','dafasdghafg',1156304492,'127.0.0.1'),(5,'asdfsadfasdfas','asdfasdf@asdf.de','asdfasdfasdf',1156305870,'127.0.0.1'),(6,'asdfasdfasdf','asdfasdfasdfasdf@asdf.de','asdfasdfasdf',1156306849,'127.0.0.1'),(7,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307074,'127.0.0.1'),(8,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307074,'127.0.0.1'),(9,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307091,'127.0.0.1'),(10,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307099,'127.0.0.1'),(11,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307100,'127.0.0.1'),(12,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307101,'127.0.0.1'),(13,'sdfsdfgdsfg','asdfasdfasdfasdf@asdf.de','ghfgghjghj',1156307307,'127.0.0.1'),(14,'asdfasdfasfd','ad@ad.de','adfasdfasdf',1156307578,'127.0.0.1'),(15,'sdfgsdfgsdfg','rrrrr@rrrrrr.de','rrrrrrrrrrrrrrrrrrrrrr',1156307696,'127.0.0.1'),(16,'test','test@test.de','i want to fuck you',1158356510,'127.0.0.1'),(17,'test','test@test.de','clansuite is just the best !!! you guys rock!!',1158356530,'127.0.0.1'),(18,'test','test@test.de','how are you? feeling well?',1158356544,'127.0.0.1'),(19,'test','test@test.de','next time this will be a chat :)',1158356601,'127.0.0.1'),(20,'Guest','test@test.de','you sure?',1158356786,'127.0.0.1');
/*!40000 ALTER TABLE `cs_shoutbox` ENABLE KEYS */;

--
-- Table structure for table `cs_static_pages`
--

DROP TABLE IF EXISTS `cs_static_pages`;
CREATE TABLE `cs_static_pages` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `html` text NOT NULL,
  `iframe` tinyint(1) NOT NULL default '0',
  `iframe_height` int(11) NOT NULL default '300',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_static_pages`
--


/*!40000 ALTER TABLE `cs_static_pages` DISABLE KEYS */;
INSERT INTO `cs_static_pages` VALUES (1,'credits','Those are the people who helped','','Clansuite <br />\r\nCredits<br />\r\n<u><strong><br />\r\n</strong></u><br />\r\n<br />\r\n<br />\r\n<table width=\"691\" height=\"393\" cellspacing=\"1\" cellpadding=\"1\" border=\"1\" align=\"\" summary=\"\">\r\n    <tbody>\r\n        <tr>\r\n            <td align=\"center\">Class</td>\r\n            <td align=\"center\">Author<br />\r\n            </td>\r\n            <td align=\"center\">&nbsp;Licence</td>\r\n        </tr>\r\n        <tr>\r\n            <td>tar.class.php</td>\r\n            <td>Vincent Blavet &lt;vincent@phpconcept.net&gt;<br />\r\n            Copyright (c) 1997-2003 The PHP Group <br />\r\n            </td>\r\n            <td>PHP license v3</td>\r\n        </tr>\r\n        <tr>\r\n            <td>PEAR, the PHP Extension and Application Repository</td>\r\n            <td>Sterling Hughes &lt;sterling@php.net&gt;<br />\r\n            Stig Bakken &lt;ssb@php.net&gt;<br />\r\n            Tomas V.V.Cox &lt;cox@idecnet.com&gt;<br />\r\n            Greg Beaver &lt;cellog@php.net&gt;<br />\r\n            &nbsp;Copyright&nbsp; 1997-2006 The PHP Group</td>\r\n            <td>PHP license v3</td>\r\n        </tr>\r\n        <tr>\r\n            <td>Swift Mailer: A Flexible PHP Mailer Class</td>\r\n            <td>&quot;Chris Corbyn&quot; &lt;chris@w3style.co.uk&gt;<br />\r\n            Copyright 2006 Chris Corbyn</td>\r\n            <td>LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Smarty: the PHP compiling template engine</td>\r\n            <td valign=\"top\">Monte Ohrt &lt;monte at ohrt dot com&gt;<br />\r\n            Andrei Zmievski &lt;andrei@php.net&gt;<br />\r\n            Copyright 2001-2005 New Digital Group, Inc.</td>\r\n            <td valign=\"top\">LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Sajax : cross-platform, cross-browser web scripting toolkit</td>\r\n            <td valign=\"top\">Copyright 2005-2006 modernmethod</td>\r\n            <td valign=\"top\">BSD</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Imagemanger</td>\r\n            <td valign=\"top\">Xiang Wei ZHUO &lt;wei@zhuo.org&gt;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">DHTML Calendar Javascript</td>\r\n            <td valign=\"top\">Copyright Mihai Bazon, 2002-2005</td>\r\n            <td valign=\"top\">LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Tab Pane Javascript</td>\r\n            <td valign=\"top\">Copyright (c) 2002, 2003, 2006 Erik Arvidsson</td>\r\n            <td valign=\"top\">Apache License v2</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\"><a href=\"http://www.fckeditor.net/\">FCKEditor</a>- WYSIWYG</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Icons by <a href=\"http://www.famfamfam.com/lab/icons/\">famfamfam</a></td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">mygosumenu\'s</td>\r\n            <td valign=\"top\">Copyright 2003,2004 Cezary Tomczak</td>\r\n            <td valign=\"top\">BSD</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Bitstream Vera Fonts </td>\r\n            <td valign=\"top\">Copyright (c) 2003 by Bitstream, Inc.</td>\r\n            <td valign=\"top\">own</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />',1,300),(2,'google','Google','http://www.google.de','',1,500),(3,'help','The help for ClanSuite','','<strong><font size=\"4\">Help</font><br />\r\n<br />\r\n</strong><strong> - gogo<br />\r\n- gogogogo<br />\r\n- gogogogogogo</strong>',1,300),(4,'manual','The Manual','','<font size=\"4\">Manual</font><br />\r\n<br />\r\n- some content',1,300),(5,'about','About ClanSuite','','<font size=\"4\">About</font><br />\r\n<br />\r\n- some content',1,300);
/*!40000 ALTER TABLE `cs_static_pages` ENABLE KEYS */;

--
-- Table structure for table `cs_submodules`
--

DROP TABLE IF EXISTS `cs_submodules`;
CREATE TABLE `cs_submodules` (
  `submodule_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`submodule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_submodules`
--


/*!40000 ALTER TABLE `cs_submodules` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_submodules` ENABLE KEYS */;

--
-- Table structure for table `cs_user_group`
--

DROP TABLE IF EXISTS `cs_user_group`;
CREATE TABLE `cs_user_group` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `group_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_user_group`
--


/*!40000 ALTER TABLE `cs_user_group` DISABLE KEYS */;
INSERT INTO `cs_user_group` VALUES (1,1),(2,1),(2,2);
/*!40000 ALTER TABLE `cs_user_group` ENABLE KEYS */;

--
-- Table structure for table `cs_user_right`
--

DROP TABLE IF EXISTS `cs_user_right`;
CREATE TABLE `cs_user_right` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `right_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_user_right`
--


/*!40000 ALTER TABLE `cs_user_right` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_user_right` ENABLE KEYS */;

--
-- Table structure for table `cs_users`
--

DROP TABLE IF EXISTS `cs_users`;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_users`
--


/*!40000 ALTER TABLE `cs_users` DISABLE KEYS */;
INSERT INTO `cs_users` VALUES (1,'support@clansuite.com','admin','d1ca11799e222d429424d47b424047002ea72d44','','',0,0,'Administrator','Administrator','I\'m the admin',0,1),(3,'test@test.de','test','974c2e9429ade22627f12ecb4b400f224474dfd0','','',1158144934,0,'test','test','test',0,1);
/*!40000 ALTER TABLE `cs_users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

