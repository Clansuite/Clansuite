<?php
/*
* Smarty plugin
* --------------------------------------------------------
* File:    prefilter.inserttplnames.php
* Type:    prefilter
* Name:    inserttplcomment
* Version: 1.0
* Date:    03 Jun 2006
* Purpose: Add Comment with Teplatename at begin & end of
*		   included tpl
* Install: Place in your (local) plugins directory and
*          add the call:
*          $smarty->load_filter('pre', 'inserttplnames');
* Author:  Jens-André Koch
* --------------------------------------------------------
*/
function smarty_prefilter_inserttplnames( $tpl_source, &$compiler )
{
    #die(var_dump($this));
    return "\n<!-- [Start] Included Template {\$smarty.template} -->\n".$tpl_source."\n<!-- [-End-] Included Template {\$smarty.template}  -->\n";
}
?>