{* Debugausgabe des Arrays: {$news|@var_dump} *}


{foreach item=news from=$news}

<!-- Anker-Sprungmarke fr {$news.news_id}--> <a name="news-{$news.news_id}"></a>
<table border="1" cellspacing="1" cellpadding="3">
    <tr> 
        <td height="20" ><b>{$news.news_title} - {$news.cat_name}</b></td>
        <td rowspan="3" valign="top"><img src="{$news.image}" alt="Category-Image: {$news.cat_name} " /><center><br /></td>
    </tr>

    <tr> 
        <td valign="top" class="dunkler"><font size="1">geschrieben von <a href='index.php?mod=users&id={$news.user_id}'>{$news.nick}</a> am {$news.news_added} - <a href='index.php?mod=news&sub=newscomments&id={$news.news_id}'>{$news.nr_news_comments} comments</a></font></td>
    </tr>
  
    <tr> 
        <td height="175" width="80%" valign="top">{$news.news_body}</td>
    </tr>
  
    <tr>
         <td>
            <strong>&raquo;</strong>
            <a href="index.php?mod=news&sub=newscomments&id={$news.news_id}">{$news.nr_news_comments} Comments</a>
            {if isset($news.lastcomment_by) }<span> : {$news.lastcomment_by}</span>{/if}
    	</td>
    </tr>
</table>
<br />

  <div class="image">{if isset($news.image)} <img src="{php} print BASE_URL; {/php}{$news.cat_image_url}" alt="{$news.cat_image_url}"/> {/if}</div>


{/foreach}

