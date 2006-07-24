{doc_info DOCTYPE=XHTML LEVEL=Strict}
{* everything in doc_raw is moved "as is" to header *}
{doc_raw}
<meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="{$language}">
<meta name="author" content="{$author}">
<meta http-equiv="reply-to" content="{$email}">
<meta name="description" content="{$description}">
<meta name="keywords" content="{$keywords}">

<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/admin.css" />

{$additional_head}
{$redirect}
<title>{$std_page_title} - {$mod_page_title}</title>
{/doc_raw}


	
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