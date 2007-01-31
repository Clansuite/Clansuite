{doc_raw}
    {* Tabs *}
    <link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/luna.css" />
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/tabpane.js"></script>
{/doc_raw}

<form action="index.php?mod=admin&sub=settings&action=update" method="POST">
<div class="tab-pane" id="tab-pane-1">

   {* #### TAB PAGE - STANDARD SETTINGS #### *}

   <div class="tab-page">
       <h2 class="tab">{translate}Standard{/translate}</h2>
       
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
        <thead>
        <tr>
            <td class="td_header_small" width="15px" colspan="2">  {translate}General Settings{/translate}  </td>
        </tr>
        </thead>
            
            <tr>
                <td class="cell2" width="15%">
                    {translate}Page Title{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->std_page_title}" name="config[std_page_title]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="td_header_small" width="15px" colspan="2">  {translate}Standard Template with Files{/translate}  </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Standard Template{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->tpl_name}" name="config[tpl_name]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Standard Template Wrapper File{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->tpl_wrapper_file}" name="config[tpl_wrapper_file]" class="input_text" />
                </td>
            </tr>
              <tr>
                <td class="cell2" width="15%">
                    {translate}Standard CSS File{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->std_css}" name="config[std_css]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Standard JS File{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->std_javascript}" name="config[std_javascript]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="td_header_small" width="15px" colspan="2">  {translate}Default Language{/translate}  </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Standard Language{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->language}" name="config[language]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="td_header_small" width="15px" colspan="2">  {translate}Default Module / Action{/translate}  </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Standard module{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->std_module}" name="config[std_module]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Standard module action{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->std_module_action}" name="config[std_module_action]" class="input_text" />
                </td>
            </tr>          
        </table>
   </div>

    {* #### TAB PAGE - META TAGSINFOS SETTINGS #### *}

    <div class="tab-page">
        <h2 class="tab">{translate}Meta Tags{/translate}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
        <thead>
        <tr>
            <td class="td_header_small" width="15px" colspan="2">  {translate}Define Meta Tags{/translate}  </td>
        </tr>
        </thead>
            
            <tr>
                <td class="cell2" width="15%">
                    {translate}Description{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->meta.description}" name="config[meta][description]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Language{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->meta.language}" name="config[meta][language]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Author{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->meta.author}" name="config[meta][author]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}eMail{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->meta.email}" name="config[meta][email]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Keywords{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->meta.keywords}" name="config[meta][keywords]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="td_header_small" width="15px" colspan="2">  {translate}Define Dublin Core Metadata Elements{/translate}  </td>
            </tr>
        </table>
    </div>

   {* #### TAB PAGE - EMAIL SETTINGS #### *}

   <div class="tab-page">
       <h2 class="tab">{translate}eMail{/translate}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
        <thead>
        <tr>
            <td class="td_header_small" width="15px" colspan="2">  {translate}Mailer Configuration{/translate}  </td>
        </tr>
        </thead>
            
            <tr>
                <td class="cell2" width="15%">
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
                <td class="cell2" width="15%">
                    {translate}Mailerhost{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->mailerhost}" name="config[mailerhost]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Mailerport{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->mailerport}" name="config[mailerport]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}SMTP username{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->smtp_username}" name="config[smtp_username]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}SMTP password{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->smtp_password}" name="config[smtp_password]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Mail encryption{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->mailencryption}" name="config[mailencryption]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}From{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->from}" name="config[from]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}From name{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->from_name}" name="config[from_name]" class="input_text" />
                </td>
            </tr>
        </table>
   </div>


   {* #### TAB PAGE - LOGIN SETTINGS #### *}

   <div class="tab-page">
       <h2 class="tab">{translate}Login{/translate}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
        <thead>
        <tr>
            <td class="td_header_small" width="15px" colspan="2">  {translate}Login{/translate}  </td>
        </tr>
        </thead>
            
            <tr>
                <td class="cell2" width="15%">
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
                <td class="cell2" width="15%">
                    {translate}Remember me time{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->remember_me_time}" name="config[remember_me_time]" class="input_text" />&nbsp; seconds
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Maximum login attempts{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->max_login_attempts}" name="config[max_login_attempts]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Login ban minutes{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->login_ban_minutes}" name="config[login_ban_minutes]" class="input_text" />
                </td>
            </tr>
            <tr><td class="td_header_small" width="15px" colspan=2>  {translate}Password Settings{/translate}  </td></tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Minimum password length{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->min_pass_length}" name="config[min_pass_length]" class="input_text" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
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
                <td class="cell2" width="15%">
                    {translate}Salt{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->salt}" name="config[salt]" class="input_text" />
                </td>
            </tr>
        </table>
   </div>

   {* #### TAB PAGE - SESSION SETTINGS #### *}

   <div class="tab-page">
       <h2 class="tab">{translate}Session{/translate}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
        <thead>
        <tr>
            <td class="td_header_small" width="15px" colspan="2">  {translate}Session{/translate}  </td>
        </tr>
        </thead>
            
            <tr>
                <td class="cell2" width="15%">
                    {translate}Use cookies{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[use_cookies]" class="input_text" {if $cfg->use_cookies == 1}checked{/if} /> {translate}yes{/translate}
                    <input type="radio" value="0" name="config[use_cookies]" class="input_text" {if $cfg->use_cookies == 0}checked{/if} /> {translate}no{/translate}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Use only cookies{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[use_cookies_only]" class="input_text" {if $cfg->use_cookies_only == 1}checked{/if} /> {translate}yes{/translate}
                    <input type="radio" value="0" name="config[use_cookies_only]" class="input_text" {if $cfg->use_cookies_only == 0}checked{/if} /> {translate}no{/translate}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Session name{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="text" value="{$cfg->session_name}" name="config[session_name]" class="input_text" />
                </td>
            </tr>
        </table>
   </div>

   {* #### TAB PAGE - ERRORS SETTINGS #### *}

   <div class="tab-page">
       <h2 class="tab">{translate}Errors{/translate}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
        <thead>
        <tr>
            <td class="td_header_small" width="15px" colspan="2">  {translate}Error Reporting{/translate}  </td>
        </tr>
        </thead>
            
            <tr>
                <td class="cell2" width="15%">
                    {translate}Suppress Errors{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[suppress_errors]" class="input_text" {if $cfg->suppress_errors == 1}checked{/if} /> {translate}yes{/translate}
                    <input type="radio" value="0" name="config[suppress_errors]" class="input_text" {if $cfg->suppress_errors == 0}checked{/if} /> {translate}no{/translate}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Debugging{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[debug]" class="input_text" {if $cfg->debug == 1}checked{/if} /> {translate}yes{/translate}
                    <input type="radio" value="0" name="config[debug]" class="input_text" {if $cfg->debug == 0}checked{/if} /> {translate}no{/translate}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {translate}Debugging in a popup{/translate}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[debug_popup]" class="input_text" {if $cfg->debug_popup == 1}checked{/if} /> {translate}yes{/translate}
                    <input type="radio" value="" name="config[debug_popup]" class="input_text" {if $cfg->debug_popup == 0}checked{/if} /> {translate}no{/translate}
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