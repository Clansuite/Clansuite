{doc_info DOCTYPE=XHTML LEVEL=Strict}
{* everything in doc_raw is moved "as is" to header *}
{doc_raw}
<meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="{$meta.language}">
<meta name="author" content="{$meta.author}">
<meta http-equiv="reply-to" content="{$meta.email}">
<meta name="description" content="{$meta.description}">
<meta name="keywords" content="{$meta.keywords}">

<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/admin.css" />

{$additional_head}
{$redirect}
<title>{$std_page_title} - {$mod_page_title}</title>

<!--
page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}
-->
{/doc_raw}


	
	<!-- Main-Container //-->
			
	<!-- start: Logo - Kopfzeile //-->
	<div id="header"> 
    	<strong>Clansuite - Control Center</strong>
    	<span>{$smarty.now|date_format:"%A - %e %B %Y | %H:%M"}</span>
	</div> 
	<!-- end: Logo - Kopfzeile //-->
	
    {include file="admin/adminmenu/adminmenu.tpl"}

    <br />
    <br />
    <div id="content">
    {$content}
    </div>
    <br />
    <br />
    
</body>
</html>