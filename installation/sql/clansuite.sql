-- MySQL dump 10.11
--
-- Host: localhost    Database: clansuite
-- ------------------------------------------------------
-- Server version	5.0.51a

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
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_adminmenu` (
  `id` tinyint(3) unsigned NOT NULL default '0',
  `parent` tinyint(3) unsigned NOT NULL default '0',
  `type` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `order` tinyint(4) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `right_to_view` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`,`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_adminmenu`
--

/*!40000 ALTER TABLE `cs_adminmenu` DISABLE KEYS */;
INSERT INTO `cs_adminmenu` VALUES (1,0,'folder','Home','?mod=controlcenter','Clansuite Control Center','_self',0,'layout_header.png','admin'),(2,0,'folder','Modules','','Modules','_self',1,'package.png','cc_access'),(3,2,'folder','Downloads','','Downloads','_self',0,'disk.png',''),(4,3,'item','Manage Downloads','index.php?mod=downloads&amp;sub=admin','Manage Downloads','_self',0,'application_form_edit.png',''),(5,2,'folder','Forum','','Forum','_self',1,'application_view_list.png',''),(6,5,'item','Manage Forum','index.php?mod=forum&amp;sub=admin','Manage Forum','_self',0,'application_form_edit.png',''),(7,2,'folder','Gallery','','Gallery','_self',2,'map_go.png',''),(8,7,'item','Manage Gallery','index.php?mod=gallery&amp;sub=admin','Manage Gallery','_self',0,'application_form_edit.png',''),(9,2,'folder','Guestbook','index.php?mod=guestbook&amp;action=show','Guestbook','_self',3,'book_open.png',''),(10,9,'item','Manage Guestbook','index.php?mod=guestbook&amp;sub=admin','Manage Guestbook','_self',0,'application_form_edit.png',''),(11,2,'folder','Matches','index.php?mod=matches&amp;action=show','Matches','_self',4,'database_go.png',''),(12,11,'item','Manage Matches','index.php?mod=matches&amp;sub=admin','Manage Matches','_self',0,'application_form_edit.png',''),(13,2,'folder','Messaging','','Messaging','_self',5,'email_open_image.png',''),(14,13,'item','Manage Messages','index.php?mod=messaging&amp;sub=admin','Manage Messages','_self',0,'application_form_edit.png',''),(15,2,'folder','News','','News','_self',6,'page_edit.png',''),(16,15,'item','Manage News','index.php?mod=news&amp;sub=admin','Manage News','_self',0,'application_form_edit.png','cc_edit_news'),(17,15,'item','Create news','index.php?mod=news&amp;sub=admin&amp;action=create','Create news','_self',1,'add.png','cc_create_news'),(18,2,'folder','Replays','','Replays','_self',7,'film.png',''),(19,18,'item','Manage Replays','index.php?mod=replays&amp;sub=admin','Manage Replays','_self',0,'application_form_edit.png',''),(20,2,'folder','Serverlist','','Serverlist','_self',8,'table.png',''),(21,20,'item','Show Servers','index.php?mod=serverlist&amp;sub=admin&amp;action=show','Show Servers','_self',0,'application_view_list.png',''),(22,20,'item','Add Server','index.php?mod=serverlist&amp;sub=admin&amp;action=create','Add Server','_self',1,'application_form_edit.png',''),(23,2,'folder','Shoutbox','','Shoutbox','_self',9,'comment.png',''),(24,23,'item','Manage Shoutbox','index.php?mod=shoutbox&amp;sub=admin','Manage Shoutbox','_self',0,'application_form_edit.png',''),(25,2,'folder','Static Pages','','Static Pages','_self',10,'html.png',''),(26,25,'item','Create new Static Page','index.php?mod=staticpages&amp;sub=admin&amp;action=create','Create new Static Page','_self',0,'add.png',''),(27,25,'item','Show Static Pages','index.php?mod=staticpages&amp;sub=admin','Show Static Pages','_self',1,'pencil.png',''),(28,2,'folder','Quotes','','Quotes','_self',11,'report.png',''),(29,28,'item','Manage Quotes','index.php?mod=quote&amp;sub=admin','Manage Quotes','_self',0,'report.png',''),(30,0,'folder','Administration','','Administration','_self',2,'textfield_key.png',''),(31,30,'folder','Users','','Users','_self',0,'user_suit.png',''),(32,31,'item','Manage users','index.php?mod=users&amp;sub=admin','Manage users','_self',0,'add.png','cc_show_users'),(33,31,'item','Search a User','index.php?mod=users&amp;sub=admin&amp;action=search','Search a User','_self',1,'magnifier.png','cc_search_users'),(34,30,'folder','Groups','','Groups','_self',1,'group.png',''),(35,34,'item','Show all Groups','index.php?mod=groups&amp;sub=admin','Show all Groups','_self',0,'table.png',''),(36,34,'item','Create a group','index.php?mod=groups&amp;sub=admin&amp;action=create','Create a group','_self',1,'add.png',''),(37,30,'folder','Permissions','','Permissions','_self',2,'key.png',''),(38,37,'item','Manage permissions','index.php?mod=permissions&amp;sub=admin','Manage permissions','_self',0,'add.png',''),(39,30,'item','Categories','index.php?mod=categories&amp;sub=admin','Categories','_self',3,'spellcheck.png',''),(40,30,'folder','Layout & Styles','','Layout & Styles','_self',4,'layout_header.png',''),(41,40,'item','BB Code Editor','index.php?mod=admin&amp;sub=bbcode','BB Code Editor','_self',0,'text_bold.png',''),(42,40,'item','Adminmenu Editor','index.php?mod=menu&amp;sub=admin&amp;action=menueditor','Adminmenu Editor','_self',1,'application_form_edit.png',''),(43,40,'item','Template Editor','index.php?mod=admin&amp;sub=templates','Template Editor','_self',2,'layout_edit.png',''),(44,40,'item','Themes Manager','index.php?mod=admin&amp;sub=themes','Themes Manager','_self',3,'layout_edit.png','cc_edit_themes'),(45,0,'folder','System','','System','_self',3,'computer.png',''),(46,45,'item','Settings','index.php?mod=settings&amp;sub=admin','Settings','_self',0,'settings.png',''),(47,45,'folder','Database','','Database','_self',1,'database_gear.png',''),(48,47,'item','Optimize','index.php?mod=database&amp;action=optimize','Optimize','_self',1,'database_gear.png',''),(49,47,'item','Export & Backup','index.php?mod=database&amp;action=backup','Export & Backup','_self',2,'database_key.png',''),(50,45,'folder','Modules','','Modules','_self',2,'bricks.png',''),(51,50,'item','Install new modules','index.php?mod=modulemanager&amp;sub=admin&amp;action=install','Install new modules','_self',0,'package.png',''),(52,50,'item','Create Module','index.php?mod=modulemanager&amp;sub=admin&amp;action=creator','Create a module','_self',1,'add.png',''),(53,50,'item','Manage modules','index.php?mod=modulemanager&amp;sub=admin&amp;action=show','Manage modules','_self',2,'bricks_edit.png',''),(54,50,'item','Export a module','index.php?mod=modulemanager&amp;sub=admin&amp;action=export','Export a module','_self',3,'compress.png',''),(55,45,'folder','Language','','Language','_self',3,'spellcheck.png',''),(56,55,'item','Language Editor','index.php?mod=language&amp;sub=admin','Language Editor','_self',0,'spellcheck.png',''),(57,45,'item','Bridges','index.php?mod=admin&amp;sub=bridges','Bridges','_self',4,'application_view_list.png',''),(58,0,'folder','Help','','Help','_self',4,'help.png',''),(59,58,'item','Help','index.php?mod=controlcenter&amp;sub=static&amp;action=show&amp;page=help','Help','_self',0,'help.png',''),(60,58,'item','Manual','http://www.clansuite.com/documentation/user/manual/','Manual','_blank',1,'book_open.png',''),(61,58,'item','Report Bugs & Issues','index.php?mod=controlcenter&amp;action=bugs','Report Bugs & Issues','_self',2,'error.png',''),(62,58,'item','About Clansuite','index.php?mod=controlcenter&amp;action=about','About Clansuite','_self',3,'information.png',''),(63,47,'item','Import','','Import','_self',0,'database_go.png',''),(64,45,'item','Information','','Information','_self',5,'information.png','');
/*!40000 ALTER TABLE `cs_adminmenu` ENABLE KEYS */;

--
-- Table structure for table `cs_adminmenu_backup`
--

