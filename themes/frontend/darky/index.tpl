<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>

    {* display cache time as comment *}
    <!-- This Page was cached on {$smarty.now|dateformat}. -->

    {* jQuery *}

    <script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.js"></script>
    <script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.ui.js"></script>

    {* Clip *}

    <script src="{$www_root_themes_core}javascript/clip.js" type="text/javascript"></script>

    {* Dublin Core Metatags *}

    <link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
    <meta name="DC.Title" content="Clansuite - just an eSport CMS" />
    <meta name="DC.Creator" content="Jens-Andre Koch" />
    <meta name="DC.Date" content="20080101" />
    <meta name="DC.Identifier" content="http://www.clansuite.com/" />
    <meta name="DC.Subject" content="Subject" />
    <meta name="DC.Subject.Keyword " content="Subject.Keyword" />
    <meta name="DC.Subject.Keyword" content="Subject.Keyword" />
    <meta name="DC.Description" content="Description" />
    <meta name="DC.Publisher" content="Publisher" />
    <meta name="DC.Coverage" content="Coverage" />
    
    {* Metatags *}

    <meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="content-language" content="{$meta.language}" />
    <meta http-equiv="reply-to" content="{$meta.email}" />

    <meta name="author" content="{$meta.author}" />
    <meta name="description" content="{$meta.description}" />

    {* Favicon *}

    <link rel="shortcut icon" href="{$www_root_themes_core}images/clansuite_logos/Clansuite-Favicon-16.ico" />
    <link rel="icon" href="{$www_root_themes_core}images/clansuite_logos/Clansuite-Favicon-16.ico" type="image/gif" />

    {* Cascading Style Sheets *}

    <link href="{$www_root_themes_core}css/core.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="{$www_root_themes}darky/css/darky.css" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}css/jquery-ui-lightness/jquery-ui-1.7.2.custom.css" />    

    {* Pagetitle *}

    <title>{$pagetitle} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>

</head>

<body>

	<div id="wrapper" class="res_normal">

        
        <div id="header"><img src="{$www_root_themes}darky/images/logo.gif" /></div>
              
        	<div id="left">
				<div class="widgetcontainer">
                	<div class="widgettitle">Moduletitle</div>
                    <div class="widgetcontent">
                		<ul>
                    		<li>left</li>
                    		<li>left</li>
                    		<li>left</li>
                    		<li>left</li>
                    		<li>left</li>
						</ul>
                    </div>
                </div>
				<div class="widgetcontainer" id="widget_latestnews">{load_module name="news" action="widget_latestnews" items="2"}</div>
		        <div class="widgetcontainer" id="widget_newsarchive">{load_module name="news" action="widget_archive"}</div>
		        <div class="widgetcontainer" id="widget_nextmatches">{load_module name="matches" action="widget_nextmatches" items="3"}</div>
        		<div class="widgetcontainer" id="widget_latestmatches">{load_module name="matches" action="widget_latestmatches" items="3"}</div>
		        <div class="widgetcontainer" id="widget_topmatch">{load_module name="matches" action="widget_topmatch"}</div>
            </div>
            
            <div id="right">
			   	{* {if isset($smarty.session.user.user_id) && $smarty.session.user.user_id == 0 &&
	        	isset($smarty.session.user.authed) && $smarty.session.user.authed == 1 } *}
		        <div class="widgetcontainer" id="widget_login">{load_module name="account" action="widget_login"}</div>
			    {*{else}
		        <div class="widgetcontainer" id="widget_usercenter">{load_module name="user" action="widget_usercenter"}</div>
			    {/if} *}
    		    <div class="widgetcontainer" id="widget_tsviewer">{load_module name="teamspeakviewer" action="widget_tsviewer"}</div>
	        	<div class="widgetcontainer" id="widget_tsministatus">{load_module name="teamspeakviewer" action="widget_tsministatus"}</div>
            </div>
            
            <div id="main">
                	<div class="topwidgets">
				        <div class="widgetcontainer threecols left" id="widget_newscategories_list">{load_module name="news" action="widget_newscategories_list"}</div>
				        <div class="widgetcontainer threecols right" id="widget_newscategories_dropdown">{load_module name="news" action="widget_newscategories_dropdown"}</div>
				        <div class="widgetcontainer threecols middle" id="widget_newsfeeds">{load_module name="news" action="widget_newsfeeds"}</div>
                    </div>
					<div id="content">
						{$content}
                	</div>
            </div>
                           
        <div id="bottomwidgets" class="clearfix">
        		<div class="widgetcontainer threecols left" id="widget_quotes">{load_module name="quotes" action="widget_quotes"}</div>
		        <div class="widgetcontainer threecols right" id="widget_stats">{load_module name="statistics" action="widget_statistics"}</div>
                <div class="threecols middle">
			        <div class="widgetcontainer twocols left" id="widget_lastregistered">{load_module name="users" action="widget_lastregisteredusers"}</div>
    	    		<div class="widgetcontainer twocols right" id="widget_randomuser">{load_module name="users" action="widget_randomuser"}</div>
				</div>
         </div>
        
        <div id="foot" class="clearfix">
                <div class="widgetcontainer threecols left">
                	<span class="heading">Block left</span>
                	<ul>
                    	<li>left</li>
                    	<li>left</li>
                    	<li>left</li>
                    	<li>left</li>
                    	<li>left</li>
					</ul>
                </div>

                <div class="widgetcontainer threecols right">
                	<span class="heading">Block right</span>
                	<ul>
                    	<li>right</li>
                    	<li>right</li>
                    	<li>right</li>
                    	<li>right</li>
                    	<li>right</li>
					</ul>
                </div>

                <div class="widgetcontainer threecols middle">
                	<span class="heading">Block middle</span>
                	<ul>
                    	<li>middle</li>
                    	<li>middle</li>
                    	<li>middle</li>
                    	<li>middle</li>
                    	<li>middle</li>
					</ul>
                </div>
            <div class="copyright clearfix">{include file='copyright.tpl'}<br />Theme: {$smarty.session.user.frontend_theme} by {* {$theme_copyright} *}<br />{include file='server_stats.tpl'}</div>
        </div>
        
    </div>

</body>
</html>
