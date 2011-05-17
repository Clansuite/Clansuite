<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$meta.language|default:'en'}" lang="{$meta.language|default:'en'}">
<head>

	{* display cache time as comment *}
	<!-- This Page was cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}. -->

	{* Include the Clansuite Header Notice *}
	{include file='clansuite_header_notice.tpl'}

	{* Pagetitle *}
	<title>{$pagetitle} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>

	{* Dublin Core Metatags *}
	<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
	<meta name="DC.Title" content="Clansuite - just an eSport CMS" />
	<meta name="DC.Creator" content="Jens-Andre Koch" />
	<meta name="DC.Date" content="20080101" />
	<meta name="DC.Identifier" content="http://www.clansuite.com/" />
	<meta name="DC.Subject" content="Subject" />
	<meta name="DC.Subject.Keyword " content="Subject.Keyword" />
	<meta name="DC.Description" content="Description" />
	<meta name="DC.Publisher" content="Publisher" />
	<meta name="DC.Coverage" content="Coverage" />

	{* Metatags *}
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="content-language" content="{$meta.language}" />
	<meta name="author" content="{$meta.author}" />
	<meta http-equiv="reply-to" content="{$meta.email}" />
	<meta name="description" content="{$meta.description}" />
	<meta name="keywords" content="{$meta.keywords}" />
	<meta name="generator" content="Clansuite - just an eSports CMS. Version {$clansuite_version}" />

	{* Favicon *}
	<link rel="shortcut icon" href="{$www_root_themes_core}images/clansuite_logos/Clansuite-Favicon-16.ico" />
	<link rel="icon" href="{$www_root_themes_core}images/clansuite_logos/Clansuite-Favicon-16.ico" type="image/gif" />

	{* Clip *}
	<script src="{$www_root_themes_core}javascript/clip.js" type="text/javascript" charset="utf-8"></script>

	{* Cascading Style Sheets *}
	<link rel="stylesheet" type="text/css" href="{$css}" />

	<!-- jQuery -->
	<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.js" charset="utf-8"></script>

</head>
<body>

{* ------------ Begin: csToolbar ------------ *}
<div id="csToolbar">
	<div class="widget" id="widget_toolbar">{load_module name="toolbox" action="widget_toolbar"}</div>
</div>
{* ------------  End: csToolbar ------------ *}

<div id="csPagepane">
	<div id="csPageaera">

		<a name="top"></a>

		{* ------------ Begin: csHeader ------------ *}
		<div id="csHeader">
			<div id="multiSearchForm">{load_module name="search" action="widget_multisearch"}</div>
			<img alt="Clansuite Header" src="{$www_root_theme}images/logo/clansuite_light.png" width="313" height="77" />
		</div>
		{* ------------  End: csHeader ------------ *}


		{* ------------ Begin: csTopNav ------------ *}
		<div id="csTopNav">
			{include file='breadcrumbs.tpl'}
		</div>
		{* ------------  End: csTopNav ------------ *}


		{* ------------ Begin: csMain ------------ *}
		<div id="csMain">

			{* Left Widget Bar *}
			<div id="csCol1">
				<div id="csCol1_content" class="clearfix">
					<div class="widget-bar left">
						<div id="widget_menu">{load_module name="menu" action="widget_menu"}</div>
						{* <div id="widget_latestnews">{load_module name="news" action="widget_latestnews"}</div> *}
						<div id="widget_cssbuilder">{load_module name="index" action="widget_toolbox"}</div>
					</div>
				</div>
			</div>

			{* Right Widget Bar *}
			<div id="csCol2">
				<div id="csCol2_content" class="clearfix">
					<div class="widget-bar right">
						<div id="widget_login">{load_module name="account" action="widget_login"}</div>
						<div id="widget_ts3viewer">{load_module name="teamspeakviewer" action="widget_ts3viewer"}</div>
						<div id="widget_about">{load_module name="index" action="widget_about"}</div>
						<div align="right">{addtoany}</div>
					</div>
				</div>
			</div>

			{* Main Content *}
			<div id="csCol3">
				<div id="csCol3_content" class="clearfix">
					{$content}
				</div>
				<div id="ie_clearing">&nbsp;</div>
			</div>

		</div>
		{* ------------ End: csMain ------------ *}


		{* ------------ Begin: csFooter ------------ *}
		<div id="csFooter">
			<div class="gridblock">
				<div class="grid20l" id="widget_quotes">
					<div class="gridcontent">&nbsp;</div>
				</div>

				<div class="grid20l " id="widget_lastregistered">
					<div class="gridcontent">
						{load_module name="users" action="widget_lastregisteredusers"}
					</div>
				</div>

				<div class="grid20l" id="widget_randomuser">
					<div class="gridcontent">
						{load_module name="users" action="widget_randomuser"}
					</div>
				</div>

				<div class="grid20l" id="widget_usersonline">
					<div class="gridcontent">
						{load_module name="users" action="widget_usersonline"}
					</div>
				</div>

				<div class="grid20r" id="widget_stats">
					<div class="gridcontent">
						{* {load_module name="statistics" action="widget_statistics"} *}
					</div>
				</div>

			</div>
		</div>
		{* ------------ End: csFooter ------------ *}


	</div>{* End: csPageaera *}
</div>{* End: csPagepane *}


{include file='copyright.tpl'}

{* Display Smarty Debug Console *}
{* {if $smarty.const.DEBUG == true}{debug}{/if} *}

</body>
</html>