DROP TABLE IF EXISTS `cs_adminmenu_backup`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
  `right_to_view` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`,`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_adminmenu_backup`
--

/*!40000 ALTER TABLE `cs_adminmenu_backup` DISABLE KEYS */;
INSERT INTO `cs_adminmenu_backup` VALUES (1,0,'folder','Home','?mod=admin','Clansuite Control Center','_self',0,'layout_header.png','admin'),(2,0,'folder','Modules','','Modules','_self',1,'package.png','cc_access'),(3,2,'folder','Downloads','','Downloads','_self',0,'disk.png',''),(4,3,'item','Manage Downloads','index.php?mod=downloads&amp;sub=admin','Manage Downloads','_self',0,'application_form_edit.png',''),(5,2,'folder','Forum','','Forum','_self',1,'application_view_list.png',''),(6,5,'item','Manage Forum','index.php?mod=forum&amp;sub=admin','Manage Forum','_self',0,'application_form_edit.png',''),(7,2,'folder','Gallery','','Gallery','_self',2,'map_go.png',''),(8,7,'item','Manage Gallery','index.php?mod=gallery&amp;sub=admin','Manage Gallery','_self',0,'application_form_edit.png',''),(9,2,'folder','Guestbook','index.php?mod=guestbook&amp;action=show','Guestbook','_self',3,'book_open.png',''),(10,9,'item','Manage Guestbook','index.php?mod=guestbook&amp;sub=admin','Manage Guestbook','_self',0,'application_form_edit.png',''),(11,2,'folder','Matches','index.php?mod=matches&amp;action=show','Matches','_self',4,'database_go.png',''),(12,11,'item','Manage Matches','index.php?mod=matches&amp;sub=admin','Manage Matches','_self',0,'application_form_edit.png',''),(13,2,'folder','Messaging','','Messaging','_self',5,'email_open_image.png',''),(14,13,'item','Manage Messages','index.php?mod=messaging&amp;sub=admin','Manage Messages','_self',0,'application_form_edit.png',''),(15,2,'folder','News','','News','_self',6,'page_edit.png',''),(16,15,'item','Manage News','index.php?mod=news&amp;sub=admin','Manage News','_self',0,'application_form_edit.png','cc_edit_news'),(17,15,'item','Create news','index.php?mod=news&amp;sub=admin&amp;action=create','Create news','_self',1,'add.png','cc_create_news'),(18,2,'folder','Replays','','Replays','_self',7,'film.png',''),(19,18,'item','Manage Replays','index.php?mod=replays&amp;sub=admin','Manage Replays','_self',0,'application_form_edit.png',''),(20,2,'folder','Serverlist','','Serverlist','_self',8,'table.png',''),(21,20,'item','Show Servers','index.php?mod=serverlist&amp;sub=admin&amp;action=show','Show Servers','_self',0,'application_view_list.png',''),(22,20,'item','Add Server','index.php?mod=serverlist&amp;sub=admin&amp;action=create','Add Server','_self',1,'application_form_edit.png',''),(23,2,'folder','Shoutbox','','Shoutbox','_self',9,'comment.png',''),(24,23,'item','Manage Shoutbox','index.php?mod=shoutbox&amp;sub=admin','Manage Shoutbox','_self',0,'application_form_edit.png',''),(25,2,'folder','Static Pages','','Static Pages','_self',10,'html.png',''),(26,25,'item','Create new Static Page','index.php?mod=staticpages&amp;sub=admin&amp;action=create','Create new Static Page','_self',0,'add.png',''),(27,25,'item','Show Static Pages','index.php?mod=staticpages&amp;sub=admin','Show Static Pages','_self',1,'pencil.png',''),(28,2,'folder','Quotes','','Quotes','_self',11,'report.png',''),(29,28,'item','Manage Quotes','index.php?mod=quote&amp;sub=admin','Manage Quotes','_self',0,'report.png',''),(30,0,'folder','Administration','','Administration','_self',2,'textfield_key.png',''),(31,30,'folder','Users','','Users','_self',0,'user_suit.png',''),(32,31,'item','Manage users','index.php?mod=users&amp;sub=admin','Manage users','_self',0,'add.png','cc_show_users'),(33,31,'item','Search a User','index.php?mod=users&amp;sub=admin&amp;action=search','Search a User','_self',1,'magnifier.png','cc_search_users'),(34,30,'folder','Groups','','Groups','_self',1,'group.png',''),(35,34,'item','Show all Groups','index.php?mod=groups&amp;sub=admin','Show all Groups','_self',0,'table.png',''),(36,34,'item','Create a group','index.php?mod=groups&amp;sub=admin&amp;action=create','Create a group','_self',1,'add.png',''),(37,30,'folder','Permissions','','Permissions','_self',2,'key.png',''),(38,37,'item','Manage permissions','index.php?mod=permissions&amp;sub=admin','Manage permissions','_self',0,'add.png',''),(39,30,'item','Categories','index.php?mod=categories&amp;sub=admin','Categories','_self',3,'spellcheck.png',''),(40,30,'folder','Layout & Styles','','Layout & Styles','_self',4,'layout_header.png',''),(41,40,'item','BB Code Editor','index.php?mod=admin&amp;sub=bbcode','BB Code Editor','_self',0,'text_bold.png',''),(42,40,'item','Adminmenu Editor','index.php?mod=menu&amp;sub=admin&amp;action=menueditor','Adminmenu Editor','_self',1,'application_form_edit.png',''),(43,40,'item','Template Editor','index.php?mod=admin&amp;sub=templates','Template Editor','_self',2,'layout_edit.png',''),(44,40,'item','Themes Manager','index.php?mod=admin&amp;sub=themes','Themes Manager','_self',3,'layout_edit.png','cc_edit_themes'),(45,0,'folder','System','','System','_self',3,'computer.png',''),(46,45,'item','Settings','index.php?mod=settings&amp;sub=admin','Settings','_self',0,'settings.png',''),(47,45,'folder','Database','','Database','_self',1,'database_gear.png',''),(48,47,'item','Optimize','index.php?mod=database&amp;action=optimize','Optimize','_self',0,'database_go.png',''),(49,47,'item','Backup','index.php?mod=database&amp;action=backup','Backup','_self',1,'database_key.png',''),(50,45,'folder','Modules','','Modules','_self',2,'bricks.png',''),(51,50,'item','Install new modules','index.php?mod=modulemanager&amp;sub=admin&amp;action=install','Install new modules','_self',0,'package.png',''),(52,50,'item','Create Module','index.php?mod=modulemanager&amp;sub=admin&amp;action=creator','Create a module','_self',1,'add.png',''),(53,50,'item','Manage modules','index.php?mod=modulemanager&amp;sub=admin&amp;action=show','Manage modules','_self',2,'bricks_edit.png',''),(54,50,'item','Export a module','index.php?mod=modulemanager&amp;sub=admin&amp;action=export','Export a module','_self',3,'compress.png',''),(55,45,'folder','Language','','Language','_self',3,'spellcheck.png',''),(56,55,'item','Language Editor','index.php?mod=language&amp;sub=admin','Language Editor','_self',0,'spellcheck.png',''),(57,45,'item','Bridges','index.php?mod=admin&amp;sub=bridges','Bridges','_self',4,'application_view_list.png',''),(58,0,'folder','Help','','Help','_self',4,'help.png',''),(59,58,'item','Help','index.php?mod=admin&amp;sub=static&amp;action=show&amp;page=help','Help','_self',0,'help.png',''),(60,58,'item','Manual','http://www.clansuite.com/documentation/user/manual/','Manual','_blank',1,'book_open.png',''),(61,58,'item','Report Bugs & Issues','index.php?mod=admin&amp;action=bugs','Report Bugs & Issues','_self',2,'error.png',''),(62,58,'item','About Clansuite','index.php?mod=admin&amp;action=about','About Clansuite','_self',3,'information.png','');
/*!40000 ALTER TABLE `cs_adminmenu_backup` ENABLE KEYS */;

--
-- Table structure for table `cs_adminmenu_shortcuts`
--

DROP TABLE IF EXISTS `cs_adminmenu_shortcuts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_adminmenu_shortcuts` (
  `id` tinyint(4) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `href` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `order` tinyint(4) NOT NULL default '30',
  `cat` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_adminmenu_shortcuts`
--

