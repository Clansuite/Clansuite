<div id="contentright">
    hallo erstmal, 
    ich bin text aus der datei right.tpl
    und das wars auch schon fast 
    <hr>
	
	{* später in plugin überführen*}
	<!-- UserOnline //-->
	<div class="boxed">
		<div class="title"><a href="{php} echo WWW_ROOT.'/whoisonline.php'; {/php}">UserOnline</a></div>
        	 <div id="body">
			 	  {php} $UserOnline = User::get_online_user(); 
        	 	   echo $UserOnline[RegisteredOnline]; {/php} Member
        	 	   {if $UserOnline[RegisteredOnline] > 0}
				   {literal}
        	 	   <!-- load toggler //-->
				   <script src="/shared/jscript/toggle.js" type="text/javascript"> </script>
				   <!-- create toggle button //-->
				   <IMG style="CURSOR: hand" 
				   		onclick="if (document.getElementById('nicksonline').style.display == 'inline') 
						{ document.getElementById('nicksonline').style.display = 'none'; this.src = '{/literal}{php}echo WWW_ROOT;{/php}{literal}/images/expand.gif'; } 
						else { document.getElementById('nicksonline').style.display = 'inline'; this.src = '{/literal}{php}echo WWW_ROOT;{/php}{literal}/images/collapse.gif'; }"
					alt="" src="{/literal}{php}echo WWW_ROOT;{/php}{literal}/images/expand.gif"> 
					{/literal}
					<!-- area to hide/show //-->
					<DIV id="nicksonline" style="DISPLAY: none"> 
				    {php} for($x=0;$x<count($UserOnline);$x++)
				   		  { 
				   		  echo '<a href="'. WWW_ROOT.'/users/profil.php?user_id='. $UserOnline[$x][user_id] .'">'. $UserOnline[$x][nick] .'</a><p />';
						  } {/php}
					</DIV>
					{/if}
					<br>{php} echo $UserOnline[Guest]; {/php} Guests
			 </div>
	</div>
	
	{* später in plugin überführen*}
	<!-- Statistik: Visits //-->
	<div class="boxed">
		<div class="title"><a href="{php} echo WWW_ROOT.'/module/stats/stats.php'; {/php}">Visits</a></div>
        	 <div id="body">
			 	  {php} $Stats = User::get_online_user(); {/php}
        	 	  Today {php} echo $Stats[today]; {/php}
        	 	  Yesterday	{php} echo $Stats[yesterday] {/php}
				  Month {php} echo $Stats[month]; {/php} 
				  <hr>
				  Total	{php} echo $Stats[total]; {/php}	 
			 </div>
	</div>


</div>