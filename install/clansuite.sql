-- MySQL dump 10.10
--
-- Host: localhost    Database: clansuite
-- ------------------------------------------------------
-- Server version	5.0.24a-community

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
INSERT INTO `cs_adminmenu` VALUES (2,0,'folder','Modules','','Modules','_self',1,'package.png'),(3,2,'folder','News','','News','_self',0,'page_edit.png'),(4,3,'item','Manage News','index.php?mod=news&sub=admin','Manage News','_self',0,'application_form_edit.png'),(5,2,'folder','Articles','','Articles','_self',1,'report.png'),(6,2,'folder','Static Pages','','Static Pages','_self',2,'html.png'),(7,6,'item','Create new Static Page','index.php?mod=static&sub=admin&action=create','Create new Static Page','_self',0,'add.png'),(8,6,'item','Show Static Pages','index.php?mod=static&sub=admin','Show Static Pages','_self',1,'pencil.png'),(9,2,'folder','Forum','','Forum','_self',3,'application_view_list.png'),(10,2,'folder','Guestbook','index.php?mod=guestbook&action=show','Guestbook','_self',4,'book_open.png'),(11,2,'folder','Matches','index.php?mod=matches&action=show','Matches','_self',5,'database_go.png'),(12,2,'folder','Serverlist','','Serverlist','_self',6,'table.png'),(13,12,'item','Show Servers','index.php?mod=serverlist&sub=admin&action=show','Show Servers','_self',0,'application_view_list.png'),(14,12,'item','Add Server','index.php?mod=serverlist&sub=admin&action=create','Add Server','_self',1,'application_form_edit.png'),(15,2,'folder','Shoutbox','','Shoutbox','_self',7,'comment.png'),(16,2,'folder','Downloads','','Downloads','_self',8,'disk.png'),(17,2,'folder','Gallery','','Gallery','_self',9,'map_go.png'),(18,2,'folder','Replays','','Replays','_self',10,'film.png'),(19,2,'folder','eMail','','eMail','_self',11,'email_open_image.png'),(20,0,'folder','Administration','','Administration','_self',2,'textfield_key.png'),(21,20,'folder','Users','','Users','_self',0,'user_suit.png'),(22,21,'item','Show all Users','index.php?mod=admin&sub=users','Show all Users','_self',0,'table.png'),(23,21,'item','Create a user','index.php?mod=admin&sub=users&action=create','Create a user','_self',1,'add.png'),(24,21,'item','Search a User','index.php?mod=admin&sub=users&action=search','Search a User','_self',2,'magnifier.png'),(25,20,'folder','Groups','','Groups','_self',1,'group.png'),(26,25,'item','Show all Groups','index.php?mod=admin&sub=groups','Show all Groups','_self',0,'table.png'),(27,25,'item','Create a group','index.php?mod=admin&sub=groups&action=create','Create a group','_self',1,'add.png'),(28,20,'folder','Permissions','','Permissions','_self',2,'key.png'),(29,28,'item','Show all Permissions','index.php?mod=admin&sub=permissions','Show all Permissions','_self',0,'table.png'),(30,20,'item','Categories','index.php?mod=admin&sub=categories','Categories','_self',3,'spellcheck.png'),(31,20,'folder','Layout & Styles','','Layout & Styles','_self',4,'layout_header.png'),(32,31,'item','BB Code Editor','index.php?mod=admin&sub=bbcode','BB Code Editor','_self',0,'text_bold.png'),(33,31,'item','Adminmenu Editor','index.php?mod=admin&sub=menueditor','Adminmenu Editor','_self',1,'application_form_edit.png'),(34,31,'item','Template Editor','index.php?mod=admin&sub=templates','Template Editor','_self',2,'layout_edit.png'),(35,0,'folder','System','','System','_self',3,'computer.png'),(36,35,'item','Settings','index.php?mod=admin&sub=settings','Settings','_self',0,'settings.png'),(37,35,'folder','Database','','Database','_self',1,'database_gear.png'),(38,37,'item','Optimize','index.php?mod=database&action=optimize','Optimize','_self',0,'database_go.png'),(39,37,'item','Backup','index.php?mod=database&action=backup','Backup','_self',1,'database_key.png'),(40,35,'folder','Modules','','Modules','_self',2,'bricks.png'),(41,40,'item','Install new modules','index.php?mod=admin&sub=modules&action=install_new','Install new modules','_self',0,'package.png'),(42,40,'item','Create a module/submodule','index.php?mod=admin&sub=modules&action=create_new','Create a new module/submodule','_self',1,'add.png'),(43,40,'item','Show and edit modules','index.php?mod=admin&sub=modules&action=show_all','Show and edit modules','_self',2,'bricks_edit.png'),(44,40,'item','Export a module','index.php?mod=admin&sub=modules&action=export','Export a module','_self',3,'compress.png'),(45,35,'folder','Language','','Language','_self',3,'spellcheck.png'),(46,45,'item','Language Editor','index.php?mod=language&sub=editor','Language Editor','_self',0,'spellcheck.png'),(47,0,'folder','Help','','Help','_self',4,'help.png'),(48,47,'item','Help','index.php?mod=admin&sub=static&action=show&page=help','Help','_self',0,'help.png'),(49,47,'item','Manual','index.php?mod=admin&sub=manual','Manual','_self',1,'book_open.png'),(50,47,'item','Report Bugs & Give Feedback','index.php?mod=admin&sub=bugs','Report Bugs & Give Feedback','_self',2,'error.png'),(51,47,'item','About Clansuite','index.php?mod=admin&sub=static&action=show&page=about','About Clansuite','_self',3,'information.png'),(52,0,'folder','Main','http://www.clansuite.com/index.php?mod=admin','Main','_self',0,'');
/*!40000 ALTER TABLE `cs_adminmenu` ENABLE KEYS */;

