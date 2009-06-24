<table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

    {* /---------------------------------------------------
       |
       |     Tab: Developers >> Developer Settings
       |
       \--------------------------------------------------- *}

    <tr>
        <td class="td_header_small"  colspan="2">
            {t}Developer Settings{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Help Edit Mode{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input type="radio" value="1" name="config[error][help_edit_mode]" {if isset($config.error.help_edit_mode) && ($config.error.help_edit_mode == 1)}checked="checked"{/if} /> {t}yes{/t}
            <input type="radio" value="0" name="config[error][help_edit_mode]" {if isset($config.error.help_edit_mode) && ($config.error.help_edit_mode == 0)}checked="checked"{/if} /> {t}no{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}GZip Compression{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}When you turn this setting on, your pages are served compressed to your clients - making your site quicker:{/t}</small><br />
            <input type="radio" value="1" name="config[error][compression]" {if isset($config.error.compression) && ($config.error.compression == 1)}checked="checked"{/if} /> {t}yes{/t}
            <input type="radio" value="0" name="config[error][compression]" {if isset($config.error.compression) && ($config.error.compression == 0)}checked="checked"{/if} /> {t}no{/t}
        </td>
    </tr>

    {* /---------------------------------------------------
       |
       |     Tab: Developers >> Error Reporting
       |
       \--------------------------------------------------- *}

    <tr>
        <td class="td_header_small"  colspan="2">
            {t}Error Reporting{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Suppress Errors{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input type="radio" value="1" name="config[error][suppress_errors]" {if isset($config.error.suppress_errors) && ($config.error.suppress_errors == 1)}checked="checked"{/if} /> {t}yes{/t}
            <input type="radio" value="0" name="config[error][suppress_errors]" {if isset($config.error.suppress_errors) && ($config.error.suppress_errors == 0)}checked="checked"{/if} /> {t}no{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Debugging{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input type="radio" value="1" name="config[error][debug]" {if isset($config.error.debug) && ($config.error.debug == 1)}checked="checked"{/if} /> {t}yes{/t}
            <input type="radio" value="0" name="config[error][debug]" {if isset($config.error.debug) && ($config.error.debug == 0)}checked="checked"{/if} /> {t}no{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Enable Webdebug Toolbar{/t}
             <br/>
            ?lang=
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}You may want to turn on the webdebug toolbar. When this is activated a debug toolbar will be displayed in the upper right corner.{/t}</small><br />
            <input type="radio" value="1" name="config[debug][webdebug]" {if isset($config.debug.webdebug) && $config.debug.webdebug == 1}checked="checked"{/if} /> {t}activated{/t}
            <input type="radio" value="0" name="config[debug][webdebug]" {if isset($config.debug.webdebug) && $config.debug.webdebug == 0}checked="checked"{/if} /> {t}deactivated{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Debugging in a popup{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input type="radio" value="1" name="config[error][debug_popup]" {if isset($config.error.debug_popup) && ($config.error.debug_popup == 1)}checked="checked"{/if} /> {t}yes{/t}
            <input type="radio" value="0" name="config[error][debug_popup]" {if isset($config.error.debug_popup) && ($config.error.debug_popup == 0)}checked="checked"{/if} /> {t}no{/t}
        </td>
    </tr>
</table>