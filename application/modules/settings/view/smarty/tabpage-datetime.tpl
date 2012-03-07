<table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

    {* /---------------------------------------------------
       |
       |     Tab: Date & Time >> Date & Timezone Settings
       |
       \--------------------------------------------------- *}

    <tr>
        <td class="td_header_small"  colspan="2">
            {t}Date & Timezone Settings{/t}
        </td>
    </tr>    
    <tr>
        <td class="cell2" width="15%">
            {t}Dateformat{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}You may provide the format in which dates are displayed.{/t}</small><br />
            <input class="input_text" type="text" value="{if isset($config.locale.dateformat)}{$config.locale.dateformat}{else}%A, %B %e, %Y{/if}" name="config[locale][dateformat]" />
            <br />
            <small>{t}Example: %A, %B %e, %Y{/t} results in {$smarty.now|date_format:"%A, %B %e, %Y"} {if isset($config.locale.dateformat)} {$smarty.now|date_format:$config.locale.dateformat} {/if} <br />
            <a href="http://www.smarty.net/manual/de/language.modifier.date.format.php">Date Format Help</a> </small><br />            
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Default Timezone{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}Select the default timzone:{/t}</small><br />
            {html_options name='config[locale][timezone]' options=$timezones selected=$config.locale.timezone separator='<br />'}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Daylight Savings Time (Summertime){/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}If you enable this, the Daylight Savings Time (or Summertime) corrects the time for the above {t}Default Timezone{/t}. The clock is advanced an hour, so that afternoons have more daylight and mornings have less:{/t}</small><br />
            
            <label for="daylight_saving_1">
                <input id="daylight_saving_1" type="radio" value="1" name="config[locale][daylight_saving]" {if isset($config.locale.daylight_saving) && $config.locale.daylight_saving == 1}checked="checked"{/if} /> 
                {t}yes [Summertime = Default Timezone + 1 hour]{/t}
            </label> 
            
            <label for="daylight_saving_0">
                <input id="daylight_saving_0" type="radio" value="0" name="config[locale][daylight_saving]" {if isset($config.locale.daylight_saving) && $config.locale.daylight_saving == 0}checked="checked"{/if} /> 
                {t}no [Normal Time = Default Timezone]{/t}
            </label>
       </td>
    </tr>
</table>