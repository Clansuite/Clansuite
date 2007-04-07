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

{* AJAX Needed *}
    <div class="options">
        <div class="edit_button">
            {if $smarty.session.user.user_id == $info.user_id.0}
                <input class="ButtonGreen" type="button" value="{translate}Edit my profile{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=account&amp;sub=general&amp;action=edit", options: {method: "get"}}, {className: "alphacube", width:650, height: 550});{/literal}' />
            {/if}
            {if $smarty.session.rights.cc_access == 1 && $smarty.session.rights.cc_edit_generals}
                <input class="ButtonGreen" type="button" value="{translate}Edit profile (admin){/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=admin&amp;sub=users&amp;action=cc_edit_generals", options: {method: "get"}}, {className: "alphacube", width:450, height: 400});{/literal}' />
            {/if}
        </div>
    </div>
</div>