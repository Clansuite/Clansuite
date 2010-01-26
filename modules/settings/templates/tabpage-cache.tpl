<table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

    {* /---------------------------------------------------
       |
       |     Tab: Cache >> Cache Settings
       |
       \--------------------------------------------------- *}

    <tr>
        <td class="td_header_small"  colspan="2">
            {t}Cache Settings{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Cache{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>Specify one of the following Cache Adapter to use: APC, memcached, xcache, eaccelerator, file-based. </small><br/>
            {html_options name='config[cache][adapter]' options=$cache_adapters selected=$config.cache.adapter separator='<br />'}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Cache On{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input type="radio" value="1" name="config[cache][caching]" {if $config.cache.caching == 1}checked="checked"{/if} /> {t}yes{/t}
            <input type="radio" value="0" name="config[cache][caching]" {if $config.cache.caching == 0}checked="checked"{/if} /> {t}no{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Cache Lifetime{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{$config.cache.cache_lifetime}" name="config[cache][cache_lifetime]" />&nbsp; seconds
            <br /> <small>{t}set to -1 if developers mode on{/t}</small>
        </td>
    </tr>
</table>