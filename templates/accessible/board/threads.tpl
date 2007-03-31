Threads in Forum {$board_navigation.parent_forum} (threads.tpl)

<table border="0" cellspacing="0" cellpadding="0" width="800px" align="center">
    
    <tr class="tr_header">
        <th>Statusimage</th>
        <th>Topic</th>
        <th>Created</th>
        <th>Answers</th>
        <th>Hits</th>
        <th>Last Entry</th>
    </tr>

   {* {foreach item=forum from=$threads} *}
         <tr>
            <td><b>{$thread.title}</b> <br /> {$thread.description}</td>
            
            <td>{$thread.threads} Threads <br /> {$thread.posts} Posts</td>
            
            <td>{$thread.first_post_date} {$thread.first_post_author}</td>
            
            <td>{$thread.number_of_answers} #answers</td>
            <td>{$thread.number_of_clicks} #clicks</td> 
            
            <td><a href='index.php?mod=board&amp;action=showthread&amp;id={$thread.id_of_last_post}'>{$thread.name_of_last_post} Name of Last Post </a>
                <br />
                {$fourm.date_of_last_post} Date of Last Post
                <br />
                <a href='index.php?mod=users&amp;id={$thread.userid_of_last_post}'>{$thread.username_of_last_post} Author of Last Post</a>
            </td>   
            
            
        </tr>
   {* {/foreach} *}
</table>