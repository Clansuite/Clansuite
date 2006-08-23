{doc_info DOCTYPE=XHTML1.1 LEVEL=Normal}
{* everything in doc_raw is moved "as is" to header *}
{doc_raw}
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" />
<head>
<meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="{$meta.language}" />
<meta name="author" content="{$meta.author}" />
<meta http-equiv="reply-to" content="{$meta.email}" />
<meta name="description" content="{$meta.description}" />
<meta name="keywords" content="{$meta.keywords}" />

<link rel="shortcut icon" href="{$www_tpl_root}/images/favicon.ico" />
<link rel="icon" href="{$www_tpl_root}/images/animated_favicon.gif" type="image/gif" />

<link rel="stylesheet" type="text/css" href="{$css}" />
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/XulMenu.js"></script>
<script src="{$javascript}" type="text/javascript" language="javascript"></script>
{$additional_head}
{$redirect}
<title>{$std_page_title} - {$mod_page_title}</title>
<!--
page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}
-->
{/doc_raw}

<!-- BrowserCheck //-->
 <h2 class="oops">{translate}
	You shouldn't be able to read this, because this site uses complex stylesheets to 
	display the information - your browser doesn't support these new standards. However, all 
	is not lost, you can upgrade your browser absolutely free, so please 
	
	UPGRADE NOW to a <a href="http://www.webstandards.org/upgrade/"  
	title="Download a browser that complies with Web standards.">
	standards-compliant browser</a>. If you decide against doing so, then 
	this and other similar sites will be lost to you. Remember...upgrading is free, and it 
	enhances your view of the Web.{/translate}
</h2>

<table cellspacing="0" cellpadding="0" width="100%">
<tr>
    <td height="180">

        <div id="bar">
            <center>
                <img src="{$www_tpl_root}/images/clansuite-header.png">
            </center>
        </div>

    </td>
</tr>
</table>
<script type="text/javscript">
    var arrow1 = new Image(4, 7);
    arrow1.src = "{$www_tpl_root}/images/arrow1.gif";
    var arrow2 = new Image(4, 7);
    arrow2.src = "{$www_tpl_root}/images/arrow2.gif";
</script>

<table cellspacing="0" cellpadding="0" width="100%">
<tr>
    <td id="header" width="10px">
        Menu
    </td>
    
    <td id="header">
        Content
    </td>
    
    <td id="header" width="200px">
        Infos
    </td>
</tr>
<tr>
    <td id="bar" width="10px" height="300">
        <div id="left_menu">
            <table id="menu1" height="300" cellspacing="0" cellpadding="0" class="XulMenu">
            <tr>
                <td>
                    
                    <a class="button" href="javascript:void(0)">Public<img class="arrow" src="{$www_tpl_root}/images/arrow1.gif" width="4" height="7" alt="" /></a>
    
                    <div class="section">
                        <a class="item" href="javascript:void(0)"><img class="pic" src="{$www_tpl_root}/images/icons/modules.png" border="0" width="16" height="16">Modules<img class="arrow" src="{$www_tpl_root}/images/arrow1.gif" width="4" height="7" alt="" /></a>
                          <div class="section">
                              <a class="item" href="index.php">Main</a>
                              <a class="item" href="index.php?mod=news"><img class="pic" src="{$www_tpl_root}/images/icons/news.png" border="0" width="16" height="16">News</a>
                              <a class="item" href="index.php?mod=shoutbox"><img class="pic" src="{$www_tpl_root}/images/icons/shoutbox.png" border="0" width="16" height="16">Shoutbox</a>
                          </div>
                          
                        <a class="item" href="index.php?mod=users"><img class="pic" src="{$www_tpl_root}/images/icons/users.png" border="0" width="16" height="16">Users<img class="arrow" src="{$www_tpl_root}/images/arrow1.gif" width="4" height="7" alt="" /></a>
                          <div class="section">
                              <a class="item" href="index.php?mod=account">Login</a>
                              <a class="item" href="index.php?mod=account"><img class="pic" src="{$www_tpl_root}/images/icons/logout.png" border="0" width="16" height="16">Logout</a>
                          </div>
                    </div>
                    
                    <a class="button" href="index.php?mod=admin">Admin</a>
     
                </td>
            </tr>
            </table>
            
        </div>
    </td>
    
    <td id="bar">
        <div id="content" style="padding: 0">
            {$content}
        </div>
    </td>
    
    <td id="bar" width="200px">
		{mod name="shoutbox" func="show"}
		
        {mod name="account" func="login"}
     
        <div id="right_menu">
            Online: {$stats.online}<br />
            Siteimpressions: {$stats.page_impressions}<br />
            All Impressions: {$stats.all_impressions}<br />
            <img src="{$www_tpl_root}/images/clansuite-80x15.png">
	    </div>
    </td>
</tr>
</table>
<div id="bar" style="padding: 5px" align="center">
    <span class="copyright">
        {$copyright}
    </span>
    &nbsp;Queries: {$query_counter}
</div>
<script type="text/javascript">
    var menu1 = new XulMenu("menu1");
    menu1.type = "vertical";
    menu1.position.level1.top = 0;
    menu1.arrow1 = "{$www_tpl_root}/images/arrow1.gif";
    menu1.arrow2 = "{$www_tpl_root}/images/arrow2.gif";
    menu1.init();
</script>

<br />
<br />

</body>
</html>