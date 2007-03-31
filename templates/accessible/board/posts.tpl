Posts in Thread of Forum {$board_navigation.parent_forum} (posts.tpl)

<table border="0" cellspacing="0" cellpadding="0" width="800px" align="center">
    
    <tr class="tr_header">
        <th>Author</th>
        <th>Entry</th>
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
            -group
            <br />
            -online/offline
            </td> 
            
            <td>
                <b>{$post.title} posttitle</b> - {$post.description} postdesc
                
                <br />
                {$post.message} MESSAGE
                <br />
                Profile - WWW - ICQ    
            </td>                   
            
         </tr>
         
         
 {*   {/foreach} *}
</table>