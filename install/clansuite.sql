-- MySQL dump 10.10
--
-- Host: localhost    Database: clansuite
-- ------------------------------------------------------
-- Server version	5.0.20-community

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
LOCK TABLES `cs_admin_shortcuts` WRITE;
INSERT INTO `cs_admin_shortcuts` VALUES (1,'Console','index.php?mod=admin&sub=console','console.png',45),(2,'Downloads','index.php?mod=admin&sub=downloads','downloads.png',30),(3,'Articles','index.php?mod=admin&sub=articles','articles.png',30),(4,'Links','index.php?mod=admin&sub=links','links.png',30),(5,'Calendar','index.php?mod=admin&sub=calendar','calendar.png',30),(6,'Time','index.php?mod=admin&sub=time','time.png',30),(7,'Email','index.php?mod=admin&sub=email','email.png',3),(8,'Shoutbox','index.php?mod=admin&sub=shoutbox','shoutbox.png',30),(9,'Help','index.php?mod=admin&sub=help','help.png',40),(10,'Security','index.php?mod=admin&sub=security','security.png',41),(11,'Gallery','index.php?mod=admin&sub=gallery','gallery.png',30),(12,'System','index.php?mod=admin&sub=system','system.png',42),(13,'Replays','index.php?mod=admin&sub=replays','replays.png',30),(14,'News','index.php?mod=admin&sub=news','news.png',2),(15,'Settings','index.php?mod=admin&sub=settings','settings.png',43),(16,'Users','index.php?mod=admin&sub=users','users.png',1),(17,'Backup','index.php?mod=admin&sub=backup','backup.png',44),(18,'Templates','index.php?mod=admin&sub=templates','templates.png',4),(19,'Groups','index.php?mod=admin&sub=groups','groups.png',2),(20,'Search','index.php?mod=admin&sub=search','search.png',30);
UNLOCK TABLES;
/*!40000 ALTER TABLE `cs_admin_shortcuts` ENABLE KEYS */;

--
-- Table structure for table `cs_adminmenu`
--

DROP TABLE IF EXISTS `cs_adminmenu`;
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
-- Dumping data for table `cs_adminmenu`
--


