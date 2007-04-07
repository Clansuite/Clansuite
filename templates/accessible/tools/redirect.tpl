<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>{translate}Redirect - Please wait...{/translate}</title>
<link rel="stylesheet" type="text/css" href="{$css}" />
{$redirect}
</head>
<body>
<div id="redirect_heading">  
    <p>
        {$heading}
        <br /> 
        <img src="{$www_root_tpl_core}/images/symbols/redirect.png" alt="" />
    </p>
    <div class="redirect_message">
        {$message}
    </div>        
</div>
</body>
</html>