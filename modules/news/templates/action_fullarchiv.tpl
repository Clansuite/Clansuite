{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$news|@var_dump}
   <hr>
   {$pagination_links|@var_dump}
*}

{if !empty($news)}
    {pagination}
 
	<!-- News Fullarchiv Wrap -->
	
	<table width="100%">
		<tr>
			<td>Datum</td>
			<td>Titel</td>
			<td>Kategorie</td>
			<td>Author</td>
			<td>Kommentare</td>
		</tr>
		
		{foreach item=news from=$news}
		<!-- Anker-Sprungmarke für {$news.news_id}-->
		<a name="news-{$news.news_id}"></a>
		<tr>
			<td>{$news.created_at|date_format:"%Y.%m.%d"}</td>
			<td><a href='index.php?mod=news&action=showone&id={$news.news_title}'>{$news.news_title}</a></td>
			<td><a href='index.php?mod=news&action=show&cat={$news.CsCategories.cat_id}'>{$news.CsCategories.name}</a></td>
			<td><a href='index.php?mod=users&amp;id={$news.CsUsers.user_id}'>{$news.CsUsers.nick}</a></td>
			<td><a href='index.php?mod=news&amp;action=showone&amp;id={$news.news_id}'>{$news.CsComments.nr_news_comments}</a></td>
		</tr>
		{/foreach}
	</table>
	
	
{else}
{t}There are no news archived.{/t}
{/if}