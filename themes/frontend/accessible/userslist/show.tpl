<h1>{t}Userlist{/t}</h1>
{* Debugausgabe des Arrays: {$userslist|@var_dump} {html_alt_table loop=$userslist} *}
{pagination}
<table id="userslist" class="forum">
	<thead>
		<tr>
			<th class="nick-rank"><span>{t}Rank{/t}</span>{t}Nickname{/t}</th>
			<th>{t}E-Mail{/t}</th>
			<th>{t}Registrationdate{/t}</th>
			<th>{t}PM{/t}</th>
		</tr>
	</thead>
	<tbody>
{foreach item=user from=$userslist}
		<tr>
			<td class="nick-rank">
{foreach item=group from=$user.CsUserGroups}				
				<span style="color:{$group.CsGroup.color}">
					{$group.CsGroup.name}
				</span>
{/foreach}
				<a href="#profile-link" title="{t}Go to the profile of $user.nick{/t}">{$user.nick}</a>
			</td>
			<td>{$user.email}</td>
			<td>{$user.joined}</td>
			<td><a href="">PM</a></td>
		</tr>
{/foreach}
	</tbody>
</table>