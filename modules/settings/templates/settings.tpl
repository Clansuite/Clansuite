{* {$config|@var_dump} *}

{*

LOCALIZATION
---------------
Long Date/time format:
Format of full date and time display.

Short Date/time format:
Format of short date and time display.

Long Date format:
Format of full date display.

Short Date format:
Format of short date display. 12-31-06, 31-12-06 oder mit / 12/31/06, 31/12/06

Time format:
Format of time display.	 19:00 PM oder 07:00PM

Time Zone: GMT+1

CRONSJOBS / SCHEDULER
---------------------
Scheduler run frequency (minutes):


SYSTEM HEALTH
-------------

1) .htaccess Health (check if htaccess exists in essential paths)

2) check template permissions

3) email template permissions

4) general directory permissons

*}

{*
5) GOOGLE MAPS API
<tbody><tr><td class="text" colspan="2"> </td></tr><tr><td width="440" colspan="2" class="maindescbig"><b>GOOGLE MAPS API SETTINGS</b></td></tr><tr><td height="4" colspan="2" class="small"><hr size="1"/></td></tr><tr><td colspan="2"><table width="100%" cellspacing="3" cellpadding="3" border="0"><tbody><tr height="20"><td class="maindescbig">Enable google maps:<span class="maindesc"/></td><td width="200" align="right" class="maindescbig"><input type="checkbox" checked="" name="in_gmap_enable"/> <a href="javascript:var winpops=window.open('http://www.webscribble.com/products/webnetwork/documentation/1.0/gmap_api.htm#1','1522','scrollbars=yes,menubar=no,resizable=yes,location=no,screenX=50,screenY=100,width=520,height=600')"><img border="0" src="images/help.gif"/></a></td></tr><tr height="20"><td class="maindescbig">Google maps API key:<span class="maindesc"/></td><td width="200" align="right" class="maindescbig"><input type="text" style="width: 150px;" value="ABQIAAAAsQcrfZak2biaSIc1i-YTmBQ-rFo6-mNicSPX2ogSS-kustWM6xTSxkinDndVUadgH9E8lBZPjQ_TQQ" name="in_gmap_api_key"/> <a href="javascript:var winpops=window.open('http://www.webscribble.com/products/webnetwork/documentation/1.0/gmap_api.htm#2','1522','scrollbars=yes,menubar=no,resizable=yes,location=no,screenX=50,screenY=100,width=520,height=600')"><img border="0" src="images/help.gif"/></a></td></tr></tbody></table></td></tr>
	<tr>
		<td height="6" bgcolor="#ffffff" colspan="2"><spacer height="6" width="100%" type="block"/></td>
	</tr>
	<tr>
		<td height="4" colspan="2"><hr size="1"/></td>
	</tr>
	<tr>
		<td align="right" colspan="2" class="text"><br/>
			<input type="image" border="0" onclick="return confirm('Save settings?');" alt="Save" src="images/save.gif"/> 
			<a href="main_frame.php?plugin=system_settings_desc"><img border="0" src="images/cancel.gif" alt="Cancel"/></a> 
            <br/><br/>
		</td>
	</tr>

</tbody>

6) EMOTICONS MANAGER

*}
{assign var=reduce value=$templatepath|strrpos:"/templates"}
{assign var=tplpath value=$templatepath|@truncate:$reduce:"":true}

{doc_raw}
    {* Tabs with Tabpane *}
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/css/tabpane/luna.css" />
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/tabpane/tabpane.js"></script>
{/doc_raw}

<form action="index.php?mod=admin&amp;sub=settings&amp;action=update" method="post" accept-charset="UTF-8">

