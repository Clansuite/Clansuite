{* {$config|var_dump} *}

{move_to target="pre_head_close"}
<script src="{$www_root_themes_core}javascript/webtoolkit.sha1.js" type="text/javascript"></script>
<script type="text/javascript">
function hashLoginPassword(theForm)
{
    theForm.password.value = SHA1(theForm.password.value);
}
</script>
{/move_to}

<div class="table table-border">
	<div class="table-header table-border-bottom"><img src="{$www_root_theme}images/icons/lock.png" />{t}Login{/t}</div>
	<div class="table-content-menu">
		<div class="formareaWidget">

			<form action="{$www_root}index.php?mod=account&action=login"
					method="post"
					id="block_login_form"
					accept-charset="UTF-8"
					onsubmit="hashLoginPassword(this);">

			<div class="container" style="padding:2px;">

				{if isset($config.login.login_method) && $config.login.login_method == 'email'}
				<div>
					<label for="email">{t}Email:{/t}</label>
					<input class="input_text" type="text" name="email" id="email"
							 onblur="if(this.value=='')this.value=this.defaultValue;"
							 onfocus="if(this.value==this.defaultValue)this.value='';"
							 value="email" 
							 style="width: 90px;"/>
				</div>
				{elseif isset($config.login.login_method) && $config.login.login_method == 'nick'}
				<div>
					<label for="nickname">{t}Nickname:{/t}</label>
					<input class="input_text" type="text" name="nickname" id="nickname"
							 onblur="if(this.value=='')this.value=this.defaultValue;"
							 onfocus="if(this.value==this.defaultValue)this.value='';"
							 value="nickname" 
							 style="width: 90px;"/>
				</div>
				{/if}

				<div>
					<label for="block_password">{t}Password:{/t}</label>
					<input class="input_text" type="password" name="password" id="block_password"
								 onblur="if(this.value=='')this.value=this.defaultValue;"
								 onfocus="if(this.value==this.defaultValue)this.value='';"
								 value="******" 
								 style="width: 90px;"/>
				</div>

				<div>
					<label></label>
					<label for="remember_me" class="labelsmall">
						<input type="checkbox" name="remember_me" id="remember_me" value="1" {if isset($smarty.post.remember_me)} checked="checked" {/if} style="margin-left:20px;"/>
						{t}Remember me (Cookie){/t}
					</label>
				</div>

				<div class="buttonsarea">
					<label></label>
					<input type="submit" name="submit" id="login_button" value="{t}Login{/t}" class="btn-red" />
				</div>

				<div align="center">
					<span style="font-family: Arial, Helvetica, sans-serif; font-size:11px;">
						<a href="{$www_root}index.php?mod=account&action=register">{t}Not yet registered?{/t}</a> <br />
						<a href="{$www_root}index.php?mod=account&action=forgot_password">{t}Forgot password?{/t}</a> <br />
						<a href="{$www_root}index.php?mod=account&action=activation_email">{t}Did not get an activation email?{/t}</a>
					</span>
				</div>

			</div>
			</form>
		</div>
	</div>
</div>
<div class="tablespacer10">&nbsp;</div>
