<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="td_header" width="100%" colspan="3">
        Overview of Static Pages
        </td>

    </tr>
    <tr>

        <td class="td_header_small" width="20%">
        Title
        </td>

        <td class="td_header_small" width="60%">
        Information
        </td>

        <td class="td_header_small" width="20%" align="center">
        Edit
        </td>
    </tr>
{foreach key=key item=item from=$info}
<form action="index.php?mod=static&sub=admin&action=edit" method="post">
    <tr>
        <td class="cell1" align="center">
            <input type="hidden" name="id" value="{$item.id}">
            <b><a href="index.php?mod=staticpages&page={$item.title}" target="_blank">{$item.title}</a></b>
        </td>
        <td class="cell2">
            {$item.description}
        </td>
        <td class="cell1" align="center">
            <input class="ButtonOrange" type="submit" name="submit_id" value="{translate}Edit{/translate}" />

        </td>
    </tr>
</form>
{/foreach}
</table>