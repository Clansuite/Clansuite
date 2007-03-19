{* Debugausgabe des Arrays: {$guestbook|@var_dump} *}
{html_alt_table loop=$guestbook}
{* ############### Guestbook Show Entries ##################### *}
<table width="100%" border="1" cellspacing="1" cellpadding="2" bgcolor="$border">
    <tr bgcolor="$bghead">
        <td class="title">
            <img src="images/icons/posticon.gif" alt="" />
            <a name="$n"> # $n by</a> <strong>$name</strong>
        </td>
    </tr>
    <tr>
        <td bgcolor="$pagebg"></td>
    </tr>
    <tr bgcolor="$bg1">
       <td>
           <table width="100%" border="1" cellspacing="0" cellpadding="2">
               <tr>
                   <td>
                       <table width="100%" border="1" cellpadding="0" cellspacing="0">
                           <tr>
                               <td><em>$date</em> $email $hp $icq IP: $ip</td>
                               <td align="right">$quote$actions</td>
                           </tr>
                       </table>
                       <hr width="200" size="1" noshade="noshade" align="left" />
                       <div style="overflow:hidden;">$message</div>
                   </td>
               </tr>
               <tr>
                   <td>$admincomment</td>
               </tr>
           </table>
       </td>
    </tr>
</table>
<br />
{foreach item=entry from=$guestbook}
    {$entry.gb_id}
    {$entry.gb_added}
    {$entry.gb_nick} <a href="index.php?mod=users&amp;show">{$entry.gb_nick}</a>
    {$entry.gb_email}
    {$entry.gb_icq}
    {$entry.gb_website}
    {$entry.gb_town}
    {$entry.gb_text}
    {$entry.gb_ip}
{/foreach}
{* ############### Guestbook Add Entry ##################### *}
<div id="guestbook_add_entry">
    <form action="index.php?mod=guestbook" method="post" onsubmit="return chkFormular()">
        <fieldset>
            <dl>
                <dt><label for="gbname">Your Name:</label></dt>
                <dd><input type="text" name="gbname" id="gbname" class="form_off" onfocus="this.className='form_on'" onblur="this.className='form_off'" /></dd>
                <dt><label for="gbemail">Your e-mail:</label></dt>
                <dd><input type="text" name="gbemail" id="gbemail" class="form_off" onfocus="this.className='form_on'" onblur="this.className='form_off'" /></dd>
                <dt><label for="icq">ICQ:</label></dt>
                <dd><input type="text" name="icq" id="icq" class="form_off" onfocus="this.className='form_on'" onblur="this.className='form_off'" /></dd>
                <dt><label for="gburl">Your homepage:</label></dt>
                <dd><input type="text" name="gburl" id="gburl" class="form_off" onfocus="this.className='form_on'" onblur="this.className='form_off'" value="http://" /></dd>
                <dt><label for="message">Message:</label></dt>
                <dd><textarea name="message" id="message" rows="5" cols="35" style="width:$picsize_l\px;" class="form_off" onfocus="this.className='form_on'" onblur="this.className='form_off'"></textarea></dd>
            </dl>
        </fieldset>
        <div class="form_bottom">
            <input name="save" type="submit" value="Submit" class="button" />
        </div>
    </form>
</div>