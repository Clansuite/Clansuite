<html>
<head>
<link rel="stylesheet" href="{$css}" type="text/css" />
<link rel="stylesheet" href="{$www_root_themes_core}/css/error.css" type="text/css" />

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
    position: relative;
    left: 25%;
    top: 25%;
}
</style>

<title>{$pagetitle} - Maintenance Mode</title>
</head>
<body>

<div id="centered">

            <fieldset class="error_beige">
            <legend>
            	<strong>Maintenance Mode</strong>
            </legend>
            {if isset($maintenance_reason)}
            {$maintenance_reason}
            {else}            
            <b>The website is down!</b></a>
            <br />SITE is currently undergoing scheduled maintenance.
            <br />Sorry for the inconvenience. Please try back in 60 minutes.
            {/if}
            <br/><br/><br />
            <object width="425" height="344">
            <param name="movie" value="http://www.youtube.com/v/rVC7I5VcTiw&hl=de_DE&fs=1&"></param>
            <param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param>
            <embed src="http://www.youtube.com/v/rVC7I5VcTiw&hl=de_DE&fs=1&" type="application/x-shockwave-flash" 
                   allowscriptaccess="always" allowfullscreen="true" width="425" height="344">
            </embed>
            </object>
            </fieldset>

{*
    <div id="centered">

            {load_module name="account" func="login"}

    </div>
*}

</div>
</body>
</html>