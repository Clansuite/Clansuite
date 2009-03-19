; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>
;
; Clansuite Configuration File :
; D:\xampplite\htdocs\work\clansuite\trunk\/configuration/clansuite.config.php
;


;----------------------------------------
; database
;----------------------------------------
[database]
db_host = "localhost"
db_type = "mysql"
db_username = "clansuite"
db_password = "toop"
db_name = "clansuite"
db_prefix = "cs_"
db_cache = "APC"

;----------------------------------------
; paths
;----------------------------------------
[paths]
core_folder = "core"
libraries_folder = "libraries"
language_folder = "languages"
themes_folder = "themes"
mod_folder = "modules"
upload_folder = "uploads"
logfiles_folder = "logs"

;----------------------------------------
; email
;----------------------------------------
[email]
from = "system@website.com"

;----------------------------------------
; template
;----------------------------------------
[template]
std_page_title = "Team Clansuite"
theme = "standard"
tpl_wrapper_file = "index.tpl"
std_css = "standard.css"
std_javascript = "standard.js"

;----------------------------------------
; switches
;----------------------------------------
[switches]
themeswitch_via_url = 0
languageswitch_via_url = 0
webdebug = 0

;----------------------------------------
; defaults
;----------------------------------------
[defaults]
default_module = "index"
default_action = "show"

;----------------------------------------
; language
;----------------------------------------
[language]
timezone = "Europe/Berlin"
gmtoffset = 3600
language = "de"
outputcharset = "UTF-8"

;----------------------------------------
; login
;----------------------------------------
[login]
login_method = "nick"
remember_me_time = 90
max_login_attempts = 5
login_ban_minutes = 30
min_pass_length = 6
encryption = "sha1"
salt = "1-3-5-8-4-1-7-2-4-1-4-1"
email_activation = 0

;----------------------------------------
; openid
;----------------------------------------
[openid]
openid_trustroot = "http://www.clansuite.com/openid/"
openid_showloginbox = 1
openid_showcommentsbox = 0

;----------------------------------------
; upload
;----------------------------------------
[upload]
max_upload_filesize = 1048576

;----------------------------------------
; session
;----------------------------------------
[session]
use_cookies = 1
use_cookies_only = 0
session_expire_time = 30
check_ip = 1
check_browser = 1
check_host = 1
maximal_password_tries = 3

;----------------------------------------
; error
;----------------------------------------
[error]
suppress_errors = 0
debug = 1
xdebug = 1
debug_popup = 0

;----------------------------------------
; cache
;----------------------------------------
[cache]
cache = APC
caching = 0
cache_lifetime = 90

;----------------------------------------
; maintenance
;----------------------------------------
[maintenance]
maintenance = 0
maintenance_reason = "SITE is currently undergoing scheduled maintenance.<br />Sorry for the inconvenience. Please try back in 60 minutes."

;----------------------------------------
; meta
;----------------------------------------
[meta]
description = "Clansuite - just an e-sport content management system."
language = "de"
author = "Jens-André Koch, Florian Wolf & Clansuite Development Team"
email = "system@clansuite.com"
keywords = "Clansuite, open-source, eSport, cms, clan,content management system, portal, online gaming"
domain = "clansuite-dev.com"

;----------------------------------------
; webserver
;----------------------------------------
[webserver]
mod_rewrite = 0

;----------------------------------------
; locale
;----------------------------------------
[locale]
locale = 3600

[statistics]
enabled = 1

; DO NOT REMOVE THIS LINE */ ?>