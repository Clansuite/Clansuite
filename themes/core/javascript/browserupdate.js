/**
 * Browser Update Notification Script
 * Copyright (c) 2007-2009 http://browser-update.org/.
 * MIT Style License, http://browser-update.org//LICENSE.txt
 */
var $browserupdate = {}
$browserupdate.ol = window.onload;
window.onload=function(){
     if ($browserupdate.ol) $browserupdate.ol();
     var e = document.createElement("script");
     e.setAttribute("type", "text/javascript");
     e.setAttribute("src", "http://browser-update.org/update.js");
     document.body.appendChild(e);
}