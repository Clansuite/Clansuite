{$chmod_tpl}

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    
    <td id="td_header" width="120px">
    {translate}Title{/translate}
    </td>
    
    <td id="td_header" width="80%">
    {translate}Information{/translate}
    </td>
    
    <td id="td_header" width="5%" align="center">
    {translate}Option{/translate}
    </td>

</tr>
{foreach key=schluessel item=wert from=$content.whitelisted}
<form action="/index.php?mod=admin&sub=modules&action=export" method="POST">
<tr>

    <td id="cell1" align="center">
    <b>{$wert.title}</b><br />
    <img width="100px" height="100px" src="{$www_core_tpl_root}/images/{$wert.image_name}">
    </td>
    
    <td id="cell2">
    <table cellpadding="2" cellspacing="2" border="0">
        <tr><td><b>{translate}Description:{/translate}</b></td><td>{$wert.description}</td></tr>
        <tr><td><b>{translate}Foldername:{/translate}</b></td><td>{$wert.folder_name}</td></tr>
        <tr><td><b>{translate}Classname:{/translate}</b></td><td>{$wert.class_name}</td></tr>
        <tr><td><b>{translate}Filename:{/translate}</b></td><td>{$wert.file_name}</td></tr>
        <tr><td><b>{translate}URL:{/translate}</b></td><td><a href="/index.php?mod={$wert.name}">index.php?mod={$wert.name}</a></td></tr>
    </table>
    </td>
    
    <td id="cell1" align="center">
        <input type="hidden" name="name" value="{$wert.name}">
        <input class="input_submit" type="submit" value="{translate}Export{/translate}" name="submit">
    </td>

</tr>
</form>
{/foreach}
</table>
