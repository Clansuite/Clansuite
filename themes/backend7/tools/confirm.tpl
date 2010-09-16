<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<link rel="stylesheet" type="text/css" href="{$css}">
<title>{t}Please confirm!{/t}</title>
</head>
<body>
<form action="{$link}" method="post" accept-charset="UTF-8">
    <div id="redirect_heading">  
        <p>
            {$heading}
        </p>
        <div class="redirect_message">
            {$message}    
        </div>    
        <p>
            <input type="submit" class="ButtonRed" name="confirm" value="{t}Confirm{/t}">
            &nbsp;
            <input type="submit" class="ButtonGreen" name="abort" value="{t}Abort{/t}" />
        </p>
    </div>    
</form>
</body>
</html>