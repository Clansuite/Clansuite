{move_to target="pre_head_close"}
    <script src="{$www_root_themes_frontend}black/javascript/toolbar.js" type="text/javascript"></script>
{/move_to}


<div id="toolbarControlLeft">{t}Hello{/t} {$smarty.session.user.nick}</div>
<div id="toolbarControlRight">
	<div id="toolbarContainer">
		<div id="toolbarMenu">
			<ul>
				<li class="item1"><a href="">{t}Private Messages{/t}</a></li>
				<li class="item2"><a href="">Shoutbox</a></li>
				<li class="item3"><a href="">Favoriten</a>
					<ul>
						<li><a href="">Favoriten</a></li>
						<li><a href="">Zu Favoriten hinzufügen</a></li>
						<li><a href="">Favoriten verwalten</a></li>
					</ul>
				</li>
				<li class="item4"><a href="{$www_root_mod}index.php?mod=controlpanel&action=show" alt="{t}User Control Panel{/t}" title="{t}User Control Panel{/t}">{t}Control Panel{/t}</a></li>
				{*<li class="item5"><a rel="nofollow" href="" onclick="return log_out('Möchtest du dich wirklich abmelden?')">{t}Logout{/t}</a></li>*}
			</ul>
		</div>
	</div>
</div>
