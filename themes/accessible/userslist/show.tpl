<h1>{t}Userlist{/t}</h1>
{* Debugausgabe des Arrays: {$userslist|@var_dump}  *}
{html_alt_table loop=$userslist}

{include file="tools/paginate.tpl"}

<ul>

{foreach item=user from=$userslist}
    <li>{$user.user_id} - {$user.nick} - {$user.email} - <a href="private">Send</a></li>
{/foreach}

</ul>