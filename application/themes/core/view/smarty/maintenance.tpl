<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link rel="stylesheet" href="themes/core/css/error.css" type="text/css" />
<style type="text/css">
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    text-align: center;
}

#centered {
    border: 1px groove #DADADA;
    height: 50%;
    width: 50%;
    position: relative;
    left: 25%;
    top: 20%;
	 background: #F8F8F8;
	 padding: 1em;
}
</style>

<!--[if IE]>
<style>
fieldset {
	position: relative;
	}
legend {
	position: absolute;
	top: -.8em;
	left: .8em;
	}
</style>
<![endif]-->

<title>{title}</title>
</head>
<body>

<div id="centered">

<div style="height: 65px; border-bottom:1px solid #ccc; font-size:24pt;margin-bottom:1.5em;font-weight:bolder;color:#646464;">
		<div style="float:right;margin-right:3em;margin-top:8px;">Clansuite - just an <font color=red>e</font>Sports CMS</div>
	<div style="float:left"><img src="themes/core/images/clansuite_logos/clansuite_clown_mini_trans.png" vspace="0" hspace="0" border=0></div>
</div>


<fieldset class="error_beige">
    <legend>
        <strong>{title}</strong>
    </legend>

    <p>&nbsp;</p>

    <p>{reason}</p>
    <p>{sorry}</p>
    <p>{back}</p>

    <p>&nbsp;</p>

</fieldset>

</div>
</body>
</html>