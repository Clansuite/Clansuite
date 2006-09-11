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

		<a href="index.php?mod=static&page=impressum">Impressum</a>
	</div>
	<h1><center>
                <img src="{$www_tpl_root}/images/clansuite-header.png">
        </center>
    </h1>
</div>

<!-- #menu: Preload -->
<script type="text/javscript">
    var arrow1 = new Image(4, 7);
    arrow1.src = "{$www_tpl_root}/images/arrow1.gif";
    var arrow2 = new Image(4, 7);
    arrow2.src = "{$www_tpl_root}/images/arrow2.gif";
</script>


<!-- #nav: Hauptnavigation -->
<div id="nav">
	<a id="navigation" name="navigation"></a> <!-- Skiplink-Anker: Navigation -->
	
	<table id="menu1" cellspacing="0" cellpadding="0" class="XulMenu">
            <tr >
                <td >
                    
                    <a class="button" href="javascript:void(0)">Public<img class="arrow" src="{$www_tpl_root}/images/arrow1.gif" width="4" height="7" alt="" /></a>
    
                    <div class="section">
                        <a class="item" href="javascript:void(0)"><img class="pic" src="{$www_tpl_root}/images/icons/modules.png" border="0" width="16" height="16">Modules<img class="arrow" src="{$www_tpl_root}/images/arrow1.gif" width="4" height="7" alt="" /></a>
                          <div class="section">
                              <a class="item" href="index.php">Main</a>
                              <a class="item" href="index.php?mod=news"><img class="pic" src="{$www_tpl_root}/images/icons/news.png" border="0" width="16" height="16">News</a>
                              <a class="item" href="index.php?mod=shoutbox"><img class="pic" src="{$www_tpl_root}/images/icons/shoutbox.png" border="0" width="16" height="16">Shoutbox</a>
                              <a class="item" href="index.php?mod=static&page=credits"><img class="pic" src="{$www_tpl_root}/images/icons/news.png" border="0" width="16" height="16">Credits</a>
                          </div>
                      
                   
                        <a class="item" href="index.php?mod=users"><img class="pic" src="{$www_tpl_root}/images/icons/users.png" border="0" width="16" height="16">Users<img class="arrow" src="{$www_tpl_root}/images/arrow1.gif" width="4" height="7" alt="" /></a>
                          <div class="section">
                              <a class="item" href="index.php?mod=account">Login</a>
                              <a class="item" href="index.php?mod=account"><img class="pic" src="{$www_tpl_root}/images/icons/logout.png" border="0" width="16" height="16">Logout</a>
                          </div>
                    </div>
                </td>
                <td>
                    <a class="button" href="index.php?mod=admin">Admin</a>
     
                </td>
                <td width="60%">
                &nbsp;
                </td>
            </tr>
            </table>
	
</div>
<!-- #nav: - Ende -->

<!-- #main: Beginn Inhaltsbereich -->
<div id="main">
<a id="content" name="content"></a> <!-- Skiplink-Anker: Content -->

<!-- #col1: Erste Float-Spalte des Inhaltsbereiches -->
    <div id="col1">
      <div id="col1_content" class="clearfix">

        <h4>#col1</h4>
        <p>Erste Float-Spalte.</p>
        

	  </div>
    </div>
<!-- #col1: - Ende -->

<!-- #col2: zweite Flaot-Spalte des Inhaltsbereiches -->
    <div id="col2">
      <div id="col2_content" class="clearfix">
         
        {$content}

      </div>
    </div>
<!-- #col2: - Ende -->

<!-- #col3: Statische Spalte des Inhaltsbereiches -->
    <div id="col3">
      <div id="col3_content" class="clearfix">
        {mod name="account" func="login"}
		
		{mod name="shoutbox" func="show"}
		     
        <div>
        <h2>{translate}Statistik{/translate}</h2>
            Online: {$stats.online}<br />
            Siteimpressions: {$stats.page_impressions}<br />
            All Impressions: {$stats.all_impressions}<br />
            
	    </div>

      </div>
      <!-- IE Column Clearing -->
         <div id="ie_clearing">&nbsp;</div>
      <!-- Ende: IE Column Clearing -->

    </div>
<!-- #col3: - Ende -->

</div>
<!-- #main: - Ende -->

<!-- #Footer: Beginn Fu޺eile -->
    <div id="footer">
        Footer [Layout based on <a href="http://www.yaml.de/">YAML</a>: 1-2-3]
        <center>
        <span class="copyright">{$copyright} &nbsp; <img style="vertical-align:middle;" src="{$www_tpl_root}/images/clansuite-80x15.png"></span>
        &nbsp; Queries: {$query_counter}  
        </center>  
    </div>
<!-- #Footer: Ende -->

</div>
</div>

<!-- Menu: Init -->
<script type="text/javascript">
    var menu1 = new XulMenu("menu1");
   
    menu1.arrow1 = "{$www_tpl_root}/images/arrow1.gif";
    menu1.arrow2 = "{$www_tpl_root}/images/arrow2.gif";
    menu1.init();
</script>

</body>
</html>
