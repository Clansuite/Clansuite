; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>
;
; Clansuite Configuration File for Module Controlcenter
; \trunk\modules\controlcenter\controlcenter.config.php 
;

;----------------------------------------
; controlcenter
;----------------------------------------
[controlcenter]
show_box_shortcuts = true
show_box_news = true
show_box_security = true
show_box_extensions = true
news_rss_items = 5


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