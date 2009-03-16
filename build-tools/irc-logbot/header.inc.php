<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("config.inc.php");?>
<html>
<head>
<title><?php echo("IRC Log for $channel on $server, collected by $nick"); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="IRC Log for <?php echo($channel); ?>" />
<meta name="keywords" content="IRC Log for <?php echo($channel); ?>" />
<link href="http://www.clansuite.com/website/css/topnavigation.css" media="screen" type="text/css" rel="stylesheet"/>

<style type="text/css">
body {
    background: #d5d6d7 url("http://www.clansuite.com/website/images/kubrickbgcolor.png");
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 12px;
    color: #000000;
    min-width: 800px;
    text-align: center;
}
.irc-date  {font-family: Courier New, Courier, mono;}
.irc-green {color: #009200;}
.irc-black {color: #000000;}
.irc-brown {color: #7b0000;}
.irc-navy  {color: #00007b;}
.irc-brick {color: #9c009c;}
.irc-red   {color: #ff0000;}
}
</style>

<style type="text/css">
#topborder {
margin: -10px;
}
#headbar {
 /*background: #FFFFFF url(http://www.clansuite.com/website/images/kubrickheader-downtab.png) repeat-x scroll 0 0px;*/
}
#headbar a, a:link, a:visited {
 text-decoration: none;
}
#headmenu img {
 margin-top: 2px;
 border: 0px;
}
#langs img {
 border: 0px;
}
#maincontent
{
    background: #fff;
    margin:0 auto;	
	text-align: left;
	width:800px;
}
</style>
</head>

<body>
<div id="topborder">&nbsp;</div>
<div id="headbar">
        <div id="headmenu">
            <ul>
                <li><img width="80" src="http://www.clansuite.com/website/images/banners/clansuite-80x15.png" heigth="15" alt="Clansuite Logo 80x15" title="Clansuite Logo"/>
            </li>
                <li><a href="http://www.clansuite.com/">Home</a></li>
                <li><a href="http://www.clansuite.com/index.php#page-downloads">Downloads</a></li>
                <li><a href="http://www.clansuite.com/documentation/" target="_blank">Documentation</a></li>
                <li><a href="http://forum.clansuite.com/" target="_blank">Forum</a></li>
                <li><a href="http://trac.clansuite.com/wiki" target="_blank">Wiki</a></li>
                <li><a href="http://trac.clansuite.com/" target="_blank">Bugtracker</a></li>
            </ul>
        </div>
        <div id="langs">
            <ul>
                <!-- Change Language -->
                <li><a href="http://www.clansuite.com/index.en.php"><img title="Sprache English" alt="English" src="http://www.clansuite.com/website/images/languages/en.gif"/></a> </li>
                <li><a href="http://www.clansuite.com/index.de.php"><img title="Sprache Deutsch" alt="Deutsch" src="http://www.clansuite.com/website/images/languages/de.gif"/></a>Deutsch</li>
            </ul>
        </div>
</div>

<div id="maincontent">
<h1>Internet Relay Chat - Logs for <?php echo $channel; ?>
    <br>
    <font size="3">on <?php echo $server; ?>, collected by <?php echo $nick; ?></font>
</h1>