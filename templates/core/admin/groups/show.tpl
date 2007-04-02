<form action="index.php?mod=admin&amp;sub=groups&amp;action=delete" method="post">
    <table cellpadding="0" cellspacing="0" border="0" style="width:700px;margin:0 auto;text-align:center">
      	<tr class="tr_header">
       		<td>{translate}ID{/translate}</td>
       		<td>{translate}Name{/translate}</td>
       		<td>{translate}Sortorder{/translate}</td>
       		<td>{translate}Icon{/translate}</td>
       		<td>{translate}Image{/translate}</td>
       		<td>{translate}Description{/translate}</td>
       		<td>{translate}Members{/translate}</td>
       		<td>{translate}Options{/translate}</td>
       		<td>{translate}Delete{/translate}</td>
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
                <img src="{$www_root_tpl_core}/images/empty.png" alt="" class="border3d" />
                {else}
                <img src="{$www_root_tpl_core}/images/groups/icons/{$group.icon}" alt="" class="border3d" />
                {/if}
            </td>
            <td>
                {if $group.image==''}
                <img src="{$www_root_tpl_core}/images/empty.png" alt="" class="border3d" />
                {else}
                <img src="{$www_root_tpl_core}/images/groups/images/{$group.image}" alt="" class="border3d" />
                {/if}
            </td>
            <td>{$group.description}</td>
            <td>
                {foreach name=usersarray key=schluessel item=userswert from=$group.users}
                <a href="index.php?mod=admin&amp;sub=users&amp;action=edit&amp;user_id={$userswert.user_id}">{$userswert.nick}</a>
                {if !$smarty.foreach.usersarray.last},{/if}
                {/foreach}
            </td>
            <td>
                <input onclick="self.location.href='index.php?mod=admin&amp;sub=groups&amp;action=edit&amp;id={$group.group_id}'" type="button" value="{translate}Edit{/translate}" class="ButtonGreen" /><br />
                <input onclick="self.location.href='index.php?mod=admin&amp;sub=groups&amp;action=add_members&amp;id={$group.group_id}'" type="button" value="{translate}Add Members{/translate}" class="ButtonGreen" />
            </td>
            <td><input type="checkbox" name="delete[]" value="{$group.group_id}" /></td>
        </tr>
        {/foreach}
        <tr>
            <td colspan="9" align="right" class="cell1">
                <input onclick="self.location.href='index.php?mod=admin&amp;sub=groups&amp;action=create'" class="ButtonGreen" type="button" value="{translate}Create a new Group{/translate}" />
                <input class="ButtonGrey" type="reset" name="reset" value="{translate}Reset{/translate}" />
                <input class="ButtonRed" type="submit" name="submit" value="{translate}Delete the selected groups{/translate}" />
            </td>
        </tr>
    </table>
</form>