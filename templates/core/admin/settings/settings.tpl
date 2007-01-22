{doc_raw}
    {* Tabs *}
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/luna.css" />
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/tabpane.js"></script>
{/doc_raw}

<form action="index.php?mod=admin&sub=settings&action=update" method="POST">
<div class="tab-pane" id="tab-pane-2">

   {* #### TAB PAGE - STANDARD SETTINGS | IN WHITELIST #### *}

   <div class="tab-page">
       <h2 class="tab">{translate}Standard{/translate}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="cell2" width="10%">
                    {translate}Page Title{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->std_page_title}" name="config[std_page_title" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Standard Template{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->tpl_name}" name="config[tpl_name]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Standard Template Wrapper File{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->tpl_wrapper_file}" name="config[tpl_wrapper_file]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Standard Language{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->language}" name="config[language]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Standard module{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->std_module}" name="config[std_module]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Standard module action{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->std_module_action}" name="config[std_module_action]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Minimum password length{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->min_pass_length}" name="config[min_pass_length]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Encryption method{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <select name="config[encryption]" class="input_text">
                        <option value="md5" {if $cfg->encryption == md5}selected{/if}>{translate}MD5 (faster){/translate}</option>
                        <option value="sha1" {if $cfg->encryption == sha1}selected{/if}>{translate}SHA1 (more secure){/translate}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Salt{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->salt}" name="config[salt]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Standard CSS File{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->std_css}" name="config[std_css]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Standard JS File{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->std_javascript}" name="config[std_javascript]" class="input_text" />
                </td>
            </tr>
        </table>
   </div>

    {* #### TAB PAGE - META TAGSINFOS SETTINGS | IN WHITELIST #### *}

    <div class="tab-page">
        <h2 class="tab">{translate}Meta Tags{/translate}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="cell2" width="10%">
                    {translate}Description{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->meta.description}" name="config[meta_description]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Language{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->meta.language}" name="config[meta_language]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Author{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->meta.author}" name="config[meta_author]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}eMail{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->meta.email}" name="config[meta_email]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Keywords{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->meta.keywords}" name="config[meta_keywords]" class="input_text" />
                </td>
            </tr>
        </table>
    </div>

   {* #### TAB PAGE - EMAIL SETTINGS | IN WHITELIST #### *}

   <div class="tab-page">
       <h2 class="tab">{translate}eMail{/translate}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="cell2" width="10%">
                    {translate}Mail method{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <select name="config[mailmethod]" class="input_text">
                        <option value="mail" {if $cfg->mailmethod == mail}selected{/if}>{translate}Normal{/translate}</option>
                        <option value="smtp" {if $cfg->mailmethod == smtp}selected{/if}>{translate}SMTP{/translate}</option>
                        <option value="sendmail" {if $cfg->mailmethod == sendmail}selected{/if}>{translate}Sendmail{/translate}</option>
                        <option value="exim" {if $cfg->mailmethod == exim}selected{/if}>{translate}Exim{/translate}</option>
                        <option value="qmail" {if $cfg->mailmethod == qmail}selected{/if}>{translate}Qmail{/translate}</option>
                        <option value="postfix" {if $cfg->mailmethod == postfix}selected{/if}>{translate}PostFix{/translate}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Mailerhost{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->mailerhost}" name="config[mailerhost]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Mailerport{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->mailerport}" name="config[mailerport]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}SMTP username{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->smtp_username}" name="config[smtp_username]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}SMTP password{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->smtp_password}" name="config[smtp_password]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Mail encryption{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->mailencryption}" name="config[mailencryption]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}From{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->from}" name="config[from]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}From name{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->from_name}" name="config[from_name]" class="input_text" />
                </td>
            </tr>
        </table>
   </div>


   {* #### TAB PAGE - LOGIN SETTINGS | IN WHITELIST #### *}

   <div class="tab-page">
       <h2 class="tab">{translate}Login{/translate}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="cell2" width="10%">
                    {translate}Login method{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <select name="config[login_method]" class="input_text">
                        <option value="nick" {if $cfg->login_method == nick}selected{/if}>{translate}By nickname{/translate}</option>
                        <option value="email" {if $cfg->login_method == email}selected{/if}>{translate}By eMail{/translate}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Remember me time{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->remember_me_time}" name="config[remember_me_time]" class="input_text" />&nbsp; seconds
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Maximum login attempts{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->max_login_attempts}" name="config[max_login_attempts]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Login ban minutes{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->login_ban_minutes}" name="config[login_ban_minutes]" class="input_text" />
                </td>
            </tr>
        </table>
   </div>

   {* #### TAB PAGE - SESSION SETTINGS | IN WHITELIST #### *}

   <div class="tab-page">
       <h2 class="tab">{translate}Session{/translate}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="cell2" width="10%">
                    {translate}Use cookies{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="checkbox" value="1" name="config[use_cookies]" class="input_text" {if $cfg->use_cookies == 1}checked{/if}/>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Use only cookies{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="checkbox" value="1" name="config[use_cookies_only]" class="input_text" {if $cfg->use_cookies_only == 1}checked{/if}/>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Session name{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->session_name}" name="config[session_name]" class="input_text" />
                </td>
            </tr>
        </table>
   </div>

   {* #### TAB PAGE - ERRORS SETTINGS | IN WHITELIST #### *}

   <div class="tab-page">
       <h2 class="tab">{translate}Errors{/translate}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="cell2" width="10%">
                    {translate}Suppress Errors{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="checkbox" value="1" name="config[suppress_errors]" class="input_text" {if $cfg->suppress_errors == 1}checked{/if}/>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Debugging{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="checkbox" value="1" name="config[debug]" class="input_text" {if $cfg->debug == 1}checked{/if}/>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="10%">
                    {translate}Debuggin in a popup{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="checkbox" value="1" name="config[debug_popup]" class="input_text" {if $cfg->debug_popup == 1}checked{/if}/>
                </td>
            </tr>
        </table>
   </div>

</div>

<br />

<div align="center"><input type="submit" class="ButtonGreen" value="{translate}Change settings{/translate}" name="submit" /></div>
</form>

{* #### Init TabPane #### *}
<script type="text/javascript">setupAllTabs();</script>