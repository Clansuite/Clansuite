{* DEBUG {$info|var_dump} *}
{move_to target="pre_head_close"}
    {* Clip Clap Toggle *}
    <script type="text/javascript" src="{$www_root_themes_core}javascript/clip.js"></script>
{/move_to}

<table class="klappable" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="border-bottom: 1px solid #ACA899; padding: 5px">
            <strong>&raquo; {$smarty.request.mod} {if $smarty.request.sub!=''}&raquo; {$smarty.request.sub} {/if}&raquo; {$smarty.request.main_action}</strong>
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
                                    {$item}
                                {/if}
                         {/foreach}

                        {else}
                            {t}There is no helptext assigned.{/t}<br />
                        {/if}
            </div>

            {if $help_edit_mode==1}
                
                 <script type="text/javascript">
				 	//<![CDATA[
                    new Ajax.InPlaceEditor('helptext',
                                          'index.php?mod=controlcenter&sub=help&action=save_helptext&m={$smarty.request.mod}&s={$smarty.request.sub}&a={$smarty.request.main_action}',
                                          {handleLineBreaks: false, okText: 'Save', formClassName: 'ajax_input_text',hoverText: 'Click to Edit',cancelButton: true, cancelLink: false, cancelText:'Cancel',okButtonClass: 'ButtonGreen',cancelButtonClass: 'ButtonGrey',rows:15,cols:35, loadTextURL:'index.php?mod=controlcenter&sub=help&action=get_helptext&m={$smarty.request.mod}&s={$smarty.request.sub}&a={$smarty.request.main_action}'});
					//]]>
                 </script>
                
            {/if}

        </td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #ACA899; border-top: 1px solid #FFFFFF; padding: 5px">
            <strong>&raquo; {t}Related Links{/t}</strong>
        </td>
    </tr>
    <tr>
        <td style="border-top: 1px solid #FFFFFF; padding: 15px">

          <div id="related_links_container">
            {if $info.related_links!=''}

                        {foreach key=key item=item from=$info.related_links}
                                {if $item!=''}
                                    {$item}
                                {/if}
                         {/foreach}
            {else}
                {t}There are no links assigned.{/t}
                <br />
            {/if}
         </div>
        {if $help_edit_mode==1}
        <p>
            <input id="links_edit_button" type="button" value="Edit links" class="ButtonGreen" />
        </p>
            
             <script type="text/javascript">
				//<![CDATA[
                new Ajax.InPlaceEditor('related_links_container',
                                   'index.php?mod=controlcenter&sub=help&action=save_related_links&m={$smarty.request.mod}&s={$smarty.request.sub}&a={$smarty.request.main_action}',
                                   {externalControl:$('links_edit_button'), handleLineBreaks: false, formClassName: 'ajax_input_text', okText: 'Save',cancelButton: true, cancelLink: false, cancelText:'Cancel',okButtonClass: 'ButtonGreen',cancelButtonClass: 'ButtonGrey',rows:15,cols:35, loadTextURL:'index.php?mod=controlcenter&sub=help&action=get_related_links&m={$smarty.request.mod}&s={$smarty.request.sub}&a={$smarty.request.main_action}'}
                                    );
				//]]>
             </script>
           
        {/if}
        </td>
    </tr>
</table>
