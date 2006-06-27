--
-- phpOpenTracker - The Website Traffic and Visitor Analysis Solution
--
-- Copyright 2000 - 2005 Sebastian Bergmann. All rights reserved.
--
-- Licensed under the Apache License, Version 2.0 (the "License");
-- you may not use this file except in compliance with the License.
-- You may obtain a copy of the License at
--
--   http://www.apache.org/licenses/LICENSE-2.0
--
-- Unless required by applicable law or agreed to in writing, software
-- distributed under the License is distributed on an "AS IS" BASIS,
-- WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
-- See the License for the specific language governing permissions and
-- limitations under the License.
--

CREATE TABLE pot_accesslog (
  accesslog_id   number(11) NOT NULL,
  document_id    number(11) NOT NULL,
  timestamp      number(10) NOT NULL,
  weekday        number(1)  NOT NULL,
  hour           number(2)  NOT NULL,
  exit_target_id number(11) DEFAULT '0',
  entry_document number(1)  NOT NULL
);

CREATE INDEX pot_accesslog_accesslog_id   ON pot_accesslog(accesslog_id);
CREATE INDEX pot_accesslog_timestamp      ON pot_accesslog(timestamp);
CREATE INDEX pot_accesslog_document_id    ON pot_accesslog(document_id);
CREATE INDEX pot_accesslog_exit_target_id ON pot_accesslog(exit_target_id);

CREATE TABLE pot_add_data (
  accesslog_id number(10)    NOT NULL,
  data_field   varchar2(32)  NOT NULL,
  data_value   varchar2(255) NOT NULL
);

CREATE INDEX pot_add_data_accesslog_id ON pot_add_data(accesslog_id);

CREATE TABLE pot_documents (
  data_id      number(11)    PRIMARY KEY,
  string       varchar2(255) NOT NULL,
  document_url varchar2(255) NOT NULL
);

CREATE TABLE pot_exit_targets (
  data_id number(11)    PRIMARY KEY,
  string  varchar2(255) NOT NULL
);

CREATE TABLE pot_hostnames (
  data_id number(11)    PRIMARY KEY,
  string  varchar2(255) NOT NULL
);

CREATE TABLE pot_operating_systems (
  data_id number(11)    PRIMARY KEY,
  string  varchar2(255) NOT NULL
);

CREATE TABLE pot_referers (
  data_id number(11)    PRIMARY KEY,
  string  varchar2(255) NOT NULL
);

CREATE TABLE pot_user_agents (
  data_id number(11)    PRIMARY KEY,
  string  varchar2(255) NOT NULL
);

CREATE TABLE pot_visitors (
  accesslog_id        number(11) PRIMARY KEY,
  visitor_id          number(11) NOT NULL,
  client_id           number(10) NOT NULL,
  operating_system_id number(11) NOT NULL,
  user_agent_id       number(11) NOT NULL,
  host_id             number(11) NOT NULL,
  referer_id          number(11) NOT NULL,
  timestamp           number(10) NOT NULL,
  weekday             number(1)  NOT NULL,
  hour                number(2)  NOT NULL,
  returning_visitor   number(1)  NOT NULL
);

CREATE INDEX pot_visitors_client_time ON pot_visitors(client_id, timestamp);
