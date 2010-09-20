<table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

{* /---------------------------------------------------
   |
   |     Tab: Standard >> General Settings
   |
   \--------------------------------------------------- *}

<tr>
    <td class="td_header_small"  colspan="2">
        {t}General Settings{/t}
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Website Title{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}Name your website:{/t}</small><br />
        <input class="input_text" type="text" value="{$config.template.pagetitle}" name="config[template][pagetitle]" />
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Clanname{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}Name your clan:{/t}</small><br />
        <input class="input_text" type="text" value="{if isset($config.clan.name)}{$config.clan.name}{/if}" name="config[clan][name]" />
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Clantag{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}Set the tag of your clan:{/t}</small><br />
        <input class="input_text" type="text" value="{if isset($config.clan.tag)}{$config.clan.tag}{/if}" name="config[clan][tag]" />
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Country{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}Set your country:{/t}</small><br />
        <input class="input_text" type="text" value="{$config.language.language}" name="config[language][language]" />
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Favicon{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}You may provide an favicon for our website:{/t}</small><br />
        <input class="input_text" type="text" value="{if isset($config.template.favicon)}{$config.template.favicon}{/if}" name="config[template][favicon]" />
        <br /> <strong>todo: Upload and Chooser</strong> <small>{$www_root_themes}images/</small>
    </td>
</tr>

 {* /---------------------------------------------------
   |
   |     Tab: Standard >> Standard Template with Files
   |
   \--------------------------------------------------- *}

<tr>
    <td class="td_header_small"  colspan="2">
        {t}Standard Template with Files{/t}
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Default Frontend Theme{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}Select the default Theme to load, when a guest is visiting your site the first time.{/t}</small><br />
        <input class="input_text" type="text" value="{$config.template.frontend_theme}" name="config[template][frontend_theme]" />
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Default Backend Theme{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}Select the default Backend-Theme to load. This is defines the wrapper layout for the Control Center..{/t}</small><br />
        <input class="input_text" type="text" value="{$config.template.backend_theme}" name="config[template][backend_theme]" />
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Default Layout Filename{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}Select the default Layout to load.{/t}</small><br />
        <input class="input_text" type="text" value="{$config.template.layout}" name="config[template][layout]" />
    </td>
</tr>
  <tr>
    <td class="cell2" width="15%">
        {t}Standard CSS File{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <input class="input_text" type="text" value="{$config.template.css}" name="config[template][css]" />
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Standard JS File{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <input class="input_text" type="text" value="{$config.template.javascript}" name="config[template][javascript]" />
    </td>
</tr>

{* /---------------------------------------------------
   |
   |     Tab: Standard >> Default Module / Action
   |
   \--------------------------------------------------- *}

<tr>
    <td class="td_header_small"  colspan="2">
        {t}Default Module / Action{/t}
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Standard module{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}Select the default Module to load, when calling "index.php" or "http://yoursite.com".{/t}</small><br />
        <input class="input_text" type="text" value="{$config.defaults.module}" name="config[defaults][module]" />
     </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Standard module action{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}Select the default Action to load, when calling "index.php" or "http://yoursite.com".{/t}</small><br />
        <input class="input_text" type="text" value="{$config.defaults.action}" name="config[defaults][action]" />
    </td>
</tr>

{* /---------------------------------------------------
   |
   |     Tab: Standard >> URL Switches
   |
   \--------------------------------------------------- *}

<tr>
    <td class="td_header_small"  colspan="2">
        {t}Switches via URL Parameters{/t}
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Theme Switching{/t}
        <br/>
        ?theme=
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}You may want to turn on theme-switching via URL. When this is activated and parameter theme is appended to your URL like "?theme=xy", the "xy"-theme it will be used for display.{/t}</small><br />

        <label for="languageswitch_via_url_1">
            <input id="themeswitch_via_url_1" type="radio" value="1" name="config[switches][themeswitch_via_url]" {if isset($config.switches.themeswitch_via_url) && $config.switches.themeswitch_via_url == 1}checked="checked"{/if} />
            {t}activated{/t}
        </label>

        <label for="themeswitch_via_url_0">
            <input id="themeswitch_via_url_0" type="radio" value="0" name="config[switches][themeswitch_via_url]" {if empty($config.switches.themeswitch_via_url) or $config.switches.themeswitch_via_url == 0}checked="checked"{/if} />
            {t}deactivated{/t}
        </label>
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Language Switching{/t}
         <br/>
        ?lang=
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}You may want to turn on language-switching via URL. When this is activated and parameter lang is appended to your URL like "?lang=de", the german language (de_DE) will be used.{/t}</small><br />

        <label for="languageswitch_via_url_1">
            <input id="languageswitch_via_url_1" type="radio" value="1" name="config[switches][languageswitch_via_url]" {if isset($config.switches.languageswitch_via_url) && $config.switches.languageswitch_via_url == 1}checked="checked"{/if} />
            {t}activated{/t}
        </label>

        <label for="languageswitch_via_url_0">
            <input id="languageswitch_via_url_0" type="radio" value="0" name="config[switches][languageswitch_via_url]" {if empty($config.switches.languageswitch_via_url) or $config.switches.languageswitch_via_url == 0}checked="checked"{/if} />
            {t}deactivated{/t}
        </label>

    </td>
</tr>

{* /---------------------------------------------------
   |
   |     Tab: Standard >> Maintenance Mode
   |
   \--------------------------------------------------- *}

<tr>
    <td class="td_header_small"  colspan="2">
        {t}Maintenance Mode{/t}
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Maintenance Mode{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}You may want to turn off your site while performing updates or other types of maintenance.{/t}</small><br />

        <label for="maintenance_1">
            <input id="maintenance_1" type="radio" value="1" name="config[maintenance][maintenance]" {if isset($config.maintenance.maintenance) && $config.maintenance.maintenance == 1}checked="checked"{/if} />
            {t}activated{/t}
        </label>

        <label for="maintenance_0">
            <input id="maintenance_0" type="radio" value="0" name="config[maintenance][maintenance]" {if empty($config.maintenance.maintenance) or $config.maintenance.maintenance == 0}checked="checked"{/if} />
            {t}deactivated{/t}
        </label>
    </td>
</tr>
</table>