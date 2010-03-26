<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link rel="stylesheet" href="/themes/core/css/error.css" type="text/css" />
<style type="text/css">
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    text-align: center;
}

#centered {
    border: 0;
    height: 50%;
    width: 50%;
    position: relative;
    left: 25%;
    top: 25%;
}
</style>
<title>{* {$pagetitle} -*}{t}Maintenance Mode{/t}</title>
</head>
<body>

<div id="centered">

<fieldset class="error_beige">
    <legend>
        <strong>{t}Maintenance Mode{/t}</strong>
    </legend>
    {if isset($maintenance_reason)}

    {$maintenance_reason}

    {else}

    <b> {t}The website is down!{/t}</b>
    <br />{t}SITE is currently undergoing scheduled maintenance.{/t}
    <br />{t}Sorry for the inconvenience. Please try back in 60 minutes.{/t}

    {/if}
</fieldset>

{*
<div id="centered">
        {load_module name="account" func="login"}
</div>
*}

</div>
</body>
</html>