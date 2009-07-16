<table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

    {* /---------------------------------------------------
       |
       |     Tab: Language >> Default Language / Charset
       |
       \--------------------------------------------------- *}

    <tr>
        <td class="td_header_small"  colspan="2">
            {t}Default Language / Charset{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Standard Language{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}Language Selection is based on browser-detection, but you can specify a default language as fallback.{/t}</small><br />
            <input class="input_text" type="text" value="{if isset($config.language.language)}{$config.language.language}{/if}" name="config[language][language]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Charset{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.language.charset)}{$config.language.charset}{/if}" name="config[language][charset]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Timezone{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.language.timezone)}{$config.language.timezone}{/if}" name="config[language][timezone]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}GMT Offset{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.language.gmtoffset)}{$config.language.gmtoffset}{/if}" name="config[language][gmtoffset]" />
        </td>
    </tr>
</table>