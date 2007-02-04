{* DEBUG {$info|@var_dump} *}
{doc_raw}

    {* Prototype + Scriptaculous + Smarty_Ajax *}
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/prototype/prototype.js" ></script>
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/scriptaculous/scriptaculous.js"></script>
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/smarty_ajax.js"></script>
    {* Clip Clap Toggle *}
    <script type="text/javascript" src="{$www_core_tpl_root}/javascript/clip.js"></script>
{/doc_raw}

<table class="klappable" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="border-bottom: 1px solid #ACA899; padding: 5px">
            <b>&raquo; {$smarty.request.mod} {if $smarty.request.sub!=''}&raquo; {$smarty.request.sub} {/if}&raquo; {$smarty.request.main_action}</b>
            {if $help_edit_mode==1}
                <input type="hidden" id="save_mod" name="save_mod" value="{$smarty.request.mod}" />
                <input type="hidden" id="save_sub" name="save_sub" value="{$smarty.request.sub}" />
                <input type="hidden" id="save_action" name="save_action" value="{$smarty.request.main_action}" />
            {/if}
        </td>
    </tr>

    <tr>
        <td style="border-bottom: 1px solid #ACA899; border-top: 1px solid #FFFFFF; padding: 15px">
            <div id="helptext">
                         {if $info.helptext!=''}

                         {foreach key=key item=item from=$info.helptext}
                                {if $item!=''}
                                    {$item|nl2br}
                                {/if}
                         {/foreach}

                        {else}
                            {translate}There is no helptext assigned.{/translate}<br />
                        {/if}
            </div>

            {if $help_edit_mode==1}
                {literal}
                 <script type="text/javascript">
                    new Ajax.InPlaceEditor('helptext',
                                          'index.php?mod=admin&sub=help&action=save_helptext&m={/literal}{$smarty.request.mod}{literal}&s={/literal}{$smarty.request.sub}{literal}&a={/literal}{$smarty.request.main_action}{literal}',
                                          {handleLineBreaks: false, okText: '{/literal}Save{literal}',hoverText: '{/literal}Click to Edit{literal}',cancelText:'{/literal}Cancel{literal}',okButtonClass: 'ButtonGreen',cancelButtonClass: 'ButtonGrey',rows:15,cols:48, loadTextURL:'index.php?mod=admin&sub=help&action=get_helptext&m={/literal}{$smarty.request.mod}{literal}&s={/literal}{$smarty.request.sub}{literal}&a={/literal}{$smarty.request.main_action}{literal}'});
                 </script>
                {/literal}
            {/if}

        </td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #ACA899; border-top: 1px solid #FFFFFF; padding: 5px">
            <b>&raquo; {translate}Related Links{/translate}</b>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid #FFFFFF; padding: 15px">
         <div id="related_links_container">

            {if $info.related_links!=''}

                        {foreach key=key item=item from=$info.related_links}
                                {if $item!=''}
                                    {$item|nl2br}
                                {/if}
                         {/foreach}
            {else}
                {translate}There are no links assigned.{/translate}
                <br />
            {/if}
         </div>
        {if $help_edit_mode==1}
            {literal}
             <script type="text/javascript">
                new Ajax.InPlaceEditor('related_links_container',
                                       'index.php?mod=admin&sub=help&action=save_related_links&m={/literal}{$smarty.request.mod}{literal}&s={/literal}{$smarty.request.sub}{literal}&a={/literal}{$smarty.request.main_action}{literal}',
                                       {handleLineBreaks: false, okText: '{/literal}Save{literal}',hoverText: '{/literal}Click to Edit{literal}',cancelText:'{/literal}Cancel{literal}',okButtonClass: 'ButtonGreen',cancelButtonClass: 'ButtonGrey',rows:15,cols:48, loadTextURL:'index.php?mod=admin&sub=help&action=get_related_links&m={/literal}{$smarty.request.mod}{literal}&s={/literal}{$smarty.request.sub}{literal}&a={/literal}{$smarty.request.main_action}{literal}'});
             </script>
           {/literal}
        {/if}

    </tr>
</table>
