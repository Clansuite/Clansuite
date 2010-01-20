<h1>{t}Userlist{/t}</h1>

{* Debugausgabe des Arrays: {$userslist|@var_dump} {html_alt_table loop=$userslist} *}

{pagination}

<table id="userslist" width="100%">
	<thead>
		<tr>
			<th><span>{t}Rank{/t}</span></th>
			<th>{t}Nickname{/t}</th>
			<th>{t}E-Mail{/t}</th>
			<th>ICQ</th>
			<th>{t}Registrationdate{/t}</th>
			<th>{t}PM{/t}</th>
		</tr>
	</thead>
	<tbody>
	
{foreach item=user from=$userslist}

    <tr>
    	<td>
    	
    	{* Debugausgabe des Arrays: {$user.CsUsersGroups|@var_dump} *}
        {foreach item=group from=$user.CsGroups}
        			
        	<span style="color:{$group.color}">
        		{if isset($group.name)} {$group.name} {else} No Group! {/if}
        	</span>
        
        {/foreach}
        
        </td>
        <td>
    		<a href="#profile-link" title="{t}Go to the profile of $user.nick{/t}">{$user.nick}</a>
    	</td>
    	<td>{$user.email}</td>
    	<td>{if isset($user.CsProfiles.icq)}{icq number=$user.CsProfiles.icq title='ICQ'}{else}{/if}</td>
    	<td>{$user.joined}</td>
    	<td><a href="">PM</a></td>
    </tr>
		
{/foreach}

	</tbody>
</table>