/*!40000 ALTER TABLE `cs_adminmenu_shortcuts` DISABLE KEYS */;
INSERT INTO `cs_adminmenu_shortcuts` VALUES (1,'Console','index.php?mod=admin&amp;sub=console','console.png',45,''),(2,'Downloads','index.php?mod=admin&amp;sub=downloads','downloads.png',30,''),(3,'Articles','index.php?mod=admin&amp;sub=articles','articles.png',30,''),(4,'Links','index.php?mod=admin&amp;sub=links','links.png',30,''),(5,'Calendar','index.php?mod=admin&amp;sub=calendar','calendar.png',30,''),(6,'Time','index.php?mod=admin&amp;sub=time','time.png',30,''),(7,'Email','index.php?mod=admin&amp;sub=email','email.png',3,''),(8,'Shoutbox','index.php?mod=admin&amp;sub=shoutbox','shoutbox.png',30,''),(9,'Help','index.php?mod=admin&amp;sub=help','help.png',40,''),(10,'Security','index.php?mod=admin&amp;sub=security','security.png',41,''),(11,'Gallery','index.php?mod=admin&amp;sub=gallery','gallery.png',30,''),(12,'System','index.php?mod=admin&amp;sub=system','system.png',42,''),(13,'Replays','index.php?mod=admin&amp;sub=replays','replays.png',30,''),(14,'News','index.php?mod=admin&amp;sub=news','news.png',2,''),(15,'Settings','index.php?mod=admin&amp;sub=settings','settings.png',43,''),(16,'Users','index.php?mod=admin&amp;sub=users','users.png',1,''),(17,'Backup','index.php?mod=admin&amp;sub=backup','backup.png',44,''),(18,'Templates','index.php?mod=admin&amp;sub=templates','templates.png',4,''),(19,'Groups','index.php?mod=admin&amp;sub=groups','groups.png',2,''),(20,'Search','index.php?mod=admin&amp;sub=search','search.png',30,'');
/*!40000 ALTER TABLE `cs_adminmenu_shortcuts` ENABLE KEYS */;

--
-- Table structure for table `cs_areas`
--

DROP TABLE IF EXISTS `cs_areas`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_areas` (
  `area_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default 'New Area',
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`area_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_areas`
--

/*!40000 ALTER TABLE `cs_areas` DISABLE KEYS */;
INSERT INTO `cs_areas` VALUES (5,'Shoutbox','Rights for the shoutbox'),(4,'Control Center','The area to handle the permissions of the control center'),(6,'News','The area for the news module'),(7,'Filebrowser','The filebrowser module area'),(8,'Guestbook','The area to handle the permissions of the guestbook'),(9,'Articles','The area to handle the permissions of the articles'),(10,'Static Pages','The area to handle the permissions of the static pages'),(11,'Forum','The area to handle the permissions of the forum'),(12,'Matches','The area to handle the permissions of the matches'),(13,'Serverlist','The area to handle the permissions of the serverlist'),(14,'Downloads','The area to handle the permissions of the downloads'),(15,'Gallery','The area to handle the permissions of the gallery'),(16,'Replays','The area to handle the permissions of the replays'),(17,'Messaging','The area to handle the permissions of the messaging system'),(18,'Themes','Area to handle the themes'),(19,'Profile','All profile related rights'),(20,'Users','Area to handle the user rights');
/*!40000 ALTER TABLE `cs_areas` ENABLE KEYS */;

--
-- Table structure for table `cs_bb_code`
--

