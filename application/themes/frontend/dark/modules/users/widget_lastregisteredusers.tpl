{* {$last_registered_users|var_dump} *}

<!-- Start: last_registered_users widget @ Standard Theme // -->

<div class="table table-border">
	<div class="table-header table-border-bottom"><img src="{$www_root_theme}images/icons/user.png" />{t}Last registered users{/t}</div>
	<div class="table-content-menu" style="height:90px;">
		{foreach item=lastuser from=$last_registered_users}
			<div class="gridblock">
				<div class="grid05l">
					<div class="gridcontent">&nbsp;</div>
				</div>
				<div class="grid15l">
					<div class="gridcontent">{$lastuser.nick}</div>
				</div>
				<div class="grid70r">
					<div class="gridcontent">({$lastuser.joined|duration} ago)</div>
				</div>
			</div>

		{/foreach}
	</div>
</div>
<div class="tablespacer10">&nbsp;</div>

<!-- End: last_registered_users widget // -->