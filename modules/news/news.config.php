; <?php die('Access forbidden.'); /* DO NOT MODIFY THIS LINE! ?>
; 
; Clansuite Configuration File : 
; \trunk\modules\news\news.config.php
; 
; This file was generated on 23-10-2010 21:31
;

;----------------------------------------
; news
;----------------------------------------
[news]
resultsPerPage_show = 5
items_newswidget = 5
resultsPerPage_fullarchive = 3
resultsPerPage_archive = 3
feed_format = "RSS2.0"
feed_items = 15
resultsPerPage_adminshow = 0
feed_title = "ClanSuite Newsfeed"
feed_description = "ClanSuite | A new fast & flexible CMS for Clans."
feed_image = "/uploads/noimage.png"
feed_imagetitle = "Logo"
feed_imagedescription = "Klick mich"
feed_imageurl = "http://www.clansuite-dev.com"


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