DROP TABLE IF EXISTS `cs_bb_code`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_bb_code`
--

/*!40000 ALTER TABLE `cs_bb_code` DISABLE KEYS */;
INSERT INTO `cs_bb_code` VALUES (1,'b','<b>','</b>','block','listitem,block,inline,link',''),(2,'i','<i>','</i>','block','listitem,block,inline,link',''),(3,'center','<center>','</center>','block','listitem,block,inline,link','');
/*!40000 ALTER TABLE `cs_bb_code` ENABLE KEYS */;

--
-- Table structure for table `cs_board_forums`
--

DROP TABLE IF EXISTS `cs_board_forums`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_board_forums` (
  `forumid` int(11) NOT NULL auto_increment,
  `forumparent` int(11) NOT NULL default '0',
  `name` varchar(128) default NULL,
  `description` text,
  `displayorder` smallint(6) NOT NULL default '0',
  `moderator` int(11) default NULL,
  `posts` int(11) NOT NULL,
  `threads` int(11) NOT NULL,
  `permissions` int(11) default NULL,
  `password` varchar(32) default NULL,
  `lastpost` varchar(54) NOT NULL,
  `status` varchar(15) NOT NULL,
  `type` varchar(15) NOT NULL,
  PRIMARY KEY  (`forumid`,`forumparent`,`displayorder`,`status`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_board_forums`
--

/*!40000 ALTER TABLE `cs_board_forums` DISABLE KEYS */;
INSERT INTO `cs_board_forums` VALUES (1,0,'Apfel-Forum','Apfel',1,NULL,0,0,NULL,NULL,'','on','forum'),(2,0,'Birnen-Forum','Birne',3,NULL,0,0,NULL,NULL,'','on','forum'),(3,1,'Kirschen-Forum is a Child of Apfel-Forum','Kirsche',2,NULL,0,0,NULL,NULL,'','on','sub'),(4,3,'SahneKirschen-Forum is a Child of Kirschen-Forum','Sahnekirschen',4,NULL,0,0,NULL,NULL,'','on','sub'),(5,2,'Nashi-Birnen Forum','Nashi-Birnen',5,NULL,0,0,0,NULL,'','on','sub'),(6,0,'Orangen-Forum','Orangen',2,NULL,0,0,0,NULL,'','on','forum');
/*!40000 ALTER TABLE `cs_board_forums` ENABLE KEYS */;

--
-- Table structure for table `cs_board_posts`
--

DROP TABLE IF EXISTS `cs_board_posts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_board_posts` (
  `forumid` int(11) NOT NULL default '0',
  `threadid` int(11) NOT NULL default '0',
  `postid` int(11) NOT NULL auto_increment,
  `author` varchar(32) NOT NULL,
  `message` text NOT NULL,
  `subject` tinytext NOT NULL,
  `date` int(10) NOT NULL default '0',
  `icon` varchar(50) default NULL,
  `signatur` varchar(15) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `bbcodeoff` varchar(15) NOT NULL,
  `smileyoff` varchar(15) NOT NULL,
  `edited_by` text,
  PRIMARY KEY  (`postid`),
  KEY `fid` (`forumid`),
  KEY `tid` (`threadid`),
  KEY `dateline` (`date`),
  KEY `author` (`author`(8))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_board_posts`
--

/*!40000 ALTER TABLE `cs_board_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_board_posts` ENABLE KEYS */;

--
-- Table structure for table `cs_board_threads`
--

DROP TABLE IF EXISTS `cs_board_threads`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_board_threads` (
  `threadid` int(11) NOT NULL auto_increment,
  `forumid` int(11) NOT NULL default '0',
  `subject` varchar(128) NOT NULL,
  `icon` varchar(75) NOT NULL,
  `lastpost` varchar(54) NOT NULL,
  `views` bigint(32) NOT NULL default '0',
  `replies` int(10) NOT NULL default '0',
  `author` varchar(32) NOT NULL,
  `closed` varchar(15) NOT NULL,
  `stickified` tinyint(1) NOT NULL default '0',
  `poll` text NOT NULL,
  PRIMARY KEY  (`forumid`),
  KEY `fid` (`forumid`),
  KEY `tid` (`threadid`),
  KEY `lastpost` (`lastpost`),
  KEY `author` (`author`(8)),
  KEY `closed` (`closed`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_board_threads`
--

/*!40000 ALTER TABLE `cs_board_threads` DISABLE KEYS */;
INSERT INTO `cs_board_threads` VALUES (1,1,'Apfelernte','','',0,0,'','',0,'');
/*!40000 ALTER TABLE `cs_board_threads` ENABLE KEYS */;

--
-- Table structure for table `cs_calendar`
--

DROP TABLE IF EXISTS `cs_calendar`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_calendar` (
  `event_id` int(11) NOT NULL auto_increment,
  `cat_id` smallint(2) NOT NULL,
  `day` varchar(2) NOT NULL,
  `month` varchar(2) NOT NULL,
  `year` varchar(4) NOT NULL,
  `eventname` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`event_id`,`cat_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_calendar`
--

/*!40000 ALTER TABLE `cs_calendar` DISABLE KEYS */;
INSERT INTO `cs_calendar` VALUES (1,1,'19','4','2007','badday','badday','lalal'),(2,1,'19','4','2007','badday 2nd event','badday 2nd event','asdhfkjashdkfjaasd'),(3,1,'14','4','2007','123','123','123');
/*!40000 ALTER TABLE `cs_calendar` ENABLE KEYS */;

--
-- Table structure for table `cs_categories`
--

DROP TABLE IF EXISTS `cs_categories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_categories`
--

/*!40000 ALTER TABLE `cs_categories` DISABLE KEYS */;
INSERT INTO `cs_categories` VALUES (1,7,1,'-keine-','Diese News sind keiner Kategorie zugeordnet','','','#000000'),(2,7,2,'Allgemein','Thema Allgemein','','','#000000'),(3,7,3,'Member','Thema Members','','','#3366CC'),(4,7,4,'Page','Thema Page','','','#000000'),(5,7,5,'IRC','Thema IRC','','','#000000'),(6,7,6,'Clan-Wars','Thema Matches','','','#000000'),(7,7,7,'Sonstiges','Thema Hardware','','','#000000');
/*!40000 ALTER TABLE `cs_categories` ENABLE KEYS */;

--
-- Table structure for table `cs_comments`
--

DROP TABLE IF EXISTS `cs_comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_comments` (
  `comment_id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(11) unsigned NOT NULL default '0',
  `email` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `pseudo` varchar(25) default NULL,
  `ip` varchar(15) NOT NULL,
  `host` varchar(255) NOT NULL,
  PRIMARY KEY  (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_comments`
--

/*!40000 ALTER TABLE `cs_comments` DISABLE KEYS */;
INSERT INTO `cs_comments` VALUES (1,1,'','123','2005-07-29 13:04:07','','127.0.0.1','localhost'),(2,2,'','1234567','2005-07-29 16:50:08','blub','127.0.0.1','localhost');
/*!40000 ALTER TABLE `cs_comments` ENABLE KEYS */;

--
-- Table structure for table `cs_forum_boards`
--

DROP TABLE IF EXISTS `cs_forum_boards`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_forum_boards` (
  `id` bigint(20) NOT NULL auto_increment,
  `board_id` bigint(20) default NULL,
  `category_id` bigint(20) default NULL,
  `name` varchar(100) collate latin1_general_ci default NULL,
  `description` text collate latin1_general_ci,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_forum_boards`
--

/*!40000 ALTER TABLE `cs_forum_boards` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_forum_boards` ENABLE KEYS */;

--
-- Table structure for table `cs_forum_category`
--

DROP TABLE IF EXISTS `cs_forum_category`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_forum_category` (
  `id` bigint(20) NOT NULL auto_increment,
  `root_category_id` bigint(20) default NULL,
  `parent_category_id` bigint(20) default NULL,
  `name` varchar(50) collate latin1_general_ci default NULL,
  `description` text collate latin1_general_ci,
  PRIMARY KEY  (`id`),
  KEY `root_category_id_idx` (`root_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_forum_category`
--

/*!40000 ALTER TABLE `cs_forum_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_forum_category` ENABLE KEYS */;

--
-- Table structure for table `cs_forum_entry`
--

DROP TABLE IF EXISTS `cs_forum_entry`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_forum_entry` (
  `entry_id` bigint(20) NOT NULL default '0',
  `thread_id` bigint(20) default NULL,
  `parent_entry_id` bigint(20) default NULL,
  `author` varchar(50) collate latin1_general_ci default NULL,
  `topic` varchar(100) collate latin1_general_ci default NULL,
  `message` text collate latin1_general_ci,
  `date` bigint(20) default NULL,
  `smileyoff` tinyint(1) default NULL,
  `bbcodeoff` tinyint(1) default NULL,
  `edited_by` varchar(50) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`entry_id`),
  KEY `parent_entry_id_idx` (`parent_entry_id`),
  KEY `thread_id_idx` (`thread_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_forum_entry`
--

/*!40000 ALTER TABLE `cs_forum_entry` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_forum_entry` ENABLE KEYS */;

--
-- Table structure for table `cs_forum_threads`
--

DROP TABLE IF EXISTS `cs_forum_threads`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_forum_threads` (
  `id` bigint(20) NOT NULL auto_increment,
  `thread_id` bigint(20) default NULL,
  `board_id` bigint(20) default NULL,
  `title` varchar(200) collate latin1_general_ci default NULL,
  `updated` bigint(20) default NULL,
  `closed` tinyint(4) default NULL,
  `author` varchar(32) collate latin1_general_ci default NULL,
  `icon` varchar(75) collate latin1_general_ci default NULL,
  `lastpost` varchar(54) collate latin1_general_ci default NULL,
  `views` bigint(20) default NULL,
  `replies` int(11) default NULL,
  `stickified` tinyint(4) default NULL,
  PRIMARY KEY  (`id`),
  KEY `board_id_idx` (`board_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_forum_threads`
--

/*!40000 ALTER TABLE `cs_forum_threads` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_forum_threads` ENABLE KEYS */;

--
-- Table structure for table `cs_group_rights`
--

DROP TABLE IF EXISTS `cs_group_rights`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_group_rights` (
  `group_id` int(11) NOT NULL default '0',
  `right_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_group_rights`
--

/*!40000 ALTER TABLE `cs_group_rights` DISABLE KEYS */;
INSERT INTO `cs_group_rights` VALUES (1,20),(3,10),(3,11),(3,12),(3,13),(3,14),(3,15),(3,16),(3,17),(3,18),(3,19),(3,20),(3,21),(3,22),(3,23),(3,24),(3,25),(3,26),(3,27),(3,28);
/*!40000 ALTER TABLE `cs_group_rights` ENABLE KEYS */;

--
-- Table structure for table `cs_groups`
--

DROP TABLE IF EXISTS `cs_groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_groups` (
  `group_id` int(5) unsigned NOT NULL auto_increment,
  `sortorder` int(4) unsigned NOT NULL default '0',
  `name` varchar(80) NOT NULL,
  `description` varchar(255) NOT NULL,
  `icon` varchar(255) default NULL,
  `image` varchar(255) default NULL,
  `color` varchar(7) default NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_groups`
--

/*!40000 ALTER TABLE `cs_groups` DISABLE KEYS */;
INSERT INTO `cs_groups` VALUES (1,1,'Guest','The non-registered users.','','','#000000'),(2,2,'Normal users','The users are forced into this group after registration.','','','#006600'),(3,3,'Administrators','The website administrator with access to the control center (cc)','','','#FF0000'),(4,4,'Some group','Some testing group','','','#9900CC');
/*!40000 ALTER TABLE `cs_groups` ENABLE KEYS */;

--
-- Table structure for table `cs_guestbook`
--

DROP TABLE IF EXISTS `cs_guestbook`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_guestbook` (
  `gb_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `gb_added` int(12) default NULL,
  `gb_nick` varchar(25) default NULL,
  `gb_email` varchar(35) default NULL,
  `gb_icq` varchar(15) default NULL,
  `gb_website` varchar(35) default NULL,
  `gb_town` varchar(25) default NULL,
  `gb_text` text,
  `gb_ip` varchar(15) default NULL,
  `gb_comment` text,
  `image_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`gb_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_guestbook`
--

/*!40000 ALTER TABLE `cs_guestbook` DISABLE KEYS */;
INSERT INTO `cs_guestbook` VALUES (1,0,1003200322,'nick','jakoch@web.de','123124','www.skdjf.de','sdfsfas','sadfasdfasdfasdfasdf','1231231231','mir nur ganz alleine :) [b]fu[/b] asdfasdf asdfffffffffffffffffasd asdfsadfsafsafsadf asdfsadfsfsafsafddddddddddddddddddddddddddddd  asdfsfsdfsdfsdfddddddddddddddddddddddddd dddddasdfsdfsdfsdfsaf assadfsaf ',3),(2,0,1175392043,'nick1','vain@clansuite.com','123','','123','123','0','',0),(3,0,1175919684,'nester tester','vain@clansuite.com','1234567','http://www.test.de','blablubb','asafsdfd [b]test[/b]','127.0.0.1',NULL,0),(4,0,1175924624,'nickname','email@email.de','32452345','faffa','fafafa','faafaffaaf','127.0.0.1',NULL,3),(5,0,1175928797,'name','email@email.de','sdfasdf','sdfasdf','1234234','21efsdfasdfasdf','127.0.0.1',NULL,0);
/*!40000 ALTER TABLE `cs_guestbook` ENABLE KEYS */;

--
-- Table structure for table `cs_help`
--

DROP TABLE IF EXISTS `cs_help`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_help` (
  `help_id` int(11) NOT NULL auto_increment,
  `mod` varchar(255) NOT NULL,
  `sub` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `helptext` text NOT NULL,
  `related_links` text NOT NULL,
  PRIMARY KEY  (`help_id`),
  UNIQUE KEY `help_id` (`help_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_help`
--

/*!40000 ALTER TABLE `cs_help` DISABLE KEYS */;
INSERT INTO `cs_help` VALUES (1,'admin','','show','[b]BOLD: admin show helptext[/b] [i]ITALICS: Italiener sind Spagettifresser![/i]\n[s]STRANGETEST: not defined bbcode[/s]\n\n[code=php]\n<?php\necho \'test\';\n?>\n[/code]','www123'),(2,'admin','modules','export','test',''),(3,'admin','bbcode','show','[b]asdfsadf[/b]\n\n[i]help[/i]',''),(7,'admin','users','show','[s]not defined[/s]','[url]http://www.clansuite.com/users[/url]'),(9,'admin','groups','show','[b]wow[/b]',''),(10,'admin','settings','show','[b]ficken[/b]\n\n\nasdf','[url]www.google.de[/url]\n[url]www.clansuite.com[/url]'),(11,'admin','modules','install_new','[b]fuuuuuu[/b]\n\n\n[url]http:/hhhhasdfsadfas\n','asdfsdf'),(12,'serverlist','admin','show','','[url]http://www.google.de[/url]'),(13,'admin','modules','show_all','sadfsaf',''),(14,'guestbook','admin','show','','');
/*!40000 ALTER TABLE `cs_help` ENABLE KEYS */;

--
-- Table structure for table `cs_images`
--

DROP TABLE IF EXISTS `cs_images`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_images` (
  `image_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  PRIMARY KEY  (`image_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_images`
--

/*!40000 ALTER TABLE `cs_images` DISABLE KEYS */;
INSERT INTO `cs_images` VALUES (3,1,'upload','images/avatars/1.jpg');
/*!40000 ALTER TABLE `cs_images` ENABLE KEYS */;

--
-- Table structure for table `cs_messages`
--

DROP TABLE IF EXISTS `cs_messages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_messages` (
  `message_id` int(11) NOT NULL auto_increment,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `headline` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `timestamp` int(11) NOT NULL,
  `read` int(1) NOT NULL,
  PRIMARY KEY  (`message_id`),
  KEY `from` (`from`,`to`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_messages`
--

/*!40000 ALTER TABLE `cs_messages` DISABLE KEYS */;
INSERT INTO `cs_messages` VALUES (10,1,1,'uschi','furuzzz',1171204602,1),(11,1,1,'uschi','furuzzz',1171204602,1);
/*!40000 ALTER TABLE `cs_messages` ENABLE KEYS */;

--
-- Table structure for table `cs_mod_rel_sub`
--

DROP TABLE IF EXISTS `cs_mod_rel_sub`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_mod_rel_sub` (
  `module_id` int(11) NOT NULL,
  `submodule_id` int(11) NOT NULL,
  PRIMARY KEY  (`module_id`,`submodule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_mod_rel_sub`
--

/*!40000 ALTER TABLE `cs_mod_rel_sub` DISABLE KEYS */;
INSERT INTO `cs_mod_rel_sub` VALUES (1,70),(1,124),(1,125),(1,126),(2,1),(2,2),(2,3),(2,4),(2,5),(2,6),(2,7),(2,8),(2,9),(2,10),(2,11),(2,12),(2,13),(2,14),(2,15),(2,61),(2,72),(2,127),(2,128),(5,18),(7,16),(9,17),(13,65),(14,62),(15,66),(16,69),(17,63),(18,64),(19,68),(20,67),(21,71),(25,123),(26,129),(28,130),(30,131);
/*!40000 ALTER TABLE `cs_mod_rel_sub` ENABLE KEYS */;

--
-- Table structure for table `cs_modules`
--

DROP TABLE IF EXISTS `cs_modules`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
  `module_version` float NOT NULL,
  `clansuite_version` float NOT NULL,
  `core` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`module_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_modules`
--

/*!40000 ALTER TABLE `cs_modules` DISABLE KEYS */;
INSERT INTO `cs_modules` VALUES (1,'account','Jens-AndrÃ© Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Account Administration','This module handles all necessary account stuff - like login/logout etc.','module_account','account.module.php','account',1,'module_account.jpg',0.1,0,1),(3,'captcha','Jens-AndrÃ© Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Captcha Module','The captcha module presents a image only humanoids can read.','module_captcha','captcha.module.php','captcha',1,'module_captcha.jpg',0.1,0,1),(4,'index','Jens-AndrÃ© Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Index Module','This is the main site.','module_index','index.module.php','index',1,'module_index.jpg',0.1,0,1),(2,'admin','Jens-AndrÃ© Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Admin Interface','This is the Admin Control Panel','module_admin','admin.module.php','admin',1,'module_admin.jpg',0.1,0,1),(15,'matches','Florian Wolf, Jens-AndrÃ© Koch','http://www.clansuite.com','GPL v2','ClanSuite Group','Matches','The matches system of clansuite','module_matches','matches.module.php','matches',1,'module_matches.jpg',0.1,0.1,0),(6,'shoutbox','BjÃ¶rn Spiegel, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Shoutbox Modul','This module displays a shoutbox. You can do entries and administrate it ...','module_shoutbox','shoutbox.module.php','shoutbox',1,'module_shoutbox.jpg',0.1,0,0),(7,'news','Jens-AndrÃ© Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','News','News module','module_news','news.module.php','news',1,'module_news.jpg',0.1,0,0),(8,'filebrowser','Florian Wolf, Jens-AndrÃ© Koch','http://www.clansuite.com','GPL v2','clansuite group','Filebrowser','The filebrowser of clansuite','module_filebrowser','filebrowser.module.php','filebrowser',1,'module_filebrowser.jpg',0.1,0,0),(9,'serverlist','Jens-AndrÃ© Koch','http://www.clansuite.com','BSD','Clansuite Group','Serverlist','List Gameservers','module_serverlist','serverlist.module.php','serverlist',1,'module_serverlist.jpg',0.1,0,0),(13,'guestbook','Florian Wolf, Jens-AndrÃ© Koch','http://www.clansuite.com','GPL v2','ClanSuite Group','Guestbook','The guestbook for visitors','module_guestbook','guestbook.module.php','guestbook',1,'module_guestbook.jpg',0.1,0.1,0),(14,'forum','Florian Wolf, Jens-AndrÃ© Koch','http://www.clansuite.com','GPL v2','ClanSuite Group','Forum','The forum where people meet','module_forum','forum.module.php','forum',1,'module_forum.jpg',0.1,0.1,0),(18,'gallery','Florian Wolf, Jens-AndrÃ© Koch','http://www.clansuite.com','GPL v2','ClanSuite Group','Gallery','The gallery module of clansuite','module_gallery','gallery.module.php','gallery',1,'module_gallery.jpg',0.1,0.1,0),(19,'replays','Florian Wolf, Jens-AndrÃ© Koch','http://www.clansuite.com','GPL v2','ClanSuite Group','Replays','The replays area of clansuite','module_replays','replays.module.php','replays',1,'module_replays.jpg',0.1,0.1,0),(20,'messaging','Florian Wolf, Jens-AndrÃ© Koch','http://www.clansuite.com','GPL v2','ClanSuite Group','Messaging','The messaging module of clansuite','module_messaging','messaging.module.php','messaging',1,'module_messaging.jpg',0.1,0.1,0),(22,'staticpages','Jens-Andr? Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Static Pages','Static Pages store HTML content','module_staticpages','staticpages.module.php','staticpages',1,'module_staticpages.jpg',0.1,0.1,0),(25,'board','JAK  FW','http://www.clansuite.com','LGPL','2007 JAK  FW','Board','The Clansuite Board','module_board','board.module.php','board',1,'module_board.jpg',0,0.1,0),(28,'userslist','jak','','gpl','jak2007','userslist','userslist','module_userslist','userslist.module.php','userslist',1,'module_userslist.jpg',0.1,0.1,0),(29,'userlist','Jens Andre Koch','http://www.clansuite.com','GPL','Jens Andre Koch 2007','userlist','Lists all Users','module_userlist','userlist.module.php','userlist',1,'module_userlist.jpg',0,0.1,0),(30,'calendar','Jens Andre Koch','http://www.clansuite.com','lgpl','jak','Calendar','Calendar with Eventmanagement','module_calendar','calendar.module.php','calendar',1,'module_calendar.jpg',0.1,0.1,0);
/*!40000 ALTER TABLE `cs_modules` ENABLE KEYS */;

--
-- Table structure for table `cs_news`
--

DROP TABLE IF EXISTS `cs_news`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_news` (
  `news_id` int(11) NOT NULL auto_increment,
  `news_title` varchar(255) NOT NULL,
  `news_body` text NOT NULL,
  `cat_id` tinyint(4) NOT NULL default '0',
  `user_id` int(11) unsigned NOT NULL default '0',
  `news_added` int(11) default NULL,
  `news_status` int(11) NOT NULL,
  PRIMARY KEY  (`news_id`,`cat_id`,`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_news`
--

/*!40000 ALTER TABLE `cs_news` DISABLE KEYS */;
INSERT INTO `cs_news` VALUES (1,'testeintrag1','testbody1\r\n1\r\n2\r\n3\r\n4\r\n5\r\n6\r\n7\r\n8\r\n9\r\n10\r\ntestbody11',2,1,1168260056,0),(2,'testeintrag2','testeintrag2body',0,1,1168260056,0),(3,'testeintrag3','testeintrag3body',1,1,1168260056,0),(7,'testeintrag7','testeintrag7body',2,1,1168260056,0),(16,'Lore ipsum','<a href=\"index.html\">Nunc eget pretium</a> diam.\r\n                \r\n				<p>Praesent nisi sem, bibendum in, ultrices sit amet, euismod sit amet, dui. Fusce nibh. Curabitur pellentesque, lectus at <a href=\"index.html\">volutpat interdum</a>. Pellentesque a nibh quis nunc volutpat aliquam</p>\r\n				\r\n				<blockquote><p>Sed sodales nisl sit amet augue. Donec ultrices, augue ullamcorper posuere laoreet, turpis massa tristique justo, sed egestas metus magna sed purus.</p></blockquote>\r\n				\r\n				<code>margin-bottom: 12px;\r\n                font-size: 1.1em;\r\n                background: url(images/quote.gif);\r\n                padding-left: 28px;\r\n                color: #555;</code>\r\n\r\n                <ul>\r\n					<li>Tristique</li>\r\n					<li>Aenean</li>\r\n					<li>Pretium</li>\r\n				</ul>\r\n\r\n				<p>Eget feugiat est leo tempor quam. Ut quis neque convallis magna consequat molestie.</p>',1,1,1168260056,0);
/*!40000 ALTER TABLE `cs_news` ENABLE KEYS */;

--
-- Table structure for table `cs_options`
--

DROP TABLE IF EXISTS `cs_options`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_options` (
  `option_id` int(10) unsigned NOT NULL auto_increment,
  `name_id` int(10) unsigned NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`option_id`,`name_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_options`
--

/*!40000 ALTER TABLE `cs_options` DISABLE KEYS */;
INSERT INTO `cs_options` VALUES (1,1,'drahtgitter'),(2,2,'en');
/*!40000 ALTER TABLE `cs_options` ENABLE KEYS */;

--
-- Table structure for table `cs_profiles`
--

DROP TABLE IF EXISTS `cs_profiles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_profiles` (
  `profile_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birthday` int(11) NOT NULL,
  `gender` varchar(255) NOT NULL default '-',
  `height` int(11) NOT NULL,
  `address` varchar(255) NOT NULL default '-',
  `zipcode` varchar(255) NOT NULL default '-',
  `city` varchar(255) NOT NULL default '-',
  `country` varchar(255) NOT NULL default '-',
  `homepage` varchar(255) NOT NULL default '-',
  `icq` varchar(255) NOT NULL default '-',
  `msn` varchar(255) NOT NULL default '-',
  `skype` varchar(255) NOT NULL default '-',
  `phone` varchar(255) NOT NULL default '-',
  `mobile` varchar(255) NOT NULL default '-',
  `custom_text` text NOT NULL,
  PRIMARY KEY  (`profile_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_profiles`
--

/*!40000 ALTER TABLE `cs_profiles` DISABLE KEYS */;
INSERT INTO `cs_profiles` VALUES (1,1,1175369474,'Florian','Wolf',496274400,'male',178,'Mühlenstr. 65','78126','Jena','DE','http://www.clansuite.com','163164530','-','-','-','-','[b]bla[/b]'),(2,3,1172510321,'','',0,'-',0,'-','-','-','-','-','-','-','-','-','-','');
/*!40000 ALTER TABLE `cs_profiles` ENABLE KEYS */;

--
-- Table structure for table `cs_profiles_computer`
--

DROP TABLE IF EXISTS `cs_profiles_computer`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_profiles_computer` (
  `computer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cpu` text NOT NULL,
  `ram` text NOT NULL,
  `gpu` text NOT NULL,
  `sound` text NOT NULL,
  `hdd` text NOT NULL,
  `cdrom` text NOT NULL,
  `monitor` text NOT NULL,
  `devices` text NOT NULL,
  `network` text NOT NULL,
  `other` text NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY  (`computer_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_profiles_computer`
--

/*!40000 ALTER TABLE `cs_profiles_computer` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_profiles_computer` ENABLE KEYS */;

--
-- Table structure for table `cs_profiles_general`
--

DROP TABLE IF EXISTS `cs_profiles_general`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_profiles_general` (
  `general_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birthday` int(11) NOT NULL,
  `gender` varchar(255) NOT NULL default '-',
  `height` int(11) NOT NULL,
  `address` varchar(255) NOT NULL default '-',
  `zipcode` varchar(255) NOT NULL default '-',
  `city` varchar(255) NOT NULL default '-',
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL default '-',
  `homepage` varchar(255) NOT NULL default '-',
  `icq` varchar(255) NOT NULL default '-',
  `msn` varchar(255) NOT NULL default '-',
  `skype` varchar(255) NOT NULL default '-',
  `phone` varchar(255) NOT NULL default '-',
  `mobile` varchar(255) NOT NULL default '-',
  `custom_text` text NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY  (`general_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_profiles_general`
--

/*!40000 ALTER TABLE `cs_profiles_general` DISABLE KEYS */;
INSERT INTO `cs_profiles_general` VALUES (1,1,1175292000,'Florian','Wolf',496274400,'',178,'Mühlenstr. 65','78126','Jena','DDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDD','','http://www.clansuite.com','163164530','','','','','[b]bla[/b]',3),(2,3,1172510321,'','',0,'-',0,'-','-','-','','-','-','-','-','-','-','-','',0),(3,2,1175635148,'','',0,'-',0,'-','-','-','','-','-','-','-','-','-','-','',0);
/*!40000 ALTER TABLE `cs_profiles_general` ENABLE KEYS */;

--
-- Table structure for table `cs_profiles_guestbook`
--

DROP TABLE IF EXISTS `cs_profiles_guestbook`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_profiles_guestbook` (
  `gb_id` int(11) NOT NULL auto_increment,
  `from` int(11) NOT NULL default '0',
  `to` int(11) NOT NULL,
  `gb_added` int(12) default NULL,
  `gb_nick` varchar(25) default NULL,
  `gb_email` varchar(35) default NULL,
  `gb_icq` varchar(15) default NULL,
  `gb_website` varchar(35) default NULL,
  `gb_town` varchar(25) default NULL,
  `gb_text` text,
  `gb_ip` varchar(15) default NULL,
  `gb_comment` text,
  `image_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`gb_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_profiles_guestbook`
--

/*!40000 ALTER TABLE `cs_profiles_guestbook` DISABLE KEYS */;
INSERT INTO `cs_profiles_guestbook` VALUES (1,0,0,1003200322,'nick','email','123124','www.skdjf.de','sdfsfas','sadfasdfasdfasdfasdf','1231231231','mir nur ganz alleine :) [b]fu[/b] asdfasdf asdfffffffffffffffffasd asdfsadfsafsafsadf asdfsadfsfsafsafddddddddddddddddddddddddddddd  asdfsfsdfsdfsdfddddddddddddddddddddddddd dddddasdfsdfsdfsdfsaf assadfsaf ',3),(2,0,0,1175392043,'123','123','123','','123','123','0','',0),(3,0,0,1175919684,'nester tester','asdf','1234---1234','http://www.uschi.de','blablubb','asafsdfd [b]/uschi[/b]','127.0.0.1',NULL,0),(4,0,0,1175924624,'asdf','asdfafafaf','afaffa','faffa','fafafa','faafaffaaf','127.0.0.1',NULL,3),(5,0,0,1175928797,'asdfsadf','sdfas','sdfasdf','sdfasdf','1234234','21efsdfasdfasdf','127.0.0.1',NULL,0);
/*!40000 ALTER TABLE `cs_profiles_guestbook` ENABLE KEYS */;

--
-- Table structure for table `cs_quotes`
--

DROP TABLE IF EXISTS `cs_quotes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_quotes` (
  `quote_id` tinyint(4) NOT NULL auto_increment,
  `quote_body` text NOT NULL,
  `quote_author` varchar(40) NOT NULL,
  PRIMARY KEY  (`quote_id`),
  KEY `quotes_id` (`quote_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_quotes`
--

/*!40000 ALTER TABLE `cs_quotes` DISABLE KEYS */;
INSERT INTO `cs_quotes` VALUES (1,'The turning point came when the mechanics of the storage of knowledge within the brain was worked out. Once that had been done, it became possible to devise Educational tapes that would modify the mechanics in such a way as to place within the mind a body of knowledge ready-made so to speak.','Isaac Asimov (1920-1992)');
/*!40000 ALTER TABLE `cs_quotes` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_category_module`
--

DROP TABLE IF EXISTS `cs_rel_category_module`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_rel_category_module` (
  `category_id` int(10) unsigned NOT NULL,
  `module_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`category_id`,`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_rel_category_module`
--

/*!40000 ALTER TABLE `cs_rel_category_module` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_rel_category_module` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_category_name`
--

DROP TABLE IF EXISTS `cs_rel_category_name`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_rel_category_name` (
  `category_id` int(10) unsigned NOT NULL,
  `name_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`category_id`,`name_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_rel_category_name`
--

/*!40000 ALTER TABLE `cs_rel_category_name` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_rel_category_name` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_group_rights`
--

DROP TABLE IF EXISTS `cs_rel_group_rights`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_rel_group_rights` (
  `group_id` int(11) NOT NULL default '0',
  `right_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`group_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_rel_group_rights`
--

/*!40000 ALTER TABLE `cs_rel_group_rights` DISABLE KEYS */;
INSERT INTO `cs_rel_group_rights` VALUES (1,20),(3,10),(3,11),(3,12),(3,13),(3,14),(3,15),(3,16),(3,17),(3,18),(3,19),(3,20),(3,21),(3,22),(3,23),(3,24),(3,25),(3,26),(3,27),(3,28);
/*!40000 ALTER TABLE `cs_rel_group_rights` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_news_comments`
--

DROP TABLE IF EXISTS `cs_rel_news_comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_rel_news_comments` (
  `news_id` int(10) unsigned NOT NULL,
  `comment_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`news_id`,`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_rel_news_comments`
--

/*!40000 ALTER TABLE `cs_rel_news_comments` DISABLE KEYS */;
INSERT INTO `cs_rel_news_comments` VALUES (16,1),(16,2),(16,3),(16,4);
/*!40000 ALTER TABLE `cs_rel_news_comments` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_option_name`
--

DROP TABLE IF EXISTS `cs_rel_option_name`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_rel_option_name` (
  `option_id` int(10) unsigned NOT NULL,
  `name_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`option_id`,`name_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_rel_option_name`
--

/*!40000 ALTER TABLE `cs_rel_option_name` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_rel_option_name` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_user_groups`
--

DROP TABLE IF EXISTS `cs_rel_user_groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_rel_user_groups` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `group_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_rel_user_groups`
--

/*!40000 ALTER TABLE `cs_rel_user_groups` DISABLE KEYS */;
INSERT INTO `cs_rel_user_groups` VALUES (1,3),(8,3);
/*!40000 ALTER TABLE `cs_rel_user_groups` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_user_options`
--

DROP TABLE IF EXISTS `cs_rel_user_options`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_rel_user_options` (
  `option_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`option_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_rel_user_options`
--

/*!40000 ALTER TABLE `cs_rel_user_options` DISABLE KEYS */;
INSERT INTO `cs_rel_user_options` VALUES (1,1),(2,1);
/*!40000 ALTER TABLE `cs_rel_user_options` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_user_profile`
--

DROP TABLE IF EXISTS `cs_rel_user_profile`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_rel_user_profile` (
  `user_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`user_id`,`profile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_rel_user_profile`
--

/*!40000 ALTER TABLE `cs_rel_user_profile` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_rel_user_profile` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_user_rights`
--

DROP TABLE IF EXISTS `cs_rel_user_rights`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_rel_user_rights` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `right_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_rel_user_rights`
--

/*!40000 ALTER TABLE `cs_rel_user_rights` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_rel_user_rights` ENABLE KEYS */;

--
-- Table structure for table `cs_rights`
--

DROP TABLE IF EXISTS `cs_rights`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_rights` (
  `right_id` int(11) unsigned NOT NULL auto_increment,
  `area_id` int(11) NOT NULL default '0',
  `name` varchar(150) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY  (`right_id`,`area_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_rights`
--

/*!40000 ALTER TABLE `cs_rights` DISABLE KEYS */;
INSERT INTO `cs_rights` VALUES (11,5,'shoutbox_post','The right to post into the shoutbox'),(10,4,'cc_access','The right to access the control center'),(12,6,'cc_create_news','Add a news'),(13,7,'cc_access_filebrowser','Access the filebrowser'),(14,6,'cc_edit_news','Edit a news'),(15,6,'cc_view_news','View the news'),(16,17,'use_messaging_system','The ability to use the messaging system.'),(17,18,'cc_edit_themes','The ability to edit the themes'),(18,8,'cc_view_gb','View the guestbook'),(19,8,'cc_edit_gb','Edit the guestbook'),(20,8,'create_gb_entries','Create guestbook entries'),(21,19,'cc_edit_generals','Abilty to edit the normal profile stuff'),(22,19,'cc_edit_computers','Abilty to edit the computers in the profile'),(23,19,'cc_edit_userguestbooks','Abilty to edit the user guestbook'),(24,20,'cc_show_users','Show the users in the control center'),(25,20,'cc_create_users','Ability to create a user'),(26,20,'cc_edit_users','Ability to edit users'),(27,20,'cc_search_users','Abililty to search users (advanced) in the control center'),(28,20,'cc_delete_users','Ability to delete users'),(29,5,'shoutbox_view','Ability to view the shoutbox');
/*!40000 ALTER TABLE `cs_rights` ENABLE KEYS */;

--
-- Table structure for table `cs_serverlist`
--

DROP TABLE IF EXISTS `cs_serverlist`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_serverlist`
--

/*!40000 ALTER TABLE `cs_serverlist` DISABLE KEYS */;
INSERT INTO `cs_serverlist` VALUES (1,'team-n1.com','27339','knd-squad DEATHMATCH powered by CLANSUITE DOT COM ','steam','cs','de.png'),(2,'87.117.208.193','27025',' DigitalTakedown.org UK - Public Server','steam','cs','de.png'),(3,'83.133.81.95','27015','#German Headquarter 3 WC3FT | Team.GHQ | www.team-GHQ.eu ','steam','cs','de.png'),(4,'210.55.92.68','27980','Xtra Quake 3 RA #1','q3a','q3a','de.png'),(5,'85.14.233.32','27015','B I E R F R I E D H O F | TICK100 | by ngz-server.de','steam','cssource','de.png');
/*!40000 ALTER TABLE `cs_serverlist` ENABLE KEYS */;

--
-- Table structure for table `cs_serverviewer`
--

DROP TABLE IF EXISTS `cs_serverviewer`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_serverviewer` (
  `name` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_serverviewer`
--

/*!40000 ALTER TABLE `cs_serverviewer` DISABLE KEYS */;
INSERT INTO `cs_serverviewer` VALUES ('test');
/*!40000 ALTER TABLE `cs_serverviewer` ENABLE KEYS */;

--
-- Table structure for table `cs_session`
--

DROP TABLE IF EXISTS `cs_session`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_session` (
  `user_id` int(11) NOT NULL default '0',
  `session_id` varchar(32) NOT NULL,
  `session_data` text NOT NULL,
  `session_name` text NOT NULL,
  `session_expire` int(11) NOT NULL default '0',
  `session_visibility` tinyint(4) NOT NULL default '0',
  `session_where` text NOT NULL,
  UNIQUE KEY `session_id` (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_session`
--

/*!40000 ALTER TABLE `cs_session` DISABLE KEYS */;
INSERT INTO `cs_session` VALUES (0,'5a30d3a3591f15ffe7b112f5765a90f8','client_ip|s:9:\"127.0.0.1\";client_browser|s:87:\"Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.0.5) Gecko/2008120122 Firefox/3.0.5\";client_host|s:9:\"localhost\";user|a:11:{s:8:\"language\";s:2:\"de\";s:6:\"authed\";i:0;s:7:\"user_id\";i:0;s:4:\"nick\";s:5:\"Guest\";s:12:\"passwordhash\";s:0:\"\";s:5:\"email\";s:0:\"\";s:8:\"disabled\";i:0;s:9:\"activated\";i:0;s:5:\"theme\";s:8:\"standard\";s:6:\"groups\";a:1:{i:0;i:1;}s:6:\"rights\";a:1:{i:0;i:1;}}','suiteSID',1231449395,1,'sessionstart');
/*!40000 ALTER TABLE `cs_session` ENABLE KEYS */;

--
-- Table structure for table `cs_shoutbox`
--

DROP TABLE IF EXISTS `cs_shoutbox`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_shoutbox` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `msg` tinytext NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_shoutbox`
--

/*!40000 ALTER TABLE `cs_shoutbox` DISABLE KEYS */;
INSERT INTO `cs_shoutbox` VALUES (1,'12345','123test@test.com','texttext',1155898254,'127.0.0.1'),(2,'109876','123@123.123','shoutboxtesttest',1155898254,'127.0.0.1'),(3,'asdfasdfs','asdfasdfasdfasdf@asdf.de','dafasdghafg',1156304492,'127.0.0.1'),(4,'asdfasdfs','asdfasdfasdfasdf@asdf.de','dafasdghafg',1156304492,'127.0.0.1'),(5,'asdfsadfasdfas','asdfasdf@asdf.de','asdfasdfasdf',1156305870,'127.0.0.1'),(6,'asdfasdfasdf','asdfasdfasdfasdf@asdf.de','asdfasdfasdf',1156306849,'127.0.0.1'),(7,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307074,'127.0.0.1'),(8,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307074,'127.0.0.1'),(9,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307091,'127.0.0.1'),(10,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307099,'127.0.0.1'),(11,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307100,'127.0.0.1'),(12,'Your Nasddfasdfame','asdfasdfasdfasdf@asdf.de','sadfasdfasdfasdf',1156307101,'127.0.0.1'),(13,'sdfsdfgdsfg','asdfasdfasdfasdf@asdf.de','ghfgghjghj',1156307307,'127.0.0.1'),(14,'asdfasdfasfd','ad@ad.de','adfasdfasdf',1156307578,'127.0.0.1'),(15,'sdfgsdfgsdfg','rrrrr@rrrrrr.de','rrrrrrrrrrrrrrrrrrrrrr',1156307696,'127.0.0.1'),(16,'test','test@test.de','',1158356510,'127.0.0.1'),(17,'test','test@test.de','clansuite is just the best !!! you guys rock!!',1158356530,'127.0.0.1'),(18,'test','test@test.de','how are you? feeling well?',1158356544,'127.0.0.1'),(19,'test','test@test.de','next time this will be a chat :)',1158356601,'127.0.0.1'),(20,'Guest','test@test.de','you sure?',1158356786,'127.0.0.1'),(21,'admin','support@clansuite.com','asdfasdf',1175370494,'127.0.0.1');
/*!40000 ALTER TABLE `cs_shoutbox` ENABLE KEYS */;

--
-- Table structure for table `cs_static_pages`
--

DROP TABLE IF EXISTS `cs_static_pages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
SET character_set_client = @saved_cs_client;

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
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_submodules` (
  `submodule_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`submodule_id`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_submodules`
--

/*!40000 ALTER TABLE `cs_submodules` DISABLE KEYS */;
INSERT INTO `cs_submodules` VALUES (1,'admin','filebrowser.admin.php','module_filebrowser_admin'),(2,'configs','configs.module.php','module_admin_configs'),(3,'modules','modules.module.php','module_admin_modules'),(4,'users','users.module.php','module_admin_users'),(5,'usercenter','usercenter.module.php','module_admin_usercenter'),(6,'groups','groups.module.php','module_admin_groups'),(7,'permissions','perms.module.php','module_admin_permissions'),(8,'menueditor','menueditor.module.php','module_admin_menueditor'),(9,'static','static.module.php','module_admin_static'),(10,'bugs','bugs.module.php','module_admin_bugs'),(11,'manual','manual.module.php','module_admin_manual'),(12,'templates','templates.module.php','module_admin_templates'),(13,'settings','settings.module.php','module_admin_settings'),(14,'categories','categories.module.php','module_admin_categories'),(15,'help','help.module.php','module_admin_help'),(16,'admin','news.admin.php','module_news_admin'),(17,'admin','serverlist.admin.php','module_serverlist_admin'),(61,'bbcode','bbcode.module.php','module_admin_bbcode'),(62,'admin','forum.admin.php','module_forum_admin'),(63,'admin','articles.admin.php','module_articles_admin'),(64,'admin','gallery.admin.php','module_gallery_admin'),(65,'admin','guestbook.admin.php','module_guestbook_admin'),(66,'admin','matches.admin.php','module_matches_admin'),(67,'admin','messaging.admin.php','module_messaging_admin'),(68,'admin','replays.admin.php','module_replays_admin'),(69,'admin','downloads.admin.php','module_downloads_admin'),(70,'profile','profile.module.php','module_account_profile'),(18,'admin','staticpages.admin.php','module_static_admin'),(72,'themes','themes.module.php','module_admin_themes'),(123,'admin','board.admin.php','module_board_admin'),(124,'guestbook','guestbook.module.php','module_account_guestbook'),(125,'general','general.module.php','module_account_general'),(126,'computer','computer.module.php','module_account_computer'),(127,'bridges','bridges.module.php','module_admin_bridges'),(128,'converters','converter.module.php','module_admin_converter'),(129,'admin','userslist.admin.php','module_userslist_admin'),(130,'admin','userslist.admin.php','module_userslist_admin'),(131,'admin','calendar.admin.php','module_calendar_admin');
/*!40000 ALTER TABLE `cs_submodules` ENABLE KEYS */;

--
-- Table structure for table `cs_user_groups`
--

DROP TABLE IF EXISTS `cs_user_groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_user_groups` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `group_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_user_groups`
--

/*!40000 ALTER TABLE `cs_user_groups` DISABLE KEYS */;
INSERT INTO `cs_user_groups` VALUES (1,3);
/*!40000 ALTER TABLE `cs_user_groups` ENABLE KEYS */;

--
-- Table structure for table `cs_user_options`
--

DROP TABLE IF EXISTS `cs_user_options`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_user_options` (
  `option_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `language` varchar(255) NOT NULL,
  `theme` varchar(255) NOT NULL,
  PRIMARY KEY  (`option_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_user_options`
--

/*!40000 ALTER TABLE `cs_user_options` DISABLE KEYS */;
INSERT INTO `cs_user_options` VALUES (0,1,'en','accessible');
/*!40000 ALTER TABLE `cs_user_options` ENABLE KEYS */;

--
-- Table structure for table `cs_user_rights`
--

DROP TABLE IF EXISTS `cs_user_rights`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_user_rights` (
  `user_id` int(10) unsigned NOT NULL default '0',
  `right_id` int(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_user_rights`
--

/*!40000 ALTER TABLE `cs_user_rights` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_user_rights` ENABLE KEYS */;

--
-- Table structure for table `cs_users`
--

DROP TABLE IF EXISTS `cs_users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `cs_users` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(150) default NULL,
  `nick` varchar(25) NOT NULL,
  `passwordhash` varchar(40) NOT NULL,
  `new_passwordhash` varchar(40) NOT NULL,
  `salt` varchar(20) NOT NULL,
  `new_salt` varchar(40) NOT NULL,
  `activation_code` varchar(255) NOT NULL,
  `joined` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `disabled` tinyint(1) NOT NULL default '0',
  `activated` tinyint(1) NOT NULL default '0',
  `status` tinyint(4) NOT NULL default '0',
  `country` varchar(5) NOT NULL,
  `language` varchar(12) NOT NULL,
  `timezone` varchar(8) default NULL,
  `theme` varchar(255) NOT NULL,
  PRIMARY KEY  (`user_id`),
  KEY `email` (`email`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `cs_users`
--

/*!40000 ALTER TABLE `cs_users` DISABLE KEYS */;
INSERT INTO `cs_users` VALUES (1,'jakoch@web.de','user1','d033e22ae348aeb5660fc2140aec35850c4da997','','','','',1215754325,0,0,1,0,'de','de_DE','UTC1','standard'),(2,'user2@clansuite.com','user2','d1ca11799e222d429424d47b424047002ea72d44','','','','',1215763325,0,0,1,0,'de','de_DE','UTC1','standard'),(8,'user3@clansuite.com','user3','e5292e82b58ec55069d178b092ad25ee97f1917d','','G1vmXy','','',1215764325,0,0,1,0,'','',NULL,''),(9,'user4@clansuite.com','user4','90b525e43d877ee890e3cd800584fbddd7cd6668','','eVH0Jx','','',1215768110,0,0,1,0,'','',NULL,''),(10,'user5@clansuite.com','user5','ff4e167734b0cc1c61fb9ca064a18d85045aea80','','AxOD.2','','',1215984499,0,0,1,0,'','',NULL,''),(11,'admin@email.com','admin','3979339f2a534fea635cc6df254eb2a616490653','','CPEr2Z','','',1229294500,0,0,1,0,'','german',NULL,'');
/*!40000 ALTER TABLE `cs_users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-01-08 21:26:02
