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

{* Debuganzeige, wenn DEBUG = 1 |  {$groups|@var_dump} 
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$groups} {/if}*}

 
<form action="index.php?mod=admin&sub=groups&action=delete" method="POST">
<table cellpadding="0" cellspacing="0" border="0" width="100%">

<tr>
    <td class="td_header" width="50" align="center">{translate}ID{/translate}</td>
    <td class="td_header" width="50" align="center">{translate}Position{/translate}</td>
    <td class="td_header" width="100" align="center">{translate}Icon{/translate}</td>
    <td class="td_header" width="200" align="center">{translate}Image{/translate}</td>
    <td class="td_header" width="200" align="center">{translate}Name{/translate}</td>
    <td class="td_header" width="300" align="center">{translate}Description{/translate}</td>
    <td class="td_header" width="300" align="center">{translate}Members{/translate}</td>
    <td class="td_header" align="center">{translate}Edit{/translate}</td>
    <td class="td_header" align="center">{translate}Delete{/translate}</td>
</tr>

{assign var="td_class" value="cell1"}
{foreach key=key item=group from=$groups}
<tr>
    <input type="hidden" name="ids[]" value="{$group.group_id}" />
    <td class="{$td_class}" align="center" height="40">{$group.group_id}</td>
    <td class="{$td_class}" align="center">{$group.pos}</td>
    <td class="{$td_class}" align="center"><img src="{$www_core_tpl_root}/images/groups/{$group.icon}"></td>
    <td class="{$td_class}" align="center"><img src="{$www_core_tpl_root}/images/groups/{$group.image}"></td>
    <td class="{$td_class}" align="center" style="color: {$group.color}; font-weight: bold;">{$group.name}</td>
    <td class="{$td_class}" align="center">{$group.description}</td>
    <td class="{$td_class}" align="center">
        {foreach name=usersarray key=schluessel item=userswert from=$group.users}
        <a href="index.php?mod=admin&sub=users&action=edit&user_id={$userswert.user_id}">{$userswert.nick}</a>
        {if !$smarty.foreach.usersarray.last},{/if} 
        {/foreach}
    </td>      
    <td class="{$td_class}" align="center"><a class="input_submit" style="position: relative; top: 7px;" href="index.php?mod=admin&sub=groups&action=edit&id={$group.group_id}">Edit</a></td>
    <td class="{$td_class}" align="center"><input type="checkbox" name="delete[]" value="{$group.group_id}"></td>
    
</tr>
{if $td_class=='cell1'}{assign var="td_class" value="cell2"}{else}{assign var="td_class" value="cell1"}{/if}    
{/foreach}

<tr>
<td colspan="8" align="right">
    <input class="Button" type="reset" tabindex="3" />
    <input type="submit" name="submit" class="input_submit" value="Delete the selected groups" />
</td>
</tr>
</table>
</form>