{* Debugausgabe des Arrays: {$news|@var_dump}*}

<h2> Comments for News {$news.news_id} </h2>
 
<!-- Anker-Sprungmarke fr {$news.news_id}--> <a name="news-{$news.news_id}"></a>
<table border="1" cellspacing="1" cellpadding="3" style="width:99%">
    <tr> 
        <td height="20" ><b>{$news.news_title} - {$news.cat_name}</b></td>
        <td rowspan="3" valign="top"><img src="{$news.image}" alt="Category-Image: {$news.cat_name} " /><center><br /></td>
    </tr>

    <tr> 
        <td valign="top" class="dunkler"><font size="1">geschrieben von <a href='index.php?mod=users&amp;id={$news.user_id}'>{$news.nick}</a> am {$news.news_added} - <a href='index.php?mod=news&amp;sub=newscomments&amp;id={$news.news_id}'>{$news.nr_news_comments} comments</a></font></td>
    </tr>
  
    <tr> 
        <td height="175" width="80%" valign="top">{$news.news_body}</td>
    </tr>
  
    <tr>
         <td>
            <strong>&raquo;</strong>
            <a href="index.php?mod=news&amp;sub=newscomments&amp;id={$news.news_id}">{$news.nr_news_comments} Comments</a>
            {if isset($news.lastcomment_by) }<span> : {$news.lastcomment_by}</span>{/if}
    	</td>
    </tr>
</table>
<br />

<div class="image">{if isset($news.image)} <img src="{php} print BASE_URL; {/php}{$news.cat_image_url}" alt="{$news.cat_image_url}"/> {/if}</div>




<table border="1" cellspacing="1" cellpadding="3" style="width:99%">
<tr>
        <td colspan="2">
        <div style="float:left">Kommentare: {$news.nr_news_comments}</div>
        <div style="float:right"><a href="/index.php?mod=news&amp;action=view&amp;start=0&amp;where=89">&lt;</a>  [{$news.nr_news_comments}]  <a href="/index.php?mod=news&amp;action=view&amp;start=0&amp;where=89">&gt;</a></div>
        </td>
</tr>
    
{foreach name=commentsloop item=comments from=$news.comments}
   <tr>
        <td style="width:150px">
            <img src="symbols/countries/de.png" style="height:11px;width:16px" alt="" /> 
            <a href="/index.php?mod=users&amp;action=view&amp;id=6">Username: {$comments.pseudo} / Userid:{$comments.user_id}</a>
            <br />
            <br />
            <img src="symbols/clansphere/red.gif" alt="" /> {$comments.added}
            <br />
            <br />
            IP / host : {$comments.ip} - {$comments.host}
            <br />
            Beitr&auml;ge: 57
        </td>
        <td class="leftc">
            # {$comments.comment_id} - {$comments.added}
            <a href="#" name="com{$comments.comment_id}"></a>
            <hr style="width:100%" noshade="noshade" />
            <br />{$comments.body}
            </div>
         </td>
    </tr>
{/foreach}  

<tr>
        <td colspan="2">
        <div style="float:left">Kommentare: {$news.nr_news_comments}</div>
        <div style="float:right"><a href="/index.php?mod=news&amp;action=view&amp;start=0&amp;where=89">&lt;</a>  [{$news.nr_news_comments}]  <a href="/index.php?mod=news&amp;action=view&amp;start=0&amp;where=89">&gt;</a></div>
        </td>
</tr>
</table>
<br />

if (guest_comments_enabled) [ show add comment ]
else if (right_add_comment = 1 ) [ show add comment ]
else Bitte Login benutzen um Kommentare zu schreiben.