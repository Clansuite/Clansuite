 <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

    {* /---------------------------------------------------
       |
       |     Tab: Updates >> Updates
       |
       \--------------------------------------------------- *}

    <tr>
        <td class="td_header_small"  colspan="2">
            {t}Updates{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Check for Updates{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}Clansuite can check with [clansuite.com] whether you are running the latest version. Results will be shown on the admin control panel with links to download the updated versions.{/t}</small><br />
            <small>{t}When you enable this, Clansuite will automagically check for updates to the CMS and Modules?{/t}</small><br />
            <input type="radio" value="1" name="config[cache][caching]" {if $config.cache.caching == 1}checked="checked"{/if} /> {t}yes{/t}
            <input type="radio" value="0" name="config[cache][caching]" {if $config.cache.caching == 0}checked="checked"{/if} /> {t}no{/t}
        </td>
    </tr>
</table>