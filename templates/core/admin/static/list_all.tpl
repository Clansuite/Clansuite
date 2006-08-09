<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td id="td_header" width="100%" colspan="3">
        All static Pages
        </td>
    
    </tr>
    <tr>
        
        <td id="td_header_small" width="20%">
        Title
        </td>
        
        <td id="td_header_small" width="60%">
        Information
        </td>   

        <td id="td_header_small" width="20%" align="center">
        Edit
        </td>       
    </tr>
{foreach key=key item=item from=$info}
<form action="{$www_root}/index.php?mod=admin&sub=static&action=edit" method="POST">    
    <tr>
        <td id="cell1" align="center">
            <input type="hidden" name="id" value="{$item.id}">
            <b><a href="{$www_root}/index.php?mod=static&page={$item.title}" target="_blank">{$item.title}</a></b>
        </td>
        <td id="cell2">
            {$item.description}
        </td>
        <td id="cell1" align="center">
            <input type="submit" value="{translate}Edit{/translate}" class="input_submit">
        </td>
    </tr>
</form>
{/foreach}
</table>

</form>