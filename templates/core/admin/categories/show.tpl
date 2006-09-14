{* Debuganzeige, wenn DEBUG = 1 |    {$categories|@var_dump}
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$categories} {/if} *}

<form action="index.php?mod=admin&sub=categories&action=delete" method="POST">
    
    <table cellpadding="0" cellspacing="0" border="0" width="700" align="center">      
        <thead>
        	<tr class="tr_header">
        		<td align="center">{translate}ID{/translate}</td>
        		<td align="center">{translate}Name{/translate}</td>                
        		<td align="center">{translate}Pos{/translate}</td>
        		<td align="center">{translate}Area{/translate}</td>
        		<td align="center">{translate}Icon{/translate}</td>
        		<td align="center">{translate}Image{/translate}</td>
        		<td align="center">{translate}Description{/translate}</td>
        		<td align="center">{translate}Edit{/translate}</td>
        		<td align="center">{translate}Delete{/translate}</td>
        	</tr>
            
            {foreach key=key item=categories from=$categories}
                <tr class="{cycle values="tr_row1,tr_row2"}">
                   <input type="hidden" name="ids[]" value="{$categories.cat_id}" />
                    <td align="center">{$categories.cat_id}</td>
                    <td style="font-weight: bold;" align="center">{$categories.name}</td>
                    <td align="center">{$categories.sortorder}</td>
                    <td style="color: {$categories.color}; font-weight: bold;" align="center">{$categories.area_name}</td>
                    <td align="center">
                        {if $categories.icon==''}
                            <img src="{$www_core_tpl_root}/images/empty.png" width="16" height="16" class="border3d">
                        {else}
                            <img src="{$www_core_tpl_root}/images/categories/icons/{$categories.icon}" class="border3d">
                        {/if}
                    </td>
                    <td align="center">
                        {if $categories.image==''}
                            <img src="{$www_core_tpl_root}/images/empty.png" width="48" height="48" class="border3d" alt="48x48" title="50x50">
                        {else}
                            <img src="{$www_core_tpl_root}/images/categories/images/{$categories.image}" class="border3d" alt="48x48">
                        {/if}
                    </td>
                    <td>{$category.description}</td>
                    <td align="center"><a href="index.php?mod=admin&sub=categories&action=edit&id={$categories.cat_id}">
                    <input type="button" value="{translate}Edit{/translate}" class="ButtonGreen" /></a></td>
                    <td align="center"><input type="checkbox" name="delete[]" value="{$categories.cat_id}"></td>
                
                </tr>
            {/foreach}
            <tr>
                <td colspan="9" align="right" class="cell1">
                    <input class="ButtonGreen" type="button" onclick="location.href='index.php?mod=admin&sub=categories&action=create'" value="{translate}Create a new Category{/translate}" />
                    <input class="ButtonGrey" type="reset" name="reset" value="{translate}Reset{/translate}"/>
                    <input class="ButtonRed" type="submit" name="submit" value="{translate}Delete the selected Category(ies){/translate}" />
                </td>
            </tr>
    </table>
    
</form>