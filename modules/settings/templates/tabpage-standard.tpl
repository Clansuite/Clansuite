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
        <input class="input_text" type="text" value="{$config.template.std_page_title}" name="config[template][std_page_title]" />
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Favicon{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}You may provide an favicon for our website:{/t}</small><br />
        <input class="input_text" type="text" value="{if isset($config.template.favicon)}{$config.template.favicon}{/if}" name="config[template][favicon]" />
        <br /> <strong>todo: Upload and Chooser</strong> <small>{$www_root_themes}/images/</small>
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
        {t}Default Theme{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}Select the default Theme to load, when a guest is visiting your site the first time.{/t}</small><br />
        <input class="input_text" type="text" value="{$config.template.theme}" name="config[template][theme]" />
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
        <input class="input_text" type="text" value="{$config.template.tpl_wrapper_file}" name="config[template][tpl_wrapper_file]" />
    </td>
</tr>
  <tr>
    <td class="cell2" width="15%">
        {t}Standard CSS File{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <input class="input_text" type="text" value="{$config.template.std_css}" name="config[template][std_css]" />
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Standard JS File{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <input class="input_text" type="text" value="{$config.template.std_javascript}" name="config[template][std_javascript]" />
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
        <input class="input_text" type="text" value="{$config.defaults.default_module}" name="config[defaults][default_module]" />
     </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Standard module action{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}Select the default Action to load, when calling "index.php" or "http://yoursite.com".{/t}</small><br />
        <input class="input_text" type="text" value="{$config.defaults.default_action}" name="config[defaults][default_action]" />
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
        <input type="radio" value="1" name="config[switches][themeswitch_via_url]" {if isset($config.switches.themeswitch_via_url) && $config.switches.themeswitch_via_url == 1}checked="checked"{/if} /> {t}activated{/t}
        <input type="radio" value="0" name="config[switches][themeswitch_via_url]" {if isset($config.switches.themeswitch_via_url) && $config.switches.themeswitch_via_url == 0}checked="checked"{/if} /> {t}deactivated{/t}
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
        <input type="radio" value="1" name="config[switches][languageswitch_via_url]" {if isset($config.switches.languageswitch_via_url) && $config.switches.languageswitch_via_url == 1}checked="checked"{/if} /> {t}activated{/t}
        <input type="radio" value="0" name="config[switches][languageswitch_via_url]" {if isset($config.switches.languageswitch_via_url) && $config.switches.languageswitch_via_url == 0}checked="checked"{/if} /> {t}deactivated{/t}
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
        <input type="radio" value="1" name="config[maintenance][maintenance]" {if $config.maintenance.maintenance == 1}checked="checked"{/if} /> {t}activated{/t}
        <input type="radio" value="0" name="config[maintenance][maintenance]" {if $config.maintenance.maintenance == 0}checked="checked"{/if} /> {t}deactivated{/t}
    </td>
</tr>
<tr>
    <td class="cell2" width="15%">
        {t}Reason for maintenance{/t}
    </td>
    <td class="cell1" style="padding: 3px">
        <small>{t}You may provide a short description to your members explaining why your site has been switched off:{/t}</small><br />
        <textarea name="config[maintenance][maintenance_reason]" cols="30" rows="10" class="input_textarea">{$config.maintenance.maintenance_reason}</textarea>
    </td>
</tr>
</table>