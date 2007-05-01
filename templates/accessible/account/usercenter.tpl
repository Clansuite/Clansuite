<h3>{translate}Usercenter{/translate}</h3>
<div class="usercenter">
	{translate}You're logged in as {/translate}<strong>{$smarty.session.user.nick}</strong>
	<ul>
{if $smarty.session.user.rights.cc_access==1}
		<li><a href="index.php?mod=admin">{translate}Control Center{/translate}</a></li>
{/if}
		<li><a href="index.php?mod=account&amp;sub=options">{translate}Options{/translate}</a></li>
		<li><a href="index.php?mod=account&amp;sub=profile">{translate}Profile{/translate}</a></li>
		<li><a href="index.php?mod=messaging&amp;action=show">{if $smarty.session.user.rights.use_messaging_system == 1}{translate}Messages{/translate} ({mod name="messaging" func="get_new_messages_count"}){/if}</a></li>
		<li><a href="index.php?mod=account&amp;action=logout">{translate}Logout{/translate}</a></li>
	</ul>
	SessionCountdown:
    <div id="countdown"></div>

{literal}
    <script type="text/javascript">
    var ServerCurrentTime        = {/literal}{$SessionCurrentTime}{literal};	    // Current
    var ServerSessionLogoutTime  = {/literal}{$SessionExpireTime}{literal};	        // Logout
       
    function kaufm(x) {
      var k = (Math.round(x * 100) / 100).toString();
      k += (k.indexOf('.') == -1)? '.00' : '00';
      return k.substring(0, k.indexOf('.') + 3);
    }
    
    function count() { 
        var theCountdown = new Date(ServerSessionLogoutTime - ++ServerCurrentTime);
		//var dateString = theCountdown.toGMTString();
		document.getElementById("countdown").innerHTML = kaufm(theCountdown / 60);
    	setTimeout('count()', 1000);
    }
    setTimeout('count()', 1000);
    </script>
{/literal}

{$SessionCurrentTime|date_format:"%H:%M:%S"}  {$SessionExpireTime|date_format:"%H:%M:%S"}
</div>