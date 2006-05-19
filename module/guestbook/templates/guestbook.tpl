<h1>Guestbook</h1>

 <a href="guestbook_add.php">Post to Guestbook</a>
 <!-- BEGIN guestbook -->
 <div class="news">
	<div class="title">{gbook_nick}</div>
		<div class="submitted">
		#{gbook_id} submitted by {gbook_nick} with ip:{gbook_ip}</a>on {gbook_time_added}<br />
		email:{gbook_email}
		www:{gbook_website}
		icq:{gbook_icq}
		town:{gbook_town}
		</div>
	<div class="body">{gbook_text} <br />&nbsp;</div>
   
<!-- IF comments -->
   <h3>Comments</h3>
   
	<!-- BEGIN comments -->
	  <div class="comment">
	     <div class="header">
	     		<!-- IF pseudo --><div class="ip">{ip}|{host}</div><!-- ENDIF pseudo -->
		     	<div class="count">#{ROWCNT} (Db:{comment_id})</div>
		     	<div class="added">added by <a class="link" href="users/view.php?user_id={user_id}">{nick}</a> on {added}</div>
	     </div>
	     <div class="body">{body}</div>
	  </div>
	<!-- END comments -->
	
<!-- ENDIF comments -->
</div>
<!-- END guestbook -->