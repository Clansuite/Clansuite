<h3>{t}Login{/t}</h3>
<div class="content">
{if $err.not_filled == 1}<p class="error">{t}Please fill out all required fields!{/t}</p>{/if}
{if $err.mismatch == 1}<p class="error">{t}This combination is not stored in our database!{/t}</p>{/if}
{if $err.login_attempts > 0}<p class="error">{t}Failed Attempts:{/t}{$err.login_attempts}</p>{/if}
    <form action="index.php?mod=account&amp;action=login{if $referer|count_characters > 0}&amp;referer={$referer}{/if}" method="post" accept-charset="UTF-8">
    	<fieldset>
    		<dl>
{if $cfg->login_method == 'email'}
    			<dt><label for="email">{t}E-Mail{/t}</label></dt>
    			<dd><input type="text" id="email" name="email" value="{$smarty.post.email|escape:"html"}" /></dd>
{/if}
{if $cfg->login_method == 'nick'}
    			<dt><label for="nickname">{t}Nickname{/t}</label></dt>
    			<dd><input type="text" id="nickname" name="nickname" value="{$smarty.post.nickname|escape:"html"}" /></dd>
{/if}
    			<dt><label for="password">{t}Password{/t}</label></dt>
    			<dd><input type="password" id="password" name="password" value="" autocomplete="off" /></dd>
    		</dl>
    	</fieldset>
    	<div class="form_bottom">
    		<label for="remember">
    			<input type="checkbox" id="remember" name="remember_me" value="1" {if $smarty.post.remember_me == 1}checked="checked" {/if}/>
    			<abbr title="Cookies required">{t}Remember me{/t}</abbr>
    		</label>
    		<input type="submit" name="submit" value="{t}Login{/t}" class="button" />
    	</div>
    </form>
    <ul>
    	<li><a href="index.php?mod=account&amp;action=register">{t}Not yet registered?{/t}</a></li>
    	<li><a href="index.php?mod=account&amp;action=forgot_password">{t}Forgot password?{/t}</a></li>
    	<li><a href="index.php?mod=account&amp;action=activation_email">{t}Did not get an activation email?{/t}</a></li>
    </ul>
</div>