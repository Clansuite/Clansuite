{* {$random_user|var_dump} *}

<!-- Start: widget_randomuser @ module users // -->

<div class="table table-border">
	<div class="table-header table-border-bottom"><img src="{$www_root_theme}images/icons/user.png" />{t}Random User{/t}</div>
	<div class="table-content-menu" style="height:90px;">
		<div style="float:left; margin: 0px 3px 3px 0;">
			{gravatar email="`$random_user.email`"}
		</div>
		<p>{$random_user.user_id}</p>
		<p>{$random_user.nick}</p>
		<p>{$random_user.email}</p>
		<p>{$random_user.country}</p>
		<p>{$random_user.joined|duration} ago</p>
	</div>
</div>
<div class="tablespacer10">&nbsp;</div>

<!-- End: random_user widget // -->