<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>

    {* display cache time as comment *}

    <!-- This Page was cached on {$smarty.now|dateformat}. -->

    {* jQuery *}

    <script type="text/javascript" src="{$www_root_themes_core}/javascript/jquery/jquery.js"></script>
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/jquery/jquery.ui.js"></script>

    {* Clip *}

    <script src="{$www_root_themes_core}/javascript/clip.js" type="text/javascript"></script>

    {* Metatags *}

    <meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="content-language" content="{$meta.language}" />
    <meta http-equiv="reply-to" content="{$meta.email}" />

    <meta name="author" content="{$meta.author}" />
    <meta name="description" content="{$meta.description}" />

    {* Favicon *}

    <link rel="shortcut icon" href="{$www_root_themes_core}/images/clansuite_logos/Clansuite-Favicon-16.ico" />
    <link rel="icon" href="{$www_root_themes_core}/images/clansuite_logos/Clansuite-Favicon-16.ico" type="image/gif" />

    {* Cascading Style Sheets *}

    <link rel="stylesheet" type="text/css" href="{$www_root_themes}/backend/backend.css" />
    <link rel="stylesheet" type="text/css" href="{$www_root_themes}/backend/css/custom/jquery-ui-1.7.2.custom.css" />

    {* Pagetitle *}

    <title>{$pagetitle} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>

</head><body>

<div id="header">
	<div class="inside">

		<div class="logo"><img class="logo" src="{$www_root_themes}/backend/images/logo_backend.gif" width="197px" height="91px" alt="backend_logo" /></div>

	</div>
</div>

<div id="subHeader">
	<div class="inside">

		<div id="taskbar_Header">												<!-- Start Taskbar-Header //-->
			<div class="inside">
				<div class="taskbar_title">Taskbar</div>
            </div>
        </div>																	<!-- Ende Taskbar-Header //-->

        <div id="navmain">														<!-- Start Main-Navigation //-->
			<div class="inside">
				{* Adminmenu Navigation *}
				{include file="menu/templates/adminmenu.tpl"}
            </div>
        </div>																	<!-- Ende Main-Navigation //-->

	</div>
</div>

<div class="floatclearall"></div>												<!-- clear all floatings //-->

<table cellpadding="0" cellspacing="0" id="main">
  <tr >
    <td valign="top" id="main_left">
    	<div class="inside">
			<div id="rss-news">													<!-- Start rss-news //-->
            	<div class="box-head">
                	<img class="leftcorner" src="{$www_root_themes}/backend/images/title_leftcorner.jpg" />
					<div class="box-heading">Aktuelle ClanSuite News</div>
                </div>
                <div class="box-content">
					 {load_module name="rssreader" action="widget_rssreader"}
                </div>
            </div>																<!-- Ende rss-news //-->
			<div id="content">													<!-- Start Content //-->
            	<div class="box-head">
                	<img class="leftcorner" src="{$www_root_themes}/backend/images/title_leftcorner.jpg" />
					{include file='breadcrumbs.tpl'}
                   <!-- <div id="helpbutton">{include file='help_button.tpl'}</div> //-->
                </div>
                <div class="box-content">
					<div class="inside">
						{$content}
                    </div>
                </div>
            </div>																<!-- Ende Content //-->
        </div>
    </td>
    <td valign="top" id="main_right">
  <div class="inside">
            <div id="taskbar">
            	<div class="inside">
                    <div id="taskbar_widget_Aufgaben">
						<div class="box-head">
                        	<img class="leftcorner" src="{$www_root_themes}/backend/images/title_leftcorner.jpg" />
							<div class="box-heading">Aufgaben</div>
    	                </div>
                        <div class="box-content">
							<div class="inside">
								Aufgaben Content
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
    </td>
  </tr>
</table>

<div id="foot">
	<div class="inside">
		{include file='copyright.tpl'}
    </div>
</div>

</body>
</html>
