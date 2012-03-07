
<div class="table table-border">
	<div class="table-header"><img src="{$www_root_theme}images/icons/news.png" />{t}News{/t}</div>
{*
	<div class="table-content">
		{pagination}
	</div>
*}
</div>
<div class="linespacer10">&nbsp;</div>

{counter start="1" assign="cnt"}
{foreach item=singlenews from=$news}

<a id="news-{$singlenews.news_id}"></a><!-- Anker-Sprungmarke der News-ID {$singlenews.news_id}-->

<div class="table table-border">
	<div class="table-header table-border-bottom">
		<b>{$singlenews.news_title}  |  {icon name="category"} {$singlenews.category.name}  |  {icon name="tag"} No Tags applied yet!</b><br/>
		<span class="writtenby">
			The article was written by&nbsp;&nbsp;
			<a href='index.php?mod=users&amp;id={$singlenews.news_authored_by.user_id}'>{$singlenews.news_authored_by.nick}</a>&nbsp;&nbsp;
			on {$singlenews.created_at|date_format}.
		</span><br/>
		<span class="comments">
			{icon name="comment"}Until now, it has&nbsp;&nbsp;<a href='index.php?mod=news&amp;action=showone&amp;id={$singlenews.news_id}'>{$singlenews.nr_comments} comments.</a>
		</span>
	</div>

	<div class="table-content clearfix">
		{if $cnt is not even}
			<div class="articlepicright"><img src="{$singlenews.category.image}" alt="Category-Image: {$singlenews.category.name}" width="150" /></div>
		{else}
			<div class="articlepicleft"><img src="{$singlenews.category.image}" alt="Category-Image: {$singlenews.category.name}" width="150" /></div>
		{/if}
		<blockquote>{$singlenews.news_body}</blockquote>
	</div>

	<div class="table-footer">
		<div class="gridblock">
			<div class="grid50l">
				<div class="gridcontent">
					<strong>&raquo;</strong>
					{if $singlenews.nr_comments >0}
						<a href="index.php?mod=news&amp;action=showone&amp;id={$singlenews.news_id}">{$singlenews.nr_comments} Comments</a>
						{if isset($singlenews.comments.users.lastcomment_by)}<span> : {$singlenews.comments.users.lastcomment_by}</span>{/if} 
					{else}
						0 Comments
					{/if}
				</div>
			</div>
			<div class="grid50r">
				<div class="gridcontent">
					{if isset($smarty.session.user.rights.permission_edit_news) AND isset($smarty.session.user.rights.permission_access)}
						<form id="deleteForm" name="deleteForm" action="index.php?mod=news&amp;sub=admin&amp;action=delete&amp;front=1" method="post">
							<input type="hidden" value="{$singlenews.news_id}" name="delete[]" />
							<input type="hidden" value="{$singlenews.news_id}" name="ids[]" />
							<input class="ButtonGreen" type="button" value="{t}Edit news{/t}" />
							<input class="ButtonRed" type="submit" name="submit" value="{t}Delete{/t}" />
						</form>
					{/if}
				</div>
			</div>
		</div>

	</div>
</div>
<div class="tablespacer10">&nbsp;</div>

{counter}
{/foreach}
