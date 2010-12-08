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

    <script src="{$www_root_themes_core}javascript/clip.js" type="text/javascript"></script>

    {* Cascading Style Sheets *}

    <link rel="stylesheet" type="text/css" href="{$css}" />
      <link rel="alternate"  type="application/rss+xml" href="{$www_root}cache/photo.rss" title="" id="gallery" />

</head><body>
<a name="top"></a>

<div id="csPagepane">
	<div id="csPageaera">

			{* Header Table *}
		<div id="csHeader">
			<img alt="Clansuite Header" src="{$www_root_themes_frontend}standard/images/clansuite-header.png" width="760" height="175" />
		</div>

		<!-- Main Table -->
		<div id="csMain">

			<table cellspacing="0" cellpadding="0" width="100%">

			{* Breadcrumb *}
			<tr class="csTopNav">
				<td colspan="3">{include file='breadcrumbs.tpl'}</td>
			</tr>

			<tr>
				<!-- Left Widget Bar -->
				<td id="csTableleft" class="cell1 size9">
					<div class="widget size9" id="widget_menu">{load_module name="menu" action="widget_menu"}</div>
					<div class="widget size9" id="widget_latestnews">{load_module name="news" action="widget_latestnews"}</div>
					<div class="widget size9" id="widget_newscategories_list">{load_module name="news" action="widget_newscategories_list"}</div>
					<div class="widget size9" id="widget_newscategories_dropdown">{load_module name="news" action="widget_newscategories_dropdown"}</div>
					<div class="widget size9" id="widget_newsfeeds">{load_module name="news" action="widget_newsfeeds"}</div>
					<div class="widget size9" id="widget_newsarchive">{load_module name="news" action="widget_archive"}</div>
				</td>

				<!-- Middle + Center = Main Content -->
				<td id="csTablecenter" class="cell1 size10">
					<div id="csCol3_content">
						{$content}
					</div>
				</td>

				<!-- Right Widget Bar -->
				<td id="csTableright" class="cell1 size9">
					<div class="mt5 mb10" align="center">{addtoany}</div>

					{if $smarty.session.user.user_id == 0}
					{* <div class="widget size9" id="widget_usercenter">{load_module name="user" action="widget_usercenter"}</div> *}
					{else}
					<div class="widget size9" id="widget_login">{load_module name="account" action="widget_login"}</div>
					{/if}

					<div class="widget size9" id="widget_ts3viewer">{load_module name="teamspeakviewer" action="widget_ts3viewer"}</div>
					<div class="widget size9" id="widget_about">{load_module name="about" action="widget_about"}</div>
					<div class="widget size9" id="widget_cssbuilder">{load_module name="toolbox" action="widget_cssbuilder"}</div>
				</td>
			</tr>
			</table>
		</div>

		<!-- Footer -->
		<div id="csFooter" class="cell1">
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
						{load_module name="statistics" action="widget_statistics"}
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

{include file='copyright.tpl'}

{* Display Smarty Debug Console *}
{if $smarty.const.DEBUG == true}
{debug}
{/if}

</body>
</html>