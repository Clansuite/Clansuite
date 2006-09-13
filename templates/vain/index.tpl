{doc_info DOCTYPE=XHTML LEVEL=Transitional}
{* everything in doc_raw is moved "as is" to header *}
{doc_raw}
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<title>{$std_page_title} - {$mod_page_title}</title>
<!--
page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}
-->
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<!-- F�ie hier ihre Meta-Daten ein -->

<!-- Clansuite Meta Start -->
<meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />

<meta http-equiv="content-language" content="{$meta.language}" />
<meta name="author" content="{$meta.author}" />
<meta http-equiv="reply-to" content="{$meta.email}" />
<meta name="description" content="{$meta.description}" />
<meta name="keywords" content="{$meta.keywords}" />

<link rel="shortcut icon" href="{$www_tpl_root}/images/favicon.ico" />
<link rel="icon" href="{$www_tpl_root}/images/animated_favicon.gif" type="image/gif" />
<!-- Clansuite Meta End -->

<!-- Clansuite Additionals Start -->
<link rel="stylesheet" type="text/css" href="{$css}" />
<link href="{$www_tpl_root}/css/menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/XulMenu.js"></script>
<script src="{$javascript}" type="text/javascript" language="javascript"></script>
{$additional_head}
{$redirect}
<!-- Clansuite Additionals EnD-->

<link href="{$www_tpl_root}/css/layout_1-2-3.css" rel="stylesheet" type="text/css"/>

<!--[if lte IE 7]>
<link href="{$www_tpl_root}/css/explorer/iehacks_1-2-3.css" rel="stylesheet" type="text/css" />
<![endif]-->
{/doc_raw}
  
<div id="page_margins">
<div id="page" class="hold_floats">

<div id="header">
	<div id="topnav">
	    <!-- Start: Skiplink-Navigation -->
		<a class="skip" href="#navigation" title="Direkt zur Navigation springen">Zur Navigation springen</a>
		<a class="skip" href="#content" title="Direkt zum Inhalt springen">Zum Inhalt springen</a>	
	    <!-- Ende: Skiplink-Navigation -->

		<a href="index.php?mod=static