<h1>{translate}Guestbook{/translate}</h1>
{* Debugausgabe des Arrays: {$guestbook|@var_dump} {html_alt_table loop=$guestbook} *}

{doc_raw}
    <script src="{$www_core_tpl_root}/javascript/prototype/prototype.js" type="text/javascript"></script>
    <script src="{$www_core_tpl_root}/javascript/lightbox/lightbox.js" type="text/javascript"></script>
  	<script type="text/javascript" src="{$www_core_tpl_root}/javascript/scriptaculous/effects.js"> </script>
  	<script type="text/javascript" src="{$www_core_tpl_root}/javascript/xilinus/window.js"> </script>
  	<script type="text/javascript" src="{$www_core_tpl_root}/javascript/xilinus/window_effects.js"> </script>
  	<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/javascript/xilinus/themes/alphacube.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/javascript/xilinus/themes/alert.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/javascript/xilinus/themes/default.css" />
{/doc_raw}

{* ############### Guestbook Add Entry ##################### *}

<input class="ButtonGreen" type="button" value="{translate}Add GB entry{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />

{* ############### Show Guestbook Entries ##################### *}
<br/>

<br />
{include file="tools/paginate.tpl"}

{foreach item=entry from=$guestbook}
    Comment <a id="guestbook_entry_{$entry.gb_id}"> # {$entry.gb_id} by</a> <strong> <a href='index.php?mod=users&amp;show'>{$entry.gb_nick}</a></strong>
    <br />
    Date: {$entry.gb_added}
    <br />
    Email: {$entry.gb_email}
    <br />
    ICQ: {$entry.gb_icq}
    <br />
    WWW: {$entry.gb_website}
    <br />
    City: {$entry.gb_town}
    <br />
    IP: {$entry.gb_ip}
    <br />
    Guestbook Message: {$entry.gb_text}
    <br />
    Admin Comments: {$entry.gb_admincomment}
    <br />
    {if $smarty.session.user.rights.edit_gb == 1 AND $smarty.session.user.rights.access_controlcenter == 1}
        <input class="ButtonGreen" type="button" value="{translate}Edit or add comment{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;sub=admin&amp;action=edit&amp;id={/literal}{$entry.gb_id}{literal}&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
    {/if}
    <hr />
{/foreach}

{include file="tools/paginate.tpl"}