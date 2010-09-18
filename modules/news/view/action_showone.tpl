<!-- Start News /-->

{* Debugoutput of News Array: {$news|@var_dump} *}

<a id="news_top" id="news_top"></a>

<h2>News : {$news.news_title} </h2>

<table border="1" cellspacing="1" cellpadding="3" style="width:99%">


	<tr>
		<td height="20" ><b>{$news.news_title} - {$news.CsCategories.name}</b></td>
		<td rowspan="3" valign="top"><img src="{$news.CsCategories.image}" alt="Category-Image: {$news.CsCategories.name} " /></td>
	</tr>

	<tr>
		<td valign="top" class="dunkler"><font size="1">{t}written by{/t}<a href='index.php?mod=users&amp;id={$news.CsUsers.user_id}'>{$news.CsUsers.nick}</a> on {$news.created_at|date_format}</font></td>
	</tr>

	<tr>

		<td height="175" width="75%" valign="top">{$news.news_body}</td>
	</tr>

	{if isset($smarty.session.user.rights.permission_edit_news) AND
				 ($smarty.session.user.rights.permission_edit_news == 1) AND
				 ($smarty.session.user.rights.permission_access == 1)}
	 <tr>
		<td colspan="2">
		&nbsp;

			<form action="index.php?mod=news&amp;sub=admin&amp;action=delete&amp;front=1" method="post">
				<input type="hidden" value="{$news.news_id}" name="delete[]" />
				<input type="hidden" value="{$news.news_id}" name="ids[]" />
				<input class="ButtonGreen" type="button" value="{t}Edit news{/t}" />
				<input class="ButtonRed" type="submit" name="submit" value="{t}Delete{/t}" />
			</form>

		</td>
	 </tr>
	 {/if}

</table>

<!-- Ende News -->

<br/>

<!-- Start Comments /-->

{* Debugoutput of Comments Array: {$news_comments|@var_dump} *}

<a id="comments" id="comments"></a>

{if isset($news_comments) && isset($news_comments.0) && is_array($news_comments.0) && count($news_comments.0) > 1}

	<!-- Start Multiple Comments /-->
	<h2>{t}Comments{/t}</h2>

	{foreach item=news_comment from=$news_comments}

	{* Debugoutput of Comments Array: {$news_comment|@var_dump} *}

	<div id="news-comment-id{$news_comment.comment_id}" style="width:99%;">
		<table width="100%" border="1" cellspacing="1" cellpadding="0">
		  <tr>
			<td width="150" rowspan="2" align="center" valign="middle">
				<div align="center">
				<p>{$news_comment.pseudo} {$news_comment.CsUsers.nick}</p>
				{gravatar email="`$news_comment.CsUsers.email`"}
				</div>
			</td>
			<td>
				<div align="right">{t}Comment{/t} {$news_comment.comment_id} {t}written :{/t} {$news_comment.added}</div>
			</td>
		  </tr>
		  <tr>
			<td><div style="padding:10px;">{$news_comment.body}</div></td>
		  </tr>
		</table>
	</div>

	{/foreach}
	<!-- End Multiple Comments /-->

{elseif isset($news_comments) && isset($news_comments.0) && is_array($news_comments.0)}

   <!-- Start One Comment /-->
   <h2>1 {t}Comment{/t}</h2>

   <div id="news-comment-id{$news_comments.comment_id}" style="width:99%;">
		<table width="100%" border="1" cellspacing="1" cellpadding="0">
		  <tr>
			<td width="150" rowspan="2" align="center" valign="middle">
				<div align="center">
				<p>{$news_comments.pseudo} {$news_comments.CsUsers.nick}</p>
				{gravatar email="`$news_comments.CsUsers.email`"}
				</div>
			</td>
			<td><div align="right">geschrieben am: {$news_comments.added}</div></td>
		  </tr>
		  <tr>
			<td><div style="padding:10px;">{$news_comments.body}</div></td>
		  </tr>
		</table>
   </div>
   <!-- End One Comment /-->

{else}

	<!-- Start No Comment /-->
	<h2>{t}No Comments{/t}</h2>

	Add a Comment !

	<!-- End No Comment /-->

{/if}

<!-- Ende Comments /-->