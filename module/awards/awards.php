<?php
require '../../shared/prepend.php';

$awardslist = $Db->getAll("SELECT n.* FROM " . DB_PREFIX . "awards n ORDER BY awards_date DESC");

include_header('Awards');
 
//MAIN
$_CONFIG['template_dir'] = ROOT .'/module/awards/templates';
$Page = new SmarterTemplate( "awards.tpl" );
$Page->assign('modullanguage', $modullanguage);
$Page->assign('corelanguage', $corelanguage);
$Page->assign('awards', $awardslist);

//template ausgeben
if (DEBUG == 1) { $Page->debug(); 
echo "Debug Array from Database Query | <pre>",print_r($awardslist),"</pre>"; 
			} else { $Page->output(); }
			
include_footer();
?>