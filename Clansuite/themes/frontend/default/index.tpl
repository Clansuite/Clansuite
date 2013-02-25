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
    <meta name="generator" content="Clansuite - just an eSports CMS. Version {$application_version}" />

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
            <img alt="Clansuite Header" src="{$www_root_themes_frontend}default/images/clansuite-header.png" width="760" height="175" />
        </div>

        <!-- Main Table -->
        <div id="csMain">

            <table cellspacing="0" cellpadding="0" width="100%">

            {* Breadcrumb *}
            <tr class="csTopNav">
                <td class="menu_header" colspan="3">{include file='breadcrumbs.tpl'}</td>
            </tr>

            <tr>
                <!-- Left Widget Bar -->
                <td id="csTableleft" class="cell1 size9">
                    <div class="widget size9" id="widget_menu">{load_module name="Menu" action="widgetMenu"}</div>
                    <div class="widget size9" id="widget_latestnews">{load_module name="News" action="widgetLatestNews"}</div>
                    <div class="widget size9" id="widget_newscategories_list">{load_module name="News" action="widgetNewsCategoriesList"}</div>
                    <div class="widget size9" id="widget_newscategories_dropdown">{load_module name="News" action="widgetNewsCategoriesDropdown"}</div>
                    <div class="widget size9" id="widget_newsfeeds">{load_module name="News" action="widgetNewsFeeds"}</div>
                    <div class="widget size9" id="widget_newsarchive">{load_module name="News" action="widgetNewsArchive"}</div>
                </td>

                <!-- Middle + Center = Main Content -->
                <td id="csTablecenter" class="cell1 size10">
                    <div id="csCol3_content">
                        {$content}
                    </div>
                </td>

                <!-- Right Widget Bar -->
                <td id="csTableright" class="cell1 size9">
                    {* <div class="mt5 mb10" align="center">{addtoany}</div> *}

     {* User not 0 (guest) as id and is authed *}
                    {if $smarty.session.user.user_id != 0 and $smarty.session.user.authed == 1}
                    <div class="widget size9" id="widget_usercenter">{load_module name="Users" action="widgetUserCenter"}</div>
                    {else}
                    <div class="widget size9" id="widget_login">{load_module name="Account" action="widgetLogin"}</div>
                    {/if}

                    <div class="widget size9" id="widget_ts3viewer">{load_module name="Teamspeakviewer" action="widgetTeamspeak3Viewer"}</div>
                    <div class="widget size9" id="widget_about">{load_module name="Index" action="widgetAbout"}</div>
                    {if true == {is_authorized name="toolbox.widgetToolbox"}}
                    <div class="widget size9" id="widget_cssbuilder">{load_module name="Index" action="widgetToolbox"}</div>
                    {/if}
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
                        {load_module name="Users" action="widgetLastRegisteredUsers"}
                    </div>
                </div>

                <div class="grid20l" id="widget_randomuser">
                    <div class="gridcontent">
                        {load_module name="Users" action="widgetRandomUser"}
                    </div>
                </div>

                <div class="grid20l" id="widget_usersonline">
                    <div class="gridcontent">
                        {load_module name="Users" action="widgetUsersOnline"}
                    </div>
                </div>

                <div class="grid20r" id="widget_stats">
                    <div class="gridcontent">
                       {* {load_module name="Statistics" action="widgetStatistics"} *}
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
