; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>
;
; Clansuite Configuration File for Module Account
; \trunk\modules\account\account.config.php
;

;----------------------------------------
; account
;----------------------------------------
[account]
captchatype = recaptcha

;----------------------------------------
; login
;----------------------------------------
[login]
login_method = "nick"
remember_me_time = 90
max_login_attempts = 5
login_ban_minutes = 30
min_pass_length = 6
hash_algorithm = "sha1"
salt = "1-3-5-8-4-1-7-2-4-1-4-1"
registration_term = ""

;----------------------------------------
; properties
;----------------------------------------
[properties]
active = true
module_section =
module_id =

; DO NOT REMOVE THIS LINE */ ?>
