<?php
require '../../shared/prepend.php';

//`gbook_id`, `users_id`, `gbook_time_added`, `gbook_nick`, `gbook_email`, 
//`gbook_icq`, `gbook_website`, `gbook_town`, `gbook_text`, `gbook_ip`

$gbooklist = $Db->getAll("SELECT n.gbook_id, n.users_id, n.gbook_time_added, n.gbook_nick,
											n.gbook_email, n.gbook_icq, n.gbook_website, n.gbook_town,
											n.gbook_text, n.gbook_ip
 									FROM " . DB_PREFIX . "guestbook n ORDER BY n.gbook_time_added DESC");

foreach ($gbooklist as $k => $v) { $gbooklist[$k]['nr_comments'] = $Db->getOne("SELECT COUNT(*) FROM guestbook_comments WHERE gbook_id = ?", $v['gbook_id']); }
foreach ($gbooklist as $k => $v) { $gbooklist[$k]['lastcomment'] = $Db->getOne($Db->limitQuery("SELECT IFNULL(u.nick, c.pseudo) FROM guestbook_comments c LEFT JOIN users u USING(user_id) WHERE c.gbook_id = ? ORDER BY c.comment_id DESC", 0, 1), $v['gbook_id']);}
foreach ($gbooklist as $k => $v) { $gbooklist[$k]['comments'] = $Db->getAll("SELECT c.*, u.nick FROM guestbook_comments c LEFT JOIN users u USING(user_id) WHERE c.gbook_id = ? ORDER BY c.comment_id ASC", $v['gbook_id']); }

?>

<?php $TITLE = 'Guestbook'; ?>
<?php include '../shared/header.tpl'; ?>

    <h1>Guestbook</h1>

    <p><input type="button" class="button" value="Add" onclick="location='add.php'"></p>

    <table class="listing">
    <tr>
        <th>#</th>
        <th>Author/Nick</th>
        <th>Added</th>
        <th>Email</th>
        <th>ICQ</th>
        <th>Website</th>
        <th>Town</th>
        <th>Text</th>
        <th>IP</th>
        <th>Action</th>
    </tr>

    <?php foreach ($gbooklist as $gbook): ?>
    <tr>
        <td><?php echo $gbook['gbook_id']; ?></td>
        <td><?php echo $gbook['gbook_nick']; ?></td>
        <td><?php echo substr($gbook['gbook_time_added'], 0, -3); ?></td>
        <td><?php echo $gbook['gbook_email']; ?></td>
        <td><?php echo $gbook['gbook_icq']; ?></td>
        <td><?php echo $gbook['gbook_website']; ?></td>
        <td><?php echo $gbook['gbook_town']; ?></td>
        <td><?php echo $gbook['gbook_text']; ?></td>
        <td><?php echo $gbook['gbook_ip']; ?></td>
        <td>
            <p>
            <input type="button" class="button" value="Edit" onclick="location='edit.php?gbook_id=<?php echo $gbook['gbook_id']; ?>'">
            
            <input type="button" class="button" value="Delete" 
            onclick="if (confirm('Delete \'<?php echo $gbook['gbook_id'].' geschrieben von '.$gbook['gbook_nick'];?>\' ?')) 
            location = 'delete.php?gbook_id=<?php echo $gbook['gbook_id']; ?>';">
            
            <input type="button" class="button" value="<?php echo $gbook['nr_comments']; ?> Comments" onclick="location='gbook_comments.php?gbook_id=<?php echo $gbook['gbook_id']; ?>'">
            
            </p>
        </td>
    </tr>
    <tr>
		  <td colspan=4>
     <?php foreach ($gbook['comments'] as $n => $comment): ?>
		    
		    <div class="comment"><h3>Comments</h3>
		        <div class="header">
		            <?php if ($comment['pseudo']) echo '<div class="ip">'.$comment['ip'].' / '.$comment['host'].'</div>'; ?>
		            <div class="added">#<?php echo $n+1; ?> added by
		                <?php if ($comment['user_id']) echo '<a href="users/view.php?user_id='.$comment['user_id'].'">'.$comment['nick'].'</a>';
		                      else echo '<strong>'.$comment['pseudo'].'</strong>'; ?>
		                on <?php echo substr($comment['added'], 0, -3); ?>
		            </div>
		        </div>
		        <div class="body"><?php echo nl2br($comment['body']); ?></div>
		    </div>
		    <br>
		    <?php endforeach; ?>
	 	    </td>
	 </tr>
    <?php endforeach; ?>

    </table>
    

    <script type="text/javascript" src="../shared/listing.js"></script>

<?php include '../shared/footer.tpl'; ?>