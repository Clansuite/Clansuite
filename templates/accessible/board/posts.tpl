{* DEBUG OUTPUT of assigned Arrays:
	{$paginate|@var_dump}
*}

{$posts|@var_dump}


Posts in Threadname of Forum {$board_navigation.parent_forum} (posts.tpl)

<table border="0" cellspacing="0" cellpadding="0" align="center">

    <tr>
        <th>Author</th>
        <th>Entry </th>
    </tr>

 {*   {foreach item=post from=$posts} *}
         <tr>
            <td>
            <a name="postid" />
            <a href='index.php?mod=users&amp;id={$thread.userid_of_last_post}'>{$post.username} Author of Last Post</a>
            <br />
            -avatarimage
            <br />
            -rank
            <br />
            -x posts
            <br />
            -group
            <br />
            -online/offline
            </td>

            <td>
                <b>{$post.title} posttitle</b> - {$post.description} postdesc

                <hr width="100%" size="1" class="hrcolor"/>

                {$post.message} MESSAGE
                
                {$post.ip}
                <br />
                last edited by author / date

                <hr width="100%" size="1" class="hrcolor"/>

                Profile - WWW - ICQ
            </td>

         </tr>


 {*   {/foreach} *}
</table>
