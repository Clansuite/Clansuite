
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------------------------
-- Tabellenstruktur für Tabelle `cs_about`
-- --------------------------------------------------------------------------
CREATE TABLE `cs_about` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `sort` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------------------------
-- Daten für Tabelle `cs_about`
-- --------------------------------------------------------------------------
INSERT INTO `cs_about` VALUES(1, 'Tab Clansuite', '"<font color="red">Clansuite</font> - just an <font color="red">e</font>Sport CMS</font>"<br />\r\nis an open source Content Management System and Framework!<br /><br />\r\nClansuite was initially created by Jens-Andr&#233; Koch<br />\r\nand is licensed under GNU/GPLv2 and any later license.<br /><br />\r\nClansuite is copyright 2005-{$smarty.now|date_format:''%Y''} of Jens-Andr&#233; Koch.<br />\r\nExtensions are copyright of their respective owners.<br /><br />\r\n"Clansuite - just an eSport CMS" ist OSI Certified Open Source Software.<br />\r\nOSI Certified is a certification mark of the <a href="http://www.opensource.org/">Open Source Initiative</a>.', 0);
INSERT INTO `cs_about` VALUES(2, 'Tab Developers', 'Thanks to everyone who tested, reported bugs, made suggestions and contributed to this project. ^ _ ^<br />\r\nSend bugreports, fixes, enhancements, t-shirts, money, beer & pizza to ...', 0);


-- --------------------------------------------------------------------------
-- Tabellenstruktur für Tabelle `cs_about_developer`
-- --------------------------------------------------------------------------
CREATE TABLE `cs_about_developer` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `developer` smallint(1) NOT NULL DEFAULT '2',
  `status` smallint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `nick` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `position` text,
  `ohloh_pic` varchar(255) DEFAULT NULL,
  `alternate` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ohloh_url` varchar(255) DEFAULT NULL,
  `gift_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `gift_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------------------------
-- Daten für Tabelle `cs_about_developer`
-- --------------------------------------------------------------------------

INSERT INTO `cs_about_developer` VALUES(1, 1, 1, 'Jens-André Koch', 'vain', 'vain@clansuite.com', 'Clansuite Project Founder & Maintainer, Benevolent Dictator for Life Developer, Website, Toolbar, Serveradministration, Serverpack, 42.', 'http://www.ohloh.net/accounts/3958/widgets/account_detailed.gif', 'Ohloh profile for', 'http://www.ohloh.net/accounts/3958?ref=Detailed', 'Amazon Wishlist', 'http://www.amazon.de/gp/registry/registry.html?ie=UTF8&type=wishlist&id=2TN4SKVI467SX', 1);
INSERT INTO `cs_about_developer` VALUES(2, 1, 0, 'Florian Wolf', 'xsign.dll', 'xsign.dll@clansuite.com', 'Developer, Serveradminstrator, Javascripts and AJAX Developer of Clansuite Core v0.1, Clansuite_Datagrid, Serverpack-win32-v1.7.3.', 'http://www.ohloh.net/accounts/21946/widgets/account_detailed.gif', 'Ohloh profile for', 'http://www.ohloh.net/accounts/21946?ref=Detailed', NULL, NULL, 2);
INSERT INTO `cs_about_developer` VALUES(3, 1, 0, 'Pasqual Eusterfeldhaus', 'thunderm00n', 'thundermoon@gna.org', 'Graphics, Forum-Support & Moderation, Beta-Testing', 'http://www.ohloh.net/accounts/21968/widgets/account_detailed.gif', 'Ohloh profile for', 'http://www.ohloh.net/accounts/21968?ref=Detailed', NULL, NULL, 3);
INSERT INTO `cs_about_developer` VALUES(4, 1, 0, 'Pascal', 'raensen', 'raensen@gna.org', 'Developer of Statistics Module', 'http://www.ohloh.net/accounts/65133/widgets/account_detailed.gif', 'Ohloh profile for', 'http://www.ohloh.net/accounts/65133?ref=Detailed', NULL, NULL, 4);
INSERT INTO `cs_about_developer` VALUES(5, 2, 0, 'Daniel Winterfeldt', 'rikku', 'rikku@gna.org', 'Developer (Image-Processing-Library)', NULL, NULL, NULL, NULL, NULL, 1);
INSERT INTO `cs_about_developer` VALUES(6, 2, 0, 'Björn Spiegel', 'freq77', NULL, 'Developer (Shoutbox)', NULL, NULL, NULL, NULL, NULL, 2);
INSERT INTO `cs_about_developer` VALUES(7, 2, 0, 'Tino Goratsch', 'Vyper', 'vyper@gna.org', 'Developer, Website, Themes (especially Accessible-Theme)', NULL, NULL, NULL, NULL, NULL, 3);

