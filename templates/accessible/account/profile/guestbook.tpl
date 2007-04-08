<h1>{translate}Guestbook{/translate}</h1>
{* Debugausgabe des Arrays: {$guestbook|@var_dump} {html_alt_table loop=$guestbook} *}

{doc_raw}
    <script src="{$www_root_tpl_core}/javascript/prototype/prototype.js" type="text/javascript"></script>
    <script src="{$www_root_tpl_core}/javascript/lightbox/lightbox.js" type="text/javascript"></script>
  	<script type="text/javascript" src="{$www_root_tpl_core}/javascript/scriptaculous/effects.js"> </script>
  	<script type="text/javascript" src="{$www_root_tpl_core}/javascript/xilinus/window.js"> </script>
  	<script type="text/javascript" src="{$www_root_tpl_core}/javascript/xilinus/window_effects.js"> </script>
  	<link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/javascript/xilinus/themes/alphacube.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/javascript/xilinus/themes/alert.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/javascript/xilinus/themes/default.css" />
{/doc_raw}

<div class="user_gb">
{if $err.gb_empty == "1"}
    <div class="gb_empty">
        <span class="the_text">{translate}We are sorry, but the guestbook is empty.{/translate}</span>
        <div class="options">
            <input class="ButtonGreen" type="button" value="{translate}Add a guestbook entry{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
        </div>
    </div>
{else}
    {include file="tools/paginate.tpl"}
    <div class="options">
        <input class="ButtonGreen" type="button" value="{translate}Add a guestbook entry{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
    </div>

    {foreach from=$guestbook item=entry key=key}
        <div class="center">
            <div class="picture_infos">
                <div class="nick">{$entry.nick}</div>
                <div class="picture">
                    {if $entry.type == url}
                        <img src="{$entry.location}" alt="{$entry.nick}" class="the_pic" />
                    {elseif $entry.type == upload}
                        <a href="index.php?mod=account&sub=profile&action=show&id={$entry.user_id.1}"><img src="index.php?mod=guestbook&action=show_avatar&id={$entry.gb_id}" alt="{$entry.nick}"  class="the_pic" /></a>
                    {else}
                        <img src="{$www_root_tpl}/images/no_avatar_small.jpg" alt="{translate}No avatar{/translate}: {$entry.nick}"  class="the_pic" />
                    {/if}
                </div>
            </div>
            <div class="personal_infos">
                <div class="box_heading">{translate}Personal infos{/translate}</div>
                <dl>
                    <dt>{translate}Date{/translate}: </dt>
                        <dd>{translate}{$entry.gb_added|date_format:"%A"}{/translate}, {translate}{$entry.gb_added|date_format:"%B"}{/translate}{$entry.gb_added|date_format:" %e, %Y"}</dd>
                    <dt>{translate}eMail{/translate}: </dt>
                        <dd><a href="mailto:{$entry.gb_email}">{$entry.gb_email}</a></dd>
                    <dt>ICQ: </dt>
                        <dd>{$entry.gb_icq}</dd>
                    <dt>{translate}Website{/translate}: </dt>
                        <dd>
                            {if empty($entry.gb_website)}
                                <a href="http://www.clansuite.com" target="_blank">{translate}no website{/translate} | powered by clansuite.com - free clan cms</a>
                            {else}
                                <a href="{$entry.gb_website}" target="_blank">{$entry.gb_website}</a>
                            {/if}
                        </dd>
                    <dt>{translate}City{/translate}: </dt>
                        <dd>{$entry.gb_town}</dd>
                    {if $smarty.session.user.rights.cc_edit_gb == 1 AND $smarty.session.user.rights.cc_access == 1}
                    <dt>IP: </dt>
                        <dd>{$entry.gb_ip}</dd>
                    {/if}
                </dl>
            </div>
            <div class="message">
                <div class="box_heading">{translate}The message{/translate}</div>
                <div class="the_text">{$entry.gb_text}</div>
                {if !empty($entry.gb_comment)}
                <dl class="comment">
                    <dt>{translate}Comment{/translate}: </dt>
                        <dd>{$entry.gb_comment}</dd>
                </dl>
                {/if}
            </div>

        {* AJAX Needed *}
            {if ($smarty.session.user.rights.cc_edit_gb == 1 AND $smarty.session.user.rights.cc_access == 1) OR ($smarty.session.user.user_id == $entry.user_id) }
            <div class="options">
                <div class="edit_button">
                    {if $smarty.session.user.user_id == $entry.user_id.1}
                        <input class="ButtonGreen" type="button" value="{translate}Edit my entry{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;action=edit&amp;id={/literal}{$entry.gb_id}{literal}", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
                    {/if}
                    {if $smarty.session.user.rights.cc_edit_gb == 1 AND $smarty.session.user.rights.cc_access == 1}
                        <input class="ButtonGreen" type="button" value="{translate}Edit or add comment{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;sub=admin&amp;action=edit&amp;id={/literal}{$entry.gb_id}{literal}&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
                    {/if}
                </div>
            </div>
            {/if}
        </div>
        <div class="divider"><hr></div>
    {/foreach}
    <div class="options">
        <input class="ButtonGreen" type="button" value="{translate}Add a guestbook entry{/translate}" onclick='{literal}Dialog.info({url: "index.php?mod=guestbook&amp;action=create&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:500, height: 420});{/literal}' />
    </div>
    {include file="tools/paginate.tpl"}
{/if}
</div>
