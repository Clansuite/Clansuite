<h1>{translate}Guestbook{/translate}</h1>
{* Debugausgabe des Arrays: {$guestbook|@var_dump} {html_alt_table loop=$guestbook} *}

{doc_raw}
{* Include Lightbox *}
    <link rel="stylesheet" href="{$www_core_tpl_root}/javascript/lightbox/css/lightbox.css" type="text/css" />
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

<script src="{$www_core_tpl_root}/javascript/clip.js" type="text/javascript"></script>

<div onclick="clip('guestbook_add_entry_div');"><strong>{translate}Click to add a guestbook entry{/translate}</strong></div>
<div style="display:none;" id="guestbook_add_entry_div">
    {if !empty($message_errors)}
        <table cellpadding="5" cellspacing="0" border="0" width="400px">
            <tr>
                <td class="red_cell">
                    {if $message_errors.no_gbname == 1}
                        {translate}You should enter a username.{/translate}<br />
                    {/if}
                    {if $message_errors.no_message == 1}
                        {translate}You should enter a guestbook message.{/translate}<br />
                    {/if}
                    {if $message_errors.no_infos == 1}
                        {translate}You haven't supplied any informations at all.{/translate}<br />
                    {/if}
                </td>
            </tr>
        </table>
    {/if}
    <form name="post" method="post" action="index.php?mod=guestbook&amp;action=add_guestbook_entry">
       <fieldset>
            <dl>
                <dt><label for="gbname">Your Name:</label></dt>
                <dd><input type="text" name="info[gbname]" id="gbname" /></dd>
                <dt><label for="gbemail">Your e-mail:</label></dt>
                <dd><input type="text" name="info[gbemail]" id="gbemail" /></dd>
                <dt><label for="icq">ICQ:</label></dt>
                <dd><input type="text" name="info[gbicq]" id="icq" /></dd>
                <dt><label for="gbtown">Your Town:</label></dt>
                <dd><input type="text" name="info[gbtown]" id="gbtown" /></dd>
                <dt><label for="gburl">Your homepage:</label></dt>
                <dd><input type="text" name="info[gbwebsite]" id="gburl" value="http://" /></dd>
                <dt><label for="message">Message:</label></dt>
                <dd><textarea name="info[gbmessage]" id="message" rows="5" cols="35"></textarea></dd>
            </dl>
        </fieldset>
        <div class="form_bottom">
            <input name="submit" type="submit" value="{translate}Add Entry{/translate}" class="button" />
        </div>
    </form>
</div>

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
    {if $smarty.session.user.rights.edit_gb == 1}
        <input class="ButtonGreen" type="button" value="{translate}Edit or add comment{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;sub=admin&amp;action=edit&amp;id={/literal}{$entry.gb_id}{literal}&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
    {/if}
    <hr />
{/foreach}

{include file="tools/paginate.tpl"}