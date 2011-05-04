<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
<title>Css-Builder</title>

<style type="text/css">
    body { font-family: Arial, Helvetica, sans-serif; font-size: 9pt; }
    .tbl_header { margin:0; padding:0; width:800px; border:1px solid #ccc; text-align: center; margin-bottom: 10px;}
    .tbl_outer { margin:0; padding:0; width:800px; }
    .htitle { font-size: 12pt; font-weight: bold; background-color: #FFFFCC; }
    .tdhead { padding: 2px 0 2px 10px; background-color: #D0F2F0; font-size: 9pt; font-weight:bold;}
	 .path { font-size: 8pt; }
    .leer { font-size: 2pt; height: 5px;}
    .postfix { width:80px; font-size:9pt;height:14px;padding:0; }
    .cmSuccess { display:block; height: auto; margin: -0.3em 0 0.5em; padding: 6px 10px 5px; border: 1px solid #f6abab;
        background: #e5f8ce url('themes/core/images/16x16/success.png') no-repeat 5px 4px; color: #62b548;
    }
    .cmSuccess .cmBoxTitle { font-size: 10pt; font-weight:bold; }
    .cmSuccess .cmBoxMessage { font-size: 9pt; padding-top: 5px; text-align: left; color:#000000; }
    .cmSuccess .cmBoxLine { height: 1px; border-bottom: 1px dotted #808080; }
    .cmSuccess .cmSuccessFilenameColor { color:#0000FF; }
</style>
</head>
<body>
     {include file="action_form_cssbuilder.tpl"}
</body>
</html>
