{* DEBUG OUTPUT of assigned Arrays:
	{$paginate|@var_dump}
*}

{$subforums|@var_dump}

Forum {$subforums.0.forumparent} >> Subforumsoverview (Subforums of Parent_Forum {$board_navigation.parent_forum} ) (subforums.tpl)

<table border="0" cellspacing="0" cellpadding="0" width="800px" align="center" class="forum">

    <tr style="text-align: left;">
        <th>-</th>
        <th>Subforum</th>
        <th>Inhalt</th>
        <th>Last Entry</th>
    </tr>

   {foreach item=subforum from=$subforums}
         <tr>
            <td>
                <a href="index.php?mod=board&amp;action=unread;board=1.0;children"><img title="No new Entries" alt="No new Entries" src="off.gif"/></a>
            </td>

            <td style="text-align: left;"><b><a href="index.php?mod=board&amp;action=showboard&amp;forumid={$subforum.forumid}" > {$subforum.name} -  (id:{$subforum.forumid})</a> </b> <br /> Description: {$subforum.description} </td>

            <td>Threads: {$subforum.threads} <br /> Posts:  {$subforum.posts}</td>

            <td><a href='index.php?mod=board&amp;action=showthread&amp;id={$subforum.id_of_last_post}'>{$subforum.name_of_last_post} Name of Last Post </a>
                <br />
                {$fourm.date_of_last_post} Date of Last Post
                <br />
                <a href='index.php?mod=users&amp;id={$subforum.userid_of_last_post}'>{$subforum.username_of_last_post} Author of Last Post</a>
            </td>
        </tr>
    {/foreach}

</table>