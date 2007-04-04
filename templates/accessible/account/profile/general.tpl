{*
{doc_raw}
    <script src="{$www_root_tpl_core}/javascript/prototype/prototype.js" type="text/javascript"></script>
    <script src="{$www_root_tpl_core}/javascript/scriptaculous/scriptaculous.js" type="text/javascript"></script>
    <script src="{$www_root_tpl_core}/javascript/smarty_ajax.js" type="text/javascript"></script>
    <script type="text/javascript" src="{$www_root_tpl_core}/javascript/tablegrid.js"></script>
    <link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/css/tablegrid.css" />
{/doc_raw}
*}
<div class="general">
    <div class="picture_infos">
        <div class="nick">{$info.nick}</div>
        <div class="picture">
            {if $info.type == url}
                <img src="{$info.location}" alt="{$info.nick}" class="the_pic"/>
            {elseif $info.type == upload}
                <img src="index.php?mod=account&sub=general&action=show_avatar&id={$info.user_id.0}" alt="{$info.nick}"  class="the_pic"/>
            {else}
                <img src="{$www_root_tpl}/images/no_avatar.jpg" alt="{translate}No avatar{/translate}: {$info.nick}"  class="the_pic"/>
            {/if}
        </div>
    </div>
    <div class="personal_infos">
        <div class="box_heading">{translate}Personal infos{/translate}</div>
        <dl>
            <dt>{translate}First name{/translate}:</dt>
                <dd>{$info.first_name}</dd>
            <dt>{translate}Last name{/translate}:</dt>
                <dd>{$info.last_name}</dd>
            <dt>{translate}Gender{/translate}:</dt>
                <dd>{$info.gender}</dd>
            <dt>{translate}Birthday{/translate}:</dt>
                <dd>{$info.birthday|date_format:"%d.%m.%Y"}</dd>
            <dt>{translate}Height{/translate}:</dt>
                <dd>{$info.height}</dd>
        </dl>
    </div>
    <div class="location">
        <div class="box_heading">{translate}Location{/translate}</div>
        <dl>
            <dt>{translate}Country{/translate}:</dt>
                <dd>
                    {if $info.country == 'not_specified'}
                        <span class="not_specified">{translate}not specified{/translate}</span>
                    {else}
                        <img src="{$www_root_tpl_core}/images/countries/{$info.country|strtolower}.png" alt="{$info.country}" class="country_picture"/>
                        &nbsp;{$info.country}
                    {/if}
                </dd>
            <dt>{translate}State{/translate}:</dt>
                <dd>{$info.state|wordwrap:40:"<br />":true}</dd>
            <dt>{translate}City{/translate}:</dt>
                <dd>{$info.city}</dd>
            <dt>{translate}ZIP Code{/translate}:</dt>
                <dd>{$info.zipcode}</dd>
            <dt>{translate}Address{/translate}:</dt>
                <dd>{$info.address}</dd>
        </dl>
    </div>
    <div class="miscellaneous">
        <div class="box_heading">{translate}Miscellaneous{/translate}</div>
        <dl>
            <dt>{translate}Homepage{/translate}:</dt>
                <dd><a href="{$info.homepage}" target="_blank">{$info.homepage}</a></dd>
            <dt>{translate}ICQ{/translate}:</dt>
                <dd>{$info.icq}</dd>
            <dt>{translate}MSN{/translate}:</dt>
                <dd>{$info.msn}</dd>
            <dt>{translate}Skype{/translate}:</dt>
                <dd>{$info.skype}</dd>
            <dt>{translate}Phone{/translate}:</dt>
                <dd>{$info.phone}</dd>
            <dt>{translate}Mobile{/translate}:</dt>
                <dd>{$info.mobile}</dd>
            <dt>{translate}Custom text{/translate}:</dt>
                <dd>{$info.custom_text}</dd>
        </dl>
    </div>


