{doc_raw}
	<script type="text/javascript" src="{$www_root}/core/fckeditor/fckeditor.js"></script>
{/doc_raw}

{if $err.fill_form == 1}
    {error title="Fill form"}
        Please fill all fields.
    {/error}
{/if}
<form action="index.php?mod=news&amp;sub=admin&amp;action=create" method="post" target="_self">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="td_header" width="100%" colspan="2">
        {translate}Create news{/translate}    </td>

    </tr>
    <tr>

        <td class="td_header_small" width="40">
        {translate}Title{/translate}    </td>

        <td class="td_header_small" width="99%">
        {translate}Information{/translate}    </td>

    </tr>
    <tr>
        <td class="cell1">
            <strong>{translate}Title{/translate}:</strong>
        </td>
        <td class="cell2">
            <input name="infos[title]" type="text" value="" class="input_text" />
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
                    <option value="{$cats.cat_id}" {if isset($smarty.post.cat_id) && $smarty.post.cat_id == $cats.cat_id} selected='selected'{/if}>{$cats.name|escape:html}</option>
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
            <input type="radio" name="infos[draft]" value="0" checked="checked" />{translate}unpublished{/translate}
            <input type="radio" name="infos[draft]" value="1" />{translate}published{/translate}
        </td>
    </tr>
    <tr>
        <td colspan="2" class="cell3">
        	<script type="text/javascript">

            var sBasePath = "{$www_root}/core/fckeditor/";

            var oFCKeditor = new FCKeditor( 'infos[body]' );
            oFCKeditor.BasePath	= sBasePath;
            oFCKeditor.Height	= 400;
            oFCKeditor.Value	= '';
            oFCKeditor.Create();
        	</script>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="cell2" align="center">
            <input class="ButtonGreen" type="submit" name="submit" value="{translate}Create news{/translate}" />
        </td>
    </tr>
</table>
</form>