{doc_raw}
            {* StyleSheets *}
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/js_color_picker_v2.css" />
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/luna-long.css" />
            <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/fieldset.css" />            
            
            {* JavaScripts *}
            <script type="text/javascript" src="{$www_core_tpl_root}/javascript/tabpane.js"></script>
	        <script type="text/javascript" src="{$www_core_tpl_root}/javascript/color_functions.js"></script>		
	        <script type="text/javascript" src="{$www_core_tpl_root}/javascript/js_color_picker_v2.js"></script>
            <script type="text/javascript" src="{$www_core_tpl_root}/javascript/clip.js"></script>
{/doc_raw}

<h2>Administration of Groups</h2>

<form action="index.php?mod=admin&sub=groups&action=delete" method="POST">
<table cellpadding="0" cellspacing="0" border="0" width="100%">

<tr>
    <td class="td_header" width="50" align="center">{translate}ID{/translate}</td>
    <td class="td_header" width="50" align="center">{translate}Position{/translate}</td>
    <td class="td_header" width="200" align="center">{translate}Name{/translate}</td>
    <td class="td_header" width="300" align="center">{translate}Description{/translate}</td>
    <td class="td_header" width="100" align="center">{translate}Icon{/translate}</td>
    <td class="td_header" width="200" align="center">{translate}Image{/translate}</td>
    <td class="td_header" align="center">{translate}Edit{/translate}</td>
    <td class="td_header" align="center">{translate}Delete{/translate}</td>
</tr>

{foreach key=key item=group from=$groups}
    
<tr class="{cycle values="cell1,cell2"}">
    <input type="hidden" name="ids[]" value="{$group.group_id}" />
    <td align="center" height="30">{$group.group_id}</td>
    <td align="center">{$group.pos}</td>
    <td align="center" style="color: {$group.color}; font-weight: bold;">{$group.name}</td>
    <td align="center">{$group.description}</td>
    <td align="center">{$group.icon}</td>
    <td align="center">{$group.image}</td>
    <td align="center"><a class="input_submit" style="position: relative; top: 7px;" href="index.php?mod=admin&sub=groups&action=edit&id={$group.group_id}">Edit</a></td>
    <td align="center"><input type="checkbox" name="delete[]" value="{$group.group_id}"></td>
    
</tr>
    
{/foreach}

<tr>
<td colspan="8" align="right">
    <input class="Button" type="reset" tabindex="3" />
    <input type="submit" name="submit" class="input_submit" value="Delete the selected groups" />
</td>
</tr>
</table>
</form>