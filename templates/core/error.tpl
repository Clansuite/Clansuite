{if $error_type==1}
<html>
<head>
{$redirect}
<title>{$error_head}</title>
</head>
<body>
{/if}

<fieldset class="
{if $error_type==1}error_red
{elseif $error_type==2}error_orange
{elseif $error_type==3}error_beige
{/if}
">
	<legend>
		{$error_head}
	</legend>
	{if isset($code)}<strong>Standard Code:</strong> <i>{$code}</i><br />{/if}
	<strong>Error Message:</strong> <i>{$debug_info}</i><br />
	{if isset($file)}<strong>File:</strong> <i>{$file}</i><br />{/if}
	{if isset($line)}<strong>Line:</strong> <i>{$line}</i><br />{/if}
</fieldset>
{if $error_type==1}
</body>
</html>
{/if}