/*!40000 ALTER TABLE `cs_adminmenu` DISABLE KEYS */;
LOCK TABLES `cs_adminmenu` WRITE;
INSERT INTO `cs_adminmenu` VALUES (1,0,'folder','Home','index.php?mod=admin','Home','_self',0,''),(2,0,'folder','Modules','','Modules','_self',1,''),(3,2,'folder','Articles','','Articles','_self',0,'report.png'),(4,2,'folder','Downloads','','Downloads','_self',1,'disk.png'),(5,2,'folder','eMail','','eMail','_self',2,'email_open_image.png'),(6,2,'folder','Forum','','Forum','_self',3,'application_view_list.png'),(7,2,'folder','Gallery','','Gallery','_self',4,'map_go.png'),(8,2,'folder','News','','News','_self',5,'page_edit.png'),(9,2,'folder','Replays','','Replays','_self',6,'film.png'),(10,2,'folder','Shoutbox','','Shoutbox','_self',7,'comment.png'),(11,2,'folder','Static Pages','','Static Pages','_self',8,'html.png'),(12,11,'item','Create','index.php?mod=admin&sub=static&action=create','Create','_self',0,'add.png'),(13,11,'item','Edit','index.php?mod=admin&sub=static&action=list_all','Edit','_self',1,'pencil.png'),(14,0,'folder','System','','System','_self',2,''),(15,14,'item','Settings','index.php?mod=admin&sub=config','Settings','_self',0,'settings.png'),(16,14,'folder','Modules','','Modules','_self',1,'bricks.png'),(17,16,'item','Install new modules','index.php?mod=admin&sub=modules&action=install_new','Install new modules','_self',0,'package.png'),(18,16,'folder','Development','','Development','_self',1,'application_xp_terminal.png'),(19,18,'item','Create a module','index.php?mod=admin&sub=modules&action=create_new','Create a new module','_self',0,'add.png'),(20,18,'item','Export a module','index.php?mod=admin&sub=modules&action=export','Export a module','_self',1,'compress.png'),(21,18,'item','Edit modules','index.php?mod=admin&sub=modules&action=show_all','Edit modules','_self',2,'bricks_edit.png'),(22,14,'folder','Development','','Development','_self',2,'application_xp_terminal.png'),(23,22,'item','Template Editor','index.php?mod=admin&sub=templates','Template Editor','_self',0,'layout_edit.png'),(24,22,'item','Adminmenu Editor','index.php?mod=admin&sub=menueditor','Adminmenu Editor','_self',1,'application_form_edit.png'),(25,0,'folder','Administration','','Administration','_self',3,''),(26,25,'folder','Users','','Users','_self',0,'user_suit.png'),(27,26,'item','Show all Users','index.php?mod=admin&sub=users','Show all Users','_self',0,'table.png'),(28,26,'item','Search a User','index.php?mod=admin&sub=users&action=search','Search a User','_self',2,'magnifier.png'),(29,25,'folder','Groups','','Groups','_self',1,'group.png'),(30,29,'item','Show all Groups','index.php?mod=admin&sub=groups','Show all Groups','_self',0,'table.png'),(31,29,'item','Create a group','index.php?mod=admin&sub=groups&action=create','Create a group','_self',1,'add.png'),(32,25,'folder','Permissions','','Permissions','_self',2,'key.png'),(33,32,'item','Show all Permissions','index.php?mod=admin&sub=permissions','Show all Permissions','_self',0,'table.png'),(34,32,'item','Create a Permission','index.php?mod=admin&sub=permissions&action=create','Create a Permission','_self',1,'add.png'),(35,25,'folder','Templates','','Templates','_self',3,'layout_header.png'),(36,0,'folder','Help','','Help','_self',4,''),(37,36,'item','Help','index.php?mod=admin&sub=static&action=show&page=help','Help','_self',0,'help.png'),(38,36,'item','Manual','index.php?mod=admin&sub=manual','Manual','_self',1,'book_open.png'),(39,36,'item','Report Bugs & Give Feedback','index.php?mod=admin&sub=bugs','Report Bugs & Give Feedback','_self',2,'error.png'),(40,36,'item','About Clansuite','index.php?mod=admin&sub=static&action=show&page=about','About Clansuite','_self',3,'information.png'),(41,26,'item','Create a user','index.php?mod=admin&sub=users&action=create','Create a user','_self',1,'add.png');
UNLOCK TABLES;
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
LOCK TABLES `cs_adminmenu_old` WRITE;
INSERT INTO `cs_adminmenu_old` VALUES (1,0,'folder','Home','index.php?mod=admin','Home','_self',0,''),(2,0,'folder','Modules','','Modules','_self',1,''),(3,2,'folder','Articles','','Articles','_self',0,'report.png'),(4,2,'folder','Downloads','','Downloads','_self',1,'disk.png'),(5,2,'folder','eMail','','eMail','_self',2,'email_open_image.png'),(6,2,'folder','Forum','','Forum','_self',3,'application_view_list.png'),(7,2,'folder','Gallery','','Gallery','_self',4,'map_go.png'),(8,2,'folder','News','','News','_self',5,'page_edit.png'),(9,2,'folder','Replays','','Replays','_self',6,'film.png'),(10,2,'folder','Shoutbox','','Shoutbox','_self',7,'comment.png'),(11,2,'folder','Static Pages','','Static Pages','_self',8,'html.png'),(12,11,'item','Create','index.php?mod=admin&sub=static&action=create','Create','_self',0,'add.png'),(13,11,'item','Edit','index.php?mod=admin&sub=static&action=list_all','Edit','_self',1,'pencil.png'),(14,0,'folder','System','','System','_self',2,''),(15,14,'item','Settings','index.php?mod=admin&sub=config','Settings','_self',0,'settings.png'),(16,14,'folder','Modules','','Modules','_self',1,'bricks.png'),(17,16,'item','Install new modules','index.php?mod=admin&sub=modules&action=install_new','Install new modules','_self',0,'package.png'),(18,16,'folder','Development','','Development','_self',1,'application_xp_terminal.png'),(19,18,'item','Create a module','index.php?mod=admin&sub=modules&action=create_new','Create a new module','_self',0,'add.png'),(20,18,'item','Export a module','index.php?mod=admin&sub=modules&action=export','Export a module','_self',1,'compress.png'),(21,18,'item','Edit modules','index.php?mod=admin&sub=modules&action=show_all','Edit modules','_self',2,'bricks_edit.png'),(22,14,'folder','Development','','Development','_self',2,'application_xp_terminal.png'),(23,22,'item','Template Editor','index.php?mod=admin&sub=templates','Template Editor','_self',0,'layout_edit.png'),(24,22,'item','Adminmenu Editor','index.php?mod=admin&sub=menueditor','Adminmenu Editor','_self',1,'application_form_edit.png'),(25,0,'folder','Administration','','Administration','_self',3,''),(26,25,'folder','Users','','Users','_self',0,'user_suit.png'),(27,26,'item','Show all Users','index.php?mod=admin&sub=users','Show all Users','_self',0,'table.png'),(28,26,'item','Search a User','index.php?mod=admin&sub=users&action=search','Search a User','_self',1,'magnifier.png'),(29,25,'folder','Groups','','Groups','_self',1,'group.png'),(30,29,'item','Show all Groups','index.php?mod=admin&sub=groups','Show all Groups','_self',0,'table.png'),(31,25,'folder','Permissions','','Permissions','_self',2,'key.png'),(32,31,'item','Show all Permissions','index.php?mod=admin&sub=permissions','Show all Permissions','_self',0,'table.png'),(33,25,'folder','Templates','','Templates','_self',3,'layout_header.png'),(34,0,'folder','Help','','Help','_self',4,''),(35,34,'item','Help','index.php?mod=admin&sub=static&action=show&page=help','Help','_self',0,'help.png'),(36,34,'item','Manual','index.php?mod=admin&sub=manual','Manual','_self',1,'book_open.png'),(37,34,'item','Report Bugs & Give Feedback','index.php?mod=admin&sub=bugs','Report Bugs & Give Feedback','_self',2,'error.png'),(38,34,'item','About Clansuite','index.php?mod=admin&sub=static&action=show&page=about','About Clansuite','_self',3,'information.png'),(39,29,'item','Create a group','index.php?mod=admin&sub=groups&action=create','Create a group','_self',1,'add.png'),(40,31,'item','Create a Permission','index.php?mod=admin&sub=permissions&action=create','Create a Permission','_self',1,'add.png');
UNLOCK TABLES;
/*!40000 ALTER TABLE `cs_adminmenu_old` ENABLE KEYS */;

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
LOCK TABLES `cs_category` WRITE;
INSERT INTO `cs_category` VALUES (1,'newso',NULL,'keine','','Diese News sind keiner Kategorie zugeordnet'),(2,'news',NULL,'Allgemein','/images/allgemein.gif','Allgemein'),(3,'news',NULL,'Member','/images/news/member.gif','Thema Member'),(4,'news',NULL,'Page','/images/news/page.gif',' Thema Page'),(5,'news',NULL,'IRC','/images/news/irc.gif',' Thema IRC gehört'),(6,'news',NULL,'Clan-Wars','/images/news/clanwars.gif','Thema Matches'),(7,'news',NULL,'Sonstiges','/images/news/sonstiges.gif','alles'),(8,'news',NULL,'LAN','','lan'),(10,'/design',NULL,'good night',NULL,'0');
UNLOCK TABLES;
/*!40000 ALTER TABLE `cs_category` ENABLE KEYS */;

