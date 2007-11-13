<h3>{translate}Shoutbox{/translate}</h3>
<div class="content">
{if $show_form === true}
    <div class="shoutbox-form">
        <form action="{$request}" method="post" accept-charset="UTF-8">
        	<fieldset>
        		<dl>
        			<dt><label for="shout_name">{translate}Name{/translate}:</label></dt>
        			<dd><input class="input_text" id="shout_name" type="text" name="name" value="{$smarty.session.user.nick|escape:"html"}" /></dd>
        			<dt><label for="shout_mail">{translate}E-Mail{/translate}:</label></dt>
        			<dd><input class="input_text" id="shout_mail" type="text" name="mail" value="{$smarty.session.user.email|escape:"html"}" /></dd>
        			<dt><label for="shout_msg">{translate}Message{/translate}:</label></dt>
        			<dd><textarea class="input_textarea" id="shout_msg" name="msg" cols="17" rows="3"></textarea></dd>
        		</dl>
        	</fieldset>
        	<div class="form_bottom">
        		<input type="submit" id="shoutbox_submit" name="sent" value="{$save_entry}" class="button" />
        	</div>
        </form>
        <div id="request_return" style="text-align:center"></div>
    </div>
    <br />
    <div id="entries_box">
    	{$entries_box}
    </div>
{$entries}
{/if}
{* Ist ein Fehler aufgetreten *}
{if $is_error === true && $show_error === true}
	{include file='service/showErrorList.tpl'}
{/if}

{* Wurde der Eintrag gespeichert *}
{if $is_saved === true}
	{$save_entry}
{else}
{doc_raw}
{literal}
<script type="text/javascript">
$(document).ready(function() {

})
</script>
{/literal}
{/doc_raw}
{/if}
</div>