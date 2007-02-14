<div class="usercenter">
	{translate}You're logged in as {/translate}<strong>{$smarty.session.user.nick}</strong>
	<ul>
{if $smarty.session.user.rights.access_controlcenter==1}
		<li><a href="index.php?mod=admin">{translate}Control Center{/translate}</a></li>
{/if}
		<li><a href="index.php?mod=account&amp;sub=options">{translate}Options{/translate}</a></li>
		<li><a href="index.php?mod=account&amp;sub=profile">{translate}Profile{/translate}</a></li>
		<li><a href="index.php?mod=messaging&amp;action=show">{translate}Messages ({mod name="messaging" func="get_new_messages_count"}){/translate}</a></li>
		<li><a href="index.php?mod=account&amp;action=logout">{translate}Logout{/translate}</a></li>
	</ul>
</div>