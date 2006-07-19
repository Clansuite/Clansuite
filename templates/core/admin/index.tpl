<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="content-language" content="{$language}">
<meta name="author" content="{$author}">
<meta http-equiv="reply-to" content="{$email}">
<meta name="description" content="{$description}">
<meta name="keywords" content="{$keywords}">

<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/admin.css" />

<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/adminmenu/menu.css" />
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/XulMenu.js"></script>
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/ie5.js"></script>

<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/datatable.css" />
<script type="text/javascript" src="{$www_core_tpl_root}/admin/datatable.js"></script>

{$additional_head}
{$redirect}
<title>{$std_page_title} - {$mod_page_title}</title>
  
</head>
<body>
	
	
	<!-- Main-Container //-->
			
	<!-- start: Logo - Kopfzeile //-->
	<div id="header"> 
	<strong>Clansuite - Control Center</strong>
	<span>{$smarty.now|date_format:"%A - %e %B %Y | %H:%M"}</span>
	</div> 
	
	<!-- end: Logo - Kopfzeile //-->
	
    {include file="admin/adminmenu/adminmenu_php.tpl"}
    
    {* abgeschaltet
    {include file="admin/adminmenu/navi.tpl"}
    *}

    <br />
    <br />
    <div id="content">
    {$content}
    </div>
    <br />
    <br />
    
</body>
</html>