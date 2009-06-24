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
            <small>{t}You may provide the format in which dates are displayed. Example: d-m-Y. For an How-To read: http://us2.php.net/manual/en/function.date.php{/t}</small><br />
            <input class="input_text" type="text" value="{if isset($config.locale.dateformat)}{$config.locale.dateformat}{/if}" name="config[locale][dateformat]" />
            Example: {$smarty.now|dateformat}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Default Timezone{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}Select the default timzone:{/t}</small><br />
            {html_options name='config[locale][timezone]' options=$timezones selected=´$config.locale.timezone´ separator='<br />'}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Daylight Savings Time (Summertime){/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}If you enable this, the Daylight Savings Time (or Summertime) corrects the time for the above {t}Default Timezone{/t}. The clock is advanced an hour, so that afternoons have more daylight and mornings have less:{/t}</small><br />
            <input type="radio" value="1" name="config[locale][daylight_saving]" {if isset($config.locale.daylight_saving) && $config.locale.daylight_saving == 1}checked="checked"{/if} /> {t}yes [Summertime = Default Timezone + 1 hour]{/t}
            <input type="radio" value="0" name="config[locale][daylight_saving]" {if isset($config.locale.daylight_saving) && $config.locale.daylight_saving == 0}checked="checked"{/if} /> {t}no [Normal Time = Default Timezone]{/t}
        </td>
    </tr>
</table>