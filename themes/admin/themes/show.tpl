{$info|@var_dump}
{$info}
<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="td_header" width="100%" colspan="3">
        Avaiable Themes 
        </td>    
    </tr>
    <tr>        
        <td class="td_header_small" width="20%">                        Preview      </td>        
        <td class="td_header_small" width="60%">                        Information  </td>
        <td class="td_header_small" width="20%" align="center">         Action       </td>       
    </tr>
{foreach key=key item=item from=$info}
<form action="index.php?mod=controlcenter&sub=themes&action=edit" method="POST">    
    <tr>
        <td class="cell1" align="center">
         <img src="{$www_root}{$item.themename}/preview_thumb.png"> 
         Constructed filename: {$www_root_theme}{$item.themename}/preview_thumb.png
            <input type="hidden" name="id" value="{$item.id}">
            <b><a href="index.php?mod=controlcenter&sub=themes&page={$item.title}" target="_blank">{$item.title}</a></b>
        </td>
        <td class="cell2">
            {$item.description}
        </td>
        <td class="cell1" align="center">
            <input class="ButtonOrange" type="submit" name="submit" value="{t}Set as Default{/t}" />
            <input class="ButtonOrange" type="submit" name="submit" value="{t}Set as Personal{/t}" />
            <input class="ButtonOrange" type="submit" name="submit" value="{t}Edit{/t}" />
        </td>
    </tr>
</form>
{/foreach}
</table>