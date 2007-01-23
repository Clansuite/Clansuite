{doc_info DOCTYPE=XHTML1.1 LEVEL=Normal}
{* everything in doc_raw is moved "as is" to header *}
{doc_raw}

{* DC Tags Example todo *}
<meta name="DC.Title" content="Clansuite - just an eSport CMS" />
<meta name="DC.Creator" content="Florian Wolf, Jens-Andre Koch" />
<meta name="DC.Date" content="20070101" />
<meta name="DC.Identifier" content="http://www.clansuite.com/" />
<meta name="DC.Subject" content="Subject" />
<meta name="DC.Subject.Keyword " content="Subject.Keyword" />
<meta name="DC.Subject.Keyword" content="Subject.Keyword" />
<meta name="DC.Description" content="Description" />
<meta name="DC.Publisher" content="Publisher" />
<meta name="DC.Coverage" content="Coverage" />

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

<!--[if IE]>
<link rel="stylesheet" href="{$www_core_tpl_root}/css/IEhack.css" type="text/css" />
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/catfish.js">
<![endif]-->

{$additional_head}
{$redirect}
<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
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
    <td height="180" align="center">
                <img src="{$www_tpl_root}/images/clansuite-header.png">
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
<tr class="tr_header">
    <td width="10">
                        Menu
    </td>
    
    <td>
                        {include file='breadcrumbs.tpl'}
    </td>
    
    <td width="200">
                        Infos
    </td>
</tr>

<tr>
    <td class="cell1" width="100" height="300">
        <div class="left_menu">

            <table id="menu1" height="300" cellspacing="0" cellpadding="0" class="XulMenu">
            <tr>
                <td>
                    
                    <a class="button" href="javascript:void(0)">Public<img class="arrow" src="{$www_tpl_root}/images/arrow1.gif" width="4" height="7" alt="" /></a>
    
                    <div class="section">
                        <a class="item" href="javascript:void(0)"><img class="pic" src="{$www_tpl_root}/images/icons/modules.png" border="0" width="16" height="16">Modules<img class="arrow" src="{$www_tpl_root}/images/arrow1.gif" width="4" height="7" alt="" /></a>
                          <div class="section">
                              <a class="item" href="index.php">Main</a>
                              <a class="item" href="index.php?mod=news"><img class="pic" src="{$www_tpl_root}/images/icons/news.png" border="0" width="16" height="16">News</a>
                              <a class="item" href="index.php?mod=news&action=archiv"><img class="pic" src="{$www_tpl_root}/images/icons/news.png" border="0" width="16" height="16">Newsarchiv</a>
                              <a class="item" href="index.php?mod=serverlist"><img class="pic" src="{$www_tpl_root}/images/icons/serverlist.png" border="0" width="16" height="16">Serverlist</a>
                              <a class="item" href="index.php?mod=static&page=credits"><img class="pic" src="{$www_tpl_root}/images/icons/news.png" border="0" width="16" height="16">Credits</a>
                              <a class="item" href="index.php?mod=static&action=overview"><img class="pic" src="{$www_tpl_root}/images/icons/news.png" border="0" width="16" height="16">Static Pages Overview</a>
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
    
    <td class="cell1">
                        {$content}
    </td>
    
    <td class="cell1" style="padding: 0px;">
        <div style="margin-top: 10px">
            {mod name="account" func="login"}
        </div>
        <div style="margin-top: 10px">
		    {mod name="shoutbox" func="show"}
		</div>
        <div style="margin-top: 10px">
            <table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
                <tr>
                    <td class="td_header" colspan="2">{translate}Statistics{/translate}</td>
                </tr>
                <tr> {* {$stats|@var_dump} *}
                    <td class="cell1">Online: {$stats.online} <br/>
                                      - Users : {$stats.authed_users}
                                      - Guests : {$stats.guest_users} <br/> 
                                      Today: {$stats.today_impressions} <br/>
                                      Yesterday: {$stats.yesterday_impressions} <br/>
                                      Month: {$stats.month_impressions} <br/>
                                      <hr>
                                      This Page: {$stats.page_impressions} <br/>
                                      Total Impressions: {$stats.all_impressions} <br/>
                </tr>
          </table>
        </div>
    </td>
</tr>
</table>
<div class="cell1" style="padding: 5px" align="center">
    <span class="copyright">
        {$copyright}
        &nbsp;Queries: {$query_counter}</span><br />
        <img src="{$www_tpl_root}/images/clansuite-80x15.png">
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

{* Ajax Notification *}
<div id="notification" style="display: none;">
    <img src="{$www_core_tpl_root}/images/ajax/2.gif" align="absmiddle" />
    &nbsp; Wait - while processing your request...
</div>

</body>
</html>