-- MySQL dump 10.13  Distrib 5.1.37, for Win32 (ia32)
--
-- Host: localhost    Database: clansuite
-- ------------------------------------------------------
-- Server version	5.1.37

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
-- Current Database: `clansuite`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `clansuite` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `clansuite`;

--
-- Table structure for table `cs_adminmenu`
--

DROP TABLE IF EXISTS `cs_adminmenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_adminmenu`
--

/*!40000 ALTER TABLE `cs_adminmenu` DISABLE KEYS */;
INSERT INTO `cs_adminmenu` VALUES (1,0,'folder','Control Center','?mod=controlcenter','Clansuite Control Center','_self',0,'layout_header.png','admin'),(2,0,'folder','Redaktion','','Redaktion','_self',1,'page_edit.png',''),(3,2,'folder','News','','News','_self',0,'page_edit.png',''),(4,3,'item','Manage News','index.php?mod=news&amp;sub=admin','Manage News','_self',0,'application_form_edit.png','cc_edit_news'),(5,3,'item','Create news','index.php?mod=news&amp;sub=admin&amp;action=create','Create news','_self',1,'add.png','cc_create_news'),(6,2,'folder','Blog','','Blog','_self',1,'book_open.png',''),(7,6,'item','Manage Blog','index.php?mod=blog&amp;sub=admin','Manage Blog','_self',0,'pencil.png',''),(8,2,'folder','Articles','','Articles','_self',2,'application_form_edit.png',''),(9,8,'item','Manage Articles','index.php?mod=articles&amp;sub=admin','Manage Articles','_self',0,'bricks_edit.png',''),(10,2,'folder','Reviews','','Reviews','_self',3,'pencil.png',''),(11,10,'item','Manage Reviews','index.php?mod=reviews&amp;sub=admin','Manage Reviews','_self',0,'report.png',''),(12,2,'folder','Coverages','','Coverages','_self',4,'package.png',''),(13,12,'item','Manage Coverages','index.php?mod=coverages&amp;sub=admin','Manage Coverages','_self',0,'application_view_list.png',''),(14,0,'folder','Community','','Community','_self',2,'bricks.png',''),(15,14,'folder','Forum','','Forum','_self',0,'application_view_list.png',''),(16,15,'item','Manage Forum','index.php?mod=forum&amp;sub=admin','Manage Forum','_self',0,'application_form_edit.png',''),(17,14,'folder','Guestbook','index.php?mod=guestbook&amp;action=show','Guestbook','_self',1,'book_open.png',''),(18,17,'item','Manage Guestbook','index.php?mod=guestbook&amp;sub=admin','Manage Guestbook','_self',0,'application_form_edit.png',''),(19,14,'folder','Shoutbox','','Shoutbox','_self',2,'comment.png',''),(20,19,'item','Manage Shoutbox','index.php?mod=shoutbox&amp;sub=admin','Manage Shoutbox','_self',0,'application_form_edit.png',''),(21,0,'folder','Clanverwaltung','','Clanverwaltung','_self',3,'group.png',''),(22,21,'folder','Clankasse','','Clankasse','_self',0,'book_open.png',''),(23,22,'item','Manage Clancash','index.php?mod=clancash&amp;sub=admin','Manage Clancash','_self',0,'money_dollar.png',''),(24,21,'folder','Teams','','Teams','_self',1,'user_suit.png',''),(25,24,'item','Manage Teams','index.php?mod=teams&amp;sub=admin','Manage Teams','_self',0,'group.png',''),(26,21,'folder','Matches','index.php?mod=matches&amp;action=show','Matches','_self',2,'database_go.png',''),(27,26,'item','Manage Matches','index.php?mod=matches&amp;sub=admin','Manage Matches','_self',0,'application_form_edit.png',''),(28,21,'folder','Replays','','Replays','_self',3,'film.png',''),(29,28,'item','Manage Replays','index.php?mod=replays&amp;sub=admin','Manage Replays','_self',0,'application_form_edit.png',''),(30,21,'folder','Serverlist','','Serverlist','_self',4,'table.png',''),(31,30,'item','Show Servers','index.php?mod=serverlist&amp;sub=admin&amp;action=show','Show Servers','_self',0,'application_view_list.png',''),(32,30,'item','Add Server','index.php?mod=serverlist&amp;sub=admin&amp;action=create','Add Server','_self',1,'application_form_edit.png',''),(33,0,'folder','Mediacenter','','Mediacenter','_self',4,'film.png',''),(34,33,'folder','Downloads','','Downloads','_self',0,'disk.png',''),(35,34,'item','Manage Downloads','index.php?mod=downloads&amp;sub=admin','Manage Downloads','_self',0,'application_form_edit.png',''),(36,33,'folder','Gallery','','Gallery','_self',1,'map_go.png',''),(37,36,'item','Manage Gallery','index.php?mod=gallery&amp;sub=admin','Manage Gallery','_self',0,'application_form_edit.png',''),(38,33,'folder','Videos','','Videos','_self',2,'film.png',''),(39,38,'item','Manage Videos','index.php?mod=quotes&amp;sub=admin','Manage Videos','_self',0,'film.png',''),(40,0,'folder','Website','','Website','_self',5,'table.png',''),(41,40,'folder','Slideshow','','Slideshow','_self',0,'comment.png',''),(42,40,'folder','Quotes','','Quotes','_self',1,'report.png',''),(43,42,'item','Manage Quotes','index.php?mod=quotes&amp;sub=admin','Manage Quotes','_self',0,'report.png',''),(44,40,'folder','Static Pages','','Static Pages','_self',2,'html.png',''),(45,44,'item','Manage Static Pages','index.php?mod=staticpages&amp;sub=admin','Show Static Pages','_self',0,'pencil.png',''),(46,44,'item','Create new Static Page','index.php?mod=staticpages&amp;sub=admin&amp;action=create','Create new Static Page','_self',1,'add.png',''),(47,0,'folder','Users','','Users','_self',6,'user_suit.png',''),(48,47,'folder','Users','','Users','_self',0,'user_suit.png',''),(49,48,'item','Manage Users','index.php?mod=users&amp;sub=admin','Manage users','_self',0,'table.png','cc_show_users'),(50,48,'item','Search User','index.php?mod=users&amp;sub=admin&amp;action=search','Search a User','_self',1,'magnifier.png','cc_search_users'),(51,47,'folder','Groups','','Groups','_self',1,'group.png',''),(52,51,'item','Manage Groups','index.php?mod=groups&amp;sub=admin','Show all Groups','_self',0,'table.png',''),(53,51,'item','Create Group','index.php?mod=groups&amp;sub=admin&amp;action=create','Create a group','_self',1,'add.png',''),(54,47,'folder','Permissions','','Permissions','_self',2,'key.png',''),(55,54,'item','Manage Permissions','index.php?mod=permissions&amp;sub=admin','Manage permissions','_self',0,'add.png',''),(56,0,'folder','System','','System','_self',7,'computer.png',''),(57,56,'item','Settings','index.php?mod=settings&amp;sub=admin','Settings','_self',0,'settings.png',''),(58,56,'item','Categories','index.php?mod=categories&amp;sub=admin','Categories','_self',1,'spellcheck.png',''),(59,56,'item','Games','index.php?mod=games&amp;sub=admin','Games','_self',2,'module-inactive.gif',''),(60,56,'item','Cache','index.php?mod=cache&amp;sub=admin','Cache','self',3,'bullet_disk.png',''),(61,56,'folder','Wartung','','Wartung','_self',4,'warning.png',''),(62,61,'item','Cronjobs','index.php?mod=cronjobs&amp;sub=admin','Cronjobs','self',0,'settings.png',''),(63,61,'folder','Hooks & Events','','Hooks & Events','self',1,'settings.png',''),(64,63,'item','Bridges','index.php?mod=bridges&amp;sub=admin','Bridges','_self',0,'application_view_list.png',''),(65,61,'folder','Paket-Management','','Paket-Mangement','_self',2,'bricks.png',''),(66,65,'item','Search Pakets','index.php?mod=paketmanager&amp;sub=admin','Search Pakets','self',0,'package.png',''),(67,65,'item','Search Module','Search%20Modules','Search Module','self',1,'package.png',''),(68,65,'item','Search Themes','index.php?mod=paketmanager&amp;sub=admin','Search Themes','self',2,'package.png',''),(69,61,'folder','Database','','Database','_self',3,'database_gear.png',''),(70,69,'item','Import','','Import','_self',0,'database_go.png',''),(71,69,'item','Optimize','index.php?mod=database&amp;action=optimize','Optimize','_self',1,'database_gear.png',''),(72,69,'item','Export & Backup','index.php?mod=database&amp;action=backup','Export & Backup','_self',2,'database_key.png',''),(73,56,'folder','Layout','','Layout','_self',5,'layout_header.png',''),(74,73,'item','BB Codes','index.php?mod=admin&amp;sub=bbcode','BB Code Editor','_self',0,'text_bold.png',''),(75,73,'item','Menueditor','index.php?mod=menu&amp;sub=admin&amp;action=menueditor','Menueditor','_self',1,'application_form_edit.png',''),(76,73,'item','Templates','index.php?mod=templatemanager&amp;sub=admin','Template Editor','_self',2,'layout_edit.png',''),(77,73,'item','Themes','index.php?mod=thememanager&amp;sub=admin','Themes Manager','_self',3,'layout_edit.png','cc_edit_themes'),(78,56,'folder','Modules','','Modules','_self',6,'bricks.png',''),(79,78,'item','Install Modules','index.php?mod=modulemanager&amp;sub=admin&amp;action=install','Install new modules','_self',0,'package.png',''),(80,78,'item','Create Module','index.php?mod=modulemanager&amp;sub=admin&amp;action=builder','Create a module','_self',1,'add.png',''),(81,78,'item','Manage modules','index.php?mod=modulemanager&amp;sub=admin&amp;action=show','Manage modules','_self',2,'bricks_edit.png',''),(82,78,'item','Export a module','index.php?mod=modulemanager&amp;sub=admin&amp;action=export','Export a module','_self',3,'compress.png',''),(83,56,'folder','Language','','Language','_self',7,'spellcheck.png',''),(84,83,'item','Language Editor','index.php?mod=language&amp;sub=admin','Language Editor','_self',0,'spellcheck.png',''),(85,56,'folder','Logs','index.php?mod=logs&amp;sub=admin','Logs','self',8,'table.png',''),(86,85,'item','Overview','index.php?mod=logs&amp;ampsub=admin','Overview','self',0,'table.png',''),(87,85,'item','Error Log','index.php?mod=logs&amp;sub=admin&amp;action=errorlog','Error Log','self',1,'table.png',''),(88,85,'item','Moderator Log','index.php?mod=logs&amp;sub=admin&amp;action=','Moderator Log','self',2,'table.png',''),(89,56,'item','Statistics','index.php?mod=statistics&amp;sub=admin','Statistics','_self',9,'table.png',''),(90,56,'item','Systeminformation','index.php?mod=systeminfo&amp;sub=admin','Systeminformation','_self',10,'information.png',''),(91,0,'folder','Help','','Help','_self',8,'help.png',''),(92,91,'item','Help & Support','index.php?mod=controlcenter&amp;&amp;action=show&amp;action=supportlinks','Help','_self',0,'help.png',''),(93,91,'item','Online-Manuals','http://www.clansuite.com/documentation','Manual','_blank',1,'book_open.png',''),(94,91,'item','Report Bugs & Issues','index.php?mod=controlcenter&amp;action=bugs','Report Bugs & Issues','_self',2,'error.png',''),(95,91,'item','About Clansuite','index.php?mod=controlcenter&amp;action=about','About Clansuite','_self',3,'information.png','');
/*!40000 ALTER TABLE `cs_adminmenu` ENABLE KEYS */;

--
-- Table structure for table `cs_adminmenu_backup`
--

DROP TABLE IF EXISTS `cs_adminmenu_backup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_adminmenu_backup`
--

/*!40000 ALTER TABLE `cs_adminmenu_backup` DISABLE KEYS */;
INSERT INTO `cs_adminmenu_backup` VALUES (1,0,'folder','Control Center','?mod=controlcenter','Clansuite Control Center','_self',0,'layout_header.png','admin'),(2,0,'folder','Redaktion','','Redaktion','_self',1,'page_edit.png',''),(3,2,'folder','News','','News','_self',0,'page_edit.png',''),(4,3,'item','Manage News','index.php?mod=news&amp;sub=admin','Manage News','_self',0,'application_form_edit.png','cc_edit_news'),(5,3,'item','Create news','index.php?mod=news&amp;sub=admin&amp;action=create','Create news','_self',1,'add.png','cc_create_news'),(6,2,'folder','Blog','','Blog','_self',1,'book_open.png',''),(7,6,'item','Manage Blog','index.php?mod=blog&amp;sub=admin','Manage Blog','_self',0,'pencil.png',''),(8,2,'folder','Articles','','Articles','_self',2,'application_form_edit.png',''),(9,8,'item','Manage Articles','index.php?mod=articles&amp;sub=admin','Manage Articles','_self',0,'bricks_edit.png',''),(10,2,'folder','Reviews','','Reviews','_self',3,'pencil.png',''),(11,10,'item','Manage Reviews','index.php?mod=reviews&amp;sub=admin','Manage Reviews','_self',0,'report.png',''),(12,2,'folder','Coverages','','Coverages','_self',4,'package.png',''),(13,12,'item','Manage Coverages','index.php?mod=coverages&amp;sub=admin','Manage Coverages','_self',0,'application_view_list.png',''),(14,0,'folder','Community','','Community','_self',2,'bricks.png',''),(15,14,'folder','Forum','','Forum','_self',0,'application_view_list.png',''),(16,15,'item','Manage Forum','index.php?mod=forum&amp;sub=admin','Manage Forum','_self',0,'application_form_edit.png',''),(17,14,'folder','Guestbook','index.php?mod=guestbook&amp;action=show','Guestbook','_self',1,'book_open.png',''),(18,17,'item','Manage Guestbook','index.php?mod=guestbook&amp;sub=admin','Manage Guestbook','_self',0,'application_form_edit.png',''),(19,14,'folder','Shoutbox','','Shoutbox','_self',2,'comment.png',''),(20,19,'item','Manage Shoutbox','index.php?mod=shoutbox&amp;sub=admin','Manage Shoutbox','_self',0,'application_form_edit.png',''),(21,0,'folder','Clanverwaltung','','Clanverwaltung','_self',3,'group.png',''),(22,21,'folder','Clankasse','','Clankasse','_self',0,'book_open.png',''),(23,22,'item','Manage Clancash','index.php?mod=clancash&amp;sub=admin','Manage Clancash','_self',0,'money_dollar.png',''),(24,21,'folder','Teams','','Teams','_self',1,'user_suit.png',''),(25,24,'item','Manage Teams','index.php?mod=teams&amp;sub=admin','Manage Teams','_self',0,'group.png',''),(26,21,'folder','Matches','index.php?mod=matches&amp;action=show','Matches','_self',2,'database_go.png',''),(27,26,'item','Manage Matches','index.php?mod=matches&amp;sub=admin','Manage Matches','_self',0,'application_form_edit.png',''),(28,21,'folder','Replays','','Replays','_self',3,'film.png',''),(29,28,'item','Manage Replays','index.php?mod=replays&amp;sub=admin','Manage Replays','_self',0,'application_form_edit.png',''),(30,21,'folder','Serverlist','','Serverlist','_self',4,'table.png',''),(31,30,'item','Show Servers','index.php?mod=serverlist&amp;sub=admin&amp;action=show','Show Servers','_self',0,'application_view_list.png',''),(32,30,'item','Add Server','index.php?mod=serverlist&amp;sub=admin&amp;action=create','Add Server','_self',1,'application_form_edit.png',''),(33,0,'folder','Mediacenter','','Mediacenter','_self',4,'film.png',''),(34,33,'folder','Downloads','','Downloads','_self',0,'disk.png',''),(35,34,'item','Manage Downloads','index.php?mod=downloads&amp;sub=admin','Manage Downloads','_self',0,'application_form_edit.png',''),(36,33,'folder','Gallery','','Gallery','_self',1,'map_go.png',''),(37,36,'item','Manage Gallery','index.php?mod=gallery&amp;sub=admin','Manage Gallery','_self',0,'application_form_edit.png',''),(38,33,'folder','Videos','','Videos','_self',2,'film.png',''),(39,38,'item','Manage Videos','index.php?mod=quotes&amp;sub=admin','Manage Videos','_self',0,'film.png',''),(40,0,'folder','Website','','Website','_self',5,'table.png',''),(41,40,'folder','Slideshow','','Slideshow','_self',0,'comment.png',''),(42,40,'folder','Quotes','','Quotes','_self',1,'report.png',''),(43,42,'item','Manage Quotes','index.php?mod=quotes&amp;sub=admin','Manage Quotes','_self',0,'report.png',''),(44,40,'folder','Static Pages','','Static Pages','_self',2,'html.png',''),(45,44,'item','Manage Static Pages','index.php?mod=staticpages&amp;sub=admin','Show Static Pages','_self',0,'pencil.png',''),(46,44,'item','Create new Static Page','index.php?mod=staticpages&amp;sub=admin&amp;action=create','Create new Static Page','_self',1,'add.png',''),(47,0,'folder','Users','','Users','_self',6,'user_suit.png',''),(48,47,'folder','Users','','Users','_self',0,'user_suit.png',''),(49,48,'item','Manage Users','index.php?mod=users&amp;sub=admin','Manage users','_self',0,'table.png','cc_show_users'),(50,48,'item','Search User','index.php?mod=users&amp;sub=admin&amp;action=search','Search a User','_self',1,'magnifier.png','cc_search_users'),(51,47,'folder','Groups','','Groups','_self',1,'group.png',''),(52,51,'item','Manage Groups','index.php?mod=groups&amp;sub=admin','Show all Groups','_self',0,'table.png',''),(53,51,'item','Create Group','index.php?mod=groups&amp;sub=admin&amp;action=create','Create a group','_self',1,'add.png',''),(54,47,'folder','Permissions','','Permissions','_self',2,'key.png',''),(55,54,'item','Manage Permissions','index.php?mod=permissions&amp;sub=admin','Manage permissions','_self',0,'add.png',''),(56,0,'folder','System','','System','_self',7,'computer.png',''),(57,56,'item','Settings','index.php?mod=settings&amp;sub=admin','Settings','_self',0,'settings.png',''),(58,56,'item','Categories','index.php?mod=categories&amp;sub=admin','Categories','_self',1,'spellcheck.png',''),(59,56,'item','Games','index.php?mod=games&amp;sub=admin','Games','_self',2,'module-inactive.gif',''),(60,56,'item','Cache','index.php?mod=cache&amp;sub=admin','Cache','self',3,'bullet_disk.png',''),(61,56,'folder','Wartung','','Wartung','_self',4,'warning.png',''),(62,61,'item','Cronjobs','index.php?mod=cronjobs&amp;sub=admin','Cronjobs','self',0,'settings.png',''),(63,61,'folder','Hooks & Events','','Hooks & Events','self',1,'settings.png',''),(64,63,'item','Bridges','index.php?mod=bridges&amp;sub=admin','Bridges','_self',0,'application_view_list.png',''),(65,61,'folder','Paket-Management','','Paket-Mangement','_self',2,'bricks.png',''),(66,65,'item','Search Pakets','index.php?mod=paketmanager&amp;sub=admin','Search Pakets','self',0,'package.png',''),(67,65,'item','Search Module','Search%20Modules','Search Module','self',1,'package.png',''),(68,65,'item','Search Themes','index.php?mod=paketmanager&amp;sub=admin','Search Themes','self',2,'package.png',''),(69,61,'folder','Database','','Database','_self',3,'database_gear.png',''),(70,69,'item','Import','','Import','_self',0,'database_go.png',''),(71,69,'item','Optimize','index.php?mod=database&amp;action=optimize','Optimize','_self',1,'database_gear.png',''),(72,69,'item','Export & Backup','index.php?mod=database&amp;action=backup','Export & Backup','_self',2,'database_key.png',''),(73,56,'folder','Layout','','Layout','_self',5,'layout_header.png',''),(74,73,'item','BB Codes','index.php?mod=admin&amp;sub=bbcode','BB Code Editor','_self',0,'text_bold.png',''),(75,73,'item','Adminmenu','index.php?mod=menu&amp;sub=admin&amp;action=menueditor','Adminmenu Editor','_self',1,'application_form_edit.png',''),(76,73,'item','Templates','index.php?mod=templatemanager&amp;sub=admin','Template Editor','_self',2,'layout_edit.png',''),(77,73,'item','Themes','index.php?mod=thememanager&amp;sub=admin','Themes Manager','_self',3,'layout_edit.png','cc_edit_themes'),(78,56,'folder','Modules','','Modules','_self',6,'bricks.png',''),(79,78,'item','Install Modules','index.php?mod=modulemanager&amp;sub=admin&amp;action=install','Install new modules','_self',0,'package.png',''),(80,78,'item','Create Module','index.php?mod=modulemanager&amp;sub=admin&amp;action=builder','Create a module','_self',1,'add.png',''),(81,78,'item','Manage modules','index.php?mod=modulemanager&amp;sub=admin&amp;action=show','Manage modules','_self',2,'bricks_edit.png',''),(82,78,'item','Export a module','index.php?mod=modulemanager&amp;sub=admin&amp;action=export','Export a module','_self',3,'compress.png',''),(83,56,'folder','Language','','Language','_self',7,'spellcheck.png',''),(84,83,'item','Language Editor','index.php?mod=language&amp;sub=admin','Language Editor','_self',0,'spellcheck.png',''),(85,56,'folder','Logs','index.php?mod=logs&amp;sub=admin','Logs','self',8,'table.png',''),(86,85,'item','Overview','index.php?mod=logs&amp;ampsub=admin','Overview','self',0,'table.png',''),(87,85,'item','Error Log','index.php?mod=logs&amp;sub=admin&amp;action=errorlog','Error Log','self',1,'table.png',''),(88,85,'item','Moderator Log','index.php?mod=logs&amp;sub=admin&amp;action=','Moderator Log','self',2,'table.png',''),(89,56,'item','Statistics','index.php?mod=statistics&amp;sub=admin','Statistics','_self',9,'table.png',''),(90,56,'item','Systeminformation','index.php?mod=systeminfo&amp;sub=admin','Systeminformation','_self',10,'information.png',''),(91,0,'folder','Help','','Help','_self',8,'help.png',''),(92,91,'item','Help & Support','index.php?mod=controlcenter&amp;&amp;action=show&amp;action=supportlinks','Help','_self',0,'help.png',''),(93,91,'item','Online-Manuals','http://www.clansuite.com/documentation','Manual','_blank',1,'book_open.png',''),(94,91,'item','Report Bugs & Issues','index.php?mod=controlcenter&amp;action=bugs','Report Bugs & Issues','_self',2,'error.png',''),(95,91,'item','About Clansuite','index.php?mod=controlcenter&amp;action=about','About Clansuite','_self',3,'information.png','');
/*!40000 ALTER TABLE `cs_adminmenu_backup` ENABLE KEYS */;

