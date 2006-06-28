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

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[pot_accesslog]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[pot_accesslog]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[pot_add_data]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[pot_add_data]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[pot_documents]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[pot_documents]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[pot_exit_targets]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[pot_exit_targets]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[pot_hostnames]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[pot_hostnames]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[pot_operating_systems]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[pot_operating_systems]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[pot_referers]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[pot_referers]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[pot_user_agents]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[pot_user_agents]
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[pot_visitors]') and OBJECTPROPERTY(id, N'IsUserTable') = 1)
drop table [dbo].[pot_visitors]
GO

CREATE TABLE pot_accesslog (
  [accesslog_id]   [int] NOT NULL,
  [document_id]    [int] NOT NULL,
  [timestamp]      [int] NOT NULL,
  [weekday]        [int] NOT NULL,
  [hour]           [int] NOT NULL,
  [exit_target_id] [int] DEFAULT '0',
  [entry_document] [int] NOT NULL
)
GO
CREATE INDEX pot_accesslog_accesslog_id ON pot_accesslog(accesslog_id)
GO
CREATE INDEX pot_accesslog_timestamp ON pot_accesslog(timestamp)
GO
CREATE INDEX pot_accesslog_document_id ON pot_accesslog(document_id)
GO
CREATE INDEX pot_accesslog_exit_target_id ON pot_accesslog(exit_target_id)
GO

CREATE TABLE pot_add_data (
  [accesslog_id] [int]          NOT NULL,
  [data_field]   [varchar](32)  NOT NULL,
  [data_value]   [varchar](255) NOT NULL
)
GO

CREATE INDEX pot_add_data_accesslog_id ON pot_add_data(accesslog_id)
GO

CREATE TABLE pot_documents (
  [data_id]      [int]    PRIMARY KEY,
  [string]       [varchar](255) NOT NULL,
  [document_url] [varchar](255) NOT NULL
)
GO

CREATE TABLE pot_exit_targets (
  [data_id] [int]    PRIMARY KEY,
  [string]  [varchar](255) NOT NULL
)
GO

CREATE TABLE pot_hostnames (
  [data_id] [int]    PRIMARY KEY,
  [string]  [varchar](255) NOT NULL
)
GO

CREATE TABLE pot_operating_systems (
  [data_id] [int]    PRIMARY KEY,
  [string]  [varchar](255) NOT NULL
)
GO

CREATE TABLE pot_referers (
  [data_id] [int]    PRIMARY KEY,
  [string]  [varchar](255) NOT NULL
)
GO

CREATE TABLE pot_user_agents (
  [data_id] [int]    PRIMARY KEY,
  [string]  [varchar](255) NOT NULL
)
GO

CREATE TABLE pot_visitors (
  [accesslog_id]        [int] PRIMARY KEY,
  [visitor_id]          [int] NOT NULL,
  [client_id]           [int] NOT NULL,
  [operating_system_id] [int] NOT NULL,
  [user_agent_id]       [int] NOT NULL,
  [host_id]             [int] NOT NULL,
  [referer_id]          [int] NOT NULL,
  [timestamp]           [int] NOT NULL,
  [weekday]             [int] NOT NULL,
  [hour]                [int] NOT NULL,
  [returning_visitor]   [int] NOT NULL
)
GO

CREATE INDEX pot_visitors_client_time   ON pot_visitors(client_id, "timestamp")
GO
