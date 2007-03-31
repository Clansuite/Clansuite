{* DEBUG OUTPUT of assigned Arrays:
	{$paginate|@var_dump}
*}

{$forums|@var_dump}

Board >> Overview of Forums (forums.tpl)

<table border="0" cellspacing="0" cellpadding="0" width="800px" align="center">
    
    <tr class="tr_header">
        <th>Forum</th>
        <th>Inhalt</th>
        <th>Last Entry</th>
    </tr>

    {foreach item=forum from=$forums} 
         <tr>
            <td style="text-align: left;"><b>{$forum.name} </b> <br /> Description: {$forum.description} </td>
            
            <td>Threads: {$forum.threads} <br /> Posts:  {$forum.posts}</td>
            
            <td><a href='index.php?mod=board&amp;action=showthread&amp;id={$forum.id_of_last_post}'>{$forum.name_of_last_post} Name of Last Post </a>
                <br />
                {$fourm.date_of_last_post} Date of Last Post
                <br />
                <a href='index.php?mod=users&amp;id={$forum.userid_of_last_post}'>{$forum.username_of_last_post} Author of Last Post</a>
            </td>    
        </tr>
     {/foreach} 
</table>