<h1>{translate}Forgot Password{/translate}</h1>
<p>
	{translate}Enter your email below and a new password will be generated for your account and sent to you by email.{/translate}
</p>
{if $noSuchAccount == 1}<p class="error">{translate}There is no account with such email.{/translate}</p>{/if}
{if $accountNotActivated == 1}<p class="error">{translate}Account with this email has not been yet activated.{/translate}</p>{/if}
{if $errorWhileSending == 1}<p class="error">{translate}There was an error while sending an email. Please, try again later.{/translate}</p>{/if}
<form action="index.php?mod=account&amp;action=forgot_password" method="post">
	<fieldset>
		<dl>
			<dt><label for="email">{translate}E-Mail{/translate}:</label></dt>
			<dd><input type="text" name="email" id="email" value="{$smarty.post.email|escape:"html"}" /></dd>
		</dl>
	</fieldset>
	<div class="form_bottom">
		<input type="submit" name="submit" value="{translate}Send new password{/translate}" class="button" />
	</div>
</form>