--
-- Table structure for table `cs_adminmenu_backup`
--

DROP TABLE IF EXISTS `cs_adminmenu_backup`;
CREATE TABLE `cs_adminmenu_backup` (
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
-- Dumping data for table `cs_adminmenu_backup`
--


/*!40000 ALTER TABLE `cs_adminmenu_backup` DISABLE KEYS */;
INSERT INTO `cs_adminmenu_backup` VALUES (1,0,'item','Main','http://www.clansuite.com/index.php?mod=admin','Main','_self',0,''),(2,0,'folder','Modules','','Modules','_self',1,'package.png'),(3,2,'folder','News','','News','_self',0,'page_edit.png'),(4,3,'item','Manage News','index.php?mod=news&sub=admin','Manage News','_self',0,'application_form_edit.png'),(5,2,'folder','Articles','','Articles','_self',1,'report.png'),(6,2,'folder','Static Pages','','Static Pages','_self',2,'html.png'),(7,6,'item','Create new Static Page','index.php?mod=static&sub=admin&action=create','Create new Static Page','_self',0,'add.png'),(8,6,'item','Show Static Pages','index.php?mod=static&sub=admin','Show Static Pages','_self',1,'pencil.png'),(9,2,'folder','Forum','','Forum','_self',3,'application_view_list.png'),(10,2,'folder','Guestbook','index.php?mod=guestbook&action=show','Guestbook','_self',4,'book_open.png'),(11,2,'folder','Matches','index.php?mod=matches&action=show','Matches','_self',5,'database_go.png'),(12,2,'folder','Serverlist','','Serverlist','_self',6,'table.png'),(13,12,'item','Show Servers','index.php?mod=serverlist&sub=admin&action=show','Show Servers','_self',0,'application_view_list.png'),(14,12,'item','Add Server','index.php?mod=serverlist&sub=admin&action=create','Add Server','_self',1,'application_form_edit.png'),(15,2,'folder','Shoutbox','','Shoutbox','_self',7,'comment.png'),(16,2,'folder','Downloads','','Downloads','_self',8,'disk.png'),(17,2,'folder','Gallery','','Gallery','_self',9,'map_go.png'),(18,2,'folder','Replays','','Replays','_self',10,'film.png'),(19,2,'folder','eMail','','eMail','_self',11,'email_open_image.png'),(20,0,'folder','Administration','','Administration','_self',2,'textfield_key.png'),(21,20,'folder','Users','','Users','_self',0,'user_suit.png'),(22,21,'item','Show all Users','index.php?mod=admin&sub=users','Show all Users','_self',0,'table.png'),(23,21,'item','Create a user','index.php?mod=admin&sub=users&action=create','Create a user','_self',1,'add.png'),(24,21,'item','Search a User','index.php?mod=admin&sub=users&action=search','Search a User','_self',2,'magnifier.png'),(25,20,'folder','Groups','','Groups','_self',1,'group.png'),(26,25,'item','Show all Groups','index.php?mod=admin&sub=groups','Show all Groups','_self',0,'table.png'),(27,25,'item','Create a group','index.php?mod=admin&sub=groups&action=create','Create a group','_self',1,'add.png'),(28,20,'folder','Permissions','','Permissions','_self',2,'key.png'),(29,28,'item','Show all Permissions','index.php?mod=admin&sub=permissions','Show all Permissions','_self',0,'table.png'),(30,20,'item','Categories','index.php?mod=admin&sub=categories','Categories','_self',3,'spellcheck.png'),(31,20,'folder','Layout & Styles','','Layout & Styles','_self',4,'layout_header.png'),(32,31,'item','BB Code Editor','index.php?mod=admin&sub=bbcode','BB Code Editor','_self',0,'text_bold.png'),(33,31,'item','Adminmenu Editor','index.php?mod=admin&sub=menueditor','Adminmenu Editor','_self',1,'application_form_edit.png'),(34,31,'item','Template Editor','index.php?mod=admin&sub=templates','Template Editor','_self',2,'layout_edit.png'),(35,0,'folder','System','','System','_self',3,'computer.png'),(36,35,'item','Settings','index.php?mod=admin&sub=settings','Settings','_self',0,'settings.png'),(37,35,'folder','Database','','Database','_self',1,'database_gear.png'),(38,37,'item','Optimize','index.php?mod=database&action=optimize','Optimize','_self',0,'database_go.png'),(39,37,'item','Backup','index.php?mod=database&action=backup','Backup','_self',1,'database_key.png'),(40,35,'folder','Modules','','Modules','_self',2,'bricks.png'),(41,40,'item','Install new modules','index.php?mod=admin&sub=modules&action=install_new','Install new modules','_self',0,'package.png'),(42,40,'item','Create a module/submodule','index.php?mod=admin&sub=modules&action=create_new','Create a new module/submodule','_self',1,'add.png'),(43,40,'item','Show and edit modules','index.php?mod=admin&sub=modules&action=show_all','Show and edit modules','_self',2,'bricks_edit.png'),(44,40,'item','Export a module','index.php?mod=admin&sub=modules&action=export','Export a module','_self',3,'compress.png'),(45,35,'folder','Language','','Language','_self',3,'spellcheck.png'),(46,45,'item','Language Editor','index.php?mod=language&sub=editor','Language Editor','_self',0,'spellcheck.png'),(47,0,'folder','Help','','Help','_self',4,'help.png'),(48,47,'item','Help','index.php?mod=admin&sub=static&action=show&page=help','Help','_self',0,'help.png'),(49,47,'item','Manual','index.php?mod=admin&sub=manual','Manual','_self',1,'book_open.png'),(50,47,'item','Report Bugs & Give Feedback','index.php?mod=admin&sub=bugs','Report Bugs & Give Feedback','_self',2,'error.png'),(51,47,'item','About Clansuite','index.php?mod=admin&sub=static&action=show&page=about','About Clansuite','_self',3,'information.png');
/*!40000 ALTER TABLE `cs_adminmenu_backup` ENABLE KEYS */;

--
-- Table structure for table `cs_adminmenu_shortcuts`
--

DROP TABLE IF EXISTS `cs_adminmenu_shortcuts`;
CREATE TABLE `cs_adminmenu_shortcuts` (
  `id` tinyint(4) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `order` tinyint(4) NOT NULL default '30',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_adminmenu_shortcuts`
--


/*!40000 ALTER TABLE `cs_adminmenu_shortcuts` DISABLE KEYS */;
INSERT INTO `cs_adminmenu_shortcuts` VALUES (1,'Console','index.php?mod=admin&sub=console','console.png',45),(2,'Downloads','index.php?mod=admin&sub=downloads','downloads.png',30),(3,'Articles','index.php?mod=admin&sub=articles','articles.png',30),(4,'Links','index.php?mod=admin&sub=links','links.png',30),(5,'Calendar','index.php?mod=admin&sub=calendar','calendar.png',30),(6,'Time','index.php?mod=admin&sub=time','time.png',30),(7,'Email','index.php?mod=admin&sub=email','email.png',3),(8,'Shoutbox','index.php?mod=admin&sub=shoutbox','shoutbox.png',30),(9,'Help','index.php?mod=admin&sub=help','help.png',40),(10,'Security','index.php?mod=admin&sub=security','security.png',41),(11,'Gallery','index.php?mod=admin&sub=gallery','gallery.png',30),(12,'System','index.php?mod=admin&sub=system','system.png',42),(13,'Replays','index.php?mod=admin&sub=replays','replays.png',30),(14,'News','index.php?mod=admin&sub=news','news.png',2),(15,'Settings','index.php?mod=admin&sub=settings','settings.png',43),(16,'Users','index.php?mod=admin&sub=users','users.png',1),(17,'Backup','index.php?mod=admin&sub=backup','backup.png',44),(18,'Templates','index.php?mod=admin&sub=templates','templates.png',4),(19,'Groups','index.php?mod=admin&sub=groups','groups.png',2),(20,'Search','index.php?mod=admin&sub=search','search.png',30);
/*!40000 ALTER TABLE `cs_adminmenu_shortcuts` ENABLE KEYS */;

--
-- Table structure for table `cs_areas`
--

DROP TABLE IF EXISTS `cs_areas`;
CREATE TABLE `cs_areas` (
  `area_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default 'New Area',
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`area_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_areas`
--


/*!40000 ALTER TABLE `cs_areas` DISABLE KEYS */;
INSERT INTO `cs_areas` VALUES (5,'Shoutbox','Rights for the shoutbox'),(4,'ACP','Admin Control Panel'),(6,'News','The area for the news module'),(7,'Filebrowser','The filebrowser module area');
/*!40000 ALTER TABLE `cs_areas` ENABLE KEYS */;

--
-- Table structure for table `cs_bb_code`
--

DROP TABLE IF EXISTS `cs_bb_code`;
CREATE TABLE `cs_bb_code` (
  `bb_code_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `start_tag` varchar(255) NOT NULL,
  `end_tag` varchar(255) NOT NULL,
  `content_type` varchar(255) NOT NULL,
  `allowed_in` varchar(255) NOT NULL,
  `not_allowed_in` varchar(255) NOT NULL,
  PRIMARY KEY  (`bb_code_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_bb_code`
--


/*!40000 ALTER TABLE `cs_bb_code` DISABLE KEYS */;
INSERT INTO `cs_bb_code` VALUES (1,'b','<b>','</b>','block','listitem,block,inline,link',''),(2,'i','<i>','</i>','block','listitem,block,inline,link',''),(3,'center','<center>','</center>','block','listitem,block,inline,link','');
/*!40000 ALTER TABLE `cs_bb_code` ENABLE KEYS */;

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_categories`
--


/*!40000 ALTER TABLE `cs_categories` DISABLE KEYS */;
INSERT INTO `cs_categories` VALUES (1,7,1,'-keine-','Diese News sind keiner Kategorie zugeordnet','','','#000000'),(2,7,2,'Allgemein','Thema Allgemein','','','#000000'),(3,7,3,'Member','Thema Members','','','#3366CC'),(4,7,4,'Page','Thema Page','','','#000000'),(5,88,5,'IRC','Thema IRC','','','#000000'),(6,88,6,'Clan-Wars','Thema Matches','','','#000000'),(7,88,7,'Sonstiges','Thema Hardware','','','#000000');
/*!40000 ALTER TABLE `cs_categories` ENABLE KEYS */;

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
INSERT INTO `cs_group_rights` VALUES (1,10),(1,11),(1,12),(1,13),(3,1),(3,3),(3,4),(3,5);
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_help`
--


/*!40000 ALTER TABLE `cs_help` DISABLE KEYS */;
INSERT INTO `cs_help` VALUES (1,'admin','','show','[b]BOLD: admin show helptext[/b] [i]ITALICS: Italiener sind Spagettifresser![/i]\n[s]STRANGETEST: not defined bbcode[/s]','http://www.google.de\nneue\nhttp://www.google.de\n123123123881111212\n[b]dick[/b]\n[url]http://www.google.de[/url]\n123'),(2,'admin','modules','export','test',''),(3,'admin','bbcode','show','[b]asdfsadf[/b]\n\n[i]help[/i]',''),(7,'admin','users','show','[s]not defined[/s]','» admin » users » show >> LINK'),(9,'admin','settings','show','test123','123123123'),(10,'admin','modules','show_all','[b]dick[/b]\n','http://www.clansuite.com\n[b]dick[/b]');
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
INSERT INTO `cs_mod_rel_sub` VALUES (2,1),(2,2),(2,3),(2,4),(2,5),(2,6),(2,7),(2,8),(2,9),(2,10),(2,11),(2,12),(2,13),(2,14),(2,15),(2,61),(5,18),(7,16),(9,17);
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
  PRIMARY KEY  (`module_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_modules`
--


/*!40000 ALTER TABLE `cs_modules` DISABLE KEYS */;
INSERT INTO `cs_modules` VALUES (1,'account','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Account Administration','This module handles all necessary account stuff - like login/logout etc.','module_account','account.module.php','account',1,'module_account.jpg',0.1,0,1),(3,'captcha','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Captcha Module','The captcha module presents a image only humanoids can read.','module_captcha','captcha.module.php','captcha',1,'module_captcha.jpg',0.1,0,1),(4,'index','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Index Module','This is the main site.','module_index','index.module.php','index',1,'module_index.jpg',0.1,0,1),(2,'admin','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Admin Interface','This is the Admin Control Panel','module_admin','admin.module.php','admin',0,'module_admin.jpg',0.1,0,1),(12,'matches','Your Name','http://www.matches.com','GPL v2','Your Name','Matches','Matches module - your description','module_matches','matches.module.php','matches',0,'module_matches.jpg',0.1,0.1,0),(5,'static','Jens-André Koch,Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Static Pages','Static Pages store HTML content','module_static','static.module.php','static',0,'module_static.jpg',0.1,0,0),(6,'shoutbox','Björn Spiegel, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Shoutbox Modul','This module displays a shoutbox. You can do entries and administrate it ...','module_shoutbox','shoutbox.module.php','shoutbox',1,'module_shoutbox.jpg',0.1,0,0),(7,'news','Jens-André Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','News','News module','module_news','news.module.php','news',1,'module_news.jpg',0.1,0,0),(8,'filebrowser','Florian Wolf, Jens-André Koch','http://www.clansuite.com','GPL v2','clansuite group','Filebrowser','The filebrowser of clansuite','module_filebrowser','filebrowser.module.php','filebrowser',1,'module_filebrowser.jpg',0.1,0,0),(9,'serverlist','Jens-André Koch','http://www.clansuite.com','BSD','Clansuite Group','Serverlist','List Gameservers','module_serverlist','serverlist.module.php','serverlist',1,'module_serverlist.jpg',0.1,0,0);
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
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_news`
--


/*!40000 ALTER TABLE `cs_news` DISABLE KEYS */;
INSERT INTO `cs_news` VALUES (1,'testeintrag1','testbody1\r\n1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n10\r\ntestbody11',2,1,1168260056,0),(2,'1243','21341234',0,1,1168260056,0),(3,'2345','asdvgfas',0,1,1168260056,0),(4,'3451','dfas',0,1,1168260056,0),(5,'234512','df',0,1,1168260056,0),(6,'51','dfaas',0,1,1168260056,0),(7,'3512','sdf',0,1,1168260056,0),(8,'1234512','fasdf',0,1,1168260056,0),(9,'23512345','asdasd',0,1,1168260056,0),(10,'512351','f',0,1,1168260056,0),(11,'123451324','asdf',0,1,1168260056,0),(12,'512351234','asd',0,1,1168260056,0),(13,'12351325','asdf',0,1,1168260056,0),(14,'41234123','f',0,1,1168260056,0),(15,'312','asd',0,1,1168260056,0),(16,'123','http://www.google.de',0,1,1168260056,0);
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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_rights`
--


/*!40000 ALTER TABLE `cs_rights` DISABLE KEYS */;
INSERT INTO `cs_rights` VALUES (11,5,'shoutbox_post','The right to post into the shoutbox'),(10,4,'access_controlcenter','The right to access the ACP'),(12,6,'create_news','Add a News'),(13,7,'access_filebrowser','Access the filebrowser');
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
  `csquery_engine` varchar(60) default NULL,
  `gametype` varchar(60) default NULL,
  `image_country` varchar(20) default NULL,
  UNIQUE KEY `server_id` (`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_serverlist`
--


/*!40000 ALTER TABLE `cs_serverlist` DISABLE KEYS */;
INSERT INTO `cs_serverlist` VALUES (1,'team-n1.com','27339','knd-squad DEATHMATCH powered by CLANSUITE DOT COM ','steam','cs','de.png'),(2,'87.117.208.193','27025',' DigitalTakedown.org UK - Public Server','steam','cs','de.png'),(3,'83.133.81.95','27015','#German Headquarter 3 WC3FT | Team.GHQ | www.team-GHQ.eu ','steam','cs','de.png'),(4,'210.55.92.68','27980','Xtra Quake 3 RA #1','q3a','q3a','de.png'),(5,'85.14.233.32','27015','B I E R F R I E D H O F | TICK100 | by ngz-server.de','steam','cssource','de.png');
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
INSERT INTO `cs_session` VALUES (1,'bb813bd64ff5ae8c4f0fabc7925c7181','client_ip|s:9:\"127.0.0.1\";client_browser|s:87:\"Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1\";client_host|s:9:\"localhost\";suiteSID|s:32:\"bb813bd64ff5ae8c4f0fabc7925c7181\";user|a:11:{s:6:\"authed\";i:1;s:7:\"user_id\";s:1:\"1\";s:4:\"nick\";s:5:\"admin\";s:8:\"password\";s:40:\"d1ca11799e222d429424d47b424047002ea72d44\";s:5:\"email\";s:21:\"support@clansuite.com\";s:10:\"first_name\";s:13:\"Administrator\";s:9:\"last_name\";s:13:\"Administrator\";s:8:\"disabled\";s:1:\"0\";s:9:\"activated\";s:1:\"1\";s:6:\"groups\";a:1:{i:0;s:1:\"1\";}s:6:\"rights\";a:4:{s:20:\"access_controlcenter\";i:1;s:13:\"shoutbox_post\";i:1;s:11:\"create_news\";i:1;s:18:\"access_filebrowser\";i:1;}}','suiteSID',1170970677,1,'admin');
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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_shoutbox`
--


/*!40000 ALTER TABLE `cs_shoutbox` DISABLE KEYS */;
INSERT INTO `cs_shoutbox` VALUES (1,'12345','123test@test.com','texttext',1155898254,'127.0.0.1'),(2,'109876','123@123.123','shoutboxtesttest',1155898254,'127.0.0.1'),(3,'asdfasdfs','asdfasdfasdfasdf@asdf.de','dafasdghafg',1156304492,'127.0.0.1'),(4,'asdfasdfs','asdfasdfasdfasdf@asdf.de','dafasdghafg',1156304492,'127.0.0.1'),(5,'asdfsadfasdfas','asdfasdf@asdf.de','asdfasdfasdf',1156305870,'127.0.0.1'),(6,'asdfasdfasdf','asdfasdfasdfasdf@asdf.de','asdfasdfasdf',1156306849,'127.0.0.1'),(7,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307074,'127.0.0.1'),(8,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307074,'127.0.0.1'),(9,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307091,'127.0.0.1'),(10,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307099,'127.0.0.1'),(11,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307100,'127.0.0.1'),(12,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307101,'127.0.0.1'),(13,'sdfsdfgdsfg','asdfasdfasdfasdf@asdf.de','ghfgghjghj',1156307307,'127.0.0.1'),(14,'asdfasdfasfd','ad@ad.de','adfasdfasdf',1156307578,'127.0.0.1'),(15,'sdfgsdfgsdfg','rrrrr@rrrrrr.de','rrrrrrrrrrrrrrrrrrrrrr',1156307696,'127.0.0.1'),(16,'test','test@test.de','',1158356510,'127.0.0.1'),(17,'test','test@test.de','clansuite is just the best !!! you guys rock!!',1158356530,'127.0.0.1'),(18,'test','test@test.de','how are you? feeling well?',1158356544,'127.0.0.1'),(19,'test','test@test.de','next time this will be a chat :)',1158356601,'127.0.0.1'),(20,'Guest','test@test.de','you sure?',1158356786,'127.0.0.1');
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_static_pages`
--


/*!40000 ALTER TABLE `cs_static_pages` DISABLE KEYS */;
INSERT INTO `cs_static_pages` VALUES (1,'Credits','Without their brains Clansuite would not be - Thanks alot!','','<u><strong>Clansuite - Credits </strong></u>\r\n<br />\r\n<br />\r\n<br />\r\n<table width=\"691\" height=\"393\" cellspacing=\"1\" cellpadding=\"1\" border=\"1\" align=\"\" summary=\"\">\r\n    <tbody>\r\n        <tr>\r\n            <td align=\"center\">Class</td>\r\n            <td align=\"center\">Author<br />\r\n            </td>\r\n            <td align=\"center\">&nbsp;Licence</td>\r\n        </tr>\r\n        <tr>\r\n            <td>tar.class.php</td>\r\n            <td>Vincent Blavet &lt;vincent@phpconcept.net&gt;<br />\r\n            Copyright (c) 1997-2003 The PHP Group <br />\r\n            </td>\r\n            <td>PHP license v3</td>\r\n        </tr>\r\n        <tr>\r\n            <td>PEAR, the PHP Extension and Application Repository</td>\r\n            <td>Sterling Hughes &lt;sterling@php.net&gt;<br />\r\n            Stig Bakken &lt;ssb@php.net&gt;<br />\r\n            Tomas V.V.Cox &lt;cox@idecnet.com&gt;<br />\r\n            Greg Beaver &lt;cellog@php.net&gt;<br />\r\n            &nbsp;Copyright&nbsp; 1997-2006 The PHP Group</td>\r\n            <td>PHP license v3</td>\r\n        </tr>\r\n        <tr>\r\n            <td>Swift Mailer: A Flexible PHP Mailer Class</td>\r\n            <td>&quot;Chris Corbyn&quot; &lt;chris@w3style.co.uk&gt;<br />\r\n            Copyright 2006 Chris Corbyn</td>\r\n            <td>LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Smarty: the PHP compiling template engine</td>\r\n            <td valign=\"top\">Monte Ohrt &lt;monte at ohrt dot com&gt;<br />\r\n            Andrei Zmievski &lt;andrei@php.net&gt;<br />\r\n            Copyright 2001-2005 New Digital Group, Inc.</td>\r\n            <td valign=\"top\">LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Sajax : cross-platform, cross-browser web scripting toolkit</td>\r\n            <td valign=\"top\">Copyright 2005-2006 modernmethod</td>\r\n            <td valign=\"top\">BSD</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Imagemanger</td>\r\n            <td valign=\"top\">Xiang Wei ZHUO &lt;wei@zhuo.org&gt;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">DHTML Calendar Javascript</td>\r\n            <td valign=\"top\">Copyright Mihai Bazon, 2002-2005</td>\r\n            <td valign=\"top\">LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Tab Pane Javascript</td>\r\n            <td valign=\"top\">Copyright (c) 2002, 2003, 2006 Erik Arvidsson</td>\r\n            <td valign=\"top\">Apache License v2</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\"><a href=\"http://www.fckeditor.net/\">FCKEditor</a>- WYSIWYG</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Icons by <a href=\"http://www.famfamfam.com/lab/icons/\">famfamfam</a></td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">mygosumenu\'s</td>\r\n            <td valign=\"top\">Copyright 2003,2004 Cezary Tomczak</td>\r\n            <td valign=\"top\">BSD</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Bitstream Vera Fonts </td>\r\n            <td valign=\"top\">Copyright (c) 2003 by Bitstream, Inc.</td>\r\n            <td valign=\"top\">own</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />',1,300),(2,'Google','Google','http://www.google.de','',1,500),(3,'Help','The help for ClanSuite','','<strong><font size=\"4\">Help</font><br />\r\n<br />\r\n</strong><strong> - gogo<br />\r\n- gogogogo<br />\r\n- gogogogogogo</strong>',1,300),(4,'Manual','The Manual','','<font size=\"4\">Manual</font><br />\r\n<br />\r\n- some content',1,300),(5,'About','About ClanSuite','','<font size=\"4\">About</font><br />\r\n<br />\r\n- some content',1,300);
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
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_submodules`
--


/*!40000 ALTER TABLE `cs_submodules` DISABLE KEYS */;
INSERT INTO `cs_submodules` VALUES (1,'admin','filebrowser.admin.php','module_filebrowser_admin'),(2,'configs','configs.module.php','module_admin_configs'),(3,'modules','modules.module.php','module_admin_modules'),(4,'users','users.module.php','module_admin_users'),(5,'usercenter','usercenter.module.php','module_admin_usercenter'),(6,'groups','groups.module.php','module_admin_groups'),(7,'permissions','perms.module.php','module_admin_permissions'),(8,'menueditor','menueditor.module.php','module_admin_menueditor'),(9,'static','static.module.php','module_admin_static'),(10,'bugs','bugs.module.php','module_admin_bugs'),(11,'manual','manual.module.php','module_admin_manual'),(12,'templates','templates.module.php','module_admin_templates'),(13,'settings','settings.module.php','module_admin_settings'),(14,'categories','categories.module.php','module_admin_categories'),(15,'help','help.module.php','module_admin_help'),(16,'admin','news.admin.php','module_news_admin'),(17,'admin','serverlist.admin.php','module_serverlist_admin'),(18,'admin','static.admin.php','module_static_admin'),(61,'bbcode','bbcode.module.php','module_admin_bbcode');
/*!40000 ALTER TABLE `cs_submodules` ENABLE KEYS */;

--
-- Table structure for table `cs_user_groups`
--

DROP TABLE IF EXISTS `cs_user_groups`;
CREATE TABLE `cs_user_groups` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `group_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_user_groups`
--


/*!40000 ALTER TABLE `cs_user_groups` DISABLE KEYS */;
INSERT INTO `cs_user_groups` VALUES (1,1),(2,1),(2,2);
/*!40000 ALTER TABLE `cs_user_groups` ENABLE KEYS */;

--
-- Table structure for table `cs_user_rights`
--

DROP TABLE IF EXISTS `cs_user_rights`;
CREATE TABLE `cs_user_rights` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `right_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

--
-- Dumping data for table `cs_user_rights`
--


/*!40000 ALTER TABLE `cs_user_rights` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_user_rights` ENABLE KEYS */;

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cs_users`
--


/*!40000 ALTER TABLE `cs_users` DISABLE KEYS */;
INSERT INTO `cs_users` VALUES (1,'support@clansuite.com','admin','d1ca11799e222d429424d47b424047002ea72d44','','',0,0,'Administrator','Administrator','I\'m the admin',0,1),(2,'test@test.de','test','974c2e9429ade22627f12ecb4b400f224474dfd0','','',1158144934,0,'test','test','test',0,1);
/*!40000 ALTER TABLE `cs_users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

