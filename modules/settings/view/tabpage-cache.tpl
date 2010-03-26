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
                   
            <label for="caching_1">
                <input id="caching_1" type="radio" value="1" name="config[cache][caching]" {if isset($config.cache.caching) and $config.cache.caching == 1}checked="checked"{/if} />
                {t}yes{/t}
            </label>
            
            <label for="caching_0">
                <input id="caching_0" type="radio" value="0" name="config[cache][caching]" {if empty($config.cache.caching) or $config.cache.caching == 0}checked="checked"{/if} />
                {t}no{/t}
            </label>         
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Cache Lifetime{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.cache.cache_lifetime) and $config.cache.cache_lifetime == 1}{$config.cache.cache_lifetime}{else}0{/if}" name="config[cache][cache_lifetime]" />&nbsp; seconds
            <br /> <small>{t}Note: This is automatically set to "-1" (deactiviated) if the "Developers Mode" is active.{/t}</small>
        </td>
    </tr>
</table>