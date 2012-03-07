<table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

    {* /---------------------------------------------------
       |
       |     Tab: Meta Tags >> Define Meta Tags
       |
       \--------------------------------------------------- *}

    <tr>
        <td class="td_header_small"  colspan="2">
            {t}Define Meta Tags{/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Description{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.meta.description)}{strip}{$config.meta.description|strip}{strip}{else}description{/if}" name="config[meta][description]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Language{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.meta.language)}{strip}{$config.meta.language|strip}{strip}{else}language of this website{/if}" name="config[meta][language]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Author{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.meta.author)}{strip}{$config.meta.author|strip}{strip}{else}name of author{/if}" name="config[meta][author]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Mailer{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.meta.email)}{strip}{$config.meta.email|strip}{strip}{else}webmaster@domain.com{/if}" name="config[meta][email]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Keywords{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}Provide some comma separated keywords which describe your website. This will help search engines:{/t}</small><br />
            <input class="input_text" type="text" value="{if isset($config.meta.keywords)}{strip}{$config.meta.keywords|strip}{/strip}{else}Keyword, Keyword{/if}" name="config[meta][keywords]" />
        </td>
    </tr>

    {* /---------------------------------------------------
       |
       |     Tab: Meta Tags >> Dublin Core
       |
       \--------------------------------------------------- *}

    <tr>
        <td class="td_header_small"  colspan="2">
            {t}Define Dublin Core Metadata Elements{/t}
        </td>
    </tr>

    {* /---------------------------------------------------
       |
       |     Tab: Meta Tags >> SEO
       |
       \--------------------------------------------------- *}

     <tr>
        <td class="td_header_small"  colspan="2">
             {t}Search Engine Optimization (SEO){/t}
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Webserver mod_rewrite URL's{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <small>{t}When your Webserver has Mod Rewrite enabled, then you can turn this setting on to make your URLs more user friendly:{/t}</small><br />
            
            <label for="mod_rewrite_1">
                <input type="radio" value="1" name="config[webserver][mod_rewrite]" {if isset($config.webserver.mod_rewrite) and $config.webserver.mod_rewrite == 1}checked="checked"{/if} />
                {t}yes{/t}
            </label>
            
            <label for="mod_rewrite_0">
                <input type="radio" value="0" name="config[webserver][mod_rewrite]" {if empty($config.webserver.mod_rewrite) or  $config.webserver.mod_rewrite == 0}checked="checked"{/if} />
                {t}no{/t}
            </label>
        </td>
    </tr>
</table>