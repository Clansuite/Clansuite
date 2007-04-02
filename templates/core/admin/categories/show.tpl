{* Debuganzeige, wenn DEBUG = 1 |    {$categories|@var_dump}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$categories} {/if} *}

<form action="index.php?mod=admin&amp;sub=categories&amp;action=delete" method="post">
    <table cellpadding="0" cellspacing="0" border="0" width="700" align="center">
      	<tr class="tr_header">
       		<td align="center">{translate}ID{/translate}</td>
       		<td align="center">{translate}Name{/translate}</td>
       		<td align="center">{translate}Pos{/translate}</td>
       		<td align="center">{translate}Modul{/translate}</td>
       		<td align="center">{translate}Icon{/translate}</td>
       		<td align="center">{translate}Image{/translate}</td>
       		<td align="center">{translate}Description{/translate}</td>
       		<td align="center">{translate}Edit{/translate}</td>
       		<td align="center">{translate}Delete{/translate}</td>
       	</tr>
        {foreach key=key item=categories from=$categories}
        <tr class="{cycle values="tr_row1,tr_row2"}">
            <td align="center">
                <input type="hidden" name="ids[]" value="{$categories.cat_id}" />
              	{$categories.cat_id}
            </td>
            <td style="font-weight: bold;" align="center">{$categories.name}</td>
            <td align="center">{$categories.sortorder}</td>
            <td style="color: {$categories.color}; font-weight: bold;" align="center">{$categories.module_name}</td>
            <td align="center">
            {if $categories.icon==''}
                <img src="{$www_root_tpl_core}/images/empty.png" alt="" class="border3d" />
            {else}
                <img src="{$www_root_tpl_core}/images/categories/icons/{$categories.icon}" alt="" class="border3d" />
            {/if}
            </td>
            <td align="center">
            {if $categories.image==''}
                <img src="{$www_root_tpl_core}/images/empty.png" class="border3d" alt="48x48" />
            {else}
                <img src="{$www_root_tpl_core}/images/categories/images/{$categories.image}" class="border3d" alt="48x48" />
            {/if}
            </td>
            <td>{$category.description}</td>
            <td align="center">
                <a href="index.php?mod=admin&amp;sub=categories&amp;action=edit&amp;id={$categories.cat_id}">
                    <input type="button" value="{translate}Edit{/translate}" class="ButtonGreen" />
                </a>
            </td>
            <td align="center"><input type="checkbox" name="delete[]" value="{$categories.cat_id}" /></td>
        </tr>
        {/foreach}
        <tr>
            <td colspan="9" align="right" class="cell1">
                <input class="ButtonGreen" type="button" onclick="location.href='index.php?mod=admin&amp;sub=categories&amp;action=create'" value="{translate}Create a new Category{/translate}" />
                <input class="ButtonGrey" type="reset" name="reset" value="{translate}Reset{/translate}" />
                <input class="ButtonRed" type="submit" name="submit" value="{translate}Delete the selected Category(ies){/translate}" />
            </td>
        </tr>
    </table>
</form>