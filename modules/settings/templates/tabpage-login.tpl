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
    <tr>
        <td class="cell2" width="15%">
            {t}Filter: Session Security - check_browser{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.session.check_browser)} {$config.session.check_browser} {/if}" name="config[session][check_browser]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Filter: Session Security -  check_host{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.session.check_host)} {$config.session.check_host} {/if}" name="config[session][check_host]" />
        </td>
    </tr>
    <tr>
        <td class="cell2" width="15%">
            {t}Filter: Session Security - check_ip{/t}
        </td>
        <td class="cell1" style="padding: 3px">
            <input class="input_text" type="text" value="{if isset($config.session.check_ip)} {$config.session.check_ip} {/if}" name="config[session][check_ip]" />
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

</table>