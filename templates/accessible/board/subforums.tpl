Forum >> Subforumsoverview (Subforums of Parent_Forum {$board_navigation.parent_forum} ) (subforums.tpl)

<table border="0" cellspacing="0" cellpadding="0" width="800px" align="center">
    
    <tr class="tr_header">
        <th>Forum</th>
        <th>Inhalt</th>
        <th>Last Entry</th>
    </tr>

   {* {foreach item=subforum from=$subforum} *}
         <tr>
            <td><b>{$subforum.title} title </b> <br /> {$subforum.description} desc </td>
            
            <td>{$subforum.threads} # Threads <br /> {$subforum.posts} # Posts</td>
            
            <td><a href='index.php?mod=board&amp;action=showthread&amp;id={$subforum.id_of_last_post}'>{$subforum.name_of_last_post} Name of Last Post </a>
                <br />
                {$fourm.date_of_last_post} Date of Last Post
                <br />
                <a href='index.php?mod=users&amp;id={$subforum.userid_of_last_post}'>{$subforum.username_of_last_post} Author of Last Post</a>
            </td>    
        </tr>
   {* {/foreach} *}
    
</table>