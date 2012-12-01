{* {$widgetRandomUser|var_dump} *}

<!-- Start: widgetRandomUser @ module users // -->

<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
	<tr>
		<td class="menu_header" colspan="2">{t}Random User{/t}</td>
	</tr>
	<tr>
		<td class="cell1">
				{$widgetRandomUser.user_id}
				<br />
				{$widgetRandomUser.nick}
				<br />
				{$widgetRandomUser.email}
				<br />
				{gravatar email="`$widgetRandomUser.email`"}
				<br />
				{$widgetRandomUser.country}
				<br />
				{$widgetRandomUser.joined|duration} ago
				<br />
		</td>
	</tr>

</table>

<!-- End: widgetRandomUser widget // -->
