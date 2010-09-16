<h1>{t}Register{/t}</h1>
{if $err.not_filled == 1}<p class="error">{t}Please fill out all required fields!{/t}</p>{/if}
{if $err.nick_wrong == 1}<p class="error">{t}The nickname contains violating characters!{/t}</p>{/if}
{if $err.email_wrong == 1}<p class="error">{t}The email address is wrong!{/t}</p>{/if}
{if $err.email_exists == 1}<p class="error">{t}The email address already exists in our database!{/t}</p>{/if}
{if $err.nick_exists == 1}<p class="error">{t}The nickname already exists in our database!{/t}</p>{/if}
{if $err.pass_too_short == 1}<p class="error">{t}The password is too short!{/t}</p>{/if}
{if $err.passes_do_not_fit == 1}<p class="error">{t}The passwords aren"t the same!{/t}</p>{/if}
{if $err.wrong_captcha == 1}<p class="error">{t}The code you entered is wrong!{/t}</p>{/if}
<form action="index.php?mod=account&amp;action=register" method="post" accept-charset="UTF-8">
	<fieldset>
		<dl>
			<dt><label for="nick">{t}Nick:{/t}</label></dt>
			<dd><input class="input_text" type="text" id="nick" name="nick" value="{$smarty.post.nick|escape:"html"}" /></dd>
			<dt><label for="email">{t}E-Mail:{/t}</label></dt>
			<dd><input class="input_text" onkeyup="javascript:mailTest()" type="text" name="email" id="email" value="{$smarty.post.email|escape:"html"}" /></dd>
			<dt><label for="email2">{t}Confirm E-Mail:{/t}</label></dt>
			<dd><input class="input_text" onkeyup="javascript:mailTest()" type="text" name="email2" id="email2" value="{$smarty.post.email2|escape:"html"}" /></dd>
			<dt><label for="password">{t}Password:{/t}</label></dt>
			<dd><input class="input_text" onkeyup="javascript:passTest()" type="password" id="password" name="password" value="" /></dd>
			<dt><label for="password2">{t}Confirm Password:{/t}</label></dt>
			<dd><input class="input_text" onkeyup="javascript:passTest()" type="password" id="password2" name="password2" value="" /></dd>
			<dt><span>{t}Password Security:{/t}</span></dt>
			<dd><div id="password_verification" style="width:1px;height:15px;background:#f00;border:1px solid #333">&nbsp;</div></dd>
			<dt><label for="captcha">{t}Enter Code:{/t}</label></dt>
			<dd>
				<img src="{$captcha_url}" alt="" style="border:thin solid black;" />
				<br />
				<input class="input_text" type="text" id="captcha" name="captcha" value="" />
			</dd>
		</dl>
	</fieldset>
	<div class="form_bottom">
		<input type="submit" name="submit" value="{t}Register{/t}" class="button" />
	</div>
</form>