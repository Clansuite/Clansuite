{* DEBUG OUTPUT of assigned Arrays:
	{$paginate|@var_dump}
*}

{$forums|@var_dump}

<h2>Board - Overview of Forums </h2> (forums.tpl)

<table border="0" cellspacing="0" cellpadding="0" width="800px" align="center">
    
    <tr style="text-align: left;">
        <th>-</th>
        <th>Boards</th>
        <th>Inhalt</th>
        <th>Last Entry</th>
    </tr>

    {foreach item=forum from=$forums} 
         <tr>
            <td rowspan="2">
                <a href="index.php?mod=board&amp;action=unread;board=1.0;children"><img title="No new Entries" alt="No new Entries" src="off.gif"/></a>
            </td>
            
            <td style="text-align: left;"><b><a href="index.php?mod=board&amp;action=showboard&amp;forumid={$forum.forumid}" > {$forum.name} -  (id:{$forum.forumid})</a> </b> <br /> Description: {$forum.description} </td>
            
            <td>Threads: {$forum.threads} <br /> Posts:  {$forum.posts}</td>
            
            <td><a href='index.php?mod=board&amp;action=showthread&amp;id={$forum.id_of_last_post}'>{$forum.name_of_last_post} Name of Last Post </a>
                <br />
                {$forum.date_of_last_post} Date of Last Post
                <br />
                <a href='index.php?mod=users&amp;id={$forum.userid_of_last_post}'>{$forum.username_of_last_post} Author of Last Post</a>
            </td>    
        </tr>
        {* Show Subforums in case the Forum has some *}
      {*  {if isset($forum.subforums)} *}
        <tr>
            <td colspan="3" style="text-align: left;">Subboards: 
        {*    {foreach item=subforum from=$forum.subforums} *}
              <b><a href="index.php?mod=board&amp;action=showboard&amp;forumid={$subforum.id}" > {$subforum.name} -  (id:{$subforum.id})</a> </b>
              <br />
        {*    {/foreach} *}
            </td>
        </tr>
      {*  {/if} *}
     {/foreach} 
</table>