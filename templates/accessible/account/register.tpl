<h1>{translate}Register{/translate}</h1>
{if $err.not_filled == 1}<p class="error">{translate}Please fill out all required fields!{/translate}</p>{/if}
{if $err.nick_wrong == 1}<p class="error">{translate}The nickname contains violating characters!{/translate}</p>{/if}
{if $err.email_wrong == 1}<p class="error">{translate}The email address is wrong!{/translate}</p>{/if}
{if $err.email_exists == 1}<p class="error">{translate}The email address already exists in our database!{/translate}</p>{/if}
{if $err.nick_exists == 1}<p class="error">{translate}The nickname already exists in our database!{/translate}</p>{/if}
{if $err.pass_too_short == 1}<p class="error">{translate}The password is too short!{/translate}</p>{/if}
{if $err.passes_do_not_fit == 1}<p class="error">{translate}The passwords aren"t the same!{/translate}</p>{/if}
{if $err.wrong_captcha == 1}<p class="error">{translate}The code you entered is wrong!{/translate}</p>{/if}
<form action="index.php?mod=account&amp;action=register" method="post" accept-charset="UTF-8">
	<fieldset>
		<dl>
			<dt><label for="nick">{translate}Nick:{/translate}</label></dt>
			<dd><input class="input_text" type="text" id="nick" name="nick" value="{$smarty.post.nick|escape:"html"}" /></dd>
			<dt><label for="email">{translate}E-Mail:{/translate}</label></dt>
			<dd><input class="input_text" onkeyup="javascript:mailTest()" type="text" name="email" id="email" value="{$smarty.post.email|escape:"html"}" /></dd>
			<dt><label for="email2">{translate}Confirm E-Mail:{/translate}</label></dt>
			<dd><input class="input_text" onkeyup="javascript:mailTest()" type="text" name="email2" id="email2" value="{$smarty.post.email2|escape:"html"}" /></dd>
			<dt><label for="password">{translate}Password:{/translate}</label></dt>
			<dd><input class="input_text" onkeyup="javascript:passTest()" type="password" id="password" name="password" value="" /></dd>
			<dt><label for="password2">{translate}Confirm Password:{/translate}</label></dt>
			<dd><input class="input_text" onkeyup="javascript:passTest()" type="password" id="password2" name="password2" value="" /></dd>
			<dt><span>{translate}Password Security:{/translate}</span></dt>
			<dd><div id="password_verification" style="width:1px;height:15px;background:#f00;border:1px solid #333">&nbsp;</div></dd>
			<dt><label for="captcha">{translate}Enter Code:{/translate}</label></dt>
			<dd>
				<img src="{$captcha_url}" alt="" style="border:thin solid black;" />
				<br />
				<input class="input_text" type="text" id="captcha" name="captcha" value="" />
			</dd>
		</dl>
	</fieldset>
	<div class="form_bottom">
		<input type="submit" name="submit" value="{translate}Register{/translate}" class="button" />
	</div>
</form>