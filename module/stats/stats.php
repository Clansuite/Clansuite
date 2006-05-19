<?php
require '../../shared/prepend.php';

$statslist = $Db->getAll("SELECT n.* FROM " . DB_PREFIX . "stats n ORDER BY date DESC");

include_header('Stats');
 
//MAIN
$_CONFIG['template_dir'] = ROOT .'/module/stats/templates';
$Page = new SmarterTemplate( "stats.tpl" );
$Page->assign('modullanguage', $modullanguage);
$Page->assign('corelanguage', $corelanguage);
$Page->assign('stats', $statslist);

//template ausgeben
if (DEBUG == 1) { $Page->debug(); 
echo "Debug Array from Database Query | <pre>",print_r($statslist),"</pre>"; 
			} else { $Page->output(); }
			
include_footer();
?>