<div class="tab-pane" id="tab-pane-1">
   {* #### TAB PAGE - STANDARD SETTINGS #### *}
   <div class="tab-page">
       <h2 class="tab">{t}Standard{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

             {* /---------------------------------------------------
               |
               |     Tab: Standard >> General Settings
               |
               \--------------------------------------------------- *}

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
                    <small>{t}Name your website:{/t}</small><br />
                    <input class="input_text" type="text" value="{$config.template.std_page_title}" name="config[template][std_page_title]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Favicon{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}You may provide an favicon for our website:{/t}</small><br />
                    <input class="input_text" type="text" value="{if isset($config.template.favicon)}{$config.template.favicon}{/if}" name="config[template][favicon]" />
                    <br /> <strong>todo: Upload and Chooser</strong> <small>{$www_root_themes}/images/</small>
                </td>
            </tr>

             {* /---------------------------------------------------
               |
               |     Tab: Standard >> Standard Template with Files
               |
               \--------------------------------------------------- *}

            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Standard Template with Files{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Default Theme{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}Select the default Theme to load, when a guest is visiting your site the first time.{/t}</small><br />
                    <input class="input_text" type="text" value="{$config.template.theme}" name="config[template][theme]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Default Layout Filename{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}Select the default Layout to load.{/t}</small><br />
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

            {* /---------------------------------------------------
               |
               |     Tab: Standard >> Default Module / Action
               |
               \--------------------------------------------------- *}

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
                    <small>{t}Select the default Module to load, when calling "index.php" or "http://yoursite.com".{/t}</small><br />
                    <input class="input_text" type="text" value="{$config.defaults.default_module}" name="config[defaults][default_module]" />
                 </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Standard module action{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}Select the default Action to load, when calling "index.php" or "http://yoursite.com".{/t}</small><br />
                    <input class="input_text" type="text" value="{$config.defaults.default_action}" name="config[defaults][default_action]" />
                </td>
            </tr>

            {* /---------------------------------------------------
               |
               |     Tab: Standard >> URL Switches
               |
               \--------------------------------------------------- *}

            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Switches via URL Parameters{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Theme Switching{/t}
                    <br/>
                    ?theme=
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}You may want to turn on theme-switching via URL. When this is activated and parameter theme is appended to your URL like "?theme=xy", the "xy"-theme it will be used for display.{/t}</small><br />
                    <input type="radio" value="1" name="config[switches][themeswitch_via_url]" {if isset($config.switches.themeswitch_via_url) && $config.switches.themeswitch_via_url == 1}checked="checked"{/if} /> {t}activated{/t}
                    <input type="radio" value="0" name="config[switches][themeswitch_via_url]" {if isset($config.switches.themeswitch_via_url) && $config.switches.themeswitch_via_url == 0}checked="checked"{/if} /> {t}deactivated{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Language Switching{/t}
                     <br/>
                    ?lang=
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}You may want to turn on language-switching via URL. When this is activated and parameter lang is appended to your URL like "?lang=de", the german language (de_DE) will be used.{/t}</small><br />
                    <input type="radio" value="1" name="config['switches']['languageswitch_via_url']" {if isset($config.switches.languageswitch_via_url) && $config.switches.languageswitch_via_url == 1}checked="checked"{/if} /> {t}activated{/t}
                    <input type="radio" value="0" name="config['switches']['languageswitch_via_url']" {if isset($config.switches.languageswitch_via_url) && $config.switches.languageswitch_via_url == 0}checked="checked"{/if} /> {t}deactivated{/t}
                </td>
            </tr>



            {* /---------------------------------------------------
               |
               |     Tab: Standard >> Maintenance Mode
               |
               \--------------------------------------------------- *}

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
                    <small>{t}You may want to turn off your site while performing updates or other types of maintenance.{/t}</small><br />
                    <input type="radio" value="1" name="config[maintenance][maintenance]" {if $config.maintenance.maintenance == 1}checked="checked"{/if} /> {t}activated{/t}
                    <input type="radio" value="0" name="config[maintenance][maintenance]" {if $config.maintenance.maintenance == 0}checked="checked"{/if} /> {t}deactivated{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Reason for maintenance{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}You may provide a short description to your members explaining why your site has been switched off:{/t}</small><br />
                    <textarea name="config[maintenance][maintenance_reason]" cols="30" rows="10" class="input_textarea">{$config.maintenance.maintenance_reason}</textarea>
                </td>
            </tr>
        </table>
   </div>


    {* #### TAB PAGE - META TAGSINFOS SETTINGS #### *}
    <div class="tab-page">
        <h2 class="tab">{t}Meta Tags{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

            {* /---------------------------------------------------
               |
               |     Tab: Meta Tags >> Define Meta Tags
               |
               \--------------------------------------------------- *}

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
                    <small>{t}Provide some comma separated keywords which describe your website. This will help search engines:{/t}</small><br />
                    <input class="input_text" type="text" value="{$config.meta.keywords}" name="config[meta][keywords]" />
                </td>
            </tr>

            {* /---------------------------------------------------
               |
               |     Tab: Meta Tags >> Dublin Core
               |
               \--------------------------------------------------- *}

            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Define Dublin Core Metadata Elements{/t}
                </td>
            </tr>

            {* /---------------------------------------------------
               |
               |     Tab: Meta Tags >> SEO
               |
               \--------------------------------------------------- *}

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
                    <small>{t}When your Webserver has Mod Rewrite enabled, then you can turn this setting on to make your URLs more user friendly:{/t}</small><br />
                    <input type="radio" value="1" name="config[webserver][mod_rewrite]" {if $config.webserver.mod_rewrite == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[webserver][mod_rewrite]" {if $config.webserver.mod_rewrite == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
        </table>
    </div>


   {* #### TAB PAGE - Language SETTINGS #### *}
   <div class="tab-page">
       <h2 class="tab">{t}Language{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

            {* /---------------------------------------------------
               |
               |     Tab: Language >> Default Language / Charset
               |
               \--------------------------------------------------- *}

            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Default Language / Charset{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Standard Language{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}Language Selection is based on browser-detection, but you can specify a default language as fallback.{/t}</small><br />
                    <input class="input_text" type="text" value="{if isset($config.language.language)}{$config.language.language}{/if}" name="config[language][language]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Charset{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{if isset($config.language.charset)}{$config.language.charset}{/if}" name="config[language][charset]" />
                </td>
            </tr>
        </tr>
    </table>
    </div>


   {* #### TAB PAGE - EMAIL SETTINGS #### *}
   <div class="tab-page">
       <h2 class="tab">{t}Email{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

            {* /---------------------------------------------------
               |
               |     Tab: Email >> Mail Server Configuration
               |
               \--------------------------------------------------- *}

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
                        <option value="mail"     {if isset($config.email.mailmethod) && $config.email.mailmethod == 'mail'}selected="selected"{/if}>{t}Normal{/t}</option>
                        <option value="smtp"     {if isset($config.email.mailmethod) && $config.email.mailmethod == 'smtp'}selected="selected"{/if}>{t}SMTP{/t}</option>
                        <option value="sendmail" {if isset($config.email.mailmethod) && $config.email.mailmethod == 'sendmail'}selected="selected"{/if}>{t}Sendmail{/t}</option>
                        <option value="exim"     {if isset($config.email.mailmethod) && $config.email.mailmethod == 'exim'}selected="selected"{/if}>{t}Exim{/t}</option>
                        <option value="qmail"    {if isset($config.email.mailmethod) && $config.email.mailmethod == 'qmail'}selected="selected"{/if}>{t}Qmail{/t}</option>
                        <option value="postfix"  {if isset($config.email.mailmethod) && $config.email.mailmethod == 'postfix'}selected="selected"{/if}>{t}PostFix{/t}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Mailerhost{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{if isset($config.email.mailerhost)}{$config.email.mailerhost}{/if}" name="config[email][mailerhost]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Mailerport{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{if isset($config.email.mailerport)}{$config.email.mailerport}{/if}" name="config[email][mailerport]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Mail encryption{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <select name="config[email][mailencryption]" class="input_text">
                        <option value="SWIFT_OPEN" {if isset($config.email.mailencryption) && $config.email.mailencryption == 'SWIFT_OPEN'}selected="selected"{/if}>{t}No encryption{/t}</option>
                        <option value="SWIFT_SSL"  {if isset($config.email.mailencryption) && $config.email.mailencryption == 'SWIFT_SSL'}selected="selected"{/if}>{t}SSL encryption{/t}</option>
                        <option value="SWIFT_TLS"  {if isset($config.email.mailencryption) && $config.email.mailencryption == 'SWIFT_TLS'}selected="selected"{/if}>{t}TLS/SSL encryption{/t}</option>
                    </select>
                </td>
            </tr>

            {* /---------------------------------------------------
               |
               |     Tab: Email >> SMTP Authentication
               |
               \--------------------------------------------------- *}


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
                    <input class="input_text" type="text" value="{if isset($config.email.smtp_username)}{$config.email.smtp_username}{/if}" name="config[email][smtp_username]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}SMTP password{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{if isset($config.email.smtp_password)}{$config.email.smtp_password}{/if}" name="config[email][smtp_password]" />
                </td>
            </tr>

            {* /---------------------------------------------------
               |
               |     Tab: Email >> Email sender address
               |
               \--------------------------------------------------- *}

            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Email sender address{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}From (eMail){/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}Bitte geben Sie die Emailadresse des Systems bzw. des Systemadministrators ein;{/t}<br /></small>
                    <input class="input_text" type="text" value="{if isset($config.email.from)}{$config.email.from}{/if}" name="config[email][from]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}From (name){/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}Bitte geben Sie den vollen Namen des Absenders des Systems bzw. des Systemadministrators ein;{/t}<br /></small>
                    <input class="input_text" type="text" value="{if isset($config.email.from_name)}{$config.email.from_name}{/if}" name="config[email][from_name]" />
                </td>
            </tr>


            {* /---------------------------------------------------
               |
               |     Tab: Email >> Send Test Mail
               |
               \--------------------------------------------------- *}

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

            {* /---------------------------------------------------
               |
               |     Tab: Login >> Login
               |
               \--------------------------------------------------- *}


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
                    <small>{t}Choose the login method: You may select Login by Nickname or Email.{/t}</small><br />
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
                    <small>{t}Set the Number of days for your login cookie to be stored on your computer.{/t}</small><br />
                    <input class="input_text" type="text" value="{$config.login.remember_me_time}" name="config[login][remember_me_time]" />&nbsp; days
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Maximum login attempts{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}Set the number of Login Attempts. The clients IP will get banned temporarily, when attempted to login in this number of times.{/t}</small><br />
                    <input class="input_text" type="text" value="{$config.login.max_login_attempts}" name="config[login][max_login_attempts]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Login ban minutes{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}Choose Number of minutes to ban a certain ip, after too many login attempts.{/t}</small><br />
                    <input class="input_text" type="text" value="{$config.login.login_ban_minutes}" name="config[login][login_ban_minutes]" />
                </td>
            </tr>

            {* /---------------------------------------------------
               |
               |     Tab: Login >> Password Settings
               |
               \--------------------------------------------------- *}

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
                    <small>{t}Select the password encryption method: md5 or sha1.{/t}</small><br />
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

            {* /---------------------------------------------------
               |
               |     Tab: Login >> Session Parameters
               |
               \--------------------------------------------------- *}

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
                    <input class="input_text" type="text" value="{if isset($config.session.session_expire_time)} {$config.session.session_expire_time} {/if}" name="config[session][session_expire_time]" />&nbsp; minutes
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Session name{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{if isset($config.session.session_name)} {$config.session.session_name} {/if}" name="config[session][session_name]" />
                </td>
            </tr>

            {* /---------------------------------------------------
               |
               |     Tab: Login >> OpenID
               |
               \--------------------------------------------------- *}

            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}OpenID{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Trust Root{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <input class="input_text" type="text" value="{if isset($config.openid.openid_trustroot)}{$config.openid.openid_trustroot}{/if}" name="config[openid][openid_trustroot]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Add to Login Box{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}When you enable this setting, the OpenID login fields will be displayed in the login box:{/t}</small><br />
                    <input type="radio" value="1" name="config[openid][openid_showloginbox]" {if isset($config.openid.openid_showloginbox) && $config.openid.openid_showloginbox == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[openid][openid_showloginbox]" {if isset($config.openid.openid_showloginbox) && $config.openid.openid_showloginbox == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Add to Comments{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                   <small>{t}When you enable this setting, the OpenID login fields will be displayed in the comments area's:{/t}</small><br />
                    <input type="radio" value="1" name="config[openid][openid_showcommentsbox]" {if isset($config.openid.showcommentsbox) && $config.openid.showcommentsbox == 1} checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[openid][openid_showcommentsbox]" {if isset($config.openid.showcommentsbox) && $config.openid.showcommentsbox == 0} checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>

            {* /---------------------------------------------------
               |
               |     Tab: Login >> Registration
               |
               \--------------------------------------------------- *}

            <tr>
                <td class="td_header_small"  colspan="2">
                    {t}Registration{/t}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Registration & Usage Terms{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}You may provide registration terms, which are displayed to your upcoming members during the registration process:{/t}</small><br />
                    <textarea name="config[login][registration_term]" cols="30" rows="10" class="input_textarea">{if isset($config.login.registration_term)}{$config.login.registration_term}{/if}</textarea>
                </td>
            </tr>

            {* /---------------------------------------------------
               |
               |     Tab: Login >> reCaptcha
               |
               \--------------------------------------------------- *}

            <tr>
                <td class="td_header_small"  colspan="2">
                    reCAPTCHA
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    Get a reCAPTCHA key
                </td>
                <td class="cell1" style="padding: 3px">
                    {php}
                        # Load Recaptcha Library
                        require (ROOT_MOD . 'recaptcha/library/recaptchalib.php');

                        # Build Link to get an free reCAPTCHA key
                        echo '<a href="';
                        echo recaptcha_get_signup_url($_SERVER['SERVER_NAME'], 'www.clansuite.com');
                        echo '">free reCAPTCHA key</a>';
                    {/php}
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}reCAPTCHA Public Key{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}Please provide your public key.{/t}</small><br />
                    <input size=50 class="input_text" type="text" name="config[recaptcha][public_key]">{if isset($config.recaptcha.public_key)} {$config.recaptcha.public_key} {/if}</textarea>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}reCAPTCHA Private Key{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}Please provide your private key.{/t}</small><br />
                   <input size=50 class="input_text" type="text" name="config[recaptcha][private_key]">{if isset($config.recaptcha.private_key)} {$config.recaptcha.private_key} {/if}</textarea>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Add to Register User Dialog{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}When you enable this setting, the reCAPTCHA will be displayed in the registration box:{/t}</small><br />
                    <input type="radio" value="1" name="config[recaptcha][showinregisterbox]" {if isset($config.recaptcha.showinregisterbox) && $config.recaptcha.showinregisterbox == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[recaptcha][showinregisterbox]" {if isset($config.recaptcha.showinregisterbox) && $config.recaptcha.showinregisterbox == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
        </table>
   </div>


  {* #### TAB PAGE - DEVELOPER SETTINGS #### *}
   <div class="tab-page">
       <h2 class="tab">{t}Developers{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

            {* /---------------------------------------------------
               |
               |     Tab: Developers >> Developer Settings
               |
               \--------------------------------------------------- *}

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
                    <small>{t}When you turn this setting on, your pages are served compressed to your clients - making your site quicker:{/t}</small><br />
                    <input type="radio" value="1" name="config[error][compression]" {if $config.error.compression == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[error][compression]" {if $config.error.compression == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>

            {* /---------------------------------------------------
               |
               |     Tab: Developers >> Error Reporting
               |
               \--------------------------------------------------- *}

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
            <!--
             tr>
                <td class="cell2" width="15%">
                    {t}Report Errors{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}When you turn this setting on, your errors are reported back to the Clansuite Community for bug resolving:{/t}</small><br />
                    <input type="radio" value="1" name="config[error][reporthome_errors]" {if $config.error.reporthome_errors == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[error][reporthome_errors]" {if $config.error.reporthome_errors == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
            -->
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

            {* /---------------------------------------------------
               |
               |     Tab: Date & Time >> Date & Timezone Settings
               |
               \--------------------------------------------------- *}

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
                    <small>{t}You may provide the format in which dates are displayed. Example: d-m-Y. For an How-To read: http://us2.php.net/manual/en/function.date.php{/t}</small><br />
                    <input class="input_text" type="text" value="{if isset($config.locale.dateformat)}{$config.locale.dateformat}{/if}" name="config[locale][dateformat]" />
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Default Timezone{/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}Select the default timzone:{/t}</small><br />
                    <select name="config[locale][timezone]" class="input_text">
                        <option value="md5" {if isset($config.locale.timezone) && $config.locale.timezone == 'Berlin'}selected="selected"{/if}>Berlin</option>
                        <option value="sha1" {if isset($config.locale.timezone) && $config.locale.timezone == '123'}selected="selected"{/if}>123</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="cell2" width="15%">
                    {t}Daylight Savings Time (Summertime){/t}
                </td>
                <td class="cell1" style="padding: 3px">
                    <small>{t}If you enable this, the Daylight Savings Time (or Summertime) corrects the time for the above {t}Default Timezone{/t}. The clock is advanced an hour, so that afternoons have more daylight and mornings have less:{/t}</small><br />
                    <input type="radio" value="1" name="config[locale][daylight_saving]" {if isset($config.locale.daylight_saving) && $config.locale.daylight_saving == 1}checked="checked"{/if} /> {t}yes [Summertime = Default Timezone + 1 hour]{/t}
                    <input type="radio" value="0" name="config[locale][daylight_saving]" {if isset($config.locale.daylight_saving) && $config.locale.daylight_saving == 0}checked="checked"{/if} /> {t}no [Normal Time = Default Timezone - 1 hour]{/t}
                </td>
            </tr>
        </table>
   </div>

   {* #### TAB PAGE - CACHE SETTINGS #### *}
   <div class="tab-page">
       <h2 class="tab">{t}Cache{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

            {* /---------------------------------------------------
               |
               |     Tab: Cache >> Cache Settings
               |
               \--------------------------------------------------- *}

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
                    <br /> <small>{t}set to -1 if developers mode on{/t}</small>
                </td>
            </tr>
        </table>
   </div>
   {* #### TAB PAGE - Auto-Updater #### *}
   <div class="tab-page">
       <h2 class="tab">{t}Updates{/t}</h2>
        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

            {* /---------------------------------------------------
               |
               |     Tab: Updates >> Updates
               |
               \--------------------------------------------------- *}

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
                    <small>{t}Clansuite can check with [clansuite.com] whether you are running the latest version. Results will be shown on the admin control panel with links to download the updated versions.{/t}</small><br />
                    <small>{t}When you enable this, Clansuite will automagically check for updates to the CMS and Modules?{/t}</small><br />
                    <input type="radio" value="1" name="config[cache][caching]" {if $config.cache.caching == 1}checked="checked"{/if} /> {t}yes{/t}
                    <input type="radio" value="0" name="config[cache][caching]" {if $config.cache.caching == 0}checked="checked"{/if} /> {t}no{/t}
                </td>
            </tr>
        </table>
   </div>
</div>
<br />
<div style="text-align:center">
    <input type="submit" class="ButtonGreen" value="{t}Save Settings{/t}" name="submit" />
</div>
</form>

{* #### Init TabPane #### *}

<script type="text/javascript">setupAllTabs();</script>