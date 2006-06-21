{if $error_type==1}
<html>
<head>
{$redirect}
<title>{$error_head}</title>
</head>
<body>
{/if}

<p>
<fieldset style="border-color: red; background: 
{if $error_type==1}red
{elseif $error_type==2}orange
{elseif $error_type==3}turquoise
{/if}
;">
	<legend>
		<strong style='border: 1px solid #000000; background: white; -moz-opacity:0.75; filter:alpha(opacity=75);'>&nbsp;{$error_head}&nbsp;</strong>
	</legend>
	<label>
		{if isset($code)}<strong>Standard Code:</strong> <i>{$code}</i><br>{/if}
		<strong>Error Message:</strong> <i>{$debug_info}</i><br>
		{if isset($file)}<strong>File:</strong> <i>{$file}</i><br>{/if}
		{if isset($line)}<strong>Line:</strong> <i>{$line}</i><br>{/if}
	</label>
</fieldset>
</p>

{if $error_type==1}
</body>
</html>
{/if}
