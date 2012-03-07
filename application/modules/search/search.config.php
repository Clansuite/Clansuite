; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>
; 
; Clansuite Configuration File for Module Search
; \trunk\modules\search\search.config.php 
;

;----------------------------------------
; search
;----------------------------------------
[search]


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
action_show = 'r|a'
action_admin_show = 'r|a'

; widgets
widget_search = 'r|a|m|g'



; DO NOT REMOVE THIS LINE */ ?>