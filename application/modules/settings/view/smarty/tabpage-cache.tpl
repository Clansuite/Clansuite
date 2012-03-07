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
            {html_options name='config[cache][driver]' options=$cache_driver selected=$config.cache.driver separator='<br />'}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Cache On{/t}
        </td>
        <td class="cell1" style="padding: 3px">

            <label for="enabled_1">
                <input id="caching_1" type="radio" value="1" name="config[cache][enabled]" {if isset($config.cache.enabled) and $config.cache.enabled == 1}checked="checked"{/if} />
                {t}yes{/t}
            </label>

            <label for="enabled_0">
                <input id="caching_0" type="radio" value="0" name="config[cache][enabled]" {if empty($config.cache.enabled) or $config.cache.enabled == 0}checked="checked"{/if} />
                {t}no{/t}
            </label>
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Cache Lifetime{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.cache.lifetime) and $config.cache.lifetime == 1}{$config.cache.lifetime}{else}0{/if}" name="config[cache][lifetime]" />&nbsp; seconds
            <br /> <small>{t}Note: This is automatically set to "-1" (deactivated) if "Development Mode" active.{/t}</small>
        </td>
    </tr>
</table>