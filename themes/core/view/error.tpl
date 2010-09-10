{* In case error_type 2 and 3 a header is set and doc_raw content is moved there. *}
{* In case error_type 1 (fatal error) such movement takes not place! It's directly linked !*}

{if $error_type == 1}
<html>
<head>
<link rel="stylesheet" href="{$www_root_themes_core}css/error.css" type="text/css" />
{$redirect}
<title>{$error_head} - errortype {$error_type}</title>
</head>
<body>
{else}
<link rel="stylesheet" href="{$www_root_themes_core}css/error.css" type="text/css" />
{/if}


<fieldset class="
{if $error_type == 1}error_red
{elseif $error_type == 2}error_orange
{elseif $error_type == 3}error_beige
{/if}
">
	<legend>
		{$error_head}
	</legend>
	{if isset($code)}<strong>Standard Code:</strong> <em>{$code}</em><br />{/if}
	<strong>Error Message:</strong> <em>{$debug_info}</em><br />
	{if isset($file)}<strong>File:</strong> <em>{$file}</em><br />{/if}
	{if isset($line)}<strong>Line:</strong> <em>{$line}</em><br />{/if}
</fieldset>

{if $error_type==1}
</body>
</html>
{/if}