<form action="index.php?mod=controlcenter&amp;sub=groups&amp;action=delete" method="post" accept-charset="UTF-8">
    <table cellpadding="0" cellspacing="0" border="0" style="width:700px;margin:0 auto;text-align:center">
      	<tr class="tr_header">
       		<td>{t}ID{/t}</td>
       		<td>{t}Name{/t}</td>
       		<td>{t}Sortorder{/t}</td>
       		<td>{t}Icon{/t}</td>
       		<td>{t}Image{/t}</td>
       		<td>{t}Description{/t}</td>
       		<td>{t}Members{/t}</td>
       		<td>{t}Options{/t}</td>
       		<td>{t}Delete{/t}</td>
       	</tr>
        {foreach key=key item=group from=$groups}
        <tr class="{cycle values="tr_row1,tr_row2"}">
            <td>
                <input type="hidden" name="ids[]" value="{$group.group_id}" />
                {$group.group_id}
            </td>
            <td style="color:{$group.color};font-weight:bold">{$group.name}</td>
            <td>{$group.sortorder}</td>
            <td>
                {if $group.icon==''}
                <img src="{$www_root_themes_core}images/empty.png" alt="" class="border3d" />
                {else}
                <img src="{$www_root_themes_core}images/groups/icons/{$group.icon}" alt="" class="border3d" />
                {/if}
            </td>
            <td>
                {if $group.image==''}
                <img src="{$www_root_themes_core}images/empty.png" alt="" class="border3d" />
                {else}
                <img src="{$www_root_themes_core}images/groups/images/{$group.image}" alt="" class="border3d" />
                {/if}
            </td>
            <td>{$group.description}</td>
            <td>
                {foreach name=usersarray key=schluessel item=userswert from=$group.users}
                <a href="index.php?mod=controlcenter&amp;sub=users&amp;action=edit&amp;user_id={$userswert.user_id}">{$userswert.nick}</a>
                {if !$smarty.foreach.usersarray.last},{/if}
                {/foreach}
            </td>
            <td>
                <input onclick="self.location.href='index.php?mod=controlcenter&amp;sub=groups&amp;action=edit&amp;id={$group.group_id}'" type="button" value="{t}Edit{/t}" class="ButtonGreen" /><br />
                <input onclick="self.location.href='index.php?mod=controlcenter&amp;sub=groups&amp;action=add_members&amp;id={$group.group_id}'" type="button" value="{t}Add Members{/t}" class="ButtonGreen" />
            </td>
            <td>{if $group.group_id gt 3}<input type="checkbox" name="delete[]" value="{$group.group_id}" />{else}Core Group{/if}</td>
        </tr>
        {/foreach}
        <tr>
            <td colspan="9" align="right" class="cell1">
                <input onclick="self.location.href='index.php?mod=controlcenter&amp;sub=groups&amp;action=create'" class="ButtonGreen" type="button" value="{t}Create a new Group{/t}" />
                <input class="ButtonGrey" type="reset" name="reset" value="{t}Reset{/t}" />
                <input class="ButtonRed" type="submit" name="submit" value="{t}Delete the selected groups{/t}" />
            </td>
        </tr>
    </table>
</form>