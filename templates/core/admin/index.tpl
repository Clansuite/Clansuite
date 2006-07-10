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

<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/navi.css" />

<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/example1.css" />
<script type="text/javascript" src="{$www_core_tpl_root}/admin/XulMenu.js"></script>
<script type="text/javascript" src="{$www_core_tpl_root}/admin/ie5.js"></script>

{$additional_head}
{$redirect}
<title>{$std_page_title} - {$mod_page_title}</title>

{literal}

<style type="text/css">
    body { font-family: tahoma, verdana; font-size: 11px; color: #000000; margin: 0; padding: 10px; }
    
    #header { padding: 0.1em 1em; margin-top: 1em 0; 
    border: #ACA899 1px solid; 
    height: 25px; background: #FE9E32; color: #dddddd; }
    #header a:link, #header a:visited { text-decoration: none; color: #ffffff; font-weight: bold; }
    #header a:hover { text-decoration: underline; }
    #header strong { font-size: 13px; }
    
    a:link { text-decoration: none; color: #FE9E32; font-weight: bold; }
    a:visited { text-decoration: none; color: #FE9E32; font-weight: bold; }
    a:hover { text-decoration: underline; color: #FE9E32; font-weight: bold; }
    
    #main { margin: 1em; }
    .block { background: #f2f2f2; padding: 0.1em 1em; border: #eeeeee 1px solid; }
    .light { color: #999999; }
    .small { font-size: 11px; }
    .big { font-size: 13px; }
    
    p { margin: 1em 0; }
    h1 { font-size: 18px; font-weight: bold; color: #777777; margin: 0.5em 0; padding: 0; border-bottom: #777777 1px solid; }
    
    form { margin: 1em 0; }
    form .description { margin: 0; font-size: 10px; }
    label { display: block; font-weight: bold; margin-bottom: 2px; margin-top: 0.5em; color: #333333; }
    .label { font-weight: bold; margin-bottom: 2px; margin-top: 0.5em; color: #333333; }
    .submit { margin-top: 0.5em; }
    .button { cursor: pointer; padding: 0 0.2em; }
    .listing .block1 { background: #F4F4F4; padding: 0.2em 1em; }
    .listing .block2 { background: #ffffff; padding: 0.2em 1em; }
    .listing th { background: #9C9C9C; color: #ffffff; padding: 0.2em 0.4em; }
    
    #bar { background: #ECE9D8; border: 1px solid;
        border-color: #ffffff #ACA899 #ACA899 #ffffff;
        padding-top: 3px; padding-bottom: 3px;
        padding-left: 5px; cursor: default;  }
    
	#user { position: right; right: 20px; top: 0px; background: url('<?php echo WWW_ROOT ?>/admin/images/user.gif') no-repeat 0% 0%; padding-left: 23px; padding-top: 4px; }
	
	#search { position: absolute; top: 0; right: 5px;    }
    
    #search input, #search select { font-family: georgia, tahoma, verdana;
        font-size: 12px;  margin-top: 4px;  }
    
    p { font-family: georgia, tahoma, verdana; font-size: 11px; margin: 2em; }
   
    div.clearer {clear: left; line-height: 0; height: 0; style=clear:both;}
</style>

{/literal}
    
    
</head>
<body>
<body>
	
	
	<!-- Main-Container //-->
			
	<!-- start: Logo - Kopfzeile //-->
	<div id="header"> 
	<strong>Clansuite - Control Center</strong>
	</div> 
	<!-- end: Logo - Kopfzeile //-->
	
{include file="admin/adminmenu_php.tpl"}


<br />
<br />
<br />
<br />
And here goes the Admin Interface
Content: {$content}
<br />
<br />
<br />
<br />
</body>
</html>