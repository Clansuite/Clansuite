<h3>{translate}Usercenter{/translate}</h3>
<div class="content usercenter">
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
{doc_raw}
{literal}
    <script type="text/javascript">
    var ServerCurrentTime        = {/literal}{$SessionCurrentTime}{literal};	    // Current
    var ServerSessionLogoutTime  = {/literal}{$SessionExpireTime}{literal};	        // Logout

    function count() {
        var theCountdown = new Date((ServerSessionLogoutTime - ++ServerCurrentTime) * 1000);
		// var dateString = theCountdown.toGMTString();
		if( theCountdown.getSeconds() < 10 )
		  addNull = '0';
		else
		  addNull = '';
		jQuery('#countdown').text(theCountdown.getMinutes() + ':' + addNull + theCountdown.getSeconds());
    	setTimeout('count()', 1000);
    }
    setTimeout('count()', 1000);
    </script>
{/literal}
{/doc_raw}
{$SessionCurrentTime|date_format:"%H:%M:%S"} {$SessionExpireTime|date_format:"%H:%M:%S"}
</div>