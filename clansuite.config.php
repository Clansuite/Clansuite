;<?php die( ' Access forbidden!' ); /* DO NOT MODIFY THIS LINE!
;
; Clansuite - just an eSports CMS
; Jens-Andre Koch � 2005 - onwards
; Florian Wolf �
; http://www.clansuite.com/
;
; LICENSE:
;
;    This program is free software; you can redistribute it and/or modify
;    it under the terms of the GNU General Public License as published by
;    the Free Software Foundation; either version 2 of the License, or
;    (at your option) any later version.
;
;    This program is distributed in the hope that it will be useful,
;    but WITHOUT ANY WARRANTY; without even the implied warranty of
;    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
;    GNU General Public License for more details.
;
;    You should have received a copy of the GNU General Public License
;    along with this program; if not, write to the Free Software
;    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
;
; @license    GNU/GPL, see COPYING.txt
;
; @author     Florian Wolf <xsign.dll@clansuite.com>
; @author     Jens-Andre Koch <vain@clansuite.com>
; @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005 - onwards), Florian Wolf
;
; @link       http://www.clansuite.com
; @link       http://gna.org/projects/clansuite
; @since      File available since Release 0.2
;
; @version    SVN: $Id: index.php 2005 2008-05-06 15:49:18Z xsign $
;

;----------------------------------------
; Database Settings
;----------------------------------------
[database]
db_type      = "mysql"
db_username  = "clansuite"
db_password  = "toop"
db_name      = "clansuite"
db_host      = "localhost"
db_prefix    = "cs_"

;----------------------------------------
; Standard Path Configuration
;----------------------------------------
[paths]
core_folder        = "core"
libraries_folder   = "libraries"
language_folder    = "languages"
themes_folder      = "themes"
mod_folder         = "modules"
upload_folder      = "uploads"

;----------------------------------------
; SwiftMail configuration
; methods: smtp, sendmail, exim, mail
; if no port is given: ports 25 & 465 are used
; encryption types: SWIFT_OPEN (no) / SWIFT_SSL (SSL) / SWIFT_TLS (TLS/SSL)
;----------------------------------------
[email]
mailmethod      = "mail"
mailerhost      = "localhost"
mailerport      = 21
smtp_username   = "clansuite"
smtp_password   = "toop"
mailencryption  = "SWIFT_OPEN"
from            = "system@clansuite.com"
from_name       = "ClanSuite Group"

;----------------------------------------
; Global Template Configurations
;----------------------------------------
[template]
theme               = "drahtgitter"
tpl_wrapper_file    = "index.tpl"
std_page_title      = "clansuite.com"
std_css             = "standard.css"
std_javascript      = "standard.js"

;----------------------------------------
; URL Switches
;----------------------------------------
[switches]
; Activate Prefilterplugin for Themeswitching via GET Parameter ?theme=
themeswitch_via_url = 1
; Activate Prefilterplugin for Languageswitching via GET Parameter ?lang=
languageswitch_via_url = 1

;----------------------------------------
; defaults
;----------------------------------------
[defaults]
; Controller Resolver : Default Module and Default Action
default_module = "index"
default_action = "show"

;----------------------------------------
; Default Language / Locale Setting
;----------------------------------------
[language]
language        = "de"
outputcharset   = "UTF-8"

; Time Zone
; more timezones in Appendix H of PHP Manual -> http:;us2.php.net/manual/en/timezones.php
timezone = "Europe/Berlin"

;----------------------------------------
; Login Configuration & Password Encryption
;----------------------------------------
[login]
; email or nick
login_method        = "nick"
; days
remember_me_time    = 90
; minutes

max_login_attempts  = 5
login_ban_minutes   = 30 

min_pass_length = 6
encryption      = "sha1"
salt            = "1-3-5-8-4-1-7-2-4-1-4-1"

; OpenID
[openid]
openid_trustroot        = "http://www.clansuite.com/openid/"
openid_showcommentsbox  = 1
openid_showloginbox     = 1

;----------------------------------------
; File/Upload configuration
;----------------------------------------
max_upload_filesize = 1048576

;----------------------------------------
; Session configuration
;----------------------------------------
[session]
use_cookies         = 1
use_cookies_only    = 0
session_expire_time = 30

;----------------------------------------
; Error Handling
;----------------------------------------
[error]
suppress_errors = 0
debug           = 1
debug_popup     = 0
help_edit_mode              = 0

[version]
clansuite_version           = 0.2
clansuite_version_state     = "alpha"
clansuite_version_name      = "Trajan"

;----------------------------------------
; Cache
;----------------------------------------
[cache]
caching         = 0
cache_lifetime  = 90

;----------------------------------------
; Maintenance Mode
;----------------------------------------
[maintenance]
maintenance         = 0
maintenance_reason  = "SITE is currently undergoing scheduled maintenance.<br />Sorry for the inconvenience. Please try back in 60 minutes."

;----------------------------------------
; Meta Tag Informations
;----------------------------------------
[meta]
description     = "Clansuite - just an e-sport content management system."
language        = "de"
author          = "Jens-Andre Koch, Florian Wolf &amp; Clansuite Development Team"
email           = "system@clansuite.com"
keywords        = "Clansuite, open-source, eSport, cms, clan,content management system, portal, online gaming"


; DO NOT REMOVE THIS LINE */ ?>