--
-- Table structure for table `cs_group_rights`
--

DROP TABLE IF EXISTS `cs_group_rights`;
CREATE TABLE `cs_group_rights` (
  `group_id` int(5) unsigned zerofill NOT NULL default '00000',
  `right_id` int(5) unsigned zerofill NOT NULL default '00000',
  `right_pos` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`group_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_group_rights`
--


/*!40000 ALTER TABLE `cs_group_rights` DISABLE KEYS */;
LOCK TABLES `cs_group_rights` WRITE;
INSERT INTO `cs_group_rights` VALUES (00001,00001,1),(00002,00002,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `cs_group_rights` ENABLE KEYS */;

--
-- Table structure for table `cs_groups`
--

DROP TABLE IF EXISTS `cs_groups`;
CREATE TABLE `cs_groups` (
  `group_id` int(5) unsigned NOT NULL auto_increment,
  `pos` int(4) unsigned NOT NULL default '1',
  `name` varchar(75) default NULL,
  `description` varchar(255) NOT NULL,
  `icon` varchar(255) default NULL,
  `image` varchar(255) NOT NULL,
  `color` varchar(7) NOT NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_groups`
--


/*!40000 ALTER TABLE `cs_groups` DISABLE KEYS */;
LOCK TABLES `cs_groups` WRITE;
INSERT INTO `cs_groups` VALUES (1,1,'Administrator','asdfasfd','1star.gif','4star.gif','#CC0000'),(2,2,'Newsadministration','Administration of Modul: News','2star.gif','5star.gif','#3333CC'),(3,3,'Guestsbook Administration','Administration of Modul: Guestbook','3star.gif','5star.gif','#3333CC');
UNLOCK TABLES;
/*!40000 ALTER TABLE `cs_groups` ENABLE KEYS */;

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
LOCK TABLES `cs_modules` WRITE;
INSERT INTO `cs_modules` VALUES (77,'account','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Account Administration','This module handles all necessary account stuff - like login/logout etc.','module_account','account.module.php','account',1,'module_account.jpg',0.1,0,1,'s:0:\"\";'),(79,'captcha','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Captcha Module','The captcha module presents a image only humanoids can read.','module_captcha','captcha.module.php','captcha',1,'module_captcha.jpg',0.1,0,1,'s:0:\"\";'),(80,'index','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Index Module','This is the main site.','module_index','index.module.php','index',1,'module_index.jpg',0.1,0,1,'s:0:\"\";'),(78,'admin','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Admin Interface','This is the Admin Control Panel','module_admin','admin.module.php','admin',1,'module_admin.jpg',0.1,0,1,'a:12:{s:7:\"configs\";a:2:{i:0;s:18:\"configs.module.php\";i:1;s:20:\"module_admin_configs\";}s:7:\"modules\";a:2:{i:0;s:18:\"modules.module.php\";i:1;s:20:\"module_admin_modules\";}s:5:\"users\";a:2:{i:0;s:16:\"users.module.php\";i:1;s:18:\"module_admin_users\";}s:10:\"usercenter\";a:2:{i:0;s:21:\"usercenter.module.php\";i:1;s:23:\"module_admin_usercenter\";}s:6:\"groups\";a:2:{i:0;s:17:\"groups.module.php\";i:1;s:19:\"module_admin_groups\";}s:11:\"permissions\";a:2:{i:0;s:16:\"perms.module.php\";i:1;s:24:\"module_admin_permissions\";}s:10:\"menueditor\";a:2:{i:0;s:21:\"menueditor.module.php\";i:1;s:23:\"module_admin_menueditor\";}s:6:\"static\";a:2:{i:0;s:17:\"static.module.php\";i:1;s:19:\"module_admin_static\";}s:4:\"bugs\";a:2:{i:0;s:15:\"bugs.module.php\";i:1;s:17:\"module_admin_bugs\";}s:6:\"manual\";a:2:{i:0;s:17:\"manual.module.php\";i:1;s:19:\"module_admin_manual\";}s:9:\"templates\";a:2:{i:0;s:20:\"templates.module.php\";i:1;s:22:\"module_admin_templates\";}s:8:\"settings\";a:2:{i:0;s:19:\"settings.module.php\";i:1;s:21:\"module_admin_settings\";}}'),(83,'static','Jens-André Koch,Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Static Pages','Static Pages store HTML content','module_static','static.module.php','static',1,'module_static.jpg',0.1,0,0,'s:0:\"\";'),(86,'shoutbox','Björn Spiegel','http://www.clansuite.com','GPL v2','Clansuite Group','Shoutbox Modul','This module displays a shoutbox. You can do entries and administrate it ...','module_shoutbox','shoutbox.module.php','shoutbox',1,'module_shoutbox.jpg',0.1,0,0,'s:0:\"\";'),(88,'news','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','News','News module','module_news','news.module.php','news',1,'module_news.jpg',0.1,0,0,'s:0:\"\";');
UNLOCK TABLES;
/*!40000 ALTER TABLE `cs_modules` ENABLE KEYS */;

--
-- Table structure for table `cs_news`
--

DROP TABLE IF EXISTS `cs_news`;
CREATE TABLE `cs_news` (
  `news_id` int(11) NOT NULL auto_increment,
  `news_title` varchar(255) NOT NULL,
  `news_body` text NOT NULL,
  `news_category` tinyint(4) NOT NULL default '0',
  `user_id` int(11) unsigned NOT NULL default '0',
  `news_added` int(11) default NULL,
  `news_hidden` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`news_id`,`news_category`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_news`
--


/*!40000 ALTER TABLE `cs_news` DISABLE KEYS */;
LOCK TABLES `cs_news` WRITE;
INSERT INTO `cs_news` VALUES (1,'testeintrag1','testbody1\r\n1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n10\r\ntestbody11',1,1,NULL,0);
UNLOCK TABLES;
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
LOCK TABLES `cs_news_comments` WRITE;
INSERT INTO `cs_news_comments` VALUES (1,1,1,'123','2005-07-29 13:04:07','','127.0.0.1','localhost'),(1,2,0,'1234567','2005-07-29 16:50:08','blub','127.0.0.1','localhost'),(2,0,0,'testeee','2006-03-04 02:25:42','test','127.0.0.1','localhost'),(2,0,0,'eee','2006-03-04 02:25:57','tester','127.0.0.1','localhost'),(3,0,1,'[center]test[/center]','2006-05-11 18:30:57','test','127.0.0.1','localhost');
UNLOCK TABLES;
/*!40000 ALTER TABLE `cs_news_comments` ENABLE KEYS */;

--
-- Table structure for table `cs_rights`
--

DROP TABLE IF EXISTS `cs_rights`;
CREATE TABLE `cs_rights` (
  `right_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_rights`
--


/*!40000 ALTER TABLE `cs_rights` DISABLE KEYS */;
LOCK TABLES `cs_rights` WRITE;
INSERT INTO `cs_rights` VALUES (1,'Settings-Edit',''),(2,'News-Edit123','Description of News'),(3,'User-Add',''),(4,'test1','test2');
UNLOCK TABLES;
/*!40000 ALTER TABLE `cs_rights` ENABLE KEYS */;

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
LOCK TABLES `cs_session` WRITE;
INSERT INTO `cs_session` VALUES (0,'4ec11651f4d2b898a45bf6a1f5b89ea4','client_ip|s:9:\"127.0.0.1\";client_browser|s:87:\"Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8.0.6) Gecko/20060728 Firefox/1.5.0.6\";client_host|s:9:\"localhost\";suiteSID|s:32:\"4ec11651f4d2b898a45bf6a1f5b89ea4\";user|a:9:{s:6:\"authed\";i:0;s:7:\"user_id\";i:0;s:4:\"nick\";s:4:\"Gast\";s:8:\"password\";s:0:\"\";s:5:\"email\";s:0:\"\";s:10:\"first_name\";s:7:\"Vorname\";s:9:\"last_name\";s:8:\"Nachname\";s:8:\"disabled\";s:0:\"\";s:9:\"activated\";s:0:\"\";}','suiteSID',1157808169,1,'admin');
UNLOCK TABLES;
/*!40000 ALTER TABLE `cs_session` ENABLE KEYS */;

--
-- Table structure for table `cs_shoutbox`
--

DROP TABLE IF EXISTS `cs_shoutbox`;
CREATE TABLE `cs_shoutbox` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) collate latin1_general_ci NOT NULL,
  `mail` varchar(100) collate latin1_general_ci NOT NULL,
  `msg` tinytext collate latin1_general_ci NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `ip` varchar(15) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `cs_shoutbox`
--


/*!40000 ALTER TABLE `cs_shoutbox` DISABLE KEYS */;
LOCK TABLES `cs_shoutbox` WRITE;
INSERT INTO `cs_shoutbox` VALUES (1,'12345','123test@test.com','texttext',1155898254,'127.0.0.1'),(2,'109876','123@123.123','shoutboxtesttest',1155898254,'127.0.0.1'),(3,'asdfasdfs','asdfasdfasdfasdf@asdf.de','dafasdghafg',1156304492,'127.0.0.1'),(4,'asdfasdfs','asdfasdfasdfasdf@asdf.de','dafasdghafg',1156304492,'127.0.0.1'),(5,'asdfsadfasdfas','asdfasdf@asdf.de','asdfasdfasdf',1156305870,'127.0.0.1'),(6,'asdfasdfasdf','asdfasdfasdfasdf@asdf.de','asdfasdfasdf',1156306849,'127.0.0.1'),(7,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307074,'127.0.0.1'),(8,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307074,'127.0.0.1'),(9,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307091,'127.0.0.1'),(10,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307099,'127.0.0.1'),(11,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307100,'127.0.0.1'),(12,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307101,'127.0.0.1'),(13,'sdfsdfgdsfg','asdfasdfasdfasdf@asdf.de','ghfgghjghj',1156307307,'127.0.0.1'),(14,'asdfasdfasfd','ad@ad.de','adfasdfasdf',1156307578,'127.0.0.1'),(15,'sdfgsdfgsdfg','rrrrr@rrrrrr.de','rrrrrrrrrrrrrrrrrrrrrr',1156307696,'127.0.0.1');
UNLOCK TABLES;
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
LOCK TABLES `cs_static_pages` WRITE;
INSERT INTO `cs_static_pages` VALUES (1,'credits','Those are the people who helped','','Clansuite <br />\r\nCredits<br />\r\n<u><strong><br />\r\n</strong></u><br />\r\n<br />\r\n<br />\r\n<table width=\"691\" height=\"393\" cellspacing=\"1\" cellpadding=\"1\" border=\"1\" align=\"\" summary=\"\">\r\n    <tbody>\r\n        <tr>\r\n            <td align=\"center\">Class</td>\r\n            <td align=\"center\">Author<br />\r\n            </td>\r\n            <td align=\"center\">&nbsp;Licence</td>\r\n        </tr>\r\n        <tr>\r\n            <td>tar.class.php</td>\r\n            <td>Vincent Blavet &lt;vincent@phpconcept.net&gt;<br />\r\n            Copyright (c) 1997-2003 The PHP Group <br />\r\n            </td>\r\n            <td>PHP license v3</td>\r\n        </tr>\r\n        <tr>\r\n            <td>PEAR, the PHP Extension and Application Repository</td>\r\n            <td>Sterling Hughes &lt;sterling@php.net&gt;<br />\r\n            Stig Bakken &lt;ssb@php.net&gt;<br />\r\n            Tomas V.V.Cox &lt;cox@idecnet.com&gt;<br />\r\n            Greg Beaver &lt;cellog@php.net&gt;<br />\r\n            &nbsp;Copyright&nbsp; 1997-2006 The PHP Group</td>\r\n            <td>PHP license v3</td>\r\n        </tr>\r\n        <tr>\r\n            <td>Swift Mailer: A Flexible PHP Mailer Class</td>\r\n            <td>&quot;Chris Corbyn&quot; &lt;chris@w3style.co.uk&gt;<br />\r\n            Copyright 2006 Chris Corbyn</td>\r\n            <td>LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Smarty: the PHP compiling template engine</td>\r\n            <td valign=\"top\">Monte Ohrt &lt;monte at ohrt dot com&gt;<br />\r\n            Andrei Zmievski &lt;andrei@php.net&gt;<br />\r\n            Copyright 2001-2005 New Digital Group, Inc.</td>\r\n            <td valign=\"top\">LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Sajax : cross-platform, cross-browser web scripting toolkit</td>\r\n            <td valign=\"top\">Copyright 2005-2006 modernmethod</td>\r\n            <td valign=\"top\">BSD</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Imagemanger</td>\r\n            <td valign=\"top\">Xiang Wei ZHUO &lt;wei@zhuo.org&gt;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">DHTML Calendar Javascript</td>\r\n            <td valign=\"top\">Copyright Mihai Bazon, 2002-2005</td>\r\n            <td valign=\"top\">LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Tab Pane Javascript</td>\r\n            <td valign=\"top\">Copyright (c) 2002, 2003, 2006 Erik Arvidsson</td>\r\n            <td valign=\"top\">Apache License v2</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\"><a href=\"http://www.fckeditor.net/\">FCKEditor</a>- WYSIWYG</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Icons by <a href=\"http://www.famfamfam.com/lab/icons/\">famfamfam</a></td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">mygosumenu\'s</td>\r\n            <td valign=\"top\">Copyright 2003,2004 Cezary Tomczak</td>\r\n            <td valign=\"top\">BSD</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Bitstream Vera Fonts </td>\r\n            <td valign=\"top\">Copyright (c) 2003 by Bitstream, Inc.</td>\r\n            <td valign=\"top\">own</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />',1,300),(2,'google','Google','http://www.google.de','',1,500),(3,'help','The help for ClanSuite','','<strong><font size=\"4\">Help</font><br />\r\n<br />\r\n</strong><strong> - gogo<br />\r\n- gogogogo<br />\r\n- gogogogogogo</strong>',1,300),(4,'manual','The Manual','','<font size=\"4\">Manual</font><br />\r\n<br />\r\n- some content',1,300),(5,'about','About ClanSuite','','<font size=\"4\">About</font><br />\r\n<br />\r\n- some content',1,300);
UNLOCK TABLES;
/*!40000 ALTER TABLE `cs_static_pages` ENABLE KEYS */;

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
LOCK TABLES `cs_user_group` WRITE;
INSERT INTO `cs_user_group` VALUES (1,1),(1,2),(1,3),(2,3);
UNLOCK TABLES;
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
LOCK TABLES `cs_user_right` WRITE;
INSERT INTO `cs_user_right` VALUES (1,1),(1,3),(2,2),(2,3);
UNLOCK TABLES;
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
LOCK TABLES `cs_users` WRITE;
INSERT INTO `cs_users` VALUES (2,'asdf2@bla.de','bla','27b276d6221741f11b727e0c24979470f2a7b90a','','66a147b49d97ad7df250b0dd91f6d930',1152208688,0,'','','',0,1),(1,'admin@localhost.de','admin','26a1102e42022f67a17add9ab0e74c9440efa7d2','26a1102e42022f67a17add9ab0e74c9440efa7d2','9dd90802013c886ccdd04d524adf3446',1152190495,0,'','','',0,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `cs_users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

