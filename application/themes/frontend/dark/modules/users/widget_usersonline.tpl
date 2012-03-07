{* Debug: {$usersonline|var_dump} *}

<!-- Start: widget_usersonline @ module users // -->

<div class="table table-border">
	<div class="table-header table-border-bottom"><img src="{$www_root_theme}images/icons/user.png" />{t}User Online{/t}</div>
	<div class="table-content-menu" style="height:90px;">
		<div class="gridblock">
			<div class="grid10l">
				<div class="gridcontent">&nbsp;</div>
			</div>
			<div class="grid50l">
				<div class="gridcontent">Users:</div>
			</div>
			<div class="grid40r">
				<div class="gridcontent">{$usersonline}</div>
			</div>
		</div>

		<div class="gridblock">
			<div class="grid10l">
				<div class="gridcontent">&nbsp;</div>
			</div>
			<div class="grid50l">
				<div class="gridcontent">Guests:</div>
			</div>
			<div class="grid40r">
				<div class="gridcontent">{$guests}</div>
			</div>
		</div>

		{* {$usersonline.number_registered}
			 <br />
			 {$usersonline.nicks}
			 <br />
			 {gravatar email="`$random_user.email`"}
			 <br />
			 {$random_user.country}
		 *}
	</div>
</div>
<div class="tablespacer10">&nbsp;</div>

<!-- End: users_online widget // -->