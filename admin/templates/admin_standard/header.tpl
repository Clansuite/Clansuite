<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="iso-8859-1" />
    
<meta http-equiv="content-language" content="{$language}">
<meta name="author" content="{$author}">
<meta http-equiv="reply-to" content="{$email}">
<meta name="description" content="{$description}">
<meta name="keywords" content="{$keywords}">
<meta name="creation-date" content="{$creation_date}">
<meta name="revisit-after" content="5 days">
<title>{$std_page_title} - {$mod_page_title}</title>

<link rel="stylesheet" type="text/css" href="{$www_root}/templates/admin_standard/style.css">

<script type="text/javascript" src="{$www_root}/menu/XulMenu.js"></script>
<link rel="stylesheet" type="text/css" href="{$www_root}/menu/menu.css" />

{*
<link rel="stylesheet" type="text/css" href="{$css}">
{$additional_head}
<script src="{$javascript}" type="text/javascript" language="javascript"></script>
*}

{literal}
<style type="text/css">
    body { font-size: 12px; font: tahoma, verdana; color: #000000; margin: 0; padding: 10px; }
    #header { background: #FE9E32; color: #dddddd; }
    #header a:link, #header a:visited { text-decoration: none; color: #ffffff; font-weight: bold; }
    #header a:hover { text-decoration: underline; }
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
	<!-- Main-Container //-->
			
	<!-- start: Logo - Kopfzeile //-->
	<div id="header"> 
	<strong>Clansuite - Control Center</strong>
	</div> 
	<!-- end: Logo - Kopfzeile //-->
	
	
	{* wünschenswert ist später einfach : $menu *}
	
	<!-- start: Menu - Kopfzeile 2 //-->
	{literal}
	<script type="text/javscript">
		/* preload images */
		var arrow1 = new Image(4, 7);
		arrow1.src = "{$www_root}/menu/images/arrow1.gif"
		var arrow2 = new Image(4, 7);
		arrow2.src = "{$www_root}/menu/images/arrow2.gif";
	</script>
	{/literal}
	<div id="bar">
	<table cellspacing="0" cellpadding="0" id="menu1" class="XulMenu">
	<tr>
	{$menu}
	</tr>
	</table>
	{literal}
	<script type="text/javascript">
		var menu1 = new XulMenu("menu1");
		menu1.arrow1 = "{$www_root}/menu/images/arrow1.gif";
		menu1.arrow2 = "{$www_root}/menu/images/arrow2.gif";
		menu1.init();
	</script>
	{/literal}
	</div>
	<!-- end: Menu- Kopfzeile 2 //-->