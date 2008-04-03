<?php 
/**
 * Security Handler
 */
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}
?>
<html>
    <head>
        <title>{t}Warning: Intrusion Detection{/t}</title>
    </head>
<body>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <div align="center">
        <img src="<?=WWW_ROOT_THEMES_CORE?>/images/security_breach.jpg" border="0">
        <br>
        <font face="Verdana" size="2" color="red">
            <strong><?=_('Warning: Intrusion Detection');?></strong>
            <br>
            <?=_('Sorry! But your latest action got classified as an possible hacking attempt or there is an unusual failure in progress.');?>
            <br>
            <?=_('User actions were logged and reported to the webmaster.');?>
        </font>
    </div>
</body>
</html>