--
-- Table structure for table `cs_adminmenu_shortcuts`
--

DROP TABLE IF EXISTS `cs_adminmenu_shortcuts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_adminmenu_shortcuts` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `href` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` tinyint(4) NOT NULL DEFAULT '30',
  `cat` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_areas` (
  `area_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'New Area',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`area_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_board_forums` (
  `forumid` int(11) NOT NULL AUTO_INCREMENT,
  `forumparent` int(11) NOT NULL DEFAULT '0',
  `name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `displayorder` smallint(6) NOT NULL DEFAULT '0',
  `moderator` int(11) DEFAULT NULL,
  `posts` int(11) NOT NULL,
  `threads` int(11) NOT NULL,
  `permissions` int(11) DEFAULT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastpost` varchar(54) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`forumid`,`forumparent`,`displayorder`,`status`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_board_posts` (
  `forumid` int(11) NOT NULL DEFAULT '0',
  `threadid` int(11) NOT NULL DEFAULT '0',
  `postid` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `subject` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `date` int(10) NOT NULL DEFAULT '0',
  `icon` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signatur` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `bbcodeoff` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `smileyoff` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `edited_by` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`postid`),
  KEY `fid` (`forumid`),
  KEY `tid` (`threadid`),
  KEY `dateline` (`date`),
  KEY `author` (`author`(8))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_board_posts`
--

/*!40000 ALTER TABLE `cs_board_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_board_posts` ENABLE KEYS */;

--
-- Table structure for table `cs_board_threads`
--

DROP TABLE IF EXISTS `cs_board_threads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_board_threads` (
  `threadid` int(11) NOT NULL AUTO_INCREMENT,
  `forumid` int(11) NOT NULL DEFAULT '0',
  `subject` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `lastpost` varchar(54) COLLATE utf8_unicode_ci NOT NULL,
  `views` bigint(32) NOT NULL DEFAULT '0',
  `replies` int(10) NOT NULL DEFAULT '0',
  `author` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `closed` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `stickified` tinyint(1) NOT NULL DEFAULT '0',
  `poll` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`forumid`),
  KEY `fid` (`forumid`),
  KEY `tid` (`threadid`),
  KEY `lastpost` (`lastpost`),
  KEY `author` (`author`(8)),
  KEY `closed` (`closed`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_categories`
--

/*!40000 ALTER TABLE `cs_categories` DISABLE KEYS */;
INSERT INTO `cs_categories` VALUES (1,7,1,'-keine-','Diese News sind keiner Kategorie zugeordnet','themes/core/images/nopreview.jpg','themes/core/images/nopreview.jpg','#000000'),(2,7,2,'Allgemein','Thema Allgemein','http://www.clansuite-dev.com/uploads/images/gallery/kunst.jpg','http://www.clansuite-dev.com/uploads/images/gallery/raetsel_1.jpg','#000000'),(3,7,3,'Member','Thema Members','themes/core/images/nopreview.jpg','themes/core/images/nopreview.jpg','#3366CC'),(4,7,4,'Page','Thema Page','themes/core/images/nopreview.jpg','themes/core/images/nopreview.jpg','#000000'),(5,7,5,'IRC','Thema IRC','themes/core/images/nopreview.jpg','themes/core/images/nopreview.jpg','#000000'),(6,7,6,'Clan-Wars','Thema Matches','themes/core/images/nopreview.jpg','themes/core/images/nopreview.jpg','#000000'),(7,7,7,'Sonstiges','Thema Hardware','','','#000000');
/*!40000 ALTER TABLE `cs_categories` ENABLE KEYS */;

--
-- Table structure for table `cs_comments`
--

DROP TABLE IF EXISTS `cs_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_comments`
--

/*!40000 ALTER TABLE `cs_comments` DISABLE KEYS */;
INSERT INTO `cs_comments` VALUES (1,1,'','123','2005-07-29 13:04:07','','127.0.0.1','localhost'),(2,2,'','1234567','2005-07-29 16:50:08','blub','127.0.0.1','localhost');
/*!40000 ALTER TABLE `cs_comments` ENABLE KEYS */;

--
-- Table structure for table `cs_cs_news_index`
--

DROP TABLE IF EXISTS `cs_cs_news_index`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_cs_news_index` (
  `user_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `news_title` text COLLATE utf8_unicode_ci NOT NULL,
  `news_body` text COLLATE utf8_unicode_ci NOT NULL,
  `keyword` text COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL,
  `field` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_cs_news_index`
--

/*!40000 ALTER TABLE `cs_cs_news_index` DISABLE KEYS */;
INSERT INTO `cs_cs_news_index` VALUES (0,7,0,'','','666',0,'news_title'),(0,7,0,'','','6666',0,'news_body'),(0,7,0,'','','br',1,'news_body'),(0,3,0,'','','stichwort',0,'news_title'),(0,3,0,'','','stichwort',0,'news_body'),(0,3,0,'','','br',1,'news_body'),(0,0,66,'','','stichwort',0,'news_title'),(0,0,66,'','','stichwort',0,'news_body'),(0,0,66,'','','stichwort',1,'news_body'),(0,1,66,'','','stichwort',0,'news_title'),(0,1,66,'','','stichwort',0,'news_body'),(0,1,66,'','','stichwortasasd',1,'news_body'),(0,3,66,'','','br',1,'news_body'),(0,3,66,'','','stichwort',0,'news_body'),(0,3,66,'','','6',1,'news_title'),(0,3,66,'','','stichwort66',0,'news_title'),(0,2,66,'','','stichwort6612366',0,'news_body'),(0,2,66,'','','stichwort6612366',0,'news_title'),(0,2,66,'','','br',1,'news_body'),(2,2,24,'','','archiviert4',0,'news_title'),(2,2,24,'','','lorem',0,'news_body'),(2,2,24,'','','ipsum',1,'news_body'),(2,2,24,'','','dolor',2,'news_body'),(2,2,24,'','','sit',3,'news_body'),(2,2,24,'','','amet',4,'news_body'),(2,2,24,'','','consectetur',5,'news_body'),(2,2,24,'','','adipiscing',6,'news_body'),(2,2,24,'','','elit',7,'news_body'),(2,2,24,'','','pellentesque',8,'news_body'),(2,2,24,'','','imperdiet',9,'news_body'),(2,2,24,'','','tristique',10,'news_body'),(2,2,24,'','','justo',11,'news_body'),(2,2,24,'','','ac',12,'news_body'),(2,2,24,'','','pretium',13,'news_body'),(2,2,24,'','','fusce',14,'news_body'),(2,2,24,'','','odio',15,'news_body'),(2,2,24,'','','nisl',16,'news_body'),(2,2,24,'','','dictum',17,'news_body'),(2,2,24,'','','sed',18,'news_body'),(2,2,24,'','','vestibulum',19,'news_body'),(2,2,24,'','','tristique',21,'news_body'),(2,2,24,'','','malesuada',22,'news_body'),(2,2,24,'','','lacus',23,'news_body'),(2,2,24,'','','suspendisse',24,'news_body'),(2,2,24,'','','potenti',25,'news_body'),(2,2,24,'','','proin',26,'news_body'),(2,2,24,'','','bibendum',27,'news_body'),(2,2,24,'','','placerat',28,'news_body'),(2,2,24,'','','neque',29,'news_body'),(2,2,24,'','','ut',30,'news_body'),(2,2,24,'','','placerat',31,'news_body'),(2,2,24,'','','nunc',32,'news_body'),(2,2,24,'','','felis',33,'news_body'),(2,2,24,'','','ligula',34,'news_body'),(2,2,24,'','','ullamcorper',35,'news_body'),(2,2,24,'','','sed',36,'news_body'),(2,2,24,'','','congue',37,'news_body'),(2,2,24,'','','ut',38,'news_body'),(2,2,24,'','','pulvinar',39,'news_body'),(2,2,24,'','','ut',40,'news_body'),(2,2,24,'','','sapien',41,'news_body'),(2,2,24,'','','nullam',42,'news_body'),(2,2,24,'','','sem',43,'news_body'),(2,2,24,'','','purus',44,'news_body'),(2,2,24,'','','adipiscing',45,'news_body'),(2,2,24,'','','ut',46,'news_body'),(2,2,24,'','','consectetur',47,'news_body'),(2,2,24,'','','ornare',49,'news_body'),(2,2,24,'','','eget',50,'news_body'),(2,2,24,'','','magna',51,'news_body'),(2,2,24,'','','vivamus',52,'news_body'),(2,2,24,'','','ut',53,'news_body'),(2,2,24,'','','eros',54,'news_body'),(2,2,24,'','','vitae',55,'news_body'),(2,2,24,'','','neque',56,'news_body'),(2,2,24,'','','aliquam',57,'news_body'),(2,2,24,'','','vulputate',58,'news_body'),(2,2,24,'','','et',59,'news_body'),(2,2,24,'','','molestie',60,'news_body'),(2,2,24,'','','arcu',61,'news_body'),(2,2,24,'','','etiam',62,'news_body'),(2,2,24,'','','et',63,'news_body'),(2,2,24,'','','metus',64,'news_body'),(2,2,24,'','','id',65,'news_body'),(2,2,24,'','','risus',66,'news_body'),(2,2,24,'','','pulvinar',67,'news_body'),(2,2,24,'','','pretium',68,'news_body'),(2,2,24,'','','sed',69,'news_body'),(2,2,24,'','','vulputate',70,'news_body'),(2,2,24,'','','venenatis',71,'news_body'),(2,2,24,'','','consectetur',72,'news_body'),(2,2,24,'','','mauris',73,'news_body'),(2,2,24,'','','id',74,'news_body'),(2,2,24,'','','odio',75,'news_body'),(2,2,24,'','','risus',76,'news_body'),(2,2,24,'','','maecenas',77,'news_body'),(2,2,24,'','','lacinia',78,'news_body'),(2,2,24,'','','iaculis',79,'news_body'),(2,2,24,'','','nisi',80,'news_body'),(2,2,24,'','','vel',81,'news_body'),(2,2,24,'','','egestas',82,'news_body'),(2,2,24,'','','suspendisse',83,'news_body'),(2,2,24,'','','potenti',84,'news_body'),(2,2,24,'','','quisque',85,'news_body'),(2,2,24,'','','vel',86,'news_body'),(2,2,24,'','','dui',87,'news_body'),(2,2,24,'','','lectus',89,'news_body'),(2,2,24,'','','ultrices',90,'news_body'),(2,2,24,'','','vehicula',91,'news_body'),(2,2,24,'','','donec',92,'news_body'),(2,2,24,'','','convallis',93,'news_body'),(2,2,24,'','','tortor',94,'news_body'),(2,2,24,'','','et',95,'news_body'),(2,2,24,'','','leo',96,'news_body'),(2,2,24,'','','vehicula',97,'news_body'),(2,2,24,'','','non',98,'news_body'),(2,2,24,'','','placerat',99,'news_body'),(2,2,24,'','','mi',100,'news_body'),(2,2,24,'','','varius',101,'news_body'),(2,2,24,'','','morbi',102,'news_body'),(2,2,24,'','','ac',103,'news_body'),(2,2,24,'','','pharetra',104,'news_body'),(2,2,24,'','','lorem',105,'news_body'),(2,2,24,'','','cum',106,'news_body'),(2,2,24,'','','sociis',107,'news_body'),(2,2,24,'','','natoque',108,'news_body'),(2,2,24,'','','penatibus',109,'news_body'),(2,2,24,'','','et',110,'news_body'),(2,2,24,'','','magnis',111,'news_body'),(2,2,24,'','','dis',112,'news_body'),(2,2,24,'','','parturient',113,'news_body'),(2,2,24,'','','montes',114,'news_body'),(2,2,24,'','','nascetur',115,'news_body'),(2,2,24,'','','ridiculus',116,'news_body'),(2,2,24,'','','mus',117,'news_body'),(2,2,24,'','','fusce',118,'news_body'),(2,2,24,'','','cursus',119,'news_body'),(2,2,24,'','','pulvinar',120,'news_body'),(2,2,24,'','','nisl',121,'news_body'),(2,2,24,'','','nunc',122,'news_body'),(2,2,24,'','','nisi',124,'news_body'),(2,2,24,'','','vel',125,'news_body'),(2,2,24,'','','purus',126,'news_body'),(2,2,24,'','','sagittis',127,'news_body'),(2,2,24,'','','bibendum',128,'news_body'),(2,2,24,'','','br',129,'news_body'),(2,2,24,'','','br',130,'news_body'),(2,2,24,'','','patze',131,'news_body'),(2,2,24,'','','br',132,'news_body'),(0,4,0,'','','test',0,'news_title'),(0,4,0,'','','test',0,'news_body'),(0,2,0,'','','test',0,'news_title'),(0,2,0,'','','fuer',1,'news_title'),(0,2,0,'','','editorfunktion',2,'news_title'),(0,6,0,'','','testfkjhg',0,'news_title'),(0,6,0,'','','oeaufjkgb',0,'news_title'),(0,6,0,'','','aelskjgfv',0,'news_body'),(0,2,0,'','','willkommen',0,'news_title'),(0,2,0,'','','auf',1,'news_title'),(0,2,0,'','','ihrer',2,'news_title'),(0,2,0,'','','neuen',3,'news_title'),(0,2,0,'','','clansuite',4,'news_title'),(0,2,0,'','','website',5,'news_title'),(0,2,0,'','','bitte',0,'news_body'),(0,2,0,'','','folgen',1,'news_body'),(0,2,0,'','','sie',2,'news_body'),(0,2,0,'','','diesen',3,'news_body'),(0,2,0,'','','schritten',4,'news_body'),(0,2,0,'','','um',5,'news_body'),(0,2,0,'','','mit',6,'news_body'),(0,2,0,'','','ihrer',7,'news_body'),(0,2,0,'','','website',8,'news_body'),(0,2,0,'','','zu',9,'news_body'),(0,2,0,'','','beginnen',10,'news_body'),(0,2,0,'','','1',14,'news_body'),(0,2,0,'','','konfigurieren',15,'news_body'),(0,2,0,'','','sie',16,'news_body'),(0,2,0,'','','ihre',17,'news_body'),(0,2,0,'','','website',18,'news_body'),(0,2,0,'','','wenn',19,'news_body'),(0,2,0,'','','sie',20,'news_body'),(0,2,0,'','','angemeldet',21,'news_body'),(0,2,0,'','','sind',22,'news_body'),(0,2,0,'','','besuchen',23,'news_body'),(0,2,0,'','','sie',24,'news_body'),(0,2,0,'','','den',25,'news_body'),(0,2,0,'','','administrationsbereich',26,'news_body'),(0,2,0,'','','dem',28,'news_body'),(0,2,0,'','','sie',29,'news_body'),(0,2,0,'','','alle',30,'news_body'),(0,2,0,'','','aspekte',31,'news_body'),(0,2,0,'','','ihrer',32,'news_body'),(0,2,0,'','','website',33,'news_body'),(0,2,0,'','','anpassen',34,'news_body'),(0,2,0,'','','und',35,'news_body'),(0,2,0,'','','konfigurieren',36,'news_body'),(0,2,0,'','','konnen',37,'news_body'),(0,2,0,'','','2',40,'news_body'),(0,2,0,'','','aktivieren',41,'news_body'),(0,2,0,'','','sie',42,'news_body'),(0,2,0,'','','zusatzliche',43,'news_body'),(0,2,0,'','','funktionen',44,'news_body'),(0,2,0,'','','als',45,'news_body'),(0,2,0,'','','nachstes',46,'news_body'),(0,2,0,'','','konnen',47,'news_body'),(0,2,0,'','','sie',48,'news_body'),(0,2,0,'','','der',50,'news_body'),(0,2,0,'','','modulubersicht',51,'news_body'),(0,2,0,'','','die',52,'news_body'),(0,2,0,'','','funktionen',53,'news_body'),(0,2,0,'','','aktivieren',54,'news_body'),(0,2,0,'','','die',55,'news_body'),(0,2,0,'','','ihren',56,'news_body'),(0,2,0,'','','speziellen',57,'news_body'),(0,2,0,'','','anforderungen',58,'news_body'),(0,2,0,'','','entsprechen',59,'news_body'),(0,2,0,'','','sie',60,'news_body'),(0,2,0,'','','finden',61,'news_body'),(0,2,0,'','','zusatzliche',62,'news_body'),(0,2,0,'','','module',63,'news_body'),(0,2,0,'','','im',64,'news_body'),(0,2,0,'','','downloadbereich',65,'news_body'),(0,2,0,'','','fur',66,'news_body'),(0,2,0,'','','drupal',67,'news_body'),(0,2,0,'','','module',68,'news_body'),(0,2,0,'','','3',71,'news_body'),(0,2,0,'','','passen',72,'news_body'),(0,2,0,'','','sie',73,'news_body'),(0,2,0,'','','das',74,'news_body'),(0,2,0,'','','design',75,'news_body'),(0,2,0,'','','ihrer',76,'news_body'),(0,2,0,'','','website',77,'news_body'),(0,2,0,'','','um',79,'news_body'),(0,2,0,'','','das',80,'news_body'),(0,2,0,'','','aussehen',81,'news_body'),(0,2,0,'','','ihrer',82,'news_body'),(0,2,0,'','','website',83,'news_body'),(0,2,0,'','','zu',84,'news_body'),(0,2,0,'','','verandern',85,'news_body'),(0,2,0,'','','besuchen',86,'news_body'),(0,2,0,'','','sie',87,'news_body'),(0,2,0,'','','den',88,'news_body'),(0,2,0,'','','theme',89,'news_body'),(0,2,0,'','','bereich',90,'news_body'),(0,2,0,'','','sie',91,'news_body'),(0,2,0,'','','konnen',92,'news_body'),(0,2,0,'','','aus',93,'news_body'),(0,2,0,'','','den',94,'news_body'),(0,2,0,'','','mitgelieferten',95,'news_body'),(0,2,0,'','','themes',96,'news_body'),(0,2,0,'','','auswahlen',97,'news_body'),(0,2,0,'','','oder',98,'news_body'),(0,2,0,'','','zusatzliche',99,'news_body'),(0,2,0,'','','themes',100,'news_body'),(0,2,0,'','','aus',101,'news_body'),(0,2,0,'','','dem',102,'news_body'),(0,2,0,'','','downloadbereich',103,'news_body'),(0,2,0,'','','fur',104,'news_body'),(0,2,0,'','','drupal',105,'news_body'),(0,2,0,'','','themes',106,'news_body'),(0,2,0,'','','herunterladen',107,'news_body'),(0,2,0,'','','4',110,'news_body'),(0,2,0,'','','beginnen',111,'news_body'),(0,2,0,'','','sie',112,'news_body'),(0,2,0,'','','inhalte',113,'news_body'),(0,2,0,'','','zu',114,'news_body'),(0,2,0,'','','schreiben',115,'news_body'),(0,2,0,'','','schliesslich',116,'news_body'),(0,2,0,'','','konnen',117,'news_body'),(0,2,0,'','','sie',118,'news_body'),(0,2,0,'','','fur',119,'news_body'),(0,2,0,'','','ihre',120,'news_body'),(0,2,0,'','','website',121,'news_body'),(0,2,0,'','','inhalte',122,'news_body'),(0,2,0,'','','erstellen',123,'news_body'),(0,2,0,'','','dieser',124,'news_body'),(0,2,0,'','','informationstext',125,'news_body'),(0,2,0,'','','verschwindet',126,'news_body'),(0,2,0,'','','sobald',127,'news_body'),(0,2,0,'','','der',128,'news_body'),(0,2,0,'','','erste',129,'news_body'),(0,2,0,'','','beitrag',130,'news_body'),(0,2,0,'','','erscheint',131,'news_body'),(0,2,0,'','','nahere',134,'news_body'),(0,2,0,'','','informationen',135,'news_body'),(0,2,0,'','','finden',136,'news_body'),(0,2,0,'','','sich',137,'news_body'),(0,2,0,'','','im',138,'news_body'),(0,2,0,'','','hilfe',139,'news_body'),(0,2,0,'','','bereich',140,'news_body'),(0,2,0,'','','oder',141,'news_body'),(0,2,0,'','','online',142,'news_body'),(0,2,0,'','','den',144,'news_body'),(0,2,0,'','','drupal',145,'news_body'),(0,2,0,'','','handbuchern',146,'news_body'),(0,2,0,'','','sie',147,'news_body'),(0,2,0,'','','konnen',148,'news_body'),(0,2,0,'','','auch',149,'news_body'),(0,2,0,'','','einen',150,'news_body'),(0,2,0,'','','beitrag',151,'news_body'),(0,2,0,'','','im',152,'news_body'),(0,2,0,'','','drupal',153,'news_body'),(0,2,0,'','','forum',154,'news_body'),(0,2,0,'','','schreiben',155,'news_body'),(0,2,0,'','','oder',156,'news_body'),(0,2,0,'','','aus',157,'news_body'),(0,2,0,'','','den',158,'news_body'),(0,2,0,'','','vielfaltigen',159,'news_body'),(0,2,0,'','','anderen',160,'news_body'),(0,2,0,'','','verfugbaren',161,'news_body'),(0,2,0,'','','support',162,'news_body'),(0,2,0,'','','angeboten',163,'news_body'),(0,2,0,'','','auswahlen',164,'news_body');
/*!40000 ALTER TABLE `cs_cs_news_index` ENABLE KEYS */;

--
-- Table structure for table `cs_downloads`
--

DROP TABLE IF EXISTS `cs_downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_downloads` (
  `download_id` int(20) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `filename` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `filepath` text COLLATE utf8_unicode_ci NOT NULL,
  `added_date` datetime NOT NULL,
  PRIMARY KEY (`download_id`),
  UNIQUE KEY `download_id` (`download_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_downloads`
--

/*!40000 ALTER TABLE `cs_downloads` DISABLE KEYS */;
INSERT INTO `cs_downloads` VALUES (1,'test','testfile1','this is a testdescription for testfile1','/uploads/downloads/testfile1.zip','2009-06-20 00:00:00');
/*!40000 ALTER TABLE `cs_downloads` ENABLE KEYS */;

--
-- Table structure for table `cs_forum_boards`
--

DROP TABLE IF EXISTS `cs_forum_boards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_forum_boards` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `board_id` bigint(20) DEFAULT NULL,
  `category_id` bigint(20) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_forum_boards`
--

/*!40000 ALTER TABLE `cs_forum_boards` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_forum_boards` ENABLE KEYS */;

--
-- Table structure for table `cs_forum_category`
--

DROP TABLE IF EXISTS `cs_forum_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_forum_category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `root_category_id` bigint(20) DEFAULT NULL,
  `parent_category_id` bigint(20) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `root_category_id_idx` (`root_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_forum_category`
--

/*!40000 ALTER TABLE `cs_forum_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_forum_category` ENABLE KEYS */;

--
-- Table structure for table `cs_forum_entry`
--

DROP TABLE IF EXISTS `cs_forum_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_forum_entry` (
  `entry_id` bigint(20) NOT NULL DEFAULT '0',
  `thread_id` bigint(20) DEFAULT NULL,
  `parent_entry_id` bigint(20) DEFAULT NULL,
  `author` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `topic` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci,
  `date` bigint(20) DEFAULT NULL,
  `smileyoff` tinyint(1) DEFAULT NULL,
  `bbcodeoff` tinyint(1) DEFAULT NULL,
  `edited_by` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`entry_id`),
  KEY `parent_entry_id_idx` (`parent_entry_id`),
  KEY `thread_id_idx` (`thread_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_forum_entry`
--

/*!40000 ALTER TABLE `cs_forum_entry` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_forum_entry` ENABLE KEYS */;

--
-- Table structure for table `cs_forum_threads`
--

DROP TABLE IF EXISTS `cs_forum_threads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_forum_threads` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `thread_id` bigint(20) DEFAULT NULL,
  `board_id` bigint(20) DEFAULT NULL,
  `title` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` bigint(20) DEFAULT NULL,
  `closed` tinyint(4) DEFAULT NULL,
  `author` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastpost` varchar(54) COLLATE utf8_unicode_ci DEFAULT NULL,
  `views` bigint(20) DEFAULT NULL,
  `replies` int(11) DEFAULT NULL,
  `stickified` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `board_id_idx` (`board_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_forum_threads`
--

/*!40000 ALTER TABLE `cs_forum_threads` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_forum_threads` ENABLE KEYS */;

--
-- Table structure for table `cs_gallery_album`
--

DROP TABLE IF EXISTS `cs_gallery_album`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_gallery_album` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(4) unsigned NOT NULL,
  `thumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_gallery_album`
--

/*!40000 ALTER TABLE `cs_gallery_album` DISABLE KEYS */;
INSERT INTO `cs_gallery_album` VALUES (1,'Erstes Album','Mein erstes Album',1,'/test/thumbs/thums.jpg');
/*!40000 ALTER TABLE `cs_gallery_album` ENABLE KEYS */;

--
-- Table structure for table `cs_games`
--

DROP TABLE IF EXISTS `cs_games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_games` (
  `games_id` int(3) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `image` text COLLATE utf8_unicode_ci NOT NULL,
  `icon` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_games`
--

/*!40000 ALTER TABLE `cs_games` DISABLE KEYS */;
INSERT INTO `cs_games` VALUES (1,'Counter-Strike','Ein 10 Jahre alter Ego-Shooter!','hht://imageurl','http://iconurl');
/*!40000 ALTER TABLE `cs_games` ENABLE KEYS */;

--
-- Table structure for table `cs_group_rights`
--

DROP TABLE IF EXISTS `cs_group_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_group_rights` (
  `group_id` int(11) NOT NULL DEFAULT '0',
  `right_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_groups` (
  `group_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `sortorder` int(4) unsigned NOT NULL DEFAULT '0',
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(7) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_help` (
  `help_id` int(11) NOT NULL AUTO_INCREMENT,
  `mod` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sub` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `helptext` text COLLATE utf8_unicode_ci NOT NULL,
  `related_links` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`help_id`),
  UNIQUE KEY `help_id` (`help_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_images`
--

/*!40000 ALTER TABLE `cs_images` DISABLE KEYS */;
INSERT INTO `cs_images` VALUES (3,1,'upload','images/avatars/1.jpg');
/*!40000 ALTER TABLE `cs_images` ENABLE KEYS */;

--
-- Table structure for table `cs_matches`
--

DROP TABLE IF EXISTS `cs_matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_matches` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `server_id` int(11) NOT NULL,
  `team1_id` int(11) NOT NULL,
  `team2_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `matchcategory_id` int(11) NOT NULL,
  `matchdate` bigint(20) NOT NULL,
  `matchstatus` tinyint(4) NOT NULL,
  `team1score` int(11) NOT NULL,
  `team2score` int(11) NOT NULL,
  `team1map1score` int(11) NOT NULL,
  `team1map2score` int(11) NOT NULL,
  `team2map1score` int(11) NOT NULL,
  `team2map2score` int(11) NOT NULL,
  `team1players` int(11) NOT NULL,
  `team2players` int(11) NOT NULL,
  `matchreport` int(11) NOT NULL,
  `matchmedia_screenshots` text COLLATE utf8_unicode_ci NOT NULL,
  `matchmedia_replays` text COLLATE utf8_unicode_ci NOT NULL,
  `team1statement` text COLLATE utf8_unicode_ci NOT NULL,
  `team2statement` text COLLATE utf8_unicode_ci NOT NULL,
  `mapname1` int(11) NOT NULL,
  `mapname2` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_matches`
--

/*!40000 ALTER TABLE `cs_matches` DISABLE KEYS */;
INSERT INTO `cs_matches` VALUES (1,1,1,1,1,2,1,1,1234567890,1,24,24,12,12,12,12,0,0,0,'','','team1 stmt','team2 stmt',0,0);
/*!40000 ALTER TABLE `cs_matches` ENABLE KEYS */;

--
-- Table structure for table `cs_messages`
--

DROP TABLE IF EXISTS `cs_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_mod_rel_sub` (
  `module_id` int(11) NOT NULL,
  `submodule_id` int(11) NOT NULL,
  PRIMARY KEY (`module_id`,`submodule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_modules` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `homepage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `license` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `copyright` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `class_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `folder_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `image_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `module_version` float NOT NULL,
  `clansuite_version` float NOT NULL,
  `core` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_modules`
--

/*!40000 ALTER TABLE `cs_modules` DISABLE KEYS */;
INSERT INTO `cs_modules` VALUES (1,'account','Jens-AndrÃƒÂ© Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Account Administration','This module handles all necessary account stuff - like login/logout etc.','module_account','account.module.php','account',1,'module_account.jpg',0.1,0,1),(3,'captcha','Jens-AndrÃƒÂ© Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Captcha Module','The captcha module presents a image only humanoids can read.','module_captcha','captcha.module.php','captcha',1,'module_captcha.jpg',0.1,0,1),(4,'index','Jens-AndrÃƒÂ© Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Index Module','This is the main site.','module_index','index.module.php','index',1,'module_index.jpg',0.1,0,1),(2,'admin','Jens-AndrÃƒÂ© Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Admin Interface','This is the Admin Control Panel','module_admin','admin.module.php','admin',1,'module_admin.jpg',0.1,0,1),(15,'matches','Florian Wolf, Jens-AndrÃƒÂ© Koch','http://www.clansuite.com','GPL v2','ClanSuite Group','Matches','The matches system of clansuite','module_matches','matches.module.php','matches',1,'module_matches.jpg',0.1,0.1,0),(6,'shoutbox','BjÃƒÂ¶rn Spiegel, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Shoutbox Modul','This module displays a shoutbox. You can do entries and administrate it ...','module_shoutbox','shoutbox.module.php','shoutbox',1,'module_shoutbox.jpg',0.1,0,0),(7,'news','Jens-AndrÃƒÂ© Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','News','News module','module_news','news.module.php','news',1,'module_news.jpg',0.1,0,0),(8,'filebrowser','Florian Wolf, Jens-AndrÃƒÂ© Koch','http://www.clansuite.com','GPL v2','clansuite group','Filebrowser','The filebrowser of clansuite','module_filebrowser','filebrowser.module.php','filebrowser',1,'module_filebrowser.jpg',0.1,0,0),(9,'serverlist','Jens-AndrÃƒÂ© Koch','http://www.clansuite.com','BSD','Clansuite Group','Serverlist','List Gameservers','module_serverlist','serverlist.module.php','serverlist',1,'module_serverlist.jpg',0.1,0,0),(13,'guestbook','Florian Wolf, Jens-AndrÃƒÂ© Koch','http://www.clansuite.com','GPL v2','ClanSuite Group','Guestbook','The guestbook for visitors','module_guestbook','guestbook.module.php','guestbook',1,'module_guestbook.jpg',0.1,0.1,0),(14,'forum','Florian Wolf, Jens-AndrÃƒÂ© Koch','http://www.clansuite.com','GPL v2','ClanSuite Group','Forum','The forum where people meet','module_forum','forum.module.php','forum',1,'module_forum.jpg',0.1,0.1,0),(18,'gallery','Florian Wolf, Jens-AndrÃƒÂ© Koch','http://www.clansuite.com','GPL v2','ClanSuite Group','Gallery','The gallery module of clansuite','module_gallery','gallery.module.php','gallery',1,'module_gallery.jpg',0.1,0.1,0),(19,'replays','Florian Wolf, Jens-AndrÃƒÂ© Koch','http://www.clansuite.com','GPL v2','ClanSuite Group','Replays','The replays area of clansuite','module_replays','replays.module.php','replays',1,'module_replays.jpg',0.1,0.1,0),(20,'messaging','Florian Wolf, Jens-AndrÃƒÂ© Koch','http://www.clansuite.com','GPL v2','ClanSuite Group','Messaging','The messaging module of clansuite','module_messaging','messaging.module.php','messaging',1,'module_messaging.jpg',0.1,0.1,0),(22,'staticpages','Jens-Andr? Koch, Florian Wolf','http://www.clansuite.com','GPL v2','Clansuite Group','Static Pages','Static Pages store HTML content','module_staticpages','staticpages.module.php','staticpages',1,'module_staticpages.jpg',0.1,0.1,0),(25,'board','JAK  FW','http://www.clansuite.com','LGPL','2007 JAK  FW','Board','The Clansuite Board','module_board','board.module.php','board',1,'module_board.jpg',0,0.1,0),(28,'userslist','jak','','gpl','jak2007','userslist','userslist','module_userslist','userslist.module.php','userslist',1,'module_userslist.jpg',0.1,0.1,0),(29,'userlist','Jens Andre Koch','http://www.clansuite.com','GPL','Jens Andre Koch 2007','userlist','Lists all Users','module_userlist','userlist.module.php','userlist',1,'module_userlist.jpg',0,0.1,0),(30,'calendar','Jens Andre Koch','http://www.clansuite.com','lgpl','jak','Calendar','Calendar with Eventmanagement','module_calendar','calendar.module.php','calendar',1,'module_calendar.jpg',0.1,0.1,0);
/*!40000 ALTER TABLE `cs_modules` ENABLE KEYS */;

--
-- Table structure for table `cs_news`
--

DROP TABLE IF EXISTS `cs_news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_news`
--

/*!40000 ALTER TABLE `cs_news` DISABLE KEYS */;
INSERT INTO `cs_news` VALUES (22,'Testeintrag 2 - Lore Ipsum ','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sed vestibulum nulla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ut felis scelerisque nibh rutrum dapibus eu et felis. Pellentesque eget nulla risus, at blandit lectus. Praesent venenatis tortor non neque molestie quis congue neque ullamcorper. Quisque at tellus sapien, molestie tempor lacus. Proin non lacus id justo dapibus feugiat ut sed nisi. Cras in purus tincidunt orci tincidunt dignissim sagittis non justo. Integer felis urna, sodales nec pharetra ac, tristique sit amet felis. Nam eget augue felis, sed sodales mauris. Aliquam molestie odio nec eros elementum quis lobortis felis fringilla. In semper sem id tellus pharetra id congue erat aliquet.\r\n',1,2,'2009-10-01 22:00:24','2010-05-13 18:02:02',4),(23,'Testeintrag 3 - Lore Ipsum ','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque imperdiet tristique justo ac pretium. Fusce odio nisl, dictum sed vestibulum in, tristique malesuada lacus. Suspendisse potenti. Proin bibendum placerat neque ut placerat. Nunc felis ligula, ullamcorper sed congue ut, pulvinar ut sapien. Nullam sem purus, adipiscing ut consectetur at, ornare eget magna. Vivamus ut eros vitae neque aliquam vulputate et molestie arcu. Etiam et metus id risus pulvinar pretium? Sed vulputate venenatis consectetur. Mauris id odio risus. Maecenas lacinia iaculis nisi vel egestas. Suspendisse potenti. Quisque vel dui in lectus ultrices vehicula. Donec convallis tortor et leo vehicula non placerat mi varius. Morbi ac pharetra lorem. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce cursus pulvinar nisl. Nunc a nisi vel purus sagittis bibendum!\r\n',2,2,'2009-10-01 22:00:24','2010-05-13 18:02:04',4),(24,'Testeintrag 4 - Lore Ipsum ','<span style=\"font-weight: bold;\">Lorem ipsum</span> <span style=\"font-style: italic;\">dolor sit amet</span>, <span style=\"text-decoration: underline;\">consectetur adipiscing elit</span>. <span style=\"font-family: georgia;\"></span><h2><br></h2><span style=\"font-family: georgia;\">Pellentesque imperdiet tristique justo ac pretium.</span> <font size=\"5\">Fusce odio nisl, dictum sed vestibulum in, tristique malesuada lacus.</font> Suspendisse potenti. Proin bibendum placerat neque ut placerat. Nunc felis ligula, ullamcorper sed congue ut, pulvinar ut sapien. Nullam sem purus, adipiscing ut consectetur at, ornare eget magna. Vivamus ut eros vitae neque aliquam vulputate et molestie arcu. Etiam et metus id risus pulvinar pretium? Sed vulputate venenatis consectetur. Mauris id odio risus. Maecenas lacinia iaculis nisi vel egestas. Suspendisse potenti. Quisque vel dui in lectus ultrices vehicula. Donec convallis tortor et leo vehicula non placerat mi varius. Morbi ac pharetra lorem. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce cursus pulvinar nisl. Nunc a nisi vel purus sagittis bibendum!<br>',2,2,'2009-10-01 22:00:24','2010-05-13 18:06:47',4),(71,'Willkommen auf Ihrer neuen Clansuite-Website!','Bitte folgen Sie diesen Schritten um mit Ihrer Website zu beginnen:\r\n\r\n   1. Konfigurieren Sie Ihre Website Wenn Sie angemeldet sind, besuchen Sie den Administrationsbereich, in dem Sie alle Aspekte Ihrer Website anpassen und konfigurieren kÃƒÂ¶nnen.\r\n   2. Aktivieren Sie zusÃƒÂ¤tzliche Funktionen Als nÃƒÂ¤chstes kÃƒÂ¶nnen Sie in der ModulÃƒÂ¼bersicht die Funktionen aktivieren, die Ihren speziellen Anforderungen entsprechen. Sie finden zusÃƒÂ¤tzliche Module im Downloadbereich fÃƒÂ¼r Clansuite-Module.\r\n   3. Passen Sie das Design Ihrer Website an Um das Aussehen Ihrer Website zu verÃƒÂ¤ndern, besuchen Sie den Theme-Bereich. Sie kÃƒÂ¶nnen aus den mitgelieferten Themes auswÃƒÂ¤hlen oder zusÃƒÂ¤tzliche Themes aus dem Downloadbereich fÃƒÂ¼r Clansuite-Themes herunterladen.\r\n   4. Beginnen Sie, Inhalte zu schreiben SchlieÃƒÅ¸lich kÃƒÂ¶nnen Sie fÃƒÂ¼r Ihre Website Inhalte erstellen. Dieser Informationstext verschwindet, sobald der erste Beitrag erscheint.\r\n\r\nNÃƒÂ¤here Informationen finden sich im Hilfe-Bereich oder online in den Clansuite-HandbÃƒÂ¼chern. Sie kÃƒÂ¶nnen auch einen Beitrag im Clansuite-Forum schreiben oder aus den vielfÃƒÂ¤ltigen anderen verfÃƒÂ¼gbaren Support-Angeboten auswÃƒÂ¤hlen.',2,0,'2009-12-02 23:00:24','2009-12-09 12:17:19',0),(21,'Testeintrag 1 - Lore Ipsum ','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam sed vestibulum nulla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ut felis scelerisque nibh rutrum dapibus eu et felis. Pellentesque eget nulla risus, at blandit lectus. Praesent venenatis tortor non neque molestie quis congue neque ullamcorper. Quisque at tellus sapien, molestie tempor lacus. Proin non lacus id justo dapibus feugiat ut sed nisi. Cras in purus tincidunt orci tincidunt dignissim sagittis non justo. Integer felis urna, sodales nec pharetra ac, tristique sit amet felis. Nam eget augue felis, sed sodales mauris. Aliquam molestie odio nec eros elementum quis lobortis felis fringilla. In semper sem id tellus pharetra id congue erat aliquet.\r\n',1,2,'2009-10-01 22:00:24','2010-05-13 18:01:49',4),(99,'cyrillic (by backend) ёЁйЙцЦ','<font size=\"3\"><span style=\"font-weight: bold;\">ёЁйЙц</span><span style=\"font-style: italic;\">ЦуУкКеЕ</span><span style=\"text-decoration: underline;\">нНгГшШ</span>щЩзЗхХъЪфФыЫвВаАпПрРоОлЛдДжЖэЭяЯчЧсСмМиИтТьЬбБюЮ<br><br>What is this?<br>a) The text is cyrillic.<br>b) It\'s formatted with a wysiwyg editor. So there are html span tags applied to the text.<br></font>',1,0,'2010-05-13 20:39:37','2010-05-13 20:47:27',0),(100,'german specialchars','ä 	ä\r\nÄ 	Ä\r\nö 	ö\r\nÖ 	Ö\r\nü 	ü\r\nÜ 	Ü\r\nß 	ß<br><br><h5><span style=\"font-weight: bold;\">ä 	ä\r\n<span style=\"font-style: italic;\">Ä 	Ä</span>\r\nö 	ö\r\n<span style=\"text-decoration: underline;\">Ö 	Ö</span>\r\n</span>ü 	ü\r\nÜ 	Ü\r\nß 	ß</h5>',1,0,'2010-05-13 20:51:14','2010-05-13 20:53:04',0),(98,'cyrillic (manual sql insert)','ёЁйЙцЦуУкКеЕнНгГшШщЩзЗхХъЪфФыЫвВаАпПрРоОлЛдДжЖэЭяЯчЧсСмМиИтТьЬбБюЮ',0,0,NULL,'2010-05-13 20:38:25',0);
/*!40000 ALTER TABLE `cs_news` ENABLE KEYS */;

--
-- Table structure for table `cs_news_index`
--

DROP TABLE IF EXISTS `cs_news_index`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_news_index` (
  `user_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `news_title` text COLLATE utf8_unicode_ci NOT NULL,
  `news_body` text COLLATE utf8_unicode_ci NOT NULL,
  `keyword` text COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL,
  `field` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_news_index`
--

/*!40000 ALTER TABLE `cs_news_index` DISABLE KEYS */;
INSERT INTO `cs_news_index` VALUES (0,7,0,'','','666',0,'news_title'),(0,7,0,'','','6666',0,'news_body'),(0,7,0,'','','br',1,'news_body'),(0,3,0,'','','stichwort',0,'news_title'),(0,3,0,'','','stichwort',0,'news_body'),(0,3,0,'','','br',1,'news_body'),(0,0,66,'','','stichwort',0,'news_title'),(0,0,66,'','','stichwort',0,'news_body'),(0,0,66,'','','stichwort',1,'news_body'),(0,1,66,'','','stichwort',0,'news_title'),(0,1,66,'','','stichwort',0,'news_body'),(0,1,66,'','','stichwortasasd',1,'news_body'),(0,3,66,'','','br',1,'news_body'),(0,3,66,'','','stichwort',0,'news_body'),(0,3,66,'','','6',1,'news_title'),(0,3,66,'','','stichwort66',0,'news_title'),(0,2,66,'','','stichwort6612366',0,'news_body'),(0,2,66,'','','stichwort6612366',0,'news_title'),(0,2,66,'','','br',1,'news_body'),(2,2,24,'','','aliquam',103,'news_body'),(2,2,24,'','','neque',102,'news_body'),(2,2,24,'','','vitae',101,'news_body'),(2,2,24,'','','eros',100,'news_body'),(2,2,24,'','','ut',99,'news_body'),(2,2,24,'','','vivamus',98,'news_body'),(2,2,24,'','','magna',97,'news_body'),(2,2,24,'','','eget',96,'news_body'),(2,2,24,'','','ornare',95,'news_body'),(2,2,24,'','','consectetur',93,'news_body'),(2,2,24,'','','ut',92,'news_body'),(2,2,24,'','','adipiscing',91,'news_body'),(2,2,24,'','','purus',90,'news_body'),(2,2,24,'','','sem',89,'news_body'),(2,2,24,'','','nullam',88,'news_body'),(2,2,24,'','','sapien',87,'news_body'),(2,2,24,'','','ut',86,'news_body'),(2,2,24,'','','pulvinar',85,'news_body'),(2,2,24,'','','ut',84,'news_body'),(2,2,24,'','','congue',83,'news_body'),(2,2,24,'','','sed',82,'news_body'),(2,2,24,'','','ullamcorper',81,'news_body'),(2,2,24,'','','ligula',80,'news_body'),(2,2,24,'','','felis',79,'news_body'),(2,2,24,'','','nunc',78,'news_body'),(2,2,24,'','','placerat',77,'news_body'),(2,2,24,'','','ut',76,'news_body'),(2,2,24,'','','neque',75,'news_body'),(2,2,24,'','','placerat',74,'news_body'),(2,2,24,'','','bibendum',73,'news_body'),(2,2,24,'','','proin',72,'news_body'),(2,2,24,'','','potenti',71,'news_body'),(2,2,24,'','','suspendisse',70,'news_body'),(2,2,24,'','','font',69,'news_body'),(2,2,24,'','','lacus',67,'news_body'),(2,2,24,'','','malesuada',66,'news_body'),(2,2,24,'','','tristique',65,'news_body'),(2,2,24,'','','vestibulum',63,'news_body'),(2,2,24,'','','sed',62,'news_body'),(2,2,24,'','','dictum',61,'news_body'),(2,2,24,'','','nisl',60,'news_body'),(2,2,24,'','','odio',59,'news_body'),(2,2,24,'','','fusce',58,'news_body'),(2,2,24,'','','5',57,'news_body'),(2,2,24,'','','font',55,'news_body'),(2,2,24,'','','span',53,'news_body'),(2,2,24,'','','pretium',51,'news_body'),(2,2,24,'','','ac',50,'news_body'),(2,2,24,'','','justo',49,'news_body'),(2,2,24,'','','tristique',48,'news_body'),(2,2,24,'','','imperdiet',47,'news_body'),(2,2,24,'','','pellentesque',46,'news_body'),(2,2,24,'','','georgia',45,'news_body'),(2,2,24,'','','family',44,'news_body'),(2,2,24,'','','font',43,'news_body'),(2,2,24,'','','style',42,'news_body'),(2,2,24,'','','span',41,'news_body'),(2,2,24,'','','h2',40,'news_body'),(2,2,24,'','','br',38,'news_body'),(2,2,24,'','','h2',37,'news_body'),(2,2,24,'','','span',36,'news_body'),(2,2,24,'','','georgia',34,'news_body'),(2,2,24,'','','family',33,'news_body'),(2,2,24,'','','font',32,'news_body'),(2,2,24,'','','style',31,'news_body'),(2,2,24,'','','span',30,'news_body'),(2,2,24,'','','span',28,'news_body'),(2,2,24,'','','elit',27,'news_body'),(2,2,24,'','','adipiscing',26,'news_body'),(2,2,24,'','','consectetur',25,'news_body'),(2,2,24,'','','underline',24,'news_body'),(2,2,24,'','','decoration',23,'news_body'),(2,2,24,'','','text',22,'news_body'),(2,2,24,'','','style',21,'news_body'),(2,2,24,'','','span',20,'news_body'),(2,2,24,'','','span',18,'news_body'),(2,2,24,'','','amet',17,'news_body'),(2,2,24,'','','sit',16,'news_body'),(2,2,24,'','','dolor',15,'news_body'),(2,2,24,'','','italic',14,'news_body'),(2,2,24,'','','style',13,'news_body'),(2,2,24,'','','font',12,'news_body'),(2,2,24,'','','style',11,'news_body'),(2,2,24,'','','span',10,'news_body'),(2,2,24,'','','span',8,'news_body'),(2,2,24,'','','ipsum',7,'news_body'),(2,2,24,'','','lorem',6,'news_body'),(2,2,24,'','','bold',5,'news_body'),(2,2,24,'','','weight',4,'news_body'),(2,2,24,'','','font',3,'news_body'),(2,2,24,'','','style',2,'news_body'),(2,2,24,'','','span',1,'news_body'),(2,2,24,'','','ipsum',4,'news_title'),(2,2,24,'','','lore',3,'news_title'),(2,2,24,'','','4',1,'news_title'),(2,2,24,'','','testeintrag',0,'news_title'),(0,4,0,'','','test',0,'news_title'),(0,4,0,'','','test',0,'news_body'),(0,2,0,'','','test',0,'news_title'),(0,2,0,'','','fuer',1,'news_title'),(0,2,0,'','','editorfunktion',2,'news_title'),(0,6,0,'','','testfkjhg',0,'news_title'),(0,6,0,'','','oeaufjkgb',0,'news_title'),(0,6,0,'','','aelskjgfv',0,'news_body'),(0,2,0,'','','willkommen',0,'news_title'),(0,2,0,'','','auf',1,'news_title'),(0,2,0,'','','ihrer',2,'news_title'),(0,2,0,'','','neuen',3,'news_title'),(0,2,0,'','','clansuite',4,'news_title'),(0,2,0,'','','website',5,'news_title'),(0,2,0,'','','bitte',0,'news_body'),(0,2,0,'','','folgen',1,'news_body'),(0,2,0,'','','sie',2,'news_body'),(0,2,0,'','','diesen',3,'news_body'),(0,2,0,'','','schritten',4,'news_body'),(0,2,0,'','','um',5,'news_body'),(0,2,0,'','','mit',6,'news_body'),(0,2,0,'','','ihrer',7,'news_body'),(0,2,0,'','','website',8,'news_body'),(0,2,0,'','','zu',9,'news_body'),(0,2,0,'','','beginnen',10,'news_body'),(0,2,0,'','','1',14,'news_body'),(0,2,0,'','','konfigurieren',15,'news_body'),(0,2,0,'','','sie',16,'news_body'),(0,2,0,'','','ihre',17,'news_body'),(0,2,0,'','','website',18,'news_body'),(0,2,0,'','','wenn',19,'news_body'),(0,2,0,'','','sie',20,'news_body'),(0,2,0,'','','angemeldet',21,'news_body'),(0,2,0,'','','sind',22,'news_body'),(0,2,0,'','','besuchen',23,'news_body'),(0,2,0,'','','sie',24,'news_body'),(0,2,0,'','','den',25,'news_body'),(0,2,0,'','','administrationsbereich',26,'news_body'),(0,2,0,'','','dem',28,'news_body'),(0,2,0,'','','sie',29,'news_body'),(0,2,0,'','','alle',30,'news_body'),(0,2,0,'','','aspekte',31,'news_body'),(0,2,0,'','','ihrer',32,'news_body'),(0,2,0,'','','website',33,'news_body'),(0,2,0,'','','anpassen',34,'news_body'),(0,2,0,'','','und',35,'news_body'),(0,2,0,'','','konfigurieren',36,'news_body'),(0,2,0,'','','konnen',37,'news_body'),(0,2,0,'','','2',40,'news_body'),(0,2,0,'','','aktivieren',41,'news_body'),(0,2,0,'','','sie',42,'news_body'),(0,2,0,'','','zusatzliche',43,'news_body'),(0,2,0,'','','funktionen',44,'news_body'),(0,2,0,'','','als',45,'news_body'),(0,2,0,'','','nachstes',46,'news_body'),(0,2,0,'','','konnen',47,'news_body'),(0,2,0,'','','sie',48,'news_body'),(0,2,0,'','','der',50,'news_body'),(0,2,0,'','','modulubersicht',51,'news_body'),(0,2,0,'','','die',52,'news_body'),(0,2,0,'','','funktionen',53,'news_body'),(0,2,0,'','','aktivieren',54,'news_body'),(0,2,0,'','','die',55,'news_body'),(0,2,0,'','','ihren',56,'news_body'),(0,2,0,'','','speziellen',57,'news_body'),(0,2,0,'','','anforderungen',58,'news_body'),(0,2,0,'','','entsprechen',59,'news_body'),(0,2,0,'','','sie',60,'news_body'),(0,2,0,'','','finden',61,'news_body'),(0,2,0,'','','zusatzliche',62,'news_body'),(0,2,0,'','','module',63,'news_body'),(0,2,0,'','','im',64,'news_body'),(0,2,0,'','','downloadbereich',65,'news_body'),(0,2,0,'','','fur',66,'news_body'),(0,2,0,'','','drupal',67,'news_body'),(0,2,0,'','','module',68,'news_body'),(0,2,0,'','','3',71,'news_body'),(0,2,0,'','','passen',72,'news_body'),(0,2,0,'','','sie',73,'news_body'),(0,2,0,'','','das',74,'news_body'),(0,2,0,'','','design',75,'news_body'),(0,2,0,'','','ihrer',76,'news_body'),(0,2,0,'','','website',77,'news_body'),(0,2,0,'','','um',79,'news_body'),(0,2,0,'','','das',80,'news_body'),(0,2,0,'','','aussehen',81,'news_body'),(0,2,0,'','','ihrer',82,'news_body'),(0,2,0,'','','website',83,'news_body'),(0,2,0,'','','zu',84,'news_body'),(0,2,0,'','','verandern',85,'news_body'),(0,2,0,'','','besuchen',86,'news_body'),(0,2,0,'','','sie',87,'news_body'),(0,2,0,'','','den',88,'news_body'),(0,2,0,'','','theme',89,'news_body'),(0,2,0,'','','bereich',90,'news_body'),(0,2,0,'','','sie',91,'news_body'),(0,2,0,'','','konnen',92,'news_body'),(0,2,0,'','','aus',93,'news_body'),(0,2,0,'','','den',94,'news_body'),(0,2,0,'','','mitgelieferten',95,'news_body'),(0,2,0,'','','themes',96,'news_body'),(0,2,0,'','','auswahlen',97,'news_body'),(0,2,0,'','','oder',98,'news_body'),(0,2,0,'','','zusatzliche',99,'news_body'),(0,2,0,'','','themes',100,'news_body'),(0,2,0,'','','aus',101,'news_body'),(0,2,0,'','','dem',102,'news_body'),(0,2,0,'','','downloadbereich',103,'news_body'),(0,2,0,'','','fur',104,'news_body'),(0,2,0,'','','drupal',105,'news_body'),(0,2,0,'','','themes',106,'news_body'),(0,2,0,'','','herunterladen',107,'news_body'),(0,2,0,'','','4',110,'news_body'),(0,2,0,'','','beginnen',111,'news_body'),(0,2,0,'','','sie',112,'news_body'),(0,2,0,'','','inhalte',113,'news_body'),(0,2,0,'','','zu',114,'news_body'),(0,2,0,'','','schreiben',115,'news_body'),(0,2,0,'','','schliesslich',116,'news_body'),(0,2,0,'','','konnen',117,'news_body'),(0,2,0,'','','sie',118,'news_body'),(0,2,0,'','','fur',119,'news_body'),(0,2,0,'','','ihre',120,'news_body'),(0,2,0,'','','website',121,'news_body'),(0,2,0,'','','inhalte',122,'news_body'),(0,2,0,'','','erstellen',123,'news_body'),(0,2,0,'','','dieser',124,'news_body'),(0,2,0,'','','informationstext',125,'news_body'),(0,2,0,'','','verschwindet',126,'news_body'),(0,2,0,'','','sobald',127,'news_body'),(0,2,0,'','','der',128,'news_body'),(0,2,0,'','','erste',129,'news_body'),(0,2,0,'','','beitrag',130,'news_body'),(0,2,0,'','','erscheint',131,'news_body'),(0,2,0,'','','nahere',134,'news_body'),(0,2,0,'','','informationen',135,'news_body'),(0,2,0,'','','finden',136,'news_body'),(0,2,0,'','','sich',137,'news_body'),(0,2,0,'','','im',138,'news_body'),(0,2,0,'','','hilfe',139,'news_body'),(0,2,0,'','','bereich',140,'news_body'),(0,2,0,'','','oder',141,'news_body'),(0,2,0,'','','online',142,'news_body'),(0,2,0,'','','den',144,'news_body'),(0,2,0,'','','drupal',145,'news_body'),(0,2,0,'','','handbuchern',146,'news_body'),(0,2,0,'','','sie',147,'news_body'),(0,2,0,'','','konnen',148,'news_body'),(0,2,0,'','','auch',149,'news_body'),(0,2,0,'','','einen',150,'news_body'),(0,2,0,'','','beitrag',151,'news_body'),(0,2,0,'','','im',152,'news_body'),(0,2,0,'','','drupal',153,'news_body'),(0,2,0,'','','forum',154,'news_body'),(0,2,0,'','','schreiben',155,'news_body'),(0,2,0,'','','oder',156,'news_body'),(0,2,0,'','','aus',157,'news_body'),(0,2,0,'','','den',158,'news_body'),(0,2,0,'','','vielfaltigen',159,'news_body'),(0,2,0,'','','anderen',160,'news_body'),(0,2,0,'','','verfugbaren',161,'news_body'),(0,2,0,'','','support',162,'news_body'),(0,2,0,'','','angeboten',163,'news_body'),(0,2,0,'','','auswahlen',164,'news_body'),(0,5,0,'','','test',0,'news_title'),(0,5,0,'','','test',0,'news_body'),(0,5,0,'','','test',0,'news_title'),(0,5,0,'','','test',0,'news_body'),(5,5,0,'','','test',0,'news_title'),(5,5,0,'','','test',0,'news_body'),(1,1,86,'','','pellentesque',44,'news_body'),(1,1,86,'','','curabitur',43,'news_body'),(1,1,86,'','','nibh',42,'news_body'),(1,1,86,'','','fusce',41,'news_body'),(1,1,86,'','','dui',40,'news_body'),(1,1,86,'','','amet',39,'news_body'),(1,1,86,'','','sit',38,'news_body'),(1,1,86,'','','euismod',37,'news_body'),(1,1,86,'','','amet',36,'news_body'),(1,1,86,'','','sit',35,'news_body'),(1,1,86,'','','ultrices',34,'news_body'),(1,1,86,'','','bibendum',32,'news_body'),(1,1,86,'','','sem',31,'news_body'),(1,1,86,'','','nisi',30,'news_body'),(1,1,86,'','','praesent',29,'news_body'),(1,1,86,'','','p',28,'news_body'),(1,1,86,'','','diam',9,'news_body'),(1,1,86,'','','pretium',7,'news_body'),(1,1,86,'','','eget',6,'news_body'),(1,1,86,'','','nunc',5,'news_body'),(1,1,86,'','','html',4,'news_body'),(1,1,86,'','','index',3,'news_body'),(1,1,86,'','','href',2,'news_body'),(1,1,86,'','','ipsum',1,'news_title'),(1,1,86,'','','lore',0,'news_title'),(0,2,71,'','','auswahlen',164,'news_body'),(0,2,71,'','','angeboten',163,'news_body'),(0,2,71,'','','support',162,'news_body'),(0,2,71,'','','verfugbaren',161,'news_body'),(0,2,71,'','','anderen',160,'news_body'),(0,2,71,'','','vielfaltigen',159,'news_body'),(0,2,71,'','','den',158,'news_body'),(0,2,71,'','','aus',157,'news_body'),(0,2,71,'','','oder',156,'news_body'),(0,2,71,'','','schreiben',155,'news_body'),(0,2,71,'','','forum',154,'news_body'),(0,2,71,'','','clansuite',153,'news_body'),(0,2,71,'','','im',152,'news_body'),(0,2,71,'','','beitrag',151,'news_body'),(0,2,71,'','','einen',150,'news_body'),(0,2,71,'','','auch',149,'news_body'),(0,2,71,'','','konnen',148,'news_body'),(0,2,71,'','','sie',147,'news_body'),(0,2,71,'','','handbuchern',146,'news_body'),(0,2,71,'','','clansuite',145,'news_body'),(0,2,71,'','','den',144,'news_body'),(0,2,71,'','','online',142,'news_body'),(0,2,71,'','','oder',141,'news_body'),(0,2,71,'','','bereich',140,'news_body'),(0,2,71,'','','hilfe',139,'news_body'),(0,2,71,'','','im',138,'news_body'),(0,2,71,'','','sich',137,'news_body'),(0,2,71,'','','finden',136,'news_body'),(0,2,71,'','','informationen',135,'news_body'),(0,2,71,'','','nahere',134,'news_body'),(0,2,71,'','','erscheint',131,'news_body'),(0,2,71,'','','beitrag',130,'news_body'),(0,2,71,'','','erste',129,'news_body'),(0,2,71,'','','der',128,'news_body'),(0,2,71,'','','sobald',127,'news_body'),(0,2,71,'','','verschwindet',126,'news_body'),(0,2,71,'','','informationstext',125,'news_body'),(0,2,71,'','','dieser',124,'news_body'),(0,2,71,'','','erstellen',123,'news_body'),(0,2,71,'','','inhalte',122,'news_body'),(0,2,71,'','','website',121,'news_body'),(0,2,71,'','','ihre',120,'news_body'),(0,2,71,'','','fur',119,'news_body'),(0,2,71,'','','sie',118,'news_body'),(0,2,71,'','','konnen',117,'news_body'),(0,2,71,'','','schliesslich',116,'news_body'),(0,2,71,'','','schreiben',115,'news_body'),(0,2,71,'','','zu',114,'news_body'),(0,2,71,'','','inhalte',113,'news_body'),(0,2,71,'','','sie',112,'news_body'),(0,2,71,'','','beginnen',111,'news_body'),(0,2,71,'','','4',110,'news_body'),(0,2,71,'','','herunterladen',107,'news_body'),(0,2,71,'','','themes',106,'news_body'),(0,2,71,'','','clansuite',105,'news_body'),(0,2,71,'','','fur',104,'news_body'),(0,2,71,'','','downloadbereich',103,'news_body'),(0,2,71,'','','dem',102,'news_body'),(0,2,71,'','','aus',101,'news_body'),(0,2,71,'','','themes',100,'news_body'),(0,2,71,'','','zusatzliche',99,'news_body'),(0,2,71,'','','oder',98,'news_body'),(0,2,71,'','','auswahlen',97,'news_body'),(0,2,71,'','','themes',96,'news_body'),(0,2,71,'','','mitgelieferten',95,'news_body'),(0,2,71,'','','den',94,'news_body'),(0,2,71,'','','aus',93,'news_body'),(0,2,71,'','','konnen',92,'news_body'),(0,2,71,'','','sie',91,'news_body'),(0,2,71,'','','bereich',90,'news_body'),(0,2,71,'','','theme',89,'news_body'),(0,2,71,'','','den',88,'news_body'),(0,2,71,'','','sie',87,'news_body'),(0,2,71,'','','besuchen',86,'news_body'),(0,2,71,'','','verandern',85,'news_body'),(0,2,71,'','','zu',84,'news_body'),(0,2,71,'','','website',83,'news_body'),(0,2,71,'','','ihrer',82,'news_body'),(0,2,71,'','','aussehen',81,'news_body'),(0,2,71,'','','das',80,'news_body'),(0,2,71,'','','um',79,'news_body'),(0,2,71,'','','website',77,'news_body'),(0,2,71,'','','ihrer',76,'news_body'),(0,2,71,'','','design',75,'news_body'),(0,2,71,'','','das',74,'news_body'),(0,2,71,'','','sie',73,'news_body'),(0,2,71,'','','passen',72,'news_body'),(0,2,71,'','','3',71,'news_body'),(0,2,71,'','','module',68,'news_body'),(0,2,71,'','','clansuite',67,'news_body'),(0,2,71,'','','fur',66,'news_body'),(0,2,71,'','','downloadbereich',65,'news_body'),(0,2,71,'','','im',64,'news_body'),(0,2,71,'','','module',63,'news_body'),(0,2,71,'','','zusatzliche',62,'news_body'),(0,2,71,'','','finden',61,'news_body'),(0,2,71,'','','sie',60,'news_body'),(0,2,71,'','','entsprechen',59,'news_body'),(0,2,71,'','','anforderungen',58,'news_body'),(0,2,71,'','','speziellen',57,'news_body'),(0,2,71,'','','ihren',56,'news_body'),(0,2,71,'','','die',55,'news_body'),(0,2,71,'','','aktivieren',54,'news_body'),(0,2,71,'','','funktionen',53,'news_body'),(0,2,71,'','','die',52,'news_body'),(0,2,71,'','','modulubersicht',51,'news_body'),(0,2,71,'','','der',50,'news_body'),(0,2,71,'','','sie',48,'news_body'),(0,2,71,'','','konnen',47,'news_body'),(0,2,71,'','','nachstes',46,'news_body'),(0,2,71,'','','als',45,'news_body'),(0,2,71,'','','funktionen',44,'news_body'),(0,2,71,'','','zusatzliche',43,'news_body'),(0,2,71,'','','sie',42,'news_body'),(0,2,71,'','','aktivieren',41,'news_body'),(0,2,71,'','','2',40,'news_body'),(0,2,71,'','','konnen',37,'news_body'),(0,2,71,'','','konfigurieren',36,'news_body'),(0,2,71,'','','und',35,'news_body'),(0,2,71,'','','anpassen',34,'news_body'),(0,2,71,'','','website',33,'news_body'),(0,2,71,'','','ihrer',32,'news_body'),(0,2,71,'','','aspekte',31,'news_body'),(0,2,71,'','','alle',30,'news_body'),(0,2,71,'','','sie',29,'news_body'),(0,2,71,'','','dem',28,'news_body'),(0,2,71,'','','administrationsbereich',26,'news_body'),(0,2,71,'','','den',25,'news_body'),(0,2,71,'','','sie',24,'news_body'),(0,2,71,'','','besuchen',23,'news_body'),(0,2,71,'','','sind',22,'news_body'),(0,2,71,'','','angemeldet',21,'news_body'),(0,2,71,'','','sie',20,'news_body'),(0,2,71,'','','wenn',19,'news_body'),(0,2,71,'','','website',18,'news_body'),(0,2,71,'','','ihre',17,'news_body'),(0,2,71,'','','sie',16,'news_body'),(0,2,71,'','','konfigurieren',15,'news_body'),(0,2,71,'','','1',14,'news_body'),(0,2,71,'','','beginnen',10,'news_body'),(0,2,71,'','','zu',9,'news_body'),(0,2,71,'','','website',8,'news_body'),(0,2,71,'','','ihrer',7,'news_body'),(0,2,71,'','','mit',6,'news_body'),(0,2,71,'','','um',5,'news_body'),(0,2,71,'','','schritten',4,'news_body'),(0,2,71,'','','diesen',3,'news_body'),(0,2,71,'','','sie',2,'news_body'),(0,2,71,'','','folgen',1,'news_body'),(0,2,71,'','','bitte',0,'news_body'),(0,2,71,'','','color',8,'news_title'),(0,2,71,'','','live',5,'news_title'),(0,2,71,'','','tests',3,'news_title'),(0,2,71,'','','mapping',2,'news_title'),(0,2,71,'','','status',1,'news_title'),(0,2,71,'','','perfect',0,'news_title'),(0,1,0,'','','asa',48,'news_body'),(0,1,0,'','','aya',47,'news_body'),(0,1,0,'','','aua',22,'news_body'),(0,1,0,'','','asa',21,'news_body'),(0,1,0,'','','afa',16,'news_body'),(0,1,0,'','','test',2,'news_body'),(0,1,0,'','','cyrillic',1,'news_body'),(0,1,0,'','','aza',120,'news_title'),(0,1,0,'','','aoea',113,'news_title'),(0,1,0,'','','aca',112,'news_title'),(0,1,0,'','','aoea',105,'news_title'),(0,1,0,'','','aa',80,'news_title'),(0,1,0,'','','aza',75,'news_title'),(0,1,0,'','','aea',70,'news_title'),(0,1,0,'','','aya',69,'news_title'),(0,1,0,'','','asa',48,'news_title'),(0,1,0,'','','aya',47,'news_title'),(0,1,0,'','','aua',22,'news_title'),(0,1,0,'','','asa',21,'news_title'),(0,1,0,'','','afa',16,'news_title'),(0,1,0,'','','test',2,'news_title'),(0,1,0,'','','cyrillic',1,'news_title'),(0,1,0,'','','qwertz',7,'news_body'),(0,1,0,'','','encode',1,'news_body'),(0,1,0,'','','utf8',0,'news_body'),(0,1,0,'','','encode',1,'news_title'),(0,1,0,'','','utf8',0,'news_title'),(0,1,0,'','','qwertz',7,'news_body'),(0,1,0,'','','encode',1,'news_body'),(0,1,0,'','','utf8',0,'news_body'),(0,1,0,'','','encode',1,'news_title'),(0,1,0,'','','utf8',0,'news_title'),(0,2,0,'','','oauss',3,'news_body'),(0,2,0,'','','abc',1,'news_body'),(0,2,0,'','','abc',2,'news_body'),(0,2,0,'','','123',0,'news_body'),(0,2,0,'','','article',2,'news_title'),(2,2,24,'','','br',175,'news_body'),(2,2,24,'','','bibendum',174,'news_body'),(2,2,24,'','','sagittis',173,'news_body'),(2,2,24,'','','purus',172,'news_body'),(2,2,24,'','','vel',171,'news_body'),(2,2,24,'','','nisi',170,'news_body'),(2,2,24,'','','nunc',168,'news_body'),(2,2,24,'','','nisl',167,'news_body'),(2,2,24,'','','pulvinar',166,'news_body'),(2,2,24,'','','cursus',165,'news_body'),(2,2,24,'','','fusce',164,'news_body'),(2,2,24,'','','mus',163,'news_body'),(2,2,24,'','','ridiculus',162,'news_body'),(2,2,24,'','','nascetur',161,'news_body'),(2,2,24,'','','montes',160,'news_body'),(2,2,24,'','','parturient',159,'news_body'),(2,2,24,'','','dis',158,'news_body'),(2,2,24,'','','magnis',157,'news_body'),(2,2,24,'','','et',156,'news_body'),(2,2,24,'','','penatibus',155,'news_body'),(2,2,24,'','','natoque',154,'news_body'),(2,2,24,'','','sociis',153,'news_body'),(2,2,24,'','','cum',152,'news_body'),(2,2,24,'','','lorem',151,'news_body'),(2,2,24,'','','pharetra',150,'news_body'),(2,2,24,'','','ac',149,'news_body'),(2,2,24,'','','morbi',148,'news_body'),(2,2,24,'','','varius',147,'news_body'),(2,2,24,'','','mi',146,'news_body'),(2,2,24,'','','placerat',145,'news_body'),(2,2,24,'','','non',144,'news_body'),(2,2,24,'','','vehicula',143,'news_body'),(2,2,24,'','','leo',142,'news_body'),(2,2,24,'','','et',141,'news_body'),(2,2,24,'','','tortor',140,'news_body'),(2,2,24,'','','convallis',139,'news_body'),(2,2,24,'','','donec',138,'news_body'),(2,2,24,'','','vehicula',137,'news_body'),(2,2,24,'','','ultrices',136,'news_body'),(2,2,24,'','','lectus',135,'news_body'),(2,2,24,'','','vel',132,'news_body'),(1,1,86,'','','lectus',45,'news_body'),(1,1,86,'','','href',48,'news_body'),(1,1,86,'','','index',49,'news_body'),(1,1,86,'','','html',50,'news_body'),(1,1,86,'','','volutpat',51,'news_body'),(1,1,86,'','','interdum',52,'news_body'),(1,1,86,'','','pellentesque',55,'news_body'),(1,1,86,'','','nibh',57,'news_body'),(1,1,86,'','','quis',58,'news_body'),(1,1,86,'','','nunc',59,'news_body'),(1,1,86,'','','volutpat',60,'news_body'),(1,1,86,'','','aliquam',61,'news_body'),(1,1,86,'','','p',62,'news_body'),(1,1,86,'','','blockquote',85,'news_body'),(1,1,86,'','','p',86,'news_body'),(1,1,86,'','','sed',87,'news_body'),(1,1,86,'','','sodales',88,'news_body'),(1,1,86,'','','nisl',89,'news_body'),(1,1,86,'','','sit',90,'news_body'),(1,1,86,'','','amet',91,'news_body'),(1,1,86,'','','augue',92,'news_body'),(1,1,86,'','','donec',93,'news_body'),(1,1,86,'','','ultrices',94,'news_body'),(1,1,86,'','','augue',95,'news_body'),(1,1,86,'','','ullamcorper',96,'news_body'),(1,1,86,'','','posuere',97,'news_body'),(1,1,86,'','','laoreet',98,'news_body'),(1,1,86,'','','turpis',99,'news_body'),(1,1,86,'','','massa',100,'news_body'),(1,1,86,'','','tristique',101,'news_body'),(1,1,86,'','','justo',102,'news_body'),(1,1,86,'','','sed',103,'news_body'),(1,1,86,'','','egestas',104,'news_body'),(1,1,86,'','','metus',105,'news_body'),(1,1,86,'','','magna',106,'news_body'),(1,1,86,'','','sed',107,'news_body'),(1,1,86,'','','purus',108,'news_body'),(1,1,86,'','','p',110,'news_body'),(1,1,86,'','','blockquote',112,'news_body'),(1,1,86,'','','code',135,'news_body'),(1,1,86,'','','margin',136,'news_body'),(1,1,86,'','','bottom',137,'news_body'),(1,1,86,'','','12px',138,'news_body'),(1,1,86,'','','font',148,'news_body'),(1,1,86,'','','1',150,'news_body'),(1,1,86,'','','1em',151,'news_body'),(1,1,86,'','','background',161,'news_body'),(1,1,86,'','','url',162,'news_body'),(1,1,86,'','','images',163,'news_body'),(1,1,86,'','','quote',164,'news_body'),(1,1,86,'','','gif',165,'news_body'),(1,1,86,'','','padding',175,'news_body'),(1,1,86,'','','28px',177,'news_body'),(1,1,86,'','','color',187,'news_body'),(1,1,86,'','','555',189,'news_body'),(1,1,86,'','','code',191,'news_body'),(1,1,86,'','','ul',202,'news_body'),(1,1,86,'','','tristique',215,'news_body'),(1,1,86,'','','aenean',234,'news_body'),(1,1,86,'','','pretium',246,'news_body'),(1,1,86,'','','ul',264,'news_body'),(1,1,86,'','','p',271,'news_body'),(1,1,86,'','','eget',272,'news_body'),(1,1,86,'','','feugiat',273,'news_body'),(1,1,86,'','','est',274,'news_body'),(1,1,86,'','','leo',275,'news_body'),(1,1,86,'','','tempor',276,'news_body'),(1,1,86,'','','quam',277,'news_body'),(1,1,86,'','','ut',278,'news_body'),(1,1,86,'','','quis',279,'news_body'),(1,1,86,'','','neque',280,'news_body'),(1,1,86,'','','convallis',281,'news_body'),(1,1,86,'','','magna',282,'news_body'),(1,1,86,'','','consequat',283,'news_body'),(1,1,86,'','','molestie',284,'news_body'),(1,1,86,'','','p',286,'news_body'),(1,1,76,'','','testnews',4,'news_title'),(1,1,76,'','','href',2,'news_body'),(1,1,76,'','','index',3,'news_body'),(1,1,76,'','','html',4,'news_body'),(1,1,76,'','','nunc',5,'news_body'),(1,1,76,'','','eget',6,'news_body'),(1,1,76,'','','pretium',7,'news_body'),(1,1,76,'','','diam',9,'news_body'),(1,1,76,'','','p',28,'news_body'),(1,1,76,'','','praesent',29,'news_body'),(1,1,76,'','','nisi',30,'news_body'),(1,1,76,'','','sem',31,'news_body'),(1,1,76,'','','bibendum',32,'news_body'),(1,1,76,'','','ultrices',34,'news_body'),(1,1,76,'','','sit',35,'news_body'),(1,1,76,'','','amet',36,'news_body'),(1,1,76,'','','euismod',37,'news_body'),(1,1,76,'','','sit',38,'news_body'),(1,1,76,'','','amet',39,'news_body'),(1,1,76,'','','dui',40,'news_body'),(1,1,76,'','','fusce',41,'news_body'),(1,1,76,'','','nibh',42,'news_body'),(1,1,76,'','','curabitur',43,'news_body'),(1,1,76,'','','pellentesque',44,'news_body'),(1,1,76,'','','lectus',45,'news_body'),(1,1,76,'','','href',48,'news_body'),(1,1,76,'','','index',49,'news_body'),(1,1,76,'','','html',50,'news_body'),(1,1,76,'','','volutpat',51,'news_body'),(1,1,76,'','','interdum',52,'news_body'),(1,1,76,'','','pellentesque',55,'news_body'),(1,1,76,'','','nibh',57,'news_body'),(1,1,76,'','','quis',58,'news_body'),(1,1,76,'','','nunc',59,'news_body'),(1,1,76,'','','volutpat',60,'news_body'),(1,1,76,'','','aliquam',61,'news_body'),(1,1,76,'','','p',62,'news_body'),(1,1,76,'','','blockquote',85,'news_body'),(1,1,76,'','','p',86,'news_body'),(1,1,76,'','','sed',87,'news_body'),(1,1,76,'','','sodales',88,'news_body'),(1,1,76,'','','nisl',89,'news_body'),(1,1,76,'','','sit',90,'news_body'),(1,1,76,'','','amet',91,'news_body'),(1,1,76,'','','augue',92,'news_body'),(1,1,76,'','','donec',93,'news_body'),(1,1,76,'','','ultrices',94,'news_body'),(1,1,76,'','','augue',95,'news_body'),(1,1,76,'','','ullamcorper',96,'news_body'),(1,1,76,'','','posuere',97,'news_body'),(1,1,76,'','','laoreet',98,'news_body'),(1,1,76,'','','turpis',99,'news_body'),(1,1,76,'','','massa',100,'news_body'),(1,1,76,'','','tristique',101,'news_body'),(1,1,76,'','','justo',102,'news_body'),(1,1,76,'','','sed',103,'news_body'),(1,1,76,'','','egestas',104,'news_body'),(1,1,76,'','','metus',105,'news_body'),(1,1,76,'','','magna',106,'news_body'),(1,1,76,'','','sed',107,'news_body'),(1,1,76,'','','purus',108,'news_body'),(1,1,76,'','','p',110,'news_body'),(1,1,76,'','','blockquote',112,'news_body'),(1,1,76,'','','code',135,'news_body'),(1,1,76,'','','margin',136,'news_body'),(1,1,76,'','','bottom',137,'news_body'),(1,1,76,'','','12px',138,'news_body'),(1,1,76,'','','font',148,'news_body'),(1,1,76,'','','1',150,'news_body'),(1,1,76,'','','1em',151,'news_body'),(1,1,76,'','','background',161,'news_body'),(1,1,76,'','','url',162,'news_body'),(1,1,76,'','','images',163,'news_body'),(1,1,76,'','','quote',164,'news_body'),(1,1,76,'','','gif',165,'news_body'),(1,1,76,'','','padding',175,'news_body'),(1,1,76,'','','28px',177,'news_body'),(1,1,76,'','','color',187,'news_body'),(1,1,76,'','','555',189,'news_body'),(1,1,76,'','','code',191,'news_body'),(1,1,76,'','','ul',202,'news_body'),(1,1,76,'','','tristique',215,'news_body'),(1,1,76,'','','aenean',234,'news_body'),(1,1,76,'','','pretium',246,'news_body'),(1,1,76,'','','ul',264,'news_body'),(1,1,76,'','','p',271,'news_body'),(1,1,76,'','','eget',272,'news_body'),(1,1,76,'','','feugiat',273,'news_body'),(1,1,76,'','','est',274,'news_body'),(1,1,76,'','','leo',275,'news_body'),(1,1,76,'','','tempor',276,'news_body'),(1,1,76,'','','quam',277,'news_body'),(1,1,76,'','','ut',278,'news_body'),(1,1,76,'','','quis',279,'news_body'),(1,1,76,'','','neque',280,'news_body'),(1,1,76,'','','convallis',281,'news_body'),(1,1,76,'','','magna',282,'news_body'),(1,1,76,'','','consequat',283,'news_body'),(1,1,76,'','','molestie',284,'news_body'),(1,1,76,'','','p',286,'news_body'),(0,2,81,'','','forms',2,'news_title'),(0,2,81,'','','bitte',0,'news_body'),(0,2,81,'','','folgen',1,'news_body'),(0,2,81,'','','sie',2,'news_body'),(0,2,81,'','','diesen',3,'news_body'),(0,2,81,'','','schritten',4,'news_body'),(0,2,81,'','','um',5,'news_body'),(0,2,81,'','','mit',6,'news_body'),(0,2,81,'','','ihrer',7,'news_body'),(0,2,81,'','','website',8,'news_body'),(0,2,81,'','','zu',9,'news_body'),(0,2,81,'','','beginnen',10,'news_body'),(0,2,81,'','','1',14,'news_body'),(0,2,81,'','','konfigurieren',15,'news_body'),(0,2,81,'','','sie',16,'news_body'),(0,2,81,'','','ihre',17,'news_body'),(0,2,81,'','','website',18,'news_body'),(0,2,81,'','','wenn',19,'news_body'),(0,2,81,'','','sie',20,'news_body'),(0,2,81,'','','angemeldet',21,'news_body'),(0,2,81,'','','sind',22,'news_body'),(0,2,81,'','','besuchen',23,'news_body'),(0,2,81,'','','sie',24,'news_body'),(0,2,81,'','','den',25,'news_body'),(0,2,81,'','','administrationsbereich',26,'news_body'),(0,2,81,'','','dem',28,'news_body'),(0,2,81,'','','sie',29,'news_body'),(0,2,81,'','','alle',30,'news_body'),(0,2,81,'','','aspekte',31,'news_body'),(0,2,81,'','','ihrer',32,'news_body'),(0,2,81,'','','website',33,'news_body'),(0,2,81,'','','anpassen',34,'news_body'),(0,2,81,'','','und',35,'news_body'),(0,2,81,'','','konfigurieren',36,'news_body'),(0,2,81,'','','konnen',37,'news_body'),(0,2,81,'','','2',40,'news_body'),(0,2,81,'','','aktivieren',41,'news_body'),(0,2,81,'','','sie',42,'news_body'),(0,2,81,'','','zusatzliche',43,'news_body'),(0,2,81,'','','funktionen',44,'news_body'),(0,2,81,'','','als',45,'news_body'),(0,2,81,'','','nachstes',46,'news_body'),(0,2,81,'','','konnen',47,'news_body'),(0,2,81,'','','sie',48,'news_body'),(0,2,81,'','','der',50,'news_body'),(0,2,81,'','','modulubersicht',51,'news_body'),(0,2,81,'','','die',52,'news_body'),(0,2,81,'','','funktionen',53,'news_body'),(0,2,81,'','','aktivieren',54,'news_body'),(0,2,81,'','','die',55,'news_body'),(0,2,81,'','','ihren',56,'news_body'),(0,2,81,'','','speziellen',57,'news_body'),(0,2,81,'','','anforderungen',58,'news_body'),(0,2,81,'','','entsprechen',59,'news_body'),(0,2,81,'','','sie',60,'news_body'),(0,2,81,'','','finden',61,'news_body'),(0,2,81,'','','zusatzliche',62,'news_body'),(0,2,81,'','','module',63,'news_body'),(0,2,81,'','','im',64,'news_body'),(0,2,81,'','','downloadbereich',65,'news_body'),(0,2,81,'','','fur',66,'news_body'),(0,2,81,'','','clansuite',67,'news_body'),(0,2,81,'','','module',68,'news_body'),(0,2,81,'','','3',71,'news_body'),(0,2,81,'','','passen',72,'news_body'),(0,2,81,'','','sie',73,'news_body'),(0,2,81,'','','das',74,'news_body'),(0,2,81,'','','design',75,'news_body'),(0,2,81,'','','ihrer',76,'news_body'),(0,2,81,'','','website',77,'news_body'),(0,2,81,'','','um',79,'news_body'),(0,2,81,'','','das',80,'news_body'),(0,2,81,'','','aussehen',81,'news_body'),(0,2,81,'','','ihrer',82,'news_body'),(0,2,81,'','','website',83,'news_body'),(0,2,81,'','','zu',84,'news_body'),(0,2,81,'','','verandern',85,'news_body'),(0,2,81,'','','besuchen',86,'news_body'),(0,2,81,'','','sie',87,'news_body'),(0,2,81,'','','den',88,'news_body'),(0,2,81,'','','theme',89,'news_body'),(0,2,81,'','','bereich',90,'news_body'),(0,2,81,'','','sie',91,'news_body'),(0,2,81,'','','konnen',92,'news_body'),(0,2,81,'','','aus',93,'news_body'),(0,2,81,'','','den',94,'news_body'),(0,2,81,'','','mitgelieferten',95,'news_body'),(0,2,81,'','','themes',96,'news_body'),(0,2,81,'','','auswahlen',97,'news_body'),(0,2,81,'','','oder',98,'news_body'),(0,2,81,'','','zusatzliche',99,'news_body'),(0,2,81,'','','themes',100,'news_body'),(0,2,81,'','','aus',101,'news_body'),(0,2,81,'','','dem',102,'news_body'),(0,2,81,'','','downloadbereich',103,'news_body'),(0,2,81,'','','fur',104,'news_body'),(0,2,81,'','','clansuite',105,'news_body'),(0,2,81,'','','themes',106,'news_body'),(0,2,81,'','','herunterladen',107,'news_body'),(0,2,81,'','','4',110,'news_body'),(0,2,81,'','','beginnen',111,'news_body'),(0,2,81,'','','sie',112,'news_body'),(0,2,81,'','','inhalte',113,'news_body'),(0,2,81,'','','zu',114,'news_body'),(0,2,81,'','','schreiben',115,'news_body'),(0,2,81,'','','schliesslich',116,'news_body'),(0,2,81,'','','konnen',117,'news_body'),(0,2,81,'','','sie',118,'news_body'),(0,2,81,'','','fur',119,'news_body'),(0,2,81,'','','ihre',120,'news_body'),(0,2,81,'','','website',121,'news_body'),(0,2,81,'','','inhalte',122,'news_body'),(0,2,81,'','','erstellen',123,'news_body'),(0,2,81,'','','dieser',124,'news_body'),(0,2,81,'','','informationstext',125,'news_body'),(0,2,81,'','','verschwindet',126,'news_body'),(0,2,81,'','','sobald',127,'news_body'),(0,2,81,'','','der',128,'news_body'),(0,2,81,'','','erste',129,'news_body'),(0,2,81,'','','beitrag',130,'news_body'),(0,2,81,'','','erscheint',131,'news_body'),(0,2,81,'','','nahere',134,'news_body'),(0,2,81,'','','informationen',135,'news_body'),(0,2,81,'','','finden',136,'news_body'),(0,2,81,'','','sich',137,'news_body'),(0,2,81,'','','im',138,'news_body'),(0,2,81,'','','hilfe',139,'news_body'),(0,2,81,'','','bereich',140,'news_body'),(0,2,81,'','','oder',141,'news_body'),(0,2,81,'','','online',142,'news_body'),(0,2,81,'','','den',144,'news_body'),(0,2,81,'','','clansuite',145,'news_body'),(0,2,81,'','','handbuchern',146,'news_body'),(0,2,81,'','','sie',147,'news_body'),(0,2,81,'','','konnen',148,'news_body'),(0,2,81,'','','auch',149,'news_body'),(0,2,81,'','','einen',150,'news_body'),(0,2,81,'','','beitrag',151,'news_body'),(0,2,81,'','','im',152,'news_body'),(0,2,81,'','','clansuite',153,'news_body'),(0,2,81,'','','forum',154,'news_body'),(0,2,81,'','','schreiben',155,'news_body'),(0,2,81,'','','oder',156,'news_body'),(0,2,81,'','','aus',157,'news_body'),(0,2,81,'','','den',158,'news_body'),(0,2,81,'','','vielfaltigen',159,'news_body'),(0,2,81,'','','anderen',160,'news_body'),(0,2,81,'','','verfugbaren',161,'news_body'),(0,2,81,'','','support',162,'news_body'),(0,2,81,'','','angeboten',163,'news_body'),(0,2,81,'','','auswahlen',164,'news_body'),(2,2,24,'','','dui',133,'news_body'),(2,2,24,'','','quisque',131,'news_body'),(2,2,24,'','','potenti',130,'news_body'),(2,2,24,'','','suspendisse',129,'news_body'),(2,2,24,'','','egestas',128,'news_body'),(2,2,24,'','','vel',127,'news_body'),(2,2,24,'','','nisi',126,'news_body'),(2,2,24,'','','iaculis',125,'news_body'),(2,2,24,'','','lacinia',124,'news_body'),(2,2,24,'','','maecenas',123,'news_body'),(2,2,24,'','','risus',122,'news_body'),(2,2,24,'','','odio',121,'news_body'),(2,2,24,'','','id',120,'news_body'),(2,2,24,'','','mauris',119,'news_body'),(2,2,24,'','','consectetur',118,'news_body'),(2,2,24,'','','venenatis',117,'news_body'),(2,2,24,'','','vulputate',116,'news_body'),(2,2,24,'','','sed',115,'news_body'),(2,2,24,'','','pretium',114,'news_body'),(2,2,24,'','','pulvinar',113,'news_body'),(2,2,24,'','','risus',112,'news_body'),(2,2,24,'','','id',111,'news_body'),(2,2,24,'','','metus',110,'news_body'),(2,2,24,'','','et',109,'news_body'),(2,2,24,'','','etiam',108,'news_body'),(2,2,24,'','','arcu',107,'news_body'),(2,2,24,'','','molestie',106,'news_body'),(2,2,24,'','','et',105,'news_body'),(2,2,24,'','','vulputate',104,'news_body'),(0,2,91,'','','br',61,'news_body'),(0,2,91,'','','ss',60,'news_body'),(0,2,91,'','','u',59,'news_body'),(0,2,91,'','','o',57,'news_body'),(0,2,91,'','','konnen',56,'news_body'),(0,2,91,'','','anpassen',53,'news_body'),(0,2,91,'','','und',54,'news_body'),(0,2,91,'','','konfigurieren',55,'news_body'),(0,2,91,'','','website',52,'news_body'),(0,2,91,'','','ihrer',51,'news_body'),(0,2,91,'','','aspekte',50,'news_body'),(0,2,91,'','','alle',49,'news_body'),(0,2,91,'','','sie',48,'news_body'),(0,2,91,'','','dem',47,'news_body'),(0,2,91,'','','administrationsbereich',45,'news_body'),(0,2,91,'','','den',44,'news_body'),(0,2,91,'','','sie',43,'news_body'),(0,2,91,'','','sind',41,'news_body'),(0,2,91,'','','besuchen',42,'news_body'),(0,2,91,'','','angemeldet',40,'news_body'),(0,2,91,'','','sie',39,'news_body'),(0,2,91,'','','wenn',38,'news_body'),(0,2,91,'','','br',37,'news_body'),(0,2,91,'','','br',36,'news_body'),(0,2,91,'','','span',35,'news_body'),(0,2,91,'','','website',33,'news_body'),(0,2,91,'','','ihre',32,'news_body'),(0,2,91,'','','sie',31,'news_body'),(0,2,91,'','','konfigurieren',30,'news_body'),(0,2,91,'','','1',29,'news_body'),(0,2,91,'','','bold',28,'news_body'),(0,2,91,'','','weight',27,'news_body'),(0,2,91,'','','font',26,'news_body'),(0,2,91,'','','style',25,'news_body'),(0,2,91,'','','span',24,'news_body'),(0,2,91,'','','br',23,'news_body'),(0,2,91,'','','br',22,'news_body'),(0,2,91,'','','span',21,'news_body'),(0,2,91,'','','beginnen',16,'news_body'),(0,2,91,'','','zu',15,'news_body'),(0,2,91,'','','website',14,'news_body'),(0,2,91,'','','ihrer',13,'news_body'),(0,2,91,'','','mit',12,'news_body'),(0,2,91,'','','um',11,'news_body'),(0,2,91,'','','schritten',10,'news_body'),(0,2,91,'','','diesen',9,'news_body'),(0,2,91,'','','sie',8,'news_body'),(0,2,91,'','','folgen',7,'news_body'),(0,2,91,'','','bitte',6,'news_body'),(0,2,91,'','','bold',5,'news_body'),(0,2,91,'','','weight',4,'news_body'),(0,2,91,'','','font',3,'news_body'),(0,2,91,'','','style',2,'news_body'),(0,2,91,'','','span',1,'news_body'),(0,2,91,'','','website',5,'news_title'),(0,2,91,'','','clansuite',4,'news_title'),(0,2,91,'','','neuen',3,'news_title'),(0,2,91,'','','ihrer',2,'news_title'),(0,2,91,'','','auf',1,'news_title'),(0,2,91,'','','willkommen',0,'news_title'),(0,1,0,'','','aya',69,'news_body'),(0,1,0,'','','aea',70,'news_body'),(0,1,0,'','','aza',75,'news_body'),(0,1,0,'','','aa',80,'news_body'),(0,1,0,'','','aoea',105,'news_body'),(0,1,0,'','','aca',112,'news_body'),(0,1,0,'','','aoea',113,'news_body'),(0,1,0,'','','aza',120,'news_body'),(0,1,0,'','','cyrillic',0,'news_title'),(0,1,0,'','','afa',13,'news_body'),(0,1,0,'','','asa',18,'news_body'),(0,1,0,'','','aua',19,'news_body'),(0,1,0,'','','aya',44,'news_body'),(0,1,0,'','','asa',45,'news_body'),(0,1,0,'','','aya',66,'news_body'),(0,1,0,'','','aea',67,'news_body'),(0,1,0,'','','aza',72,'news_body'),(0,1,0,'','','aa',77,'news_body'),(0,1,0,'','','aoea',102,'news_body'),(0,1,0,'','','aca',109,'news_body'),(0,1,0,'','','aoea',110,'news_body'),(0,1,0,'','','aza',117,'news_body'),(0,1,0,'','','cyrillic',0,'news_title'),(0,1,0,'','','afa',13,'news_body'),(0,1,0,'','','asa',18,'news_body'),(0,1,0,'','','aua',19,'news_body'),(0,1,0,'','','aya',44,'news_body'),(0,1,0,'','','asa',45,'news_body'),(0,1,0,'','','aya',66,'news_body'),(0,1,0,'','','aea',67,'news_body'),(0,1,0,'','','aza',72,'news_body'),(0,1,0,'','','aa',77,'news_body'),(0,1,0,'','','aoea',102,'news_body'),(0,1,0,'','','aca',109,'news_body'),(0,1,0,'','','aoea',110,'news_body'),(0,1,0,'','','aza',117,'news_body'),(0,1,0,'','','cyrillic',0,'news_title'),(0,1,0,'','','backend',2,'news_title'),(0,1,0,'','','n',0,'news_body'),(0,1,0,'','','dh',1,'news_body'),(0,1,0,'','','dh',2,'news_body'),(0,1,0,'','','dh',3,'news_body'),(0,1,0,'','','n',4,'news_body'),(0,1,0,'','','dh',5,'news_body'),(0,1,0,'','','nfdh',6,'news_body'),(0,1,0,'','','dh',7,'news_body'),(0,1,0,'','','dhsdhudh',8,'news_body'),(0,1,0,'','','dh',9,'news_body'),(0,1,0,'','','dh',10,'news_body'),(0,1,0,'','','dh',11,'news_body'),(0,1,0,'','','dh',12,'news_body'),(0,1,0,'','','n',13,'news_body'),(0,1,0,'','','dh',14,'news_body'),(0,1,0,'','','n',15,'news_body'),(0,1,0,'','','dh',16,'news_body'),(0,1,0,'','','dh',17,'news_body'),(0,1,0,'','','dh',18,'news_body'),(0,1,0,'','','n',19,'news_body'),(0,1,0,'','','dhynsdh',20,'news_body'),(0,1,0,'','','n',21,'news_body'),(0,1,0,'','','dh',22,'news_body'),(0,1,0,'','','n',23,'news_body'),(0,1,0,'','','dh',24,'news_body'),(0,1,0,'','','dh',25,'news_body'),(0,1,0,'','','dh',26,'news_body'),(0,1,0,'','','dh',27,'news_body'),(0,1,0,'','','dh',28,'news_body'),(0,1,0,'','','dh',29,'news_body'),(0,1,0,'','','dhynedh',30,'news_body'),(0,1,0,'','','dh',31,'news_body'),(0,1,0,'','','dhzdh',32,'news_body'),(0,1,0,'','','dh',33,'news_body'),(0,1,0,'','','dhdh',34,'news_body'),(0,1,0,'','','dh',35,'news_body'),(0,1,0,'','','dh',36,'news_body'),(0,1,0,'','','n',37,'news_body'),(0,1,0,'','','dh',38,'news_body'),(0,1,0,'','','n',39,'news_body'),(0,1,0,'','','dh',40,'news_body'),(0,1,0,'','','n',41,'news_body'),(0,1,0,'','','dh',42,'news_body'),(0,1,0,'','','n',43,'news_body'),(0,1,0,'','','dh',44,'news_body'),(0,1,0,'','','dh',45,'news_body'),(0,1,0,'','','dhoedh',46,'news_body'),(0,1,0,'','','dh',47,'news_body'),(0,1,0,'','','n',48,'news_body'),(0,1,0,'','','dhcnoedh',49,'news_body'),(0,1,0,'','','dh',50,'news_body'),(0,1,0,'','','dh',51,'news_body'),(0,1,0,'','','nzdh',52,'news_body'),(0,1,99,'','','dhdh',59,'news_body'),(0,1,99,'','','dh',58,'news_body'),(0,1,99,'','','dhzdh',57,'news_body'),(0,1,99,'','','dh',56,'news_body'),(0,1,99,'','','dhynedh',55,'news_body'),(0,1,99,'','','dh',54,'news_body'),(0,1,99,'','','dh',53,'news_body'),(0,1,99,'','','dh',52,'news_body'),(0,1,99,'','','dh',51,'news_body'),(0,1,99,'','','dh',50,'news_body'),(0,1,99,'','','dh',49,'news_body'),(0,1,99,'','','n',48,'news_body'),(0,1,99,'','','dh',47,'news_body'),(0,1,99,'','','n',46,'news_body'),(0,1,99,'','','dhynsdh',45,'news_body'),(0,1,99,'','','n',44,'news_body'),(0,1,99,'','','dh',43,'news_body'),(0,1,99,'','','dh',42,'news_body'),(0,1,99,'','','dh',41,'news_body'),(0,1,99,'','','n',40,'news_body'),(0,1,99,'','','span',39,'news_body'),(0,1,99,'','','dh',37,'news_body'),(0,1,99,'','','n',36,'news_body'),(0,1,99,'','','dh',35,'news_body'),(0,1,99,'','','dh',34,'news_body'),(0,1,99,'','','dh',33,'news_body'),(0,1,99,'','','dh',32,'news_body'),(0,1,99,'','','underline',31,'news_body'),(0,1,99,'','','decoration',30,'news_body'),(0,1,99,'','','text',29,'news_body'),(0,1,99,'','','style',28,'news_body'),(0,1,99,'','','span',27,'news_body'),(0,1,99,'','','span',26,'news_body'),(0,1,99,'','','dhsdhudh',24,'news_body'),(0,1,99,'','','dh',23,'news_body'),(0,1,99,'','','nfdh',22,'news_body'),(0,1,99,'','','dh',21,'news_body'),(0,1,99,'','','italic',20,'news_body'),(0,1,99,'','','style',19,'news_body'),(0,1,99,'','','font',18,'news_body'),(0,1,99,'','','style',17,'news_body'),(0,1,99,'','','span',16,'news_body'),(0,1,99,'','','span',15,'news_body'),(0,1,99,'','','n',13,'news_body'),(0,1,99,'','','dh',12,'news_body'),(0,1,99,'','','dh',11,'news_body'),(0,1,99,'','','dh',10,'news_body'),(0,1,99,'','','n',9,'news_body'),(0,1,99,'','','bold',8,'news_body'),(0,1,99,'','','weight',7,'news_body'),(0,1,99,'','','font',6,'news_body'),(0,1,99,'','','style',5,'news_body'),(0,1,99,'','','span',4,'news_body'),(0,1,99,'','','3',3,'news_body'),(0,1,99,'','','font',1,'news_body'),(0,1,99,'','','backend',2,'news_title'),(0,1,99,'','','cyrillic',0,'news_title'),(0,1,99,'','','dh',60,'news_body'),(0,1,99,'','','dh',61,'news_body'),(0,1,99,'','','n',62,'news_body'),(0,1,99,'','','dh',63,'news_body'),(0,1,99,'','','n',64,'news_body'),(0,1,99,'','','dh',65,'news_body'),(0,1,99,'','','n',66,'news_body'),(0,1,99,'','','dh',67,'news_body'),(0,1,99,'','','n',68,'news_body'),(0,1,99,'','','dh',69,'news_body'),(0,1,99,'','','dh',70,'news_body'),(0,1,99,'','','dhoedh',71,'news_body'),(0,1,99,'','','dh',72,'news_body'),(0,1,99,'','','n',73,'news_body'),(0,1,99,'','','dhcnoedh',74,'news_body'),(0,1,99,'','','dh',75,'news_body'),(0,1,99,'','','dh',76,'news_body'),(0,1,99,'','','nzdh',77,'news_body'),(0,1,99,'','','br',78,'news_body'),(0,1,99,'','','br',79,'news_body'),(0,1,99,'','','br',83,'news_body'),(0,1,99,'','','text',86,'news_body'),(0,1,99,'','','cyrillic',88,'news_body'),(0,1,99,'','','br',89,'news_body'),(0,1,99,'','','b',90,'news_body'),(0,1,99,'','','its',91,'news_body'),(0,1,99,'','','formatted',92,'news_body'),(0,1,99,'','','wysiwyg',95,'news_body'),(0,1,99,'','','editor',96,'news_body'),(0,1,99,'','','html',100,'news_body'),(0,1,99,'','','span',101,'news_body'),(0,1,99,'','','tags',102,'news_body'),(0,1,99,'','','applied',103,'news_body'),(0,1,99,'','','text',106,'news_body'),(0,1,99,'','','br',107,'news_body'),(0,1,99,'','','font',109,'news_body'),(0,1,0,'','','german',0,'news_title'),(0,1,0,'','','specialchars',1,'news_title'),(0,1,0,'','','ae',0,'news_body'),(0,1,0,'','','auml',2,'news_body'),(0,1,0,'','','ae',4,'news_body'),(0,1,0,'','','auml',6,'news_body'),(0,1,0,'','','oe',8,'news_body'),(0,1,0,'','','ouml',10,'news_body'),(0,1,0,'','','oe',12,'news_body'),(0,1,0,'','','ouml',14,'news_body'),(0,1,0,'','','ue',16,'news_body'),(0,1,0,'','','uuml',18,'news_body'),(0,1,0,'','','ue',20,'news_body'),(0,1,0,'','','uuml',22,'news_body'),(0,1,0,'','','ss',24,'news_body'),(0,1,0,'','','szlig',26,'news_body'),(0,1,100,'','','german',0,'news_title'),(0,1,100,'','','specialchars',1,'news_title'),(0,1,100,'','','ae',0,'news_body'),(0,1,100,'','','ae',1,'news_body'),(0,1,100,'','','ae',2,'news_body'),(0,1,100,'','','ae',3,'news_body'),(0,1,100,'','','oe',4,'news_body'),(0,1,100,'','','oe',5,'news_body'),(0,1,100,'','','oe',6,'news_body'),(0,1,100,'','','oe',7,'news_body'),(0,1,100,'','','ue',8,'news_body'),(0,1,100,'','','ue',9,'news_body'),(0,1,100,'','','ue',10,'news_body'),(0,1,100,'','','ue',11,'news_body'),(0,1,100,'','','ss',12,'news_body'),(0,1,100,'','','ss',13,'news_body'),(0,1,100,'','','br',14,'news_body'),(0,1,100,'','','br',15,'news_body'),(0,1,100,'','','h5',16,'news_body'),(0,1,100,'','','span',17,'news_body'),(0,1,100,'','','style',18,'news_body'),(0,1,100,'','','font',19,'news_body'),(0,1,100,'','','weight',20,'news_body'),(0,1,100,'','','bold',21,'news_body'),(0,1,100,'','','ae',22,'news_body'),(0,1,100,'','','ae',23,'news_body'),(0,1,100,'','','span',25,'news_body'),(0,1,100,'','','style',26,'news_body'),(0,1,100,'','','font',27,'news_body'),(0,1,100,'','','style',28,'news_body'),(0,1,100,'','','italic',29,'news_body'),(0,1,100,'','','ae',30,'news_body'),(0,1,100,'','','ae',31,'news_body'),(0,1,100,'','','span',32,'news_body'),(0,1,100,'','','oe',34,'news_body'),(0,1,100,'','','oe',35,'news_body'),(0,1,100,'','','span',37,'news_body'),(0,1,100,'','','style',38,'news_body'),(0,1,100,'','','text',39,'news_body'),(0,1,100,'','','decoration',40,'news_body'),(0,1,100,'','','underline',41,'news_body'),(0,1,100,'','','oe',42,'news_body'),(0,1,100,'','','oe',43,'news_body'),(0,1,100,'','','span',44,'news_body'),(0,1,100,'','','span',47,'news_body'),(0,1,100,'','','ue',48,'news_body'),(0,1,100,'','','ue',49,'news_body'),(0,1,100,'','','ue',50,'news_body'),(0,1,100,'','','ue',51,'news_body'),(0,1,100,'','','ss',52,'news_body'),(0,1,100,'','','ss',53,'news_body'),(0,1,100,'','','h5',54,'news_body');
/*!40000 ALTER TABLE `cs_news_index` ENABLE KEYS */;

--
-- Table structure for table `cs_opponents`
--

DROP TABLE IF EXISTS `cs_opponents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_opponents` (
  `opponent_id` int(3) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `clantag` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `websiteurl` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `ircchannel` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `image_id` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_opponents`
--

/*!40000 ALTER TABLE `cs_opponents` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_opponents` ENABLE KEYS */;

--
-- Table structure for table `cs_options`
--

DROP TABLE IF EXISTS `cs_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_options` (
  `option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_id` int(10) unsigned NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`option_id`,`name_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_profiles`
--

/*!40000 ALTER TABLE `cs_profiles` DISABLE KEYS */;
INSERT INTO `cs_profiles` VALUES (1,1,1175369474,'Florian','Wolf',496274400,'male',178,'MÃ¼hlenstr. 65','78126','Jena','DE','http://www.clansuite.com','163164530','-','-','-','-','[b]bla[/b]'),(2,3,1172510321,'','',0,'-',0,'-','-','-','-','-','-','-','-','-','-','');
/*!40000 ALTER TABLE `cs_profiles` ENABLE KEYS */;

--
-- Table structure for table `cs_profiles_computer`
--

DROP TABLE IF EXISTS `cs_profiles_computer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_profiles_computer`
--

/*!40000 ALTER TABLE `cs_profiles_computer` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_profiles_computer` ENABLE KEYS */;

--
-- Table structure for table `cs_profiles_general`
--

DROP TABLE IF EXISTS `cs_profiles_general`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_profiles_general`
--

/*!40000 ALTER TABLE `cs_profiles_general` DISABLE KEYS */;
INSERT INTO `cs_profiles_general` VALUES (1,1,1175292000,'Florian','Wolf',496274400,'',178,'MÃ¼hlenstr. 65','78126','Jena','DDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDDD','','http://www.clansuite.com','163164530','','','','','[b]bla[/b]',3),(2,3,1172510321,'','',0,'-',0,'-','-','-','','-','-','-','-','-','-','-','',0),(3,2,1175635148,'','',0,'-',0,'-','-','-','','-','-','-','-','-','-','-','',0);
/*!40000 ALTER TABLE `cs_profiles_general` ENABLE KEYS */;

--
-- Table structure for table `cs_profiles_guestbook`
--

DROP TABLE IF EXISTS `cs_profiles_guestbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_quotes` (
  `quote_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `quote_body` text COLLATE utf8_unicode_ci NOT NULL,
  `quote_author` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `quote_source` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`quote_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_quotes`
--

/*!40000 ALTER TABLE `cs_quotes` DISABLE KEYS */;
INSERT INTO `cs_quotes` VALUES (1,'The turning point came when the mechanics of the storage of knowledge within the brain was worked out. Once that had been done, it became possible to devise Educational tapes that would modify the mechanics in such a way as to place within the mind a body of knowledge ready-made so to speak.','Isaac Asimov (1920-1992)','123');
/*!40000 ALTER TABLE `cs_quotes` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_category_module`
--

DROP TABLE IF EXISTS `cs_rel_category_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_rel_category_module` (
  `category_id` int(10) unsigned NOT NULL,
  `module_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`category_id`,`module_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_rel_category_module`
--

/*!40000 ALTER TABLE `cs_rel_category_module` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_rel_category_module` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_category_name`
--

DROP TABLE IF EXISTS `cs_rel_category_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_rel_category_name` (
  `category_id` int(10) unsigned NOT NULL,
  `name_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`category_id`,`name_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_rel_category_name`
--

/*!40000 ALTER TABLE `cs_rel_category_name` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_rel_category_name` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_group_rights`
--

DROP TABLE IF EXISTS `cs_rel_group_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_rel_group_rights` (
  `group_id` int(11) NOT NULL DEFAULT '0',
  `right_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_rel_news_comments` (
  `news_id` int(10) unsigned NOT NULL,
  `comment_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`news_id`,`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_rel_news_comments`
--

/*!40000 ALTER TABLE `cs_rel_news_comments` DISABLE KEYS */;
INSERT INTO `cs_rel_news_comments` VALUES (16,2),(91,1);
/*!40000 ALTER TABLE `cs_rel_news_comments` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_option_name`
--

DROP TABLE IF EXISTS `cs_rel_option_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_rel_option_name` (
  `option_id` int(10) unsigned NOT NULL,
  `name_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`option_id`,`name_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_rel_option_name`
--

/*!40000 ALTER TABLE `cs_rel_option_name` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_rel_option_name` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_user_groups`
--

DROP TABLE IF EXISTS `cs_rel_user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_rel_user_groups` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_rel_user_options` (
  `option_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`option_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_rel_user_profile` (
  `user_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`profile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_rel_user_profile`
--

/*!40000 ALTER TABLE `cs_rel_user_profile` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_rel_user_profile` ENABLE KEYS */;

--
-- Table structure for table `cs_rel_user_rights`
--

DROP TABLE IF EXISTS `cs_rel_user_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_rel_user_rights` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `right_id` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_rel_user_rights`
--

/*!40000 ALTER TABLE `cs_rel_user_rights` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_rel_user_rights` ENABLE KEYS */;

--
-- Table structure for table `cs_rights`
--

DROP TABLE IF EXISTS `cs_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_rights` (
  `right_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `area_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`right_id`,`area_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_rights`
--

/*!40000 ALTER TABLE `cs_rights` DISABLE KEYS */;
INSERT INTO `cs_rights` VALUES (11,5,'shoutbox_post','The right to post into the shoutbox'),(10,4,'cc_access','The right to access the control center'),(12,6,'cc_create_news','Add a news'),(13,7,'cc_access_filebrowser','Access the filebrowser'),(14,6,'cc_edit_news','Edit a news'),(15,6,'cc_view_news','View the news'),(16,17,'use_messaging_system','The ability to use the messaging system.'),(17,18,'cc_edit_themes','The ability to edit the themes'),(18,8,'cc_view_gb','View the guestbook'),(19,8,'cc_edit_gb','Edit the guestbook'),(20,8,'create_gb_entries','Create guestbook entries'),(21,19,'cc_edit_generals','Abilty to edit the normal profile stuff'),(22,19,'cc_edit_computers','Abilty to edit the computers in the profile'),(23,19,'cc_edit_userguestbooks','Abilty to edit the user guestbook'),(24,20,'cc_show_users','Show the users in the control center'),(25,20,'cc_create_users','Ability to create a user'),(26,20,'cc_edit_users','Ability to edit users'),(27,20,'cc_search_users','Abililty to search users (advanced) in the control center'),(28,20,'cc_delete_users','Ability to delete users'),(29,5,'shoutbox_view','Ability to view the shoutbox');
/*!40000 ALTER TABLE `cs_rights` ENABLE KEYS */;

--
-- Table structure for table `cs_serverviewer`
--

DROP TABLE IF EXISTS `cs_serverviewer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_serverviewer` (
  `server_id` int(5) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `port` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `csquery_engine` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gametype` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_country` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`server_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_serverviewer`
--

/*!40000 ALTER TABLE `cs_serverviewer` DISABLE KEYS */;
INSERT INTO `cs_serverviewer` VALUES (1,'team-n1.com','27339','','knd-squad DEATHMATCH powered by CLANSUITE DOT COM ','steam','cs','de.png'),(2,'87.117.208.193','27025','',' DigitalTakedown.org UK - Public Server','steam','cs','de.png'),(3,'83.133.81.95','27015','','#German Headquarter 3 WC3FT | Team.GHQ | www.team-GHQ.eu ','steam','cs','de.png'),(4,'210.55.92.68','27980','','Xtra Quake 3 RA #1','q3a','q3a','de.png'),(5,'85.14.233.32','27015','','B I E R F R I E D H O F | TICK100 | by ngz-server.de','steam','cssource','de.png');
/*!40000 ALTER TABLE `cs_serverviewer` ENABLE KEYS */;

--
-- Table structure for table `cs_session`
--

DROP TABLE IF EXISTS `cs_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_session`
--

/*!40000 ALTER TABLE `cs_session` DISABLE KEYS */;
INSERT INTO `cs_session` VALUES (0,'af3pfk4pp910lbj04uscu88o85','user|a:13:{s:8:\"language\";s:2:\"de\";s:6:\"authed\";i:0;s:7:\"user_id\";i:0;s:4:\"nick\";s:5:\"Guest\";s:12:\"passwordhash\";s:0:\"\";s:5:\"email\";s:0:\"\";s:8:\"disabled\";i:0;s:9:\"activated\";i:0;s:5:\"theme\";s:8:\"standard\";s:6:\"groups\";a:1:{i:0;i:1;}s:6:\"rights\";a:1:{i:0;i:1;}s:12:\"backendtheme\";s:5:\"admin\";s:21:\"simple_captcha_string\";s:4:\"8LRm\";}Datagrid_CsNews|a:4:{s:7:\"SortKey\";s:0:\"\";s:9:\"SortValue\";s:0:\"\";s:9:\"SearchKey\";s:0:\"\";s:11:\"SearchValue\";s:0:\"\";}','suiteSID',1273783980,1,'news');
/*!40000 ALTER TABLE `cs_session` ENABLE KEYS */;

--
-- Table structure for table `cs_settings`
--

DROP TABLE IF EXISTS `cs_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_settings` (
  `option_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_id` int(10) unsigned NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`option_id`,`name_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_settings`
--

/*!40000 ALTER TABLE `cs_settings` DISABLE KEYS */;
INSERT INTO `cs_settings` VALUES (1,1,'drahtgitter'),(2,2,'en');
/*!40000 ALTER TABLE `cs_settings` ENABLE KEYS */;

--
-- Table structure for table `cs_shoutbox`
--

DROP TABLE IF EXISTS `cs_shoutbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_shoutbox` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `msg` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_static_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `html` text COLLATE utf8_unicode_ci NOT NULL,
  `iframe` tinyint(1) NOT NULL DEFAULT '0',
  `iframe_height` int(11) NOT NULL DEFAULT '300',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_static_pages`
--

/*!40000 ALTER TABLE `cs_static_pages` DISABLE KEYS */;
INSERT INTO `cs_static_pages` VALUES (1,'Credits','Without their brains Clansuite would not be - Thanks alot!','','<u><strong>Clansuite - Credits </strong></u>\r\n<br />\r\n<br />\r\n<br />\r\n<table width=\"691\" height=\"393\" cellspacing=\"1\" cellpadding=\"1\" border=\"1\" align=\"\" summary=\"\">\r\n    <tbody>\r\n        <tr>\r\n            <td align=\"center\">Class</td>\r\n            <td align=\"center\">Author<br />\r\n            </td>\r\n            <td align=\"center\">&nbsp;Licence</td>\r\n        </tr>\r\n        <tr>\r\n            <td>tar.class.php</td>\r\n            <td>Vincent Blavet &lt;vincent@phpconcept.net&gt;<br />\r\n            Copyright (c) 1997-2003 The PHP Group <br />\r\n            </td>\r\n            <td>PHP license v3</td>\r\n        </tr>\r\n        <tr>\r\n            <td>PEAR, the PHP Extension and Application Repository</td>\r\n            <td>Sterling Hughes &lt;sterling@php.net&gt;<br />\r\n            Stig Bakken &lt;ssb@php.net&gt;<br />\r\n            Tomas V.V.Cox &lt;cox@idecnet.com&gt;<br />\r\n            Greg Beaver &lt;cellog@php.net&gt;<br />\r\n            &nbsp;Copyright&nbsp; 1997-2006 The PHP Group</td>\r\n            <td>PHP license v3</td>\r\n        </tr>\r\n        <tr>\r\n            <td>Swift Mailer: A Flexible PHP Mailer Class</td>\r\n            <td>&quot;Chris Corbyn&quot; &lt;chris@w3style.co.uk&gt;<br />\r\n            Copyright 2006 Chris Corbyn</td>\r\n            <td>LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Smarty: the PHP compiling template engine</td>\r\n            <td valign=\"top\">Monte Ohrt &lt;monte at ohrt dot com&gt;<br />\r\n            Andrei Zmievski &lt;andrei@php.net&gt;<br />\r\n            Copyright 2001-2005 New Digital Group, Inc.</td>\r\n            <td valign=\"top\">LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Sajax : cross-platform, cross-browser web scripting toolkit</td>\r\n            <td valign=\"top\">Copyright 2005-2006 modernmethod</td>\r\n            <td valign=\"top\">BSD</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Imagemanger</td>\r\n            <td valign=\"top\">Xiang Wei ZHUO &lt;wei@zhuo.org&gt;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">DHTML Calendar Javascript</td>\r\n            <td valign=\"top\">Copyright Mihai Bazon, 2002-2005</td>\r\n            <td valign=\"top\">LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Tab Pane Javascript</td>\r\n            <td valign=\"top\">Copyright (c) 2002, 2003, 2006 Erik Arvidsson</td>\r\n            <td valign=\"top\">Apache License v2</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\"><a href=\"http://www.fckeditor.net/\">FCKEditor</a>- WYSIWYG</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Icons by <a href=\"http://www.famfamfam.com/lab/icons/\">famfamfam</a></td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">mygosumenu\'s</td>\r\n            <td valign=\"top\">Copyright 2003,2004 Cezary Tomczak</td>\r\n            <td valign=\"top\">BSD</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Bitstream Vera Fonts </td>\r\n            <td valign=\"top\">Copyright (c) 2003 by Bitstream, Inc.</td>\r\n            <td valign=\"top\">own</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />',1,300),(2,'Google','Google','http://www.google.de','',1,500),(3,'Help','The help for ClanSuite','','<strong><font size=\"4\">Help</font><br />\r\n<br />\r\n</strong><strong> - gogo<br />\r\n- gogogogo<br />\r\n- gogogogogogo</strong>',1,300),(4,'Manual','The Manual','','<font size=\"4\">Manual</font><br />\r\n<br />\r\n- some content',1,300),(5,'About','About ClanSuite','','<font size=\"4\">About</font><br />\r\n<br />\r\n- some content',1,300);
/*!40000 ALTER TABLE `cs_static_pages` ENABLE KEYS */;

--
-- Table structure for table `cs_staticpages`
--

DROP TABLE IF EXISTS `cs_staticpages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_staticpages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `html` text COLLATE utf8_unicode_ci NOT NULL,
  `iframe` tinyint(1) NOT NULL DEFAULT '0',
  `iframe_height` int(11) NOT NULL DEFAULT '300',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_staticpages`
--

/*!40000 ALTER TABLE `cs_staticpages` DISABLE KEYS */;
INSERT INTO `cs_staticpages` VALUES (1,'Credits','Without their brains Clansuite would not be - Thanks alot!','','<u><strong>Clansuite - Credits </strong></u>\r\n<br />\r\n<br />\r\n<br />\r\n<table width=\"691\" height=\"393\" cellspacing=\"1\" cellpadding=\"1\" border=\"1\" align=\"\" summary=\"\">\r\n    <tbody>\r\n        <tr>\r\n            <td align=\"center\">Class</td>\r\n            <td align=\"center\">Author<br />\r\n            </td>\r\n            <td align=\"center\">&nbsp;Licence</td>\r\n        </tr>\r\n        <tr>\r\n            <td>tar.class.php</td>\r\n            <td>Vincent Blavet &lt;vincent@phpconcept.net&gt;<br />\r\n            Copyright (c) 1997-2003 The PHP Group <br />\r\n            </td>\r\n            <td>PHP license v3</td>\r\n        </tr>\r\n        <tr>\r\n            <td>PEAR, the PHP Extension and Application Repository</td>\r\n            <td>Sterling Hughes &lt;sterling@php.net&gt;<br />\r\n            Stig Bakken &lt;ssb@php.net&gt;<br />\r\n            Tomas V.V.Cox &lt;cox@idecnet.com&gt;<br />\r\n            Greg Beaver &lt;cellog@php.net&gt;<br />\r\n            &nbsp;Copyright&nbsp; 1997-2006 The PHP Group</td>\r\n            <td>PHP license v3</td>\r\n        </tr>\r\n        <tr>\r\n            <td>Swift Mailer: A Flexible PHP Mailer Class</td>\r\n            <td>&quot;Chris Corbyn&quot; &lt;chris@w3style.co.uk&gt;<br />\r\n            Copyright 2006 Chris Corbyn</td>\r\n            <td>LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Smarty: the PHP compiling template engine</td>\r\n            <td valign=\"top\">Monte Ohrt &lt;monte at ohrt dot com&gt;<br />\r\n            Andrei Zmievski &lt;andrei@php.net&gt;<br />\r\n            Copyright 2001-2005 New Digital Group, Inc.</td>\r\n            <td valign=\"top\">LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Sajax : cross-platform, cross-browser web scripting toolkit</td>\r\n            <td valign=\"top\">Copyright 2005-2006 modernmethod</td>\r\n            <td valign=\"top\">BSD</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Imagemanger</td>\r\n            <td valign=\"top\">Xiang Wei ZHUO &lt;wei@zhuo.org&gt;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">DHTML Calendar Javascript</td>\r\n            <td valign=\"top\">Copyright Mihai Bazon, 2002-2005</td>\r\n            <td valign=\"top\">LGPL</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Tab Pane Javascript</td>\r\n            <td valign=\"top\">Copyright (c) 2002, 2003, 2006 Erik Arvidsson</td>\r\n            <td valign=\"top\">Apache License v2</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\"><a href=\"http://www.fckeditor.net/\">FCKEditor</a>- WYSIWYG</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Icons by <a href=\"http://www.famfamfam.com/lab/icons/\">famfamfam</a></td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">mygosumenu\'s</td>\r\n            <td valign=\"top\">Copyright 2003,2004 Cezary Tomczak</td>\r\n            <td valign=\"top\">BSD</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">Bitstream Vera Fonts </td>\r\n            <td valign=\"top\">Copyright (c) 2003 by Bitstream, Inc.</td>\r\n            <td valign=\"top\">own</td>\r\n        </tr>\r\n        <tr>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n            <td valign=\"top\">&nbsp;</td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />',1,300),(2,'Google','Google','http://www.google.de','',1,500),(3,'Help','The help for ClanSuite','','<strong><font size=\"4\">Help</font><br />\r\n<br />\r\n</strong><strong> - gogo<br />\r\n- gogogogo<br />\r\n- gogogogogogo</strong>',1,300),(4,'Manual','The Manual','','<font size=\"4\">Manual</font><br />\r\n<br />\r\n- some content',1,300),(5,'About','About ClanSuite','','<font size=\"4\">About</font><br />\r\n<br />\r\n- some content',1,300);
/*!40000 ALTER TABLE `cs_staticpages` ENABLE KEYS */;

--
-- Table structure for table `cs_statistic`
--

DROP TABLE IF EXISTS `cs_statistic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_statistic` (
  `id` int(11) NOT NULL,
  `hits` int(20) NOT NULL DEFAULT '0',
  `views` int(50) NOT NULL DEFAULT '0',
  `online` int(14) NOT NULL DEFAULT '0',
  `maxonline` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_statistic`
--

/*!40000 ALTER TABLE `cs_statistic` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_statistic` ENABLE KEYS */;

--
-- Table structure for table `cs_statistic_ip`
--

DROP TABLE IF EXISTS `cs_statistic_ip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_statistic_ip` (
  `id` int(11) NOT NULL,
  `dates` int(11) NOT NULL,
  `del` int(11) NOT NULL,
  `ip` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_statistic_ip`
--

/*!40000 ALTER TABLE `cs_statistic_ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_statistic_ip` ENABLE KEYS */;

--
-- Table structure for table `cs_statistic_stats`
--

DROP TABLE IF EXISTS `cs_statistic_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_statistic_stats` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `dates` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `count` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_statistic_stats`
--

/*!40000 ALTER TABLE `cs_statistic_stats` DISABLE KEYS */;
INSERT INTO `cs_statistic_stats` VALUES (1,'10.12.2009',1),(2,'11.12.2009',1),(3,'12.12.2009',1),(4,'20.12.2009',1),(5,'20.12.2009',1),(6,'21.12.2009',1),(7,'22.12.2009',1),(8,'23.12.2009',1),(9,'26.01.2010',1),(10,'27.01.2010',1),(11,'28.01.2010',1),(12,'29.01.2010',1),(13,'30.01.2010',1),(14,'31.01.2010',1),(15,'01.02.2010',1),(16,'02.02.2010',1),(17,'03.02.2010',1),(18,'04.02.2010',1),(19,'07.02.2010',1),(20,'15.02.2010',1),(21,'20.02.2010',1),(22,'22.02.2010',1),(23,'26.02.2010',1),(24,'27.02.2010',1),(25,'28.02.2010',1),(26,'03.03.2010',1),(27,'04.03.2010',1),(28,'05.03.2010',1),(29,'06.03.2010',1),(30,'08.03.2010',2),(31,'11.03.2010',1),(32,'13.03.2010',1),(33,'14.03.2010',1),(34,'15.03.2010',1),(35,'16.03.2010',1),(36,'17.03.2010',1),(37,'18.03.2010',1),(38,'19.03.2010',2),(39,'20.03.2010',2),(40,'21.03.2010',1),(41,'24.03.2010',1),(42,'25.03.2010',1),(43,'26.03.2010',1),(44,'27.03.2010',1),(45,'28.03.2010',1),(46,'29.03.2010',1),(47,'13.05.2010',1);
/*!40000 ALTER TABLE `cs_statistic_stats` ENABLE KEYS */;

--
-- Table structure for table `cs_user_groups`
--

DROP TABLE IF EXISTS `cs_user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_user_groups` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_user_options` (
  `option_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `language` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `theme` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`option_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_user_rights` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `right_id` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`right_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=FIXED;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_user_rights`
--

/*!40000 ALTER TABLE `cs_user_rights` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_user_rights` ENABLE KEYS */;

--
-- Table structure for table `cs_users`
--

DROP TABLE IF EXISTS `cs_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  PRIMARY KEY (`user_id`),
  KEY `email` (`email`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_users`
--

/*!40000 ALTER TABLE `cs_users` DISABLE KEYS */;
INSERT INTO `cs_users` VALUES (1,'jakoch@web.de','user1','d033e22ae348aeb5660fc2140aec35850c4da997','','','','',1215754325,0,0,1,0,'de','de_DE','UTC1','standard'),(2,'user2@clansuite.com','user2','d1ca11799e222d429424d47b424047002ea72d44','','','','',1215763325,0,0,1,0,'de','de_DE','UTC1','standard'),(8,'user3@clansuite.com','user3','e5292e82b58ec55069d178b092ad25ee97f1917d','','G1vmXy','','',1215764325,0,0,1,0,'','',NULL,''),(9,'user4@clansuite.com','user4','90b525e43d877ee890e3cd800584fbddd7cd6668','','eVH0Jx','','',1215768110,0,0,1,0,'','',NULL,''),(10,'user5@clansuite.com','user5','ff4e167734b0cc1c61fb9ca064a18d85045aea80','','AxOD.2','','',1215984499,0,0,1,0,'','',NULL,''),(11,'admin@email.com','admin','3979339f2a534fea635cc6df254eb2a616490653','','CPEr2Z','','',1229294500,0,0,1,0,'','german',NULL,''),(12,'admin@email.com','admin','06dc00bbadde6fee4c3c0e15ba1358bcb6542c64','','z36ZGW','','',1231801464,0,0,1,0,'','english',NULL,''),(13,'admin@email.com','admin','7f0ce5783e1aeb2ae60b697c8311f784f83e0f81','','y7UQ0','','',1258237342,0,0,1,0,'','german',NULL,'');
/*!40000 ALTER TABLE `cs_users` ENABLE KEYS */;

--
-- Table structure for table `cs_whoisonline`
--

DROP TABLE IF EXISTS `cs_whoisonline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_whoisonline` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `time` int(14) NOT NULL DEFAULT '0',
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `userID` int(11) DEFAULT '0',
  `site` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_whoisonline`
--

/*!40000 ALTER TABLE `cs_whoisonline` DISABLE KEYS */;
INSERT INTO `cs_whoisonline` VALUES (72,1273783967,'127.0.0.1',NULL,'/index.php?mod=news&action=show&page=1','13.05.2010');
/*!40000 ALTER TABLE `cs_whoisonline` ENABLE KEYS */;

--
-- Table structure for table `cs_whowasonline`
--

DROP TABLE IF EXISTS `cs_whowasonline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cs_whowasonline` (
  `id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `ip` text COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `site` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cs_whowasonline`
--

/*!40000 ALTER TABLE `cs_whowasonline` DISABLE KEYS */;
/*!40000 ALTER TABLE `cs_whowasonline` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-08-29  0:14:05
