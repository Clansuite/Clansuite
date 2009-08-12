{move_to target="pre_head_close"}
    {* Tabs *}
    <link rel="stylesheet" type="text/css" href="{$www_root_theme}/luna.css" />
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/tabpane.js"></script>

    {* Prototype + Scriptaculous + Smarty_Ajax + Xilinus*}
    <script src="{$www_root_themes_core}/javascript/prototype/prototype.js" type="text/javascript"></script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/scriptaculous/effects.js"></script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/xilinus/window.js"></script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/xilinus/window_effects.js"></script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/smarty_ajax.js"></script>

  	{* Smarty AJAX Request for entrance *}
    {*
    <script type="text/javascript">
    SmartyAjax.update('ajax_{if $smarty.cookies.webfxtab_profile == 0}general{elseif $smarty.cookies.webfxtab_profile == 1}computer{elseif $smarty.cookies.webfxtab_profile == 2}guestbook{/if}',
                            'index.php?mod=account&sub={if $smarty.cookies.webfxtab_profile == 0}general{elseif $smarty.cookies.webfxtab_profile == 1}computer{elseif $smarty.cookies.webfxtab_profile == 2}guestbook{/if}&action=show{if isset($id)}&id={$id}{/if}',
                            'get',
                            'f=',
                            '');
    </script>*}
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/alphacube.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/alert.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/default.css" />

{/move_to}
<div class="profile">
    <div class="tab-pane" id="profile">
        <script type="text/javascript">tp1 = new WebFXTabPane( document.getElementById( "profile" ) );</script>

        {* #### GENERALS #### *}
        <div class="tab-page" id="general">
           <h2 class="tab" {*style="height: 15px" onclick="{ajax_update update_id="ajax_general" url="index.php?mod=account&amp;sub=general&amp;action=show" method="get"}"*}>{t}General{/t}</h2>
           <script type="text/javascript">tp1.addTabPage( document.getElementById( "general" ) );</script>
            <div id="ajax_general">{mod name="account" sub="general"}</div>
        </div>

        {* #### Computer #### *}
        <div class="tab-page" id="computer">
           <h2 class="tab" {*style="height: 15px" onclick="{ajax_update update_id="ajax_computer" url="index.php?mod=account&amp;sub=computer&amp;action=show" method="get"}"*}>{t}Computer{/t}</h2>
           <script type="text/javascript">tp1.addTabPage( document.getElementById( "computer" ) );</script>
            <div id="ajax_computer">{mod name="account" sub="computer"}</div>
        </div>

        {* #### Computer #### *}
        <div class="tab-page" id="guestbook">
           <h2 class="tab" {*style="height: 15px" onclick="{ajax_update update_id="ajax_guestbook" url="index.php?mod=account&amp;sub=guestbook&amp;action=show" method="get"}"*}>{t}Guestbook{/t}</h2>
           <script type="text/javascript">tp1.addTabPage( document.getElementById( "guestbook" ) );</script>
            <div id="ajax_guestbook">{mod name="account" sub="guestbook"}</div>
        </div>
    </div>
</div>

{* #### Init TabPane #### *}
<script type="text/javascript">setupAllTabs();</script>