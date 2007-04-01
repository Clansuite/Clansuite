{if $err.fill_form == 1}
    {error title="Fill form"}
        Please fill all fields.
    {/error}
{/if}

<form action="index.php?mod=news&amp;sub=admin&amp;action=edit" method="post" target="_self">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="td_header" width="100%" height="100%" colspan="2">
        {translate}Create news{/translate}    </td>

    </tr>
    <tr>

        <td class="td_header_small" width="60">
        {translate}Title{/translate}    </td>

        <td class="td_header_small">
        {translate}Information{/translate}    </td>

    </tr>
    <tr>
        <td class="cell1">
            <strong>{translate}Title{/translate}:</strong>
        </td>
        <td class="cell2">
            <input name="infos[title]" type="text" value="{$infos.news_title|escape:html}" class="input_text" />
        </td>
    </tr>
    <tr>
        <td class="cell1">
            <strong>{translate}Category{/translate}:</strong>
        </td>
        <td class="cell2">
            <select name="infos[cat_id]" class="input_text">
                <option value="0">-- {translate}none{/translate} --</option>

                {foreach item=cats from=$newscategories}
                    <option value="{$cats.cat_id}" {if $infos.cat_id == $cats.cat_id} selected='selected'{/if}>{$cats.name|escape:html}</option>
                {/foreach}

            </select>
        </td>
    </tr>
    {*
    <tr>
        <td class="cell1">
            <strong>{translate}Visible to groups{/translate}:</strong>
        </td>
        <td class="cell2">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            {foreach item=item key=key from=$all_groups}
                <tr class="tr_row1">
                    <td width="1%">
                        <input type="checkbox" value="{$item.group_id}" class="input_text" name="infos[groups][]" checked="checked" />
                    </td>
                    <td>
                        <a href="index.php?mod=admin&amp;sub=groups&amp;action=edit&amp;id={$item.group_id}" target="_blank">{$item.name|escape:"html"}</a>
                    </td>
                </tr>
            {/foreach}
            </table>
        </td>
    </tr>
    *}
    <tr>
        <td class="cell1">
            <strong>{translate}Draft{/translate}:</strong>
        </td>
        <td class="cell2">
            <input type="radio" name="infos[draft]" value="0" {if $infos.draft==0}checked="checked"{/if} />{translate}unpublished{/translate}
            <input type="radio" name="infos[draft]" value="1" {if $infos.draft==1}checked="checked"{/if} />{translate}published{/translate}
        </td>
    </tr>
    <tr>
        <td colspan="2" class="cell3">
        	{*
            <script type="text/javascript">

            var sBasePath = "{$www_root}/core/fckeditor/";

            var oFCKeditor = new FCKeditor( 'infos[body]' );
            oFCKeditor.BasePath	= sBasePath;
            oFCKeditor.Height	= 400;
            oFCKeditor.Value	= '';
            oFCKeditor.Create();
        	</script>*}
        	{$fck}
        </td>
    </tr>
    <tr>
        <td colspan="2" class="cell2" align="center">
            <input class="ButtonRed" type="button" onclick="Dialog.okCallback()" value="{translate}Abort{/translate}"/>
            <input class="ButtonGreen" type="submit" name="submit" value="{translate}Edit news{/translate}" />
        </td>
    </tr>
</table>
<input type="hidden" name="id" value="{$infos.news_id}" />
<input type="hidden" name="infos[front]" value="{$front}" />
</form>