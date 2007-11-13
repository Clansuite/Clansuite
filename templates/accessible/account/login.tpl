<h3>{translate}Login{/translate}</h3>
<div class="content">
{if $err.not_filled == 1}<p class="error">{translate}Please fill out all required fields!{/translate}</p>{/if}
{if $err.mismatch == 1}<p class="error">{translate}This combination is not stored in our database!{/translate}</p>{/if}
{if $err.login_attempts > 0}<p class="error">{translate}Failed Attempts:{/translate}{$err.login_attempts}</p>{/if}
    <form action="index.php?mod=account&amp;action=login{if $referer|count_characters > 0}&amp;referer={$referer}{/if}" method="post" accept-charset="UTF-8">
    	<fieldset>
    		<dl>
{if $cfg->login_method == 'email'}
    			<dt><label for="email">{translate}E-Mail{/translate}</label></dt>
    			<dd><input type="text" id="email" name="email" value="{$smarty.post.email|escape:"html"}" /></dd>
{/if}
{if $cfg->login_method == 'nick'}
    			<dt><label for="nickname">{translate}Nickname{/translate}</label></dt>
    			<dd><input type="text" id="nickname" name="nickname" value="{$smarty.post.nickname|escape:"html"}" /></dd>
{/if}
    			<dt><label for="password">{translate}Password{/translate}</label></dt>
    			<dd><input type="password" id="password" name="password" value="" /></dd>
    		</dl>
    	</fieldset>
    	<div class="form_bottom">
    		<label for="remember">
    			<input type="checkbox" id="remember" name="remember_me" value="1" {if $smarty.post.remember_me == 1}checked="checked" {/if}/>
    			<abbr title="Cookies required">{translate}Remember me{/translate}</abbr>
    		</label>
    		<input type="submit" name="submit" value="{translate}Login{/translate}" class="button" />
    	</div>
    </form>
    <ul>
    	<li><a href="index.php?mod=account&amp;action=register">{translate}Not yet registered?{/translate}</a></li>
    	<li><a href="index.php?mod=account&amp;action=forgot_password">{translate}Forgot password?{/translate}</a></li>
    	<li><a href="index.php?mod=account&amp;action=activation_email">{translate}Did not get an activation email?{/translate}</a></li>
    </ul>
</div>