<h1>{t}Guestbook{/t}</h1>
{* Debugausgabe des Arrays: {$guestbook|var_dump} {html_alt_table loop=$guestbook} *}

{move_to target="pre_head_close"}
    <script src="{$www_root_themes_core}javascript/prototype/prototype.js" type="text/javascript"></script>
    <script src="{$www_root_themes_core}javascript/lightbox/lightbox.js" type="text/javascript"></script>
  	<script type="text/javascript" src="{$www_root_themes_core}javascript/scriptaculous/effects.js"> </script>
  	<script type="text/javascript" src="{$www_root_themes_core}javascript/xilinus/window.js"> </script>
  	<script type="text/javascript" src="{$www_root_themes_core}javascript/xilinus/window_effects.js"> </script>
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}javascript/xilinus/themes/alphacube.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}javascript/xilinus/themes/alert.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}javascript/xilinus/themes/default.css" />
{/move_to}

<div class="user_gb">
{if $err.gb_empty == "1"}
    <div class="gb_empty">
        <span class="the_text">{t}We are sorry, but the guestbook is empty.{/t}</span>
        <div class="options">
            <input class="ButtonGreen" type="button" value="{t}Add a guestbook entry{/t}" onclick='Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});' />
        </div>
    </div>
{else}
    {pagination}
    <div class="options">
        <input class="ButtonGreen" type="button" value="{t}Add a guestbook entry{/t}" onclick='Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});' />
    </div>

    {foreach $guestbook as entry}
        <div class="center">
            <div class="picture_infos">
                <div class="nick">{$entry.nick}</div>
                <div class="picture">
                    {if $entry.type == url}
                        <img src="{$entry.location}" alt="{$entry.nick}" class="the_pic" />
                    {elseif $entry.type == upload}
                        <a href="index.php?mod=account&sub=profile&action=show&id={$entry.user_id.1}"><img src="index.php?mod=guestbook&action=show_avatar&id={$entry.gb_id}" alt="{$entry.nick}"  class="the_pic" /></a>
                    {else}
                        <img src="{$www_root_theme}images/no_avatar_small.jpg" alt="{t}No avatar{/t}: {$entry.nick}"  class="the_pic" />
                    {/if}
                </div>
            </div>
            <div class="personal_infos">
                <div class="box_heading">{t}Personal infos{/t}</div>
                <dl>
                    <dt>{t}Date{/t}: </dt>
                        <dd>{t}{$entry.gb_added|date_format:"%A"}{/t}, {t}{$entry.gb_added|date_format:"%B"}{/t}{$entry.gb_added|date_format:" %e, %Y"}</dd>
                    <dt>{t}eMail{/t}: </dt>
                        <dd><a href="mailto:{$entry.gb_email}">{$entry.gb_email}</a></dd>
                    <dt>ICQ: </dt>
                        <dd>{$entry.gb_icq}</dd>
                    <dt>{t}Website{/t}: </dt>
                        <dd>
                            {if empty($entry.gb_website)}
                                <a href="http://www.clansuite.com" target="_blank">{t}no website{/t} | powered by clansuite.com - free clan cms</a>
                            {else}
                                <a href="{$entry.gb_website}" target="_blank">{$entry.gb_website}</a>
                            {/if}
                        </dd>
                    <dt>{t}City{/t}: </dt>
                        <dd>{$entry.gb_town}</dd>
                    {if $smarty.session.user.rights.permission_edit_gb == 1 AND $smarty.session.user.rights.permission_access == 1}
                    <dt>IP: </dt>
                        <dd>{$entry.gb_ip}</dd>
                    {/if}
                </dl>
            </div>
            <div class="message">
                <div class="box_heading">{t}The message{/t}</div>
                <div class="the_text">{$entry.gb_text}</div>
                {if !empty($entry.gb_comment)}
                <dl class="comment">
                    <dt>{t}Comment{/t}: </dt>
                        <dd>{$entry.gb_comment}</dd>
                </dl>
                {/if}
            </div>

        {* AJAX Needed *}
            {if ($smarty.session.user.rights.permission_edit_gb == 1 AND $smarty.session.user.rights.permission_access == 1) OR ($smarty.session.user.user_id == $entry.user_id) }
            <div class="options">
                <div class="edit_button">
                    {if $smarty.session.user.user_id == $entry.user_id.1}
                        <input class="ButtonGreen" type="button" value="{t}Edit my entry{/t}" onclick='Dialog.info({url: "index.php?mod=guestbook&amp;action=edit&amp;id={$entry.gb_id}", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});' />
                    {/if}
                    {if $smarty.session.user.rights.permission_edit_gb == 1 AND $smarty.session.user.rights.permission_access == 1}
                        <input class="ButtonGreen" type="button" value="{t}Edit or add comment{/t}" onclick='Dialog.info({url: "index.php?mod=guestbook&amp;sub=admin&amp;action=edit&amp;id={$entry.gb_id}&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});' />
                    {/if}
                </div>
            </div>
            {/if}
        </div>
        <div class="divider"><hr></div>
    {/foreach}
    <div class="options">
        <input class="ButtonGreen" type="button" value="{t}Add a guestbook entry{/t}" onclick='Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});' />
    </div>
    {pagination}
{/if}
</div>