<table cellpadding="0" cellspacing="0" border="0" id="profile" width="100%" style="display: none">
    <tr>
        <td class="td_header" width="1%">
            {translate}Icon{/translate}
        </td>
        <td class="td_header" width="1%">
            {translate}Fields{/translate}
        </td>
        <td class="td_header">
            {translate}Values{/translate}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/page_edit.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}First name{/translate}
        </td>
        <td class="cell1" id="first_name">
            {$info.first_name}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/page_edit.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}Last name{/translate}
        </td>
        <td class="cell1" id="last_name">
            {$info.last_name}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/cake.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}Birthday{/translate}
        </td>
        <td class="cell1" id="birthday">
            {$info.birthday|date_format:"%d.%m.%Y"}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/male.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}Gender{/translate}
        </td>
        <td class="cell1" id="gender">
            {$info.gender}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/text_linespacing.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}Height{/translate}
        </td>
        <td class="cell1" id="height">
            {$info.height}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/book.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}Address{/translate}
        </td>
        <td class="cell1" id="address">
            {$info.address}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/vcard.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}ZIP code{/translate}
        </td>
        <td class="cell1" id="zipcode">
            {$info.zipcode}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/house.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}City{/translate}
        </td>
        <td class="cell1" id="city">
            {$info.city}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/world.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}Country{/translate}
        </td>
        <td class="cell1" id="country">
            {$info.country}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/html.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}Homepage{/translate}
        </td>
        <td class="cell1" id="homepage">
            {$info.homepage}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/icq.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}ICQ{/translate}
        </td>
        <td class="cell1" id="icq">
            {$info.icq}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/msn.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}MSN{/translate}
        </td>
        <td class="cell1" id="msn">
            {$info.msn}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/skype.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}Skype{/translate}
        </td>
        <td class="cell1" id="skype">
            {$info.skype}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/telephone.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}Phone{/translate}
        </td>
        <td class="cell1" id="phone">
            {$info.phone}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/phone.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}Mobile{/translate}
        </td>
        <td class="cell1" id="mobile">
            {$info.mobile}
        </td>
    </tr>
    <tr>
        <td class="cell2">
            <div class="profile_icon"><img src="{$www_root_tpl}/images/icons/application_view_list.png" border="0" width="16" height="16" /></div>
        </td>
        <td class="cell2">
            {translate}Custom Text{/translate}
        </td>
        <td class="cell1">
            <div id="custom_text">
                {$info.custom_text}
            </div>
        </td>
    </tr>
</table>
{*
<script type="text/javascript">new TableGrid('profile', '2', 'index.php?mod=account&sub=profile&action=ajax_update', 'profile_editcell');</script>
{literal}
 <script type="text/javascript">
    new Ajax.InPlaceEditor('custom_text',
                          'index.php?mod=account&sub=profile&action=ajax_update&cell=custom_text',
                          {handleLineBreaks: false, okText: '{/literal}Save{literal}',hoverText: '{/literal}Click to Edit{literal}',cancelText:'{/literal}Cancel{literal}',okButtonClass: 'ButtonGreen',cancelButtonClass: 'ButtonGrey',rows:15,cols:48, loadTextURL:'index.php?mod=account&sub=profile&action=get_custom_text'});
 </script>
{/literal}
*}
{* AJAX Needed *}
    <div class="options">
        <div class="edit_button">
            {if $smarty.session.user.user_id == $info.user_id.0}
                <input class="ButtonGreen" type="button" value="{translate}Edit my profile{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=account&amp;sub=general&amp;action=edit", options: {method: "get"}}, {className: "alphacube", width:450, height: 550});{/literal}' />
            {/if}
            {if $smarty.session.rights.access_controlcenter == 1 && $smarty.session.rights.edit_generals}
                <input class="ButtonGreen" type="button" value="{translate}Edit profile (admin){/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=admin&amp;sub=users&amp;action=edit_generals", options: {method: "get"}}, {className: "alphacube", width:450, height: 400});{/literal}' />
            {/if}
        </div>
    </div>
</div>