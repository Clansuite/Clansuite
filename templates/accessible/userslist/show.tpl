<h1>{translate}Userlist{/translate}</h1>
{* Debugausgabe des Arrays: {$userslist|@var_dump} {html_alt_table loop=$userslist} *}

{* display pagination header *}
{if $paginate.size gt 1}
	 Items {$paginate.first}-{$paginate.last} of {$paginate.total} displayed.
{else}
	 Item {$paginate.first} of {$paginate.total} displayed.
{/if}

{* display results *}
{section name=res loop=$results}
	{$results[res]}
{/section}

{* display pagination info *}
{paginate_prev text="&lt;&lt;"} {paginate_middle format="page"}  {paginate_next text="&gt;&gt;"}

<ul>

{foreach item=user from=$userslist}
    <li>{$user.user_id} - {$user.nick} - {$user.email}</li>
{/foreach}

</ul>