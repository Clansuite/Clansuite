{* Debugausgabe des Arrays: {$guestbook|@var_dump} *} 

{html_alt_table loop=$guestbook}

{* ############### Guestbook Show Entries ##################### *}

<table width="100%" border="1" cellspacing="1" cellpadding="2" bgcolor="$border">
  <tr bgcolor="$bghead"> 
    <td class="title"><img src="images/icons/posticon.gif" width="10" height="11">
        <a name="$n"> # $n by</a> <b>$name</b>
    </td>
  </tr>
  <tr>
    <td bgcolor="$pagebg"></td>
  </tr>
  <tr bgcolor="$bg1"> 
    <td><table width="100%" border="1" cellspacing="0" cellpadding="2">
        <tr> 
          <td> 
            <table width="100%" border="1" cellpadding="0" cellspacing="0">
              <tr>
                <td><i>$date</i> $email $hp $icq IP: $ip</td>
                <td align="right"> $quote$actions</td>
              </tr>
            </table> 
            <hr width="200" size="1" noshade align="left" color="$border">
            <div style="overflow:hidden;">$message</div></td>
        </tr>
        <tr>
         <td>$admincomment</td>
        </tr>
      </table> </td>
  </tr>
</table>

<br>

{foreach item=entry from=$guestbook}

    {$entry.gb_id}
    {$entry.gb_added}
    {$entry.gb_nick} <a href='index.php?mod=users&show'>{$entry.gb_nick}</a>
    {$entry.gb_email}
    {$entry.gb_icq}
    {$entry.gb_website}
    {$entry.gb_town}
    {$entry.gb_text}
    {$entry.gb_ip}

{/foreach}

{* ############### Guestbook Add Entry ##################### *}

<div id="guestbook_add_entry">

    <form name="post" method="post" action="guestbook.php" onSubmit="return chkFormular()">
      <table width="100%" border="1" cellspacing="1" cellpadding="2" bgcolor="$border">
        <tr> 
          <td align="center" bgcolor="$bghead" class="title">new entry</td>
        </tr>
        <tr> 
          <td bgcolor="$pagebg"></td>
        </tr>
        <tr> 
          <td bgcolor="$bg1"><table width="450" border="1" align="center" cellpadding="2" cellspacing="0" class="formtable">
              <tr> 
                <td colspan="4"> <table border="1" cellspacing="0" cellpadding="2">
                    <tr> 
                      <td>your name:</td>
                      <td><input name="gbname" type="text" class="form_off" onFocus="this.className='form_on'" onBlur="this.className='form_off'" size="40"> 
                      </td>
                    </tr>
                    <tr> 
                      <td>your e-mail:</td>
                      <td><input name="gbemail" type="text" class="form_off" onFocus="this.className='form_on'" onBlur="this.className='form_off'" size="40"></td>
                    </tr>
                    <tr> 
                      <td>Icq#:</td>
                      <td><input name="icq" type="text" class="form_off" onFocus="this.className='form_on'" onBlur="this.className='form_off'" size="40"></td>
                    </tr>
                    <tr> 
                      <td>your homepage:</td>
                      <td><input name="gburl" type="text" class="form_off" onFocus="this.className='form_on'" onBlur="this.className='form_off'" value="http://" size="40"></td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td colspan="4">your message:<br> <textarea name="message" rows="5" cols="35" style="width:$picsize_l\px;" class="form_off" onFocus="this.className='form_on'" onBlur="this.className='form_off'"></textarea></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
                <td valign="bottom" align="right"> <input name="save" type="submit" value="submit"></td>
              </tr>
            </table></td>
        </tr>
      </table>
    </form>

</div>