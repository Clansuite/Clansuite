<h3>{translate}Login{/translate}</h3>
{if $err.not_filled == 1}<p class="error">{translate}Please fill out all required fields!{/translate}</p>{/if}
{if $err.mismatch == 1}<p class="error">{translate}This combination is not stored in our database!{/translate}</p>{/if}
{if $err.login_attempts > 0}<p class="error">{translate}Failed Attempts:{/translate}{$err.login_attempts}</p>{/if}
<form action="index.php?mod=account&amp;action=login{if $referer|count_characters > 0}&amp;referer={$referer}{/if}" method="post">
	<fieldset>
		<dl>
{if $cfg->login_method == 'email'}
			<dt><span>{translate}E-Mail:{/translate}</span></dt>
			<dd><input class="input_text" type="text" name="email" value="{$smarty.post.email|escape:"html"}" /></dd>
{/if}
{if $cfg->login_method == 'nick'}
			<dt><span>{translate}Nickname:{/translate}</span></dt>
			<dd><input class="input_text" type="text" name="nickname" value="{$smarty.post.nickname|escape:"html"}" /></dd>
{/if}
			<dt><span>{translate}Password:{/translate}</span></dt>
			<dd><input class="input_text" type="password" name="password" value="" /></dd>
		</dl>
	</fieldset>
	<div style="text-align:center">
		<input type="checkbox" name="remember_me" value="1" {if $smarty.post.remember_me == 1}checked="checked" {/if}/>
		<abbr title="Cookies required">{translate}Remember me{/translate}</abbr>
	</div>
	<div class="form_bottom">
		<input class="ButtonGreen" type="submit" name="submit" value="{translate}Login{/translate}" />
	</div>
</form>
<ul>
	<li><a href="index.php?mod=account&amp;action=register">{translate}Not yet registered ?{/translate}</a></li>
	<li><a href="index.php?mod=account&amp;action=forgot_password">{translate}Forgot password ?{/translate}</a></li>
	<li><a href="index.php?mod=account&amp;action=activation_email">{translate}Did not get an activation email ?{/translate}</a></li>
</ul>