<?php 
header("Expires: Sun, 19 Apr 1980 12:00:00 GMT");    // Datum aus Vergangenheit
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                                                     // immer geändert
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");                          // HTTP/1.0
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
    <title><?php echo isset($TITLE) ? $TITLE : 'Clansuite Control Center'; ?></title>

<link rel="stylesheet" type="text/css" href="<?php echo WWW_ROOT.'/shared/style.css'; ?>">
<script type="text/javascript" src="<?php echo WWW_ROOT.'/admin/shared/XulMenu.js'; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo WWW_ROOT.'/admin/shared/example1.css'; ?>" /><style type="text/css">
    body { font: 12px tahoma, verdana; color: #000000; margin: 0; padding: 10px; }
    #header { background: #FE9E32; color: #dddddd; }
    #header a:link, #header a:visited { text-decoration: none; color: #ffffff; font-weight: bold; }
    #header a:hover { text-decoration: underline; }
    a:link { text-decoration: none; color: #FE9E32; font-weight: bold; }
    a:visited { text-decoration: none; color: #FE9E32; font-weight: bold; }
    a:hover { text-decoration: underline; color: #FE9E32; font-weight: bold; }
    #main { margin: 1em; }
    .block { background: #f2f2f2; padding: 0.1em 1em; border: #eeeeee 1px solid; }
    .light { color: #999999; }
    .small { font-size: 11px; }
    .big { font-size: 13px; }
    p { margin: 1em 0; }
    h1 { font-size: 18px; font-weight: bold; color: #777777; margin: 0.5em 0; padding: 0; border-bottom: #777777 1px solid; }
    form { margin: 1em 0; }
    form .description { margin: 0; font-size: 10px; }
    label { display: block; font-weight: bold; margin-bottom: 2px; margin-top: 0.5em; color: #333333; }
    .label { font-weight: bold; margin-bottom: 2px; margin-top: 0.5em; color: #333333; }
    .submit { margin-top: 0.5em; }
    .button { cursor: pointer; padding: 0 0.2em; }
    .listing .block1 { background: #F4F4F4; padding: 0.2em 1em; }
    .listing .block2 { background: #ffffff; padding: 0.2em 1em; }
    .listing th { background: #9C9C9C; color: #ffffff; padding: 0.2em 0.4em; }
    #bar { background: #ECE9D8; border: 1px solid;
        border-color: #ffffff #ACA899 #ACA899 #ffffff;
        padding-top: 3px; padding-bottom: 3px;
        padding-left: 5px; cursor: default;  }
    
	#user { position: right; right: 20px; top: 0px; background: url('<?php echo WWW_ROOT ?>/admin/images/user.gif') no-repeat 0% 0%; padding-left: 23px; padding-top: 4px; }
	#search { position: absolute; top: 0; right: 5px;    }
    #search input, #search select { font-family: georgia, tahoma, verdana;
        font-size: 12px;  margin-top: 4px;  }
    p { font-family: georgia, tahoma, verdana; font-size: 11px; margin: 2em; }
   div.clearer {clear: left; line-height: 0; height: 0; style=clear:both;}
</style>
</head>
<body>
	<!-- Main-Container //-->
			
	<!-- start: Logo - Kopfzeile //-->
	<div id="header"> 
	<strong>Clansuite - Control Center</strong>
	</div> 
	<!-- end: Logo - Kopfzeile //-->	