<h1>{translate}Guestbook{/translate}</h1>
{* Debugausgabe des Arrays: {$guestbook|@var_dump} {html_alt_table loop=$guestbook} *} 

{* ############### Guestbook Add Entry ##################### *}

<script src="{$www_core_tpl_root}/javascript/clip.js" type="text/javascript" language="javascript"></script>

<div onclick="clip('guestbook_add_entry_div');"><b>{translate}Click to add a guestbook entry{/translate}</b></div>
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
{* display pagination header *}
Entries {$paginate.first}-{$paginate.last} out of {$paginate.total} displayed.
<br />
{* display pagination info *}
{paginate_prev text="&lt;&lt;"} {paginate_middle format="page"}  {paginate_next text="&gt;&gt;"}
<hr />

{foreach item=entry from=$guestbook}    
    Comment <a id="guestbook_entry_{$entry.gb_id}"> # {$entry.gb_id} by</a> <b> <a href='index.php?mod=users&amp;show'>{$entry.gb_nick}</a></b>
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
    todo: <strong>Frontpage Editing for Admin</strong>
    <br />
    if isset $entry.gb_admincomment -> anzeigen + [BUTTON: edit]
    <br />
    if not set = [BUTTON: add admin comment]
    <br />
    <hr />
{/foreach}

<hr />
{* display pagination info *}
{paginate_prev text="&lt;&lt;"} {paginate_middle format="page"}  {paginate_next text="&gt;&gt;"}