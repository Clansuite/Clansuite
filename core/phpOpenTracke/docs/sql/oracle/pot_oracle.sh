#!/bin/sh
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

# This script is currently experimental, use it only when you're sure
# you know what you're doing. It creates Oracle tablespaces and DB files
# for your phpOpenTracker installation. The distribution of the files in
# the directories depends on their size during production. A preliminary
# suggestion for a distribution is (measured with 35000 PI)
# Execute this script as $ORACLE_OWNER.
#
#   pot_visitors .... 60%
#   pot_accesslog ... 25%
#   pot_hostnames ...  5%
#   pot_user_agents .  5%
#   rest ............  5%
#   Final datasize was 10MB for 35000 PI.
#
# A final suggestion for the tablespace distribution follows, comments
# are welcome.

# Oracle Environment
export ORACLE_HOME=/u01/8.1.6
export ORACLE_SID=POT
export POT_OWNER=pot
export POT_PASSWORD=pot
export PATH=$PATH:$ORACLE_HOME/bin

# Default sizes of tablespaces
export DATA_DEFAULT_SIZE=10M
export INDEX_DEFAULT_SIZE=5M

# Devices for the data
export DIR_DATA_POT_ACCESSLOG=/u04/oradata/POT
export DIR_DATA_POT_HOSTNAMES=/u04/oradata/POT
export DIR_DATA_POT_VISITORS=/u04/oradata/POT
export DIR_DATA_POT_REST=/u04/oradata/POT

# Devices for the indexes (should not be the same as data)
export DIR_INDEX_POT_ACCESSLOG=/u02/oradata/POT
export DIR_INDEX_POT_HOSTNAMES=/u02/oradata/POT
export DIR_INDEX_POT_VISITORS=/u02/oradata/POT

# END SETTINGS

# Tablespace Creation

$ORACLE_HOME/bin/svrmgrl << EOF
connect internal

CREATE TABLESPACE DATA_POT_ACCESSLOG
DATAFILE '$DIR_DATA_POT_ACCESSLOG/pot_accesslog01.dbf' SIZE $DATA_DEFAULT_SIZE REUSE
EXTENT MANAGEMENT LOCAL UNIFORM SIZE 1M;

CREATE TABLESPACE INDEX_POT_ACCESSLOG
DATAFILE '$DIR_INDEX_POT_ACCESSLOG/index_pot_accesslog01.dbf' SIZE $INDEX_DEFAULT_SIZE REUSE
EXTENT MANAGEMENT LOCAL UNIFORM SIZE 1M;

CREATE TABLESPACE DATA_POT_HOSTNAMES
DATAFILE '$DIR_DATA_POT_HOSTNAMES/pot_hostnames01.dbf' SIZE $DATA_DEFAULT_SIZE REUSE
EXTENT MANAGEMENT LOCAL UNIFORM SIZE 1M;

CREATE TABLESPACE DATA_POT_VISITORS
DATAFILE '$DIR_DATA_POT_VISITORS/pot_visitors01.dbf' SIZE $DATA_DEFAULT_SIZE REUSE
EXTENT MANAGEMENT LOCAL UNIFORM SIZE 1M;

CREATE TABLESPACE INDEX_POT_VISITORS
DATAFILE '$DIR_INDEX_POT_VISITORS/index_pot_visitors01.dbf' SIZE $INDEX_DEFAULT_SIZE REUSE
EXTENT MANAGEMENT LOCAL UNIFORM SIZE 1M;

CREATE TABLESPACE DATA_POT_REST
DATAFILE '$DIR_DATA_POT_REST/pot_main01.dbf' SIZE $DATA_DEFAULT_SIZE REUSE
EXTENT MANAGEMENT LOCAL UNIFORM SIZE 1M;

CREATE TABLESPACE DATA_POT_ADD_DATA
DATAFILE '$DIR_DATA_POT_REST/pot_add_data01.dbf' SIZE $DATA_DEFAULT_SIZE REUSE
EXTENT MANAGEMENT LOCAL UNIFORM SIZE 1M;

disconnect
exit


EOF

# Table Creation

$ORACLE_HOME/bin/sqlplus $POT_OWNER/$POT_PASSWORD@$ORACLE_SID << EOF

CREATE TABLE pot_accesslog (
  accesslog_id   number(11) NOT NULL,
  document_id    number(11) NOT NULL,
  timestamp      number(10) NOT NULL,
  weekday        number(1)  NOT NULL,
  hour           number(2)  NOT NULL,
  exit_target_id number(11) DEFAULT '0',
  entry_document number(1)  NOT NULL

) TABLESPACE DATA_POT_ACCESSLOG STORAGE(initial 1M next 1M minextents 1);

create index pot_accesslog_accesslog_id   on pot_accesslog(accesslog_id)   TABLESPACE INDEX_POT_ACCESSLOG;
create index pot_accesslog_timestamp      on pot_accesslog(timestamp)      TABLESPACE INDEX_POT_ACCESSLOG;
create index pot_accesslog_document_id    on pot_accesslog(document_id)    TABLESPACE INDEX_POT_ACCESSLOG;
create index pot_accesslog_exit_target_id on pot_accesslog(exit_target_id) TABLESPACE INDEX_POT_ACCESSLOG;

CREATE TABLE pot_hostnames (
  data_id number(11)    PRIMARY KEY,
  string  varchar2(255) NOT NULL
) TABLESPACE DATA_POT_HOSTNAMES STORAGE(initial 1M next 1M minextents 1);

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

CREATE TABLE pot_documents (
  data_id number(11)    PRIMARY KEY,
  string  varchar2(255) NOT NULL,
  document_url varchar2(255) NOT NULL
) TABLESPACE DATA_POT_REST STORAGE(initial 1M next 1M minextents 1);

CREATE TABLE pot_exit_targets (
  data_id number(11)    PRIMARY KEY,
  string  varchar2(255) NOT NULL
) TABLESPACE DATA_POT_REST STORAGE(initial 1M next 1M minextents 1);

CREATE TABLE pot_operating_systems (
  data_id number(11)    PRIMARY KEY,
  string  varchar2(255) NOT NULL
) TABLESPACE DATA_POT_REST STORAGE(initial 1M next 1M minextents 1);

CREATE TABLE pot_referers (
  data_id number(11)    PRIMARY KEY,
  string  varchar2(255) NOT NULL
) TABLESPACE DATA_POT_REST STORAGE(initial 1M next 1M minextents 1);

CREATE TABLE pot_user_agents (
  data_id number(11)    PRIMARY KEY,
  string  varchar2(255) NOT NULL
) TABLESPACE DATA_POT_REST STORAGE(initial 1M next 1M minextents 1);

CREATE TABLE pot_add_data (
  accesslog_id number(11)    NOT NULL,
  data_field   varchar2(32)  NOT NULL,
  data_value   varchar2(255) NOT NULL
) TABLESPACE DATA_POT_ADD_DATA STORAGE(initial 1M next 1M minextents 1);

commit;

exit

EOF
