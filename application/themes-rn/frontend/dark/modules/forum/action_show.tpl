
<div class="table table-border">
	<div class="table-header"><img src="{$www_root_theme}images/icons/comments.png" />{t}Forum{/t}</div>
</div>
<div class="linespacer10">&nbsp;</div>

<div id="forum">


{if $withcat}
	{foreach item=categorie from=$categories}
	<table class="forumtable" cellpadding="0" cellspacing="0">
		<tr><td class="boardCategory">{$categorie.title}</td></tr>
		<tr><td class="boardCategoryDescr">{$categorie.description}</td></tr>
	</table>

	<table class="forumtable" cellpadding="0" cellspacing="0">
	<colgroup><col width="4%" /><col width="45%" /><col width="20%" /><col width="8%" /><col width="8%" /><col width="15%" /></colgroup>
		<tr>
			<th class="borderbottom td-center">#</td>
			<th class="borderleft borderbottom td-center">{t}Boardname{/t}</td>
			<th class="borderleft borderbottom td-center">{t}Letzter Beitrag{/t}</td>
			<th class="borderleft borderbottom td-center">{t}Themen{/t}</td>
			<th class="borderleft borderbottom td-center">{t}Beiträge{/t}</td>
			<th class="borderleft borderbottom td-center">{t}Moderator{/t}</td>
		</tr>

		{foreach item=board from=$boards}
		<tr>
			<td class="borderbottom td-center"><img src="{$www_root_theme}images/icons/comments.png"></td>
			<td class="forum-boardindex borderleft borderbottom td-left">
				<a href="index.php?mod=forum&action=board&id={$board.board_id}">{$board.title}</a><br />
				<span class="forum-boardindex-descr td-left">{$board.description}</span>
				{if $board.subb}
				<div class="subboardtitle td-left">Untergeordnete Boards</div>
				<ul style="padding-left:20px;">
					{foreach item=sboard from=$board.subboards}
						<li><a href="index.php?mod=forum&action=board&id={$sboard.board_id}" style="font-weight:bold; font-size:8pt;" title="Themen: {$sboard.num_topics} | Beiträge {$sboard.num_posts}">{$sboard.title}</a></li>
					{/foreach}
				</ul>
				{/if}
			</td>
			<td class="borderleft borderbottom td-left">&nbsp;</td>
			<td class="borderleft borderbottom td-center">{$board.num_topics}</td>
			<td class="borderleft borderbottom td-center">{$board.num_posts}</td>
			<td class="borderleft borderbottom td-left">&nbsp;</td>
		</tr>
		{/foreach}

	{/foreach}
	</table>
	<div class="linespacer20">&nbsp;</div>

{else}

	<table class="forumtable" cellpadding="0" cellspacing="0">
	<colgroup><col width="4%" /><col width="45%" /><col width="20%" /><col width="8%" /><col width="8%" /><col width="15%" /></colgroup>
		<tr>
			<th class="borderbottom td-center">#</td>
			<th class="borderleft borderbottom td-center">{t}Boardname{/t}</td>
			<th class="borderleft borderbottom td-center">{t}Letzter Beitrag{/t}</td>
			<th class="borderleft borderbottom td-center">{t}Themen{/t}</td>
			<th class="borderleft borderbottom td-center">{t}Beiträge{/t}</td>
			<th class="borderleft borderbottom td-center">{t}Moderator{/t}</td>
		</tr>

		{foreach item=board from=$boards}
		<tr>
			<td class="borderbottom td-center"><img src="{$www_root_theme}images/icons/comments.png"></td>
			<td class="forum-boardindex borderleft borderbottom td-left">
				<a href="index.php?mod=forum&action=board&id={$board.board_id}">{$board.title}</a><br />
				<span class="forum-boardindex-descr td-left">{$board.description}</span>
				{if $board.subb}
				<div class="subboardtitle td-left">Untergeordnete Boards</div>
				<ul style="padding-left:20px;">
					{foreach item=sboard from=$board.subboards}
						<li><a href="index.php?mod=forum&action=board&id={$sboard.board_id}" style="font-weight:bold; font-size:8pt;" title="Themen: {$sboard.num_topics} | Beiträge {$sboard.num_posts}">{$sboard.title}</a></li>
					{/foreach}
				</ul>
				{/if}
			</td>
			<td class="borderleft borderbottom td-left">&nbsp;</td>
			<td class="borderleft borderbottom td-center">{$board.num_topics}</td>
			<td class="borderleft borderbottom td-center">{$board.num_posts}</td>
			<td class="borderleft borderbottom td-left">&nbsp;</td>
		</tr>
		{/foreach}
	</table>

{/if}

</div>
