<!-- Module Heading -->
<div class="ModuleHeading">{t}Usercenter{/t}</div>
<div class="ModuleHeadingSmall">{t}This is the Administration Area of your Account. Administrate your Profile, Avatar, Picture, Signature, Buddies and Messages.{/t}</div>

<!-- Content -->
Account: {$smarty.session.user.nick}
<br /> <br />
<a href="index.php?mod=account&amp;sub=profile&amp;id={$smarty.session.user.user_id}">{t}Profile{/t}</a>
<br /> <br />
<a href="index.php?mod=account&amp;sub=options&amp;id={$smarty.session.user.user_id}">{t}Options{/t}</a>
<br /> <br />
<a href="index.php?mod=account&amp;sub=picture&amp;id={$smarty.session.user.user_id}">{t}Picture{/t}</a>
<br /> <br />
<a href="index.php?mod=account&amp;sub=avatar&amp;id={$smarty.session.user.user_id}">{t}Avatar{/t}</a>
<br /> <br />
<a href="index.php?mod=account&amp;sub=signature&amp;id={$smarty.session.user.user_id}">{t}Forum Signature{/t}</a>
<br /> <br />
<a href="index.php?mod=friendslist&amp;sub=admin&amp;id={$smarty.session.user.user_id}">{t}Buddy and Friendslist{/t}</a>
<br /> <br />
<a href="index.php?mod=messages&amp;sub=admin&amp;id={$smarty.session.user.user_id}">{t}Private Messages{/t}</a>
<br /> <br />
<a href="index.php?mod=account&amp;action=logout">
    <img height="16" border="0" width="16" alt="logout-image" src="http://www.clansuite-dev.com/themes/core/images/tango/16/System-log-out.png" style="position: relative; top: 4px;"/>
    {t}Logout{/t}
</a>