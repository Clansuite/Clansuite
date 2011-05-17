
<div class="table table-border">
	<div class="table-header table-border-bottom"><img src="{$www_root_theme}images/icons/page.gif" alt="{t}Home{/t}" />{t}Home{/t}</div>
	<div class="table-content-menu" style="padding:10px 5px 10px 5px;">

		Modul Template - Module Index - Show
		<br />
		<strong>{t}Hello{/t}</strong><br />
		<strong>{t}Welcome{/t}</strong><br />
		<p>
		<br />
		<strong>This demonstrates gettext-Support with Locales</strong>
		<br /><br />
		{t}Hello World{/t}
		<br />
		{t name=$smarty.session.user.nick}How are you, %1 ?{/t}
		<br />
		{t 1='one' 2='two' 3='three'}The 1st parameter is %1, the 2nd is %2 and the 3rd %3.{/t}
		</p>

		<p>&nbsp;</p>
		<p><b>Clansuite Role and User Based Access Control Management:</b></p>
		<p>&nbsp;&nbsp;&nbsp;-&nbsp;
			{if true == {check_permission name="index.action_show"}}
			 <font color=blue>Der User hat das Recht auf: index.action_show</font>
			{else}
			 <font color=red>Der User hat <u>kein</u> Recht auf: index.action_show</font>
			{/if}
		</p>

		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>

	</div>
</div>
<div class="tablespacer10">&nbsp;</div>
