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

.centered {
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

<div class="centered">

    <fieldset class="error_beige">
        <legend>
            <strong>Maintenance Mode</strong>
        </legend>

        {if isset($maintenance_reason)}
        {$maintenance_reason}
        {else}
        <b>The website is down!</b>
        <br />SITE is currently undergoing scheduled maintenance.
        <br />Sorry for the inconvenience. Please try back in 60 minutes.
        {/if}

        <br /><br />

        <object width="425" height="344">
        <param name="movie" value="http://www.youtube.com/v/rVC7I5VcTiw&hl=de_DE&fs=1&"></param>
        <param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param>
        <embed src="http://www.youtube.com/v/rVC7I5VcTiw&hl=de_DE&fs=1&" type="application/x-shockwave-flash"
               allowscriptaccess="always" allowfullscreen="true" width="425" height="344">
        </embed>
        </object>

    </fieldset>

    <hr style="clear:both;" />

    {*
        <div id="centered">

                {load_module name="account" func="login"}

        </div>
    *}

</div>
</body>
</html>