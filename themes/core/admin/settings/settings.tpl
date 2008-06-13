{* {$config|@var_dump} *}

{doc_raw}
    {* Tabs *}
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/admin/luna.css" />
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/tabpane.js"></script>
{/doc_raw}

<form action="index.php?mod=admin&amp;sub=settings&amp;action=update" method="post" accept-charset="UTF-8">
<div class="tab-pane" id="tab-pane-1">
   {* #### TAB PAGE - STANDARD SETTINGS #### *}
   <div class="tab-page">
       <h2 class="tab">{t}Standard{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}General Settings{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Website Title{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>Name your website:<br/></small>
                    <input class="input_text" type="text" value="{$config.template.std_page_title}" name="config[template][std_page_title]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Favicon{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>You may provide an favicon for our website:<br/></small>
                    <input class="input_text" type="text" value="{$config.template.std_page_title}" name="config[template][std_page_title]" />
                    <br /> <strong>todo: Upload and Chooser</strong> <small>{$www_root_themes}/images/</small>
                </td>
            </tr>
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Standard Template with Files{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Standard Template{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.template.theme}" name="config[template][theme]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Standard Template Wrapper File{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.template.tpl_wrapper_file}" name="config[template][tpl_wrapper_file]" />
                </td>
            </tr>
              <tr>
                <td class="cell2" width="15%">
                    {t}Standard CSS File{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.template.std_css}" name="config[template][std_css]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Standard JS File{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.template.std_javascript}" name="config[template][std_javascript]" />
                </td>
            </tr>
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Default Language{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Standard Language{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.language.language}" name="config[language][language]" />
                </td>
            </tr>
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Default Module / Action{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Standard module{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.defaults.default_module}" name="config[defaults][default_module]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Standard module action{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.defaults.default_action}" name="config[defaults][default_action]" />
                </td>
            </tr>
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Maintenance Mode{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Maintenance Mode{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>You may want to turn off your site while performing updates or other types of maintenance.<br/></small>
                    <input type="radio" value="1" name="config[maintenance][maintenance]" {if $config.maintenance.maintenance == 1}checked="checked"{/if} /> {t}activated{/t}
                    <input type="radio" value="0" name="config[maintenance][maintenance]" {if $config.maintenance.maintenance == 0}checked="checked"{/if} /> {t}deactivated{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Reason for maintenance{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>You may provide a short description to your members explaining why your site has been switched off:<br/></small>
                    <textarea name="config[maintenance][maintenance_reason]" cols="30" rows="10" class="input_textarea">{$config.maintenance.maintenance_reason}</textarea>
                </td>
            </tr>
        </table>
   </div>
    {* #### TAB PAGE - META TAGSINFOS SETTINGS #### *}
    <div class="tab-page">
        <h2 class="tab">{t}Meta Tags{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Define Meta Tags{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Description{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.meta.description}" name="config[meta][description]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Language{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.meta.language}" name="config[meta][language]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Author{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.meta.author}" name="config[meta][author]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Mailer{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.meta.email}" name="config[meta][email]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Keywords{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>Provide some comma separated keywords which describe your website. This will help search engines:<br /></small>
                    <input class="input_text" type="text" value="{$config.meta.keywords}" name="config[meta][keywords]" />
                </td>
            </tr>
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Define Dublin Core Metadata Elements{/t}
                </td>
            </tr>
             <tr>
                <td class="td_header_small"  colspan="2">
                     {t}Search Engine Optimization (SEO){/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Webserver mod_rewrite URL's{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>When your Webserver has Mod Rewrite enabled, then you can turn this setting on to make your URLs more user friendly:<br /></small>
                    <input type="radio" value="1" name="config[webserver][mod_rewrite]" {if $config.webserver.mod_rewrite == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[webserver][mod_rewrite]" {if $config.webserver.mod_rewrite == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
        </table>
    </div>
   {* #### TAB PAGE - EMAIL SETTINGS #### *}
   <div class="tab-page">
       <h2 class="tab">{t}eMailer{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Mail Server Configuration{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Mail method{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <select name="config[email][mailmethod]" class="input_text">
                        <option value="mail" {if $config.email.mailmethod == 'mail'}selected="selected"{/if}>{t}Normal{/t}</option>
                        <option value="smtp" {if $config.email.mailmethod == 'smtp'}selected="selected"{/if}>{t}SMTP{/t}</option>
                        <option value="sendmail" {if $config.email.mailmethod == 'sendmail'}selected="selected"{/if}>{t}Sendmail{/t}</option>
                        <option value="exim" {if $config.email.mailmethod == 'exim'}selected="selected"{/if}>{t}Exim{/t}</option>
                        <option value="qmail" {if $config.email.mailmethod == 'qmail'}selected="selected"{/if}>{t}Qmail{/t}</option>
                        <option value="postfix" {if $config.email.mailmethod == 'postfix'}selected="selected"{/if}>{t}PostFix{/t}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Mailerhost{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.email.mailerhost}" name="config[email][mailerhost]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Mailerport{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.email.mailerport}" name="config[email][mailerport]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Mail encryption{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <select name="config[email][mailencryption]" class="input_text">
                        <option value="SWIFT_OPEN" {if $config.email.mailencryption == 'SWIFT_OPEN'}selected="selected"{/if}>{t}No encryption{/t}</option>
                        <option value="SWIFT_SSL" {if $config.email.mailencryption == 'SWIFT_SSL'}selected="selected"{/if}>{t}SSL encryption{/t}</option>
                        <option value="SWIFT_TLS" {if $config.email.mailencryption == 'SWIFT_TLS'}selected="selected"{/if}>{t}TLS/SSL encryption{/t}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}SMTP authentication{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}SMTP username{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.email.smtp_username}" name="config[email][smtp_username]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}SMTP password{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.email.smtp_password}" name="config[email][smtp_password]" />
                </td>
            </tr>
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}eMail sender address{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}From (eMail){/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.email.from}" name="config[email][from]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}From (name){/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.email.from_name}" name="config[email][from_name]" />
                </td>
            </tr>
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Send Test Mail{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Recipient (email){/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.email.from}" name="config[email][from]" />
                    <input type="button" class="ButtonOrange" value="Send Mail" />
                </td>
            </tr>
        </table>
   </div>
   {* #### TAB PAGE - LOGIN SETTINGS #### *}
   <div class="tab-page">
       <h2 class="tab">{t}Login{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Login{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Login method{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <select name="config[login][login_method]" class="input_text">
                        <option value="nick" {if $config.login.login_method == 'nick'}selected="selected"{/if}>{t}By nickname{/t}</option>
                        <option value="email" {if $config.login.login_method == 'email'}selected="selected"{/if}>{t}By eMail{/t}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Remember me time{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.login.remember_me_time}" name="config[login][remember_me_time]" />&nbsp; days
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Maximum login attempts{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.login.max_login_attempts}" name="config[login][max_login_attempts]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Login ban minutes{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.login.login_ban_minutes}" name="config[login][login_ban_minutes]" />
                </td>
            </tr>
            <tr>
                <td class="td_header_small" colspan="2">
                    {t}Password Settings{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Minimum password length{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.login.min_pass_length}" name="config[login][min_pass_length]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Encryption method{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <select name="config[login][encryption]" class="input_text">
                        <option value="md5" {if $config.login.encryption == 'md5'}selected="selected"{/if}>{t}MD5 (faster){/t}</option>
                        <option value="sha1" {if $config.login.encryption == 'sha1'}selected="selected"{/if}>{t}SHA1 (more secure){/t}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Salt{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.login.salt}" name="config[login][salt]" />
                </td>
            </tr>
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Session Parameters{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Use cookies{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[session][use_cookies]" {if $config.session.use_cookies == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[session][use_cookies]" {if $config.session.use_cookies == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Use only cookies{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[session][use_cookies_only]" {if $config.session.use_cookies_only == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[session][use_cookies_only]" {if $config.session.use_cookies_only == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Session expire time{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.session.session_expire_time}" name="config[session][session_expire_time]" />&nbsp; minutes
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Session name{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.session.session_name}" name="config[session][session_name]" />
                </td>
            </tr>   
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}OpenID {/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Trust Root{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.open_id.openid_trustroot}" name="config[open_id][openid_trustroot]" />
                </td>                
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Add to Login Box{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[open_id][openid_showloginbox]" {if $config.open_id.openid_showloginbox == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[open_id][openid_showloginbox]" {if $config.open_id.openid_showloginbox == 0}checked="checked"{/if} /> {t}no{/t}
                </td>                
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Add to Comments{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[open_id][openid_showcommentsbox]" {if $config.open_id.showcommentsbox == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[open_id][openid_showcommentsbox]" {if $config.open_id.showcommentsbox == 0}checked="checked"{/if} /> {t}no{/t}
                </td>                
            </tr>
            
                     
        </table>
   </div>
  {* #### TAB PAGE - DEVELOPER SETTINGS #### *}
   <div class="tab-page">
       <h2 class="tab">{t}Developers{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Developer Settings{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Help Edit Mode{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[error][help_edit_mode]" {if $config.error.help_edit_mode == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[error][help_edit_mode]" {if $config.error.help_edit_mode == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}GZip Compression{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>When you turn this setting on, your pages are served compressed to your clients - making your site quicker:<br/></small>
                    <input type="radio" value="1" name="config[error][compression]" {if $config.error.compression == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[error][compression]" {if $config.error.compression == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Error Reporting{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Suppress Errors{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[error][suppress_errors]" {if $config.error.suppress_errors == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[error][suppress_errors]" {if $config.error.suppress_errors == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Debugging{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[error][debug]" {if $config.error.debug == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[error][debug]" {if $config.error.debug == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Debugging in a popup{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[error][debug_popup]" {if $config.error.debug_popup == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[error][debug_popup]" {if $config.error.debug_popup == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
        </table>
   </div>
   {* #### TAB PAGE - DATE AND TIME  #### *}
   <div class="tab-page">
       <h2 class="tab">{t}Date & Time{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Date & Timezone Settings{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Dateformat{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                <small>Format in which the date is presented. Example: d-m-Y. For How-To Read: http://us2.php.net/manual/en/function.date.php<br /></small>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Default Timezone{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small><br /></small>
                    <input class="input_text" type="text" value="{$config.cache.cache_lifetime}" name="config[cache][cache_lifetime]" />&nbsp; seconds
                    <br /> <small>set to -1 if developers mode on</small>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Enable Daylight Savings{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                <small>If you enable this, the Daylight Savings Time corrects the time for the above {t}Default Timezone{/t}<br /></small>
                </td>
            </tr>
        </table>
   </div>   
   {* #### TAB PAGE - CACHE SETTINGS #### *}
   <div class="tab-page">
       <h2 class="tab">{t}Cache{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Cache Settings{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Cache On{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input type="radio" value="1" name="config[cache][caching]" {if $config.cache.caching == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[cache][caching]" {if $config.cache.caching == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Cache Lifetime{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{$config.cache.cache_lifetime}" name="config[cache][cache_lifetime]" />&nbsp; seconds
                    <br /> <small>set to -1 if developers mode on</small>
                </td>
            </tr>
        </table>
   </div>
   {* #### TAB PAGE - Auto-Updater #### *}
   <div class="tab-page">
       <h2 class="tab">{t}Updates{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Updates{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Check for Updates{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>Clansuite can check with [clansuite.com] whether you are running the latest version. Results will be shown on the admin control panel with links to download the updated versions.<br /></small>
                    <small>When you enable this, Clansuite will automagically check for updates to the CMS and Modules?<br /></small>
                    <input type="radio" value="1" name="config[cache][caching]" {if $config.cache.caching == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[cache][caching]" {if $config.cache.caching == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>    
        </table>
   </div>
</div>
<br />
<div style="text-align:center">
    <input type="submit" class="ButtonGreen" value="{t}Change settings{/t}" name="submit" />
</div>
</form>
{* #### Init TabPane #### *}
<script type="text/javascript">setupAllTabs();</script>