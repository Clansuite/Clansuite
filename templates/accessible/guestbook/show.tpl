<h1>{translate}Guestbook{/translate}</h1>
{* Debugausgabe des Arrays: {$guestbook|@var_dump} {html_alt_table loop=$guestbook} *} 

{* ############### Guestbook Add Entry ##################### *}

<div id="guestbook_add_entry">

    <form name="post" method="post" action="index.php?mod=guestbook&action=add_guestbook_entry">
      <table width="100%" border="1" cellspacing="1" cellpadding="2">
        <tr> 
          <td align="center" class="title">{translate}Add a guestbook entry{/translate}</td>
        </tr>
        <tr>
            <td>
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
            </td>
      </table>
        <fieldset>
            <dl>
                <dt><label for="gbname">Your Name:</label></dt>
                <dd><input type="text" name="info[gbname]" id="gbname" /></dd>
                <dt><label for="gbemail">Your e-mail:</label></dt>
                <dd><input type="text" name="info[gbemail]" id="gbemail" /></dd>
                <dt><label for="icq">ICQ:</label></dt>
                <dd><input type="text" name="info[gbicq]" id="icq" /></dd>
                <dt><label for="gbtown">Your Town:</label></dt>
                <dd><input type="town" name="info[gbtown]" id="town" /></dd>
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

{foreach item=entry from=$guestbook}    
    Comment <a name="{$entry.gb_id}"> # {$entry.gb_id} by</a> <b> <a href='index.php?mod=users&show'>{$entry.gb_nick}</a></b>
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
    <hr>
{/foreach}

