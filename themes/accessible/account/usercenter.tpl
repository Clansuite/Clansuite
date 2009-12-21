<h3>{t}Usercenter{/t}</h3>
<div class="content usercenter">
	{t}You're logged in as {/t}<strong>{$smarty.session.user.nick}</strong>
	<ul>
{if isset($smarty.session.user.rights.permission_access) && $smarty.session.user.rights.permission_access == 1}
		<li><a href="index.php?mod=controlcenter">{t}Control Center{/t}</a></li>
{/if}
		<li><a href="index.php?mod=account&amp;sub=options">{t}Options{/t}</a></li>
		<li><a href="index.php?mod=account&amp;sub=profile">{t}Profile{/t}</a></li>
		<li><a href="index.php?mod=messaging&amp;action=show">{if isset($smarty.session.user.rights.use_messaging_system) && $smarty.session.user.rights.use_messaging_system == 1}{t}Messages{/t} ({load_module name="messaging" func="get_new_messages_count"}){/if}</a></li>
		<li><a href="index.php?mod=account&amp;action=logout">{t}Logout{/t}</a></li>
	</ul>{*
	SessionCountdown:
    <div id="countdown"></div>
{move_to target="pre_head_close"}

    <script type="text/javascript">
    var ServerCurrentTime        = {$SessionCurrentTime};	    // Current
    var ServerSessionLogoutTime  = {$SessionExpireTime};	        // Logout

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

{/move_to}
{$SessionCurrentTime|date_format:"%H:%M:%S"} {$SessionExpireTime|date_format:"%H:%M:%S"} *}
</div>