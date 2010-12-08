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


; -------------------------------------------------------------------------------
; define here all actions was defined in module and module admin controler
; who necesary permission to access
; -------------------------------------------------------------------------------
;
; ACL action values are:
;    all = all groups has access
;    or
;    root(r) | admin(a) | member(m) | guest(g) | bot(b)
;
;  e.g. acces for: root + admin only
;        show = r|a
;  e.g. acces for: root + admin + member only
;        show = r|a|m
;  e.g. acces for: root + admin + member + guest only
;        show = r|a|m|g
;  e.g. acces for: bots only
;        show = b
;
;----------------------------------------
; properties_acl
;----------------------------------------
[properties_acl]
action_show = 'all'




; DO NOT REMOVE THIS LINE */ ?>