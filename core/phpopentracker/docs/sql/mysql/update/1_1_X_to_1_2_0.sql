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

ALTER TABLE pot_accesslog DROP client_id;
ALTER TABLE pot_accesslog CHANGE entry_document    entry_document    TINYINT(3) UNSIGNED NOT NULL;
ALTER TABLE pot_visitors  CHANGE returning_visitor returning_visitor TINYINT(3) UNSIGNED NOT NULL;

UPDATE pot_accesslog SET entry_document    = entry_document    - 1;
UPDATE pot_visitors  SET returning_visitor = returning_visitor - 1;
