{move_to target="pre_head_close"}
<link rel="stylesheet" type="text/css" href="{$www_root_theme}css/screen/forum.css" />
{/move_to}

<div id="forum">

<table class="forumtable" cellpadding="0" cellspacing="0">

{if $withcat}
	{foreach item=categorie from=$categories}
	<table class="forumtable" cellpadding="0" cellspacing="0">
		<tr>
			<td></td>
		</tr>
	</table>

	<table class="forumtable" cellpadding="0" cellspacing="0">
	<colgroup><col width="5%" /><col width="50%" /><col width="20%" /><col width="5%" /><col width="5%" /><col width="15%" /></colgroup>
		<tr>
			<th class="bordertop borderleft borderbottom">#</td>
			<th class="forum-boardindex bordertop borderleft borderbottom">{t}Boardname{/t}</td>
			<th class="bordertop borderleft borderbottom">{t}Letzter Beitrag{/t}</td>
			<th class="bordertop borderleft borderbottom">{t}Themen{/t}</td>
			<th class="bordertop borderleft borderbottom">{t}Beiträge{/t}</td>
			<th class="bordertop borderleft borderbottom borderright">{t}Moderator{/t}</td>
		</tr>

		{foreach item=board from=$boards}
		<tr>
			<td class="bordertop borderleft borderbottom"><img src="{$www_root_theme}images/forum/forum.png"></td>
			<td class="forum-boardindex bordertop borderleft borderbottom">
				{$board.title}<br />
				<span class="forum-boardindex-descr">{$board.description}</span>
			</td>
			<td class="bordertop borderleft borderbottom">&nbsp;</td>
			<td class="bordertop borderleft borderbottom">{$board.num_topics}</td>
			<td class="bordertop borderleft borderbottom">{$board.num_topics}</td>
			<td class="bordertop borderleft borderbottom borderright">&nbsp;</td>
		</tr>
		{/foreach}

	{/foreach}

{else}

	<table class="forumtable" cellpadding="0" cellspacing="0">
	<colgroup><col width="4%" /><col width="45%" /><col width="20%" /><col width="8%" /><col width="8%" /><col width="15%" /></colgroup>
		<tr>
			<th class="borderbottom borderright">#</td>
			<th class="borderleft borderbottom">{t}Boardname{/t}</td>
			<th class="borderleft borderbottom">{t}Letzter Beitrag{/t}</td>
			<th class="borderleft borderbottom">{t}Themen{/t}</td>
			<th class="borderleft borderbottom">{t}Beiträge{/t}</td>
			<th class="borderleft borderbottom borderright">{t}Moderator{/t}</td>
		</tr>

		{foreach item=board from=$boards}
		<tr>
			<td class="borderbottom" align="center"><img src="{$www_root_theme}images/forum/forum.png"></td>
			<td class="forum-boardindex borderleft borderbottom">
				<a href="index.php?mod=forum&action=board&id={$board.board_id}">{$board.title}</a><br />
				<span class="forum-boardindex-descr">{$board.description}</span>
				{if $board.subb}
				<div class="subboardtitle">Untergeordnete Boards</div>
				<ul style="padding-left:20px;">
					{foreach item=sboard from=$board.subboards}
						<li><a href="index.php?mod=forum&action=board&id={$sboard.board_id}" style="font-weight:bold; font-size:8pt;" title="Themen: {$sboard.num_topics} | Beiträge {$sboard.num_posts}">{$sboard.title}</a></li>
					{/foreach}
				</ul>
				{/if}
			</td>
			<td class="borderleft borderbottom">&nbsp;</td>
			<td class="borderleft borderbottom" align="center">{$board.num_topics}</td>
			<td class="borderleft borderbottom" align="center">{$board.num_posts}</td>
			<td class="borderleft borderbottom borderright">&nbsp;</td>
		</tr>
		{/foreach}

{/if}

</table>
</div>
