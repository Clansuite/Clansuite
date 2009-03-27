{*
{move_to}
    <script src="{$www_root_themes_core}/javascript/prototype/prototype.js" type="text/javascript"></script>
    <script src="{$www_root_themes_core}/javascript/scriptaculous/scriptaculous.js" type="text/javascript"></script>
    <script src="{$www_root_themes_core}/javascript/smarty_ajax.js" type="text/javascript"></script>
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/tablegrid.js"></script>
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/css/tablegrid.css" />
{/move_to}
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
                <img src="{$www_root_theme}/images/no_avatar.jpg" alt="{t}No avatar{/t}: {$info.nick}"  class="the_pic"/>
            {/if}
        </div>
    </div>
    <div class="personal_infos">
        <div class="box_heading">{t}Personal infos{/t}</div>
        <dl>
            <dt>{t}First name{/t}:</dt>
                <dd>{$info.first_name}</dd>
            <dt>{t}Last name{/t}:</dt>
                <dd>{$info.last_name}</dd>
            <dt>{t}Gender{/t}:</dt>
                <dd>{$info.gender}</dd>
            <dt>{t}Birthday{/t}:</dt>
                <dd>{$info.birthday|date_format:"%d.%m.%Y"}</dd>
            <dt>{t}Height{/t}:</dt>
                <dd>{$info.height}</dd>
        </dl>
    </div>
    <div class="location">
        <div class="box_heading">{t}Location{/t}</div>
        <dl>
            <dt>{t}Country{/t}:</dt>
                <dd>
                    {if $info.country == 'not_specified'}
                        <span class="not_specified">{t}not specified{/t}</span>
                    {else}
                        <img src="{$www_root_themes_core}/images/countries/{$info.country|strtolower}.png" alt="{$info.country}" class="country_picture"/>
                        &nbsp;{$info.country}
                    {/if}
                </dd>
            <dt>{t}State{/t}:</dt>
                <dd>{$info.state|wordwrap:40:"<br />":true}</dd>
            <dt>{t}City{/t}:</dt>
                <dd>{$info.city}</dd>
            <dt>{t}ZIP Code{/t}:</dt>
                <dd>{$info.zipcode}</dd>
            <dt>{t}Address{/t}:</dt>
                <dd>{$info.address}</dd>
        </dl>
    </div>
    <div class="miscellaneous">
        <div class="box_heading">{t}Miscellaneous{/t}</div>
        <dl>
            <dt>{t}Homepage{/t}:</dt>
                <dd><a href="{$info.homepage}" target="_blank">{$info.homepage}</a></dd>
            <dt>{t}ICQ{/t}:</dt>
                <dd>{$info.icq}</dd>
            <dt>{t}MSN{/t}:</dt>
                <dd>{$info.msn}</dd>
            <dt>{t}Skype{/t}:</dt>
                <dd>{$info.skype}</dd>
            <dt>{t}Phone{/t}:</dt>
                <dd>{$info.phone}</dd>
            <dt>{t}Mobile{/t}:</dt>
                <dd>{$info.mobile}</dd>
            <dt>{t}Custom text{/t}:</dt>
                <dd>{$info.custom_text}</dd>
        </dl>
    </div>

{* AJAX Needed *}
    <div class="options">
        <div class="edit_button">
            {if $smarty.session.user.user_id == $info.user_id.0}
                <input class="ButtonGreen" type="button" value="{t}Edit my profile{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=account&amp;sub=general&amp;action=edit", options: {method: "get"}}, {className: "alphacube", width:650, height: 550});{/literal}' />
            {/if}
            {if $smarty.session.rights.permission_access == 1 && $smarty.session.rights.permission_edit_generals}
                <input class="ButtonGreen" type="button" value="{t}Edit profile (admin){/t}" onclick='{literal}Dialog.info({url: "index.php?mod=controlcenter&amp;sub=users&amp;action=permission_edit_generals", options: {method: "get"}}, {className: "alphacube", width:450, height: 400});{/literal}' />
            {/if}
        </div>
    </div>
</div>