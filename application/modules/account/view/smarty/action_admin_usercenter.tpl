<!-- Module Heading -->
<div class="ModuleHeading">{t}Usercenter{/t}</div>
<div class="ModuleHeadingSmall">{t}This is the Administration Area of your Account. You might administrate your Profile, Avatar, Picture, Signature, Buddies and Messages.{/t}</div>

<!-- Content -->
Account: {$smarty.session.user.nick}
<br />
<a href="{link_to href="account/profile/{$smarty.session.user.user_id}"}">{t}Profile{/t}</a>
<a href="{link_to href="account/options/{$smarty.session.user.user_id}"}">{t}Options{/t}</a>
<a href="{link_to href="account/picture/{$smarty.session.user.user_id}"}">{t}Picture{/t}</a>
<a href="{link_to href="account/avatar/{$smarty.session.user.user_id}"}">{t}Avatar{/t}</a>
<a href="{link_to href="account/signature/{$smarty.session.user.user_id}"}">{t}Forum Signature{/t}</a>
<a href="{link_to href="friendslist/admin/{$smarty.session.user.user_id}"}">{t}Buddy and Friendslist{/t}</a>
<a href="{link_to href="messages/admin/{$smarty.session.user.user_id}"}">{t}Private Messages{/t}</a>
<a href="index.php?mod=account&amp;action=logout">
    <img height="16" border="0" width="16" alt="logout-image" src="{$www_root_themes_core}images/icons/system-log-out.png" style="position: relative; top: 4px;"/>
    {t}Logout{/t}
</a>