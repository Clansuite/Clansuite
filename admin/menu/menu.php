<?php
/*****************************************************************************/
/* Clansuite - just another E-Sport CMS                                      */
/* Copyright (C) 1999 - 2006 Jens-André Koch (jakoch@web.de)                 */
/* Lesser General Public Licence                                                                          */
/*
/* this uses mygosumenu - xulmenu (bsd)
/* from www.gosu.pl 
/*
/*****************************************************************************/

function build_menu(&$result, $parent = 0, $level = 0)    {
    $output = array();
    $rows = count($result);
    for($i = 0; $i < $rows; $i++)
        if($result[$i]['parent'] == $parent) {
            $output[$result[$i]['id']] = array(
                'name' => $result[$i]['title'],
                'level' => $level,
                'type' => $result[$i]['type'],
                'parent' => $result[$i]['parent'],
                'id' => $result[$i]['id'],
                'href' => $result[$i]['href']
                );
            $output[$result[$i]['id']]['content'] = build_menu($result, $result[$i]['id'], $level + 1);
            if (count($output[$result[$i]['id']]['content']) == 0)
                unset($output[$result[$i]['id']]['content']);
            else
                $output[$result[$i]['id']]['expanded'] = true;
        }
    return array_values($output);
}

/*
* in case we change the menü to a strict css menu
* this function generates div-ul-li menu lists
*
*/
function get_html_list($menu) {
    $result = '<div id="navcontainer"><ul id="navlist">';
    foreach($menu as $entry) {
    	if (htmlentities($entry['href']) == 'null' ) { $entry['href'] = 'javascript:void(0)'; }
        $result .= '<li><a href="'.htmlentities($entry['href']);
        $result .= '">'.htmlentities($entry['name']);
        if (isset($entry['content']))
        $result .= get_html_list($entry['content']);
    $result .= '</a></li>';
    }
    $result .= '</ul></div>';
    return $result;
}

/*
* this function generates html-div based menu lists
*
*/
function get_html_div($menu) {
    #$result = '';
    foreach($menu as $entry) {
    	if (htmlentities($entry['href']) == 'null' )   { $entry['href'] = 'javascript:void(0)'; }
    						else   { $entry['href'] = WWW_ROOT.'/'.htmlentities($entry['href']); }
        
        if (htmlentities($entry['type']) === 'button')  { $result .= '<td>'; }      
        $result .= '<a class="'.htmlentities($entry['type']).'" href="'.htmlentities($entry['href']);
        $result .= '">'.htmlentities($entry['name']);		
        if (isset($entry['content']) & (htmlentities($entry['type']) == 'item')) { $result .= '<img class="arrow" src="'.WWW_ROOT.'/menu/images/arrow1.gif" width="4" height="7" alt="" />'; }
        $result .= '</a>';
        if (htmlentities($entry['type']) == 'section') { $result .= '<div class="section">'; }
        
        if (isset($entry['content'])) {
        	$result .= '<div class="section">';
        	$result .= get_html_div($entry['content']);
        	$result .= '</div>';
        	}
    	if (htmlentities($entry['type']) === 'button')  { $result .= '</td>'; }
    }
    #$result .= '</tr>';
    return $result;
}



global $db, $tpl, $cfg, $error, $lang, $modules;
// Auslesen der Menüdaten aus der Db und sorted in Array ablegen
$statement = $db->query( 'SELECT * FROM ' . DB_PREFIX . 'adminmenu ORDER BY id ASC, parent ASC' );
$adminmenudb = $statement ->fetchAll();

#debug input-data
#print_r($adminmenudb);

// menu aufbauen
$adminmenu = build_menu($adminmenudb); 
unset($adminmenudb);

#debug output-data
#print_r($adminmenu);

// menu formatieren 
// 1. get_html_list 
// 2. get_html_div
$smartymenu = get_html_div($adminmenu);

// menü an smarty assignen
$tpl->assign('menu', $smartymenu); 


?>