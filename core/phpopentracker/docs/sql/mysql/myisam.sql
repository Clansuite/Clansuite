#
# phpOpenTracker - The Website Traffic and Visitor Analysis Solution
#
# Copyright 2000 - 2005 Sebastian Bergmann. All rights reserved.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#   http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#

DROP TABLE IF EXISTS pot_accesslog;
CREATE TABLE cs_pot_accesslog (
  accesslog_id      INT(11)                NOT NULL,
  timestamp         INT(10)    UNSIGNED    NOT NULL,
  weekday           TINYINT(1) UNSIGNED    NOT NULL,
  `hour`            TINYINT(2) UNSIGNED    NOT NULL,
  document_id       INT(11)                NOT NULL,
  exit_target_id    INT(11)    DEFAULT '0' NOT NULL,
  entry_document    TINYINT(1) UNSIGNED    NOT NULL,

  KEY accesslog_id   (accesslog_id),
  KEY timestamp      (timestamp),
  KEY document_id    (document_id),
  KEY exit_target_id (exit_target_id)
) TYPE=MyISAM DELAY_KEY_WRITE=1 PACK_KEYS=1;

DROP TABLE IF EXISTS pot_add_data;
CREATE TABLE cs_pot_add_data (
  accesslog_id INT(11)      NOT NULL,
  data_field   VARCHAR(32)  NOT NULL,
  data_value   VARCHAR(255) NOT NULL,

  KEY accesslog_id (accesslog_id)
) TYPE=MyISAM DELAY_KEY_WRITE=1;

DROP TABLE IF EXISTS pot_documents;
CREATE TABLE cs_pot_documents (
  data_id      INT(11)      NOT NULL,
  string       VARCHAR(255) NOT NULL,
  document_url VARCHAR(255) NOT NULL,

  PRIMARY KEY (data_id)
) TYPE=MyISAM DELAY_KEY_WRITE=1;

DROP TABLE IF EXISTS pot_exit_targets;
CREATE TABLE cs_pot_exit_targets (
  data_id INT(11)      NOT NULL,
  string  VARCHAR(255) NOT NULL,

  PRIMARY KEY (data_id)
) TYPE=MyISAM DELAY_KEY_WRITE=1;

DROP TABLE IF EXISTS pot_hostnames;
CREATE TABLE cs_pot_hostnames (
  data_id INT(11)      NOT NULL,
  string  VARCHAR(255) NOT NULL,

  PRIMARY KEY (data_id)
) TYPE=MyISAM DELAY_KEY_WRITE=1;

DROP TABLE IF EXISTS pot_operating_systems;
CREATE TABLE cs_pot_operating_systems (
  data_id INT(11)      NOT NULL,
  string  VARCHAR(255) NOT NULL,

  PRIMARY KEY (data_id)
) TYPE=MyISAM DELAY_KEY_WRITE=1;

DROP TABLE IF EXISTS pot_referers;
CREATE TABLE cs_pot_referers (
  data_id INT(11)      NOT NULL,
  string  VARCHAR(255) NOT NULL,

  PRIMARY KEY (data_id)
) TYPE=MyISAM DELAY_KEY_WRITE=1;

DROP TABLE IF EXISTS pot_user_agents;
CREATE TABLE cs_pot_user_agents (
  data_id INT(11)      NOT NULL,
  string  VARCHAR(255) NOT NULL,

  PRIMARY KEY (data_id)
) TYPE=MyISAM DELAY_KEY_WRITE=1;

DROP TABLE IF EXISTS pot_visitors;
CREATE TABLE cs_pot_visitors (
  accesslog_id        INT(11)             NOT NULL,
  visitor_id          INT(11)             NOT NULL,
  client_id           INT(10)    UNSIGNED NOT NULL,
  operating_system_id INT(11)             NOT NULL,
  user_agent_id       INT(11)             NOT NULL,
  host_id             INT(11)             NOT NULL,
  referer_id          INT(11)             NOT NULL,
  timestamp           INT(10)    UNSIGNED NOT NULL,
  weekday             TINYINT(1) UNSIGNED NOT NULL,
  `hour`              TINYINT(2) UNSIGNED NOT NULL,
  returning_visitor   TINYINT(1) UNSIGNED NOT NULL,

  PRIMARY KEY         (accesslog_id),
  KEY client_time     (client_id, timestamp)
) TYPE=MyISAM DELAY_KEY_WRITE=1;
