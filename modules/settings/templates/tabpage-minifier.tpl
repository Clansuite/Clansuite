 <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

    {* /---------------------------------------------------
       |
       |     Tab: Settings >> Minifer
       |
       \--------------------------------------------------- *}

    <tr>
        <td class="td_header_small"  colspan="2">
            {t}Minifier{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Enable Minifier{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}Enable Minfier.{/t}</small><br />
            
            <label for="minifier_enabled_1">
                <input id="minifier_enabled_1" type="radio" value="1" name="config[minifer][enabled]" {if isset($config.minifer.enabled) && $config.minifer.enabled == 1}checked="checked"{/if} /> 
                {t}yes{/t}
            </label>
            
            <label for="minifier_enabled_1">
                <input id="minifier_enabled_0" type="radio" value="0" name="config[minifer][enabled]" {if empty($config.minifer.enabled) or $config.minifer.enabled == 0}checked="checked"{/if} /> 
                {t}no{/t}
            </label>
        </td>
    </tr>
</table>