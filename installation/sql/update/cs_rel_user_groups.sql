
--
-- Tabellenstruktur für Tabelle `cs_rel_user_groups`
--

CREATE TABLE `cs_rel_user_groups` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

