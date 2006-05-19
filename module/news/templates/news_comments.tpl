<h1>News {parent.corelanguage.comments}</h1>

		<div class="news">
		  <a name="news-{newslist.news_id}" /></a>
		  <div class="title">{newslist.news_title} - {newslist.cat_name} <img src="{newslist.cat_image_url}" border="0" alt="{newslist.cat_image_url}" /></div>
		  <div class="submitted">Submitted by <a href="users/view.php?user_id={newslist.user_id}">{newslist.nick}</a> on {newslist.news_added}</div>
		  <div class="body">{newslist.news_body}</div>
		</div>
		
<!-- IF newslist.comments -->
   <h3>{newslist.nr_news_comments} Comments</h3>

    <!-- BEGIN newslist.comments -->
	  <div class="comment">
	     <div class="header">
		     	<div class="count">#{ROWCNT} (Db:{comment_id})</div>
			    <!-- IF pseudo --><div class="ip">{ip}|{host}</div><!-- ENDIF pseudo -->
		     	<div class="added">added by 
				 <!-- IF user_id == "0" --> Guest
				 <!-- ELSE --> <a class="link" href="users/view.php?user_id={user_id}">{nick}</a>
				 <!-- ENDIF user_id -->
				 
				 on {added}</div>     
		 </div>
	     <div class="body">{body}</div>
	  </div>
	<!-- END newslist.comments -->
	<!-- ENDIF newslist.comments -->