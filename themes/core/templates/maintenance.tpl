<html>
<head>
<link rel="stylesheet" href="{$css}" type="text/css" />
<link rel="stylesheet" href="{$www_root_themes_core}/css/error.css" type="text/css" />
{literal}
<style type="text/css">
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    text-align: center;
}

div#centered {
    border: 0;
    height: 50%;
    width: 50%;
    position: absolute;
    left: 25%;
    top: 25%;
}
</style>
{/literal}
<title>{$std_page_title} - Maintenance Mode</title>
</head>
<body>

<div id="centered">

            <fieldset class="error_beige">
            <legend>
            	<strong>Maintenance Mode</strong>
            </legend>
            <strong>{$maintenance_reason}</strong>
            </fieldset>

    <div id="centered">

            {mod name="account" func="login"}

    </div>

</div>
</body>
</html>