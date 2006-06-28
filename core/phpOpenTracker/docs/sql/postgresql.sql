/*
 phpOpenTracker - The Website Traffic and Visitor Analysis Solution

 Copyright 2000 - 2005 Sebastian Bergmann. All rights reserved.

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
*/

CREATE TABLE "pot_accesslog" (
  "accesslog_id"   INT8 NOT NULL,
  "timestamp"      INT8 NOT NULL,
  "weekday"        INT4 NOT NULL,
  "hour"           INT4 NOT NULL,
  "document_id"    INT8 NOT NULL,
  "exit_target_id" INT8 DEFAULT 0 NOT NULL,
  "entry_document" boolean NOT NULL
);

CREATE INDEX pot_accesslog_timestamp      on pot_accesslog(timestamp);
CREATE INDEX pot_accesslog_document_id    on pot_accesslog(document_id);
CREATE INDEX pot_accesslog_exit_target_id on pot_accesslog(exit_target_id);

CREATE TABLE "pot_add_data" (
  "accesslog_id" INT8                NOT NULL,
  "data_field"   character varying(32)  NOT NULL,
  "data_value"   character varying(255) NOT NULL
);

CREATE TABLE "pot_documents" (
  "data_id"      INT8                PRIMARY KEY,
  "string"       character varying(255) NOT NULL,
  "document_url" character varying(255) NOT NULL
);

CREATE TABLE "pot_exit_targets" (
  "data_id" INT8                PRIMARY KEY,
  "string"  character varying(255) NOT NULL
);

CREATE TABLE "pot_hostnames" (
  "data_id" INT8                PRIMARY KEY,
  "string"  character varying(255) NOT NULL
);

CREATE TABLE "pot_operating_systems" (
  "data_id" INT8                PRIMARY KEY,
  "string"  character varying(255) NOT NULL
);

CREATE TABLE "pot_referers" (
  "data_id" INT8                PRIMARY KEY,
  "string"  character varying(255) NOT NULL
);

CREATE TABLE "pot_user_agents" (
  "data_id" INT8                PRIMARY KEY,
  "string"  character varying(255) NOT NULL
);

CREATE TABLE "pot_visitors" (
  "accesslog_id"        INT8 PRIMARY KEY,
  "visitor_id"          INT8 NOT NULL,
  "client_id"           INT4 NOT NULL,
  "operating_system_id" INT8 NOT NULL,
  "user_agent_id"       INT8 NOT NULL,
  "host_id"             INT8 NOT NULL,
  "referer_id"          INT8 NOT NULL,
  "timestamp"           INT8 NOT NULL,
  "weekday"             INT4 NOT NULL,
  "hour"                INT4 NOT NULL,
  "returning_visitor"   boolean NOT NULL
);

CREATE INDEX pot_visitors_client_time on pot_visitors(client_id,timestamp);

CREATE OR REPLACE FUNCTION pot_documents_duplicate_check() RETURNS
TRIGGER AS '
BEGIN
  PERFORM 1 FROM pot_documents WHERE data_id=NEW.data_id LIMIT 1;
  IF FOUND THEN
    RETURN null;
  END IF;
  RETURN NEW;
END;
' LANGUAGE 'plpgsql' WITH (iscachable);

CREATE OR REPLACE FUNCTION pot_hostnames_duplicate_check() RETURNS
TRIGGER AS '
BEGIN
  PERFORM 1 FROM pot_hostnames WHERE data_id=NEW.data_id LIMIT 1;
  IF FOUND THEN
    RETURN null;
  END IF;
  RETURN NEW;
END;
' LANGUAGE 'plpgsql' WITH (iscachable);

CREATE OR REPLACE FUNCTION pot_operating_systems_duplicate_check()
RETURNS TRIGGER AS '
BEGIN
  PERFORM 1 FROM pot_operating_systems WHERE data_id=NEW.data_id LIMIT 1;
  IF FOUND THEN
    RETURN null;
  END IF;
  RETURN NEW;
END;
' LANGUAGE 'plpgsql' WITH (iscachable);

CREATE OR REPLACE FUNCTION pot_referers_duplicate_check() RETURNS
TRIGGER AS '
BEGIN
  PERFORM 1 FROM pot_referers WHERE data_id=NEW.data_id LIMIT 1;
  IF FOUND THEN
    RETURN null;
  END IF;
  RETURN NEW;
END;
' LANGUAGE 'plpgsql' WITH (iscachable);

CREATE OR REPLACE FUNCTION pot_user_agents_duplicate_check() RETURNS
TRIGGER AS '
BEGIN
  PERFORM 1 FROM pot_user_agents WHERE data_id=NEW.data_id LIMIT 1;
  IF FOUND THEN
    RETURN null;
  END IF;
  RETURN NEW;
END;
' LANGUAGE 'plpgsql' WITH (iscachable);

create trigger pot_documents_duplicate_check_trig
 BEFORE INSERT ON pot_documents
  for each ROW
   EXECUTE PROCEDURE pot_documents_duplicate_check();

create trigger pot_hostnames_duplicate_check_trig
 BEFORE INSERT ON pot_hostnames
  for each ROW
   EXECUTE PROCEDURE pot_hostnames_duplicate_check();

create trigger pot_operating_systems_duplicate_check_trig
 BEFORE INSERT ON pot_operating_systems
  for each ROW
   EXECUTE PROCEDURE pot_operating_systems_duplicate_check();

create trigger pot_referers_duplicate_check_trig
 BEFORE INSERT ON pot_referers
  for each ROW
   EXECUTE PROCEDURE pot_referers_duplicate_check();

create trigger pot_user_agents_duplicate_check_trig
 BEFORE INSERT ON pot_user_agents
  for each ROW
   EXECUTE PROCEDURE pot_user_agents_duplicate_check();
