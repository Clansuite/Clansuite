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
{if $smarty.const.DEBUG eq "1"} Debugausgabe des Arrays:   {html_alt_table loop=$groups} {/if} *}
 
<form action="index.php?mod=admin&sub=groups&action=delete" method="POST">

    <table class="admintable" cellpadding="0" cellspacing="0" border="0" rules="all" width="80%">
    
        <caption>Show Groups</caption>
        
        <thead>
        	<tr>
        		<th scope="col">{translate}ID{/translate}</th>
        
        		<th scope="col">{translate}Position{/translate}</th>
        		<th scope="col">{translate}Icon{/translate}</th>
        		<th scope="col">{translate}Image{/translate}</th>
        		<th scope="col">{translate}Name{/translate}</th>
        		<th scope="col">{translate}Description{/translate}</th>
        		<th scope="col">{translate}Members{/translate}</th>		
        		<th scope="col">{translate}Edit{/translate}</th>
        		<th scope="col">{translate}Delete{/translate}</th>
        	</tr>
        </thead>
        
        <tbody>
            {foreach key=key item=group from=$groups}
            
            <tr id={cycle values="nix,cell2"}>
               <input type="hidden" name="ids[]" value="{$group.group_id}" />
                <td height="40">{$group.group_id}</td>
                <td>{$group.pos}</td>
                <td><img src="{$www_core_tpl_root}/images/groups/{$group.icon}"></td>
                <td><img src="{$www_core_tpl_root}/images/groups/{$group.image}"></td>
                <td style="color: {$group.color}; font-weight: bold;">{$group.name}</td>
                <td>{$group.description}</td>
                <td>
                    {foreach name=usersarray key=schluessel item=userswert from=$group.users}
                    <a href="index.php?mod=admin&sub=users&action=edit&user_id={$userswert.user_id}">{$userswert.nick}</a>
                    {if !$smarty.foreach.usersarray.last},{/if} 
                    {/foreach}
                </td>      
                <td align="center"><a class="ButtonOrange" href="index.php?mod=admin&sub=groups&action=edit&id={$group.group_id}">Edit</a></td>
                <td align="center"><input type="checkbox" name="delete[]" value="{$group.group_id}"></td>
            
            </tr>
           
            {/foreach}
        </tbody>
    
        <tfoot>
            <tr>
                <td colspan="9" align="right">
                    <div align="right">
                    <input class="ButtonGreen" type="button" name="xsubmit" id="Submit" onClick="self.location.href='index.php?mod=admin&sub=groups&action=create'"  value="Create new Group" tabindex="2" />
                    <input class="ButtonGrey" type="reset" />
                    <input class="ButtonRed" type="submit" name="submit" value="Delete the selected groups" />
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
</form>