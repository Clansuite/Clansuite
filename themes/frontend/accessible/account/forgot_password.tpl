<h1>{t}Forgot Password{/t}</h1>
<p>
	{t}Enter your email below and a new password will be generated for your account and sent to you by email.{/t}
</p>
{if $noSuchAccount == 1}<p class="error">{t}There is no account with such email.{/t}</p>{/if}
{if $accountNotActivated == 1}<p class="error">{t}Account with this email has not been yet activated.{/t}</p>{/if}
{if $errorWhileSending == 1}<p class="error">{t}There was an error while sending an email. Please, try again later.{/t}</p>{/if}
<form action="index.php?mod=account&amp;action=forgot_password" method="post" accept-charset="UTF-8">
	<fieldset>
		<dl>
			<dt><label for="email">{t}E-Mail{/t}:</label></dt>
			<dd><input type="text" name="email" id="email" value="{$smarty.post.email|escape:"html"}" /></dd>
		</dl>
	</fieldset>
	<div class="form_bottom">
		<input type="submit" name="submit" value="{t}Send new password{/t}" class="button" />
	</div>
</form>