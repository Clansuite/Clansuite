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
            <td rowspan="2">
            <a id="postid" />
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
                <table width="100%" border="0"><tbody><tr>
				<td valign="middle"><a href="index.php/post#postanchor{$post.id}"><img border="0" alt="" src=".."/></a></td>
				<td valign="middle">
					<div id="subject_252" style="font-weight: bold;">
						<a href="index.php/post#postanchor{$post.id}">{$post.title}</a>
					</div>
					<div class="smalltext">« <b>Antwort #2 am:</b> {$post.date}</div></td>
				<td>
				Quote Edit Delete Split [x]
				</td>
			</tr></tbody></table>
            
            
            
                <b>{$post.title} posttitle</b> - {$post.description} postdesc

                <hr width="100%" size="1" class="hrcolor"/>

                {$post.message} MESSAGE
                
                
                author / date Diesen Beitrag einem Moderator melden | IP: {$post.ip} Gespeichert
                
                <br />
                

                <hr width="100%" size="1" class="hrcolor"/>

                Profile - WWW - ICQ
            </td>
            </tr>
            <tr>
				<td width="85%" valign="bottom" class="smalltext">
					<table width="100%" border="0" style="table-layout: fixed;">
					<tbody>
    				<tr>
    				    <td width="100%" class="smalltext" colspan="2"></td>
    				</tr>
					<tr>
						<td valign="bottom" id="modified_250" class="smalltext">
							<i> last edited by: {$post.last_edit}</i>
						</td>
						<td valign="bottom" align="right" class="smalltext">
							<a href="index.php?action=reporttm;topic=67.0;msg=250">Moderator informieren</a>  
							<img border="0" alt="" src="http://forum.clansuite.com/Themes/EnglishSteel/images/ip.gif"/>
							<a href="index.php?action=trackip;searchip={$post.ip}">{$post.ip}</a>
						</td>
					</tr>
					</tbody>
					</table>
					<hr width="100%" size="1" class="hrcolor"/>
					<div class="signature">{$post.author.signature}</div>
				</td>
			</tr>

 {*   {/foreach} *}
</table>
