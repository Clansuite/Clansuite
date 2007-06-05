<h1>{translate}Activation Email{/translate}</h1>
{if $err.no_such_mail == 1}<p class="error">{translate}There is no account with such email.{/translate}</p>{/if}
{if $err.form_not_filled == 1}<p class="error">{translate}Please fill out the form below.{/translate}</p>{/if}
{if $err.already_activated == 1}<p class="error">{translate}There was an error while sending an email. Please, try again later.{/translate}</p>{/if}
{if $err.email_wrong == 1}<p class="error">{translate}The email address is wrong!{/translate}</p>{/if}
<p>
	{translate}If you have registered and still haven't got an activation email, use the form below to send it again.{/translate}
</p>
<form action="index.php?mod=account&amp;action=activation_email" method="post" accept-charset="UTF-8">
	<fieldset>
		<dl>
			<dt><label for="email">{translate}E-Mail{/translate}:</label></dt>
			<dd><input type="text" name="email" id="email" value="{$smarty.post.email|escape:"html"}" /></dd>
		</dl>
	</fieldset>
	<div class="form_bottom">
		<input type="submit" name="submit" value="{translate}Send activation email{/translate}" class="button" />
	</div>
</form>