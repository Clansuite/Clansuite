<!-- Start News /-->

<div class="table table-border">
	<div class="table-header"><img src="{$www_root_theme}images/icons/news.png" />{t}News{/t}</div>
</div>
<div class="linespacer10">&nbsp;</div>

<a id="news_top" id="news_top"></a>

<div class="table table-border">
	<div class="table-header table-border-bottom">
		<b>{$news.news_title}  -  {$news.category.name}</b><br/>
		<span class="writtenby">
			{t}written by{/t}&nbsp;&nbsp;
			<a href='index.php?mod=users&amp;id={$news.news_authored_by.user_id}'>{$news.news_authored_by.nick}</a>&nbsp;&nbsp;
			on {$news.created_at|date_format}.
		</span><br/>
	</div>

	<div class="table-content clearfix">
		<div class="articlepicright"><img src="{$news.category.image}" alt="Category-Image: {$news.category.name}" width="150" /></div>
		<blockquote>{$news.news_body}</blockquote>
	</div>

	<div class="table-footer">
		<div class="gridblock">
			<div class="grid50l">
				<div class="gridcontent">
					&nbsp;
					{if isset($smarty.session.user.rights.permission_edit_news) AND
					 ($smarty.session.user.rights.permission_edit_news == 1) AND
					 ($smarty.session.user.rights.permission_access == 1)}
					<form action="index.php?mod=news&amp;sub=admin&amp;action=delete&amp;front=1" method="post">
						<input type="hidden" value="{$news.news_id}" name="delete[]" />
						<input type="hidden" value="{$news.news_id}" name="ids[]" />
						<input class="ButtonGreen" type="button" value="{t}Edit news{/t}" />
						<input class="ButtonRed" type="submit" name="submit" value="{t}Delete{/t}" />
					</form>
					{/if}
				</div>
			</div>
			<div class="grid50r">
				<div class="gridcontent">
					&nbsp;
				</div>
			</div>
		</div>

	</div>
</div>
<div class="tablespacer40">&nbsp;</div>

<!-- Ende News -->

<br/>

<!-- Start Comments /-->

{* Debugoutput of Comments Array: {$news.comments|var_dump} *}

<div class="table table-border">
	<div class="table-header"><img src="{$www_root_theme}images/icons/comment.png" />{t}Comments{/t}</div>
</div>
<div class="linespacer10">&nbsp;</div>

<a id="comments" id="comments"></a>

{if isset($news.nr_comments) > 0}

	{foreach item=news_comment from=$news.comments}

	<!-- Start Comments /-->
	<div class="table table-border">
		<div class="table-header table-border-bottom">
			<div class="gridblock">
				<div class="grid15l">
					<div class="gridcontent">
						{gravatar email="`$news_comment.comment_authored_by.email`"}
					</div>
				</div>
				<div class="grid35l">
					<div class="gridcontent">
						<b>{$news_comment.pseudo} - {$news_comment.comment_authored_by.nick}</b><br/>
					</div>
				</div>
				<div class="grid50r">
					<div class="gridcontent">
						<div align="right">{t}Comment{/t} {$news_comment.comment_id} {t}written :{/t} {$news_comment.added|date_format}</div>
					</div>
				</div>
			</div>
		</div>

		<div class="table-content clearfix">
			<blockquote>{$news_comment.body}</blockquote>
		</div>

		<div class="table-footer">
			<div class="gridblock">
				<div class="grid50l">
					<div class="gridcontent">&nbsp;</div>
				</div>
				<div class="grid50r">
					<div class="gridcontent">&nbsp;</div>
				</div>
			</div>

		</div>
	</div>
	<div class="tablespacer20">&nbsp;</div>

	{/foreach}
	<!-- End Comments /-->

{else}

	<!-- Start No Comment /-->
	<h2>{t}No Comments{/t}</h2>

	Add a Comment !

	<!-- End No Comment /-->

{/if}

<!-- Ende Comments /-->