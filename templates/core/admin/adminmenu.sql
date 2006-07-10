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

INSERT INTO `cs_adminmenu` VALUES (4, 0, 'button', 'System', 'null', 'System', '_self');
INSERT INTO `cs_adminmenu` VALUES (3, 0, 'button', 'Categories', 'admin/categories/index.php', 'Categories', '_self');
INSERT INTO `cs_adminmenu` VALUES (6, 0, 'button', 'Hilfe', 'null', 'Hilfe', '_self');
INSERT INTO `cs_adminmenu` VALUES (1, 0, 'button', 'Home', 'index.php', 'Home', '_self');
INSERT INTO `cs_adminmenu` VALUES (2, 0, 'button', 'Module', 'admin/module/index.php', 'Module', '_self');
INSERT INTO `cs_adminmenu` VALUES (7, 6, 'item', 'Hilfe', 'help.php', 'Hilfe', '_self');
INSERT INTO `cs_adminmenu` VALUES (8, 6, 'item', 'Handbuch', 'manual.php', 'Handbuch', '_self');
INSERT INTO `cs_adminmenu` VALUES (5, 0, 'button', 'Users', 'admin/users/index.php', 'Users', '_self');
INSERT INTO `cs_adminmenu` VALUES (9, 6, 'item', 'Report Bug & Give Feedback', 'bugreport.php', 'Report Bug & Give Feedback', '_self');
INSERT INTO `cs_adminmenu` VALUES (10, 6, 'item', 'Über Clansuite', 'about.php', 'Über Clansuite', '_self');
INSERT INTO `cs_adminmenu` VALUES (11, 10, 'item', 'unter Clansuitel', 'test.php', 'unter Clansuite', '_self');
INSERT INTO `cs_adminmenu` VALUES (12, 6, 'item', 'test', 'null', 'test', '_self');
INSERT INTO `cs_adminmenu` VALUES (13, 4, 'item', 'Menüeditor', 'admin/menueditor.php', 'Menüeditor', '_self');
INSERT INTO `cs_adminmenu` VALUES (14, 4, 'item', 'Templateeditor', 'admin/templateeditor.php', 'TemplateEditor', '_self');
