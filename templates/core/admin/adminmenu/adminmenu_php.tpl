{php}
/*****************************************************************************/
/* Clansuite - just another E-Sport CMS                                      */
/* Copyright (C) 1999 - 2006 Jens-André Koch (jakoch@web.de)                 */
/* Lesser General Public Licence                                             */
/*
/* this uses mygosumenu - xulmenu 
/* by cagret www.gosu.pl
/*****************************************************************************/

//----------------------------------------------------------------
// Read menu from DB
//----------------------------------------------------------------
global $db;

$stmt = $db->prepare('SELECT *
                      FROM ' . DB_PREFIX .'adminmenu
                      ORDER BY id ASC, parent ASC');
$stmt->execute();
$adminmenudb = $stmt->fetchAll( PDO::FETCH_ASSOC );

function build_menu(&$result, $parent = 0, $level = 0)
{
    $output = array();
    $rows = count($result);
    for($i = 0; $i < $rows; $i++)
    {
        if($result[$i]['parent'] == $parent)
        {
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
    }    
    return array_values($output);
}

//----------------------------------------------------------------
// In case we change the menu to a strict css menu
// this function generates div-ul-li menu lists
//----------------------------------------------------------------
function get_html_list($menu) {
    $result = '<div id="navcontainer"><ul id="navlist">';
    foreach($menu as $entry) {
    	if ($entry['href'] == 'null' ) { $entry['href'] = 'javascript:void(0)'; }
        $result .= '<li><a href="'.$entry['href'];
        $result .= '">'.htmlentities($entry['name']);
        if (isset($entry['content']))
        $result .= get_html_list($entry['content']);
    $result .= '</a></li>';
    }
    $result .= '</ul></div>';
    return $result;
}

//----------------------------------------------------------------
// This function generates html-div based menu lists
//----------------------------------------------------------------
function get_html_div($menu)
{
    #$result = '';
    foreach($menu as $entry)
    {
    	if ($entry['href'] == '' )
        {
            $entry['href'] = 'javascript:void(0)';
        }
        else
        {
            $c = parse_url($$entry['href']);
            if( !array_key_exists('host', $c) )
            {
                $entry['href'] = WWW_ROOT . $entry['href'];
            }
        }
                        
        if (htmlentities($entry['type']) == 'button')
        {
            $result .= '<td>';
        }
              
        $result .= '<a class="'.htmlentities($entry['type']).'" href="'.$entry['href'];
        $result .= '">'.htmlentities($entry['name']);
        		
        if ( isset($entry['content']) AND ( htmlentities($entry['type']) == 'item') )
        {
            $result .= '<img class="arrow" src="';
            $result .= WWW_ROOT . '/templates/core/images/adminmenu/arrow1.gif" width="4" height="7" alt="" />';
        }
        $result .= '</a>';
        
        if (htmlentities($entry['type']) == 'section')
        {
            $result .= '<div class="section">';
        }
        
        if (isset($entry['content']))
        {
        	$result .= '<div class="section">';
        	$result .= get_html_div($entry['content']);
        	$result .= '</div>';
        }
    	
        if (htmlentities($entry['type']) == 'button')
        {
            $result .= '</td>';
        }
    }
    #$result .= '</tr>';
    return $result;
}

$adminmenu = build_menu($adminmenudb); unset($adminmenudb);
{/php}




<!-- start: Menu - Kopfzeile 2//-->

<script type="text/javscript">
    /* preload images */
    var arrow1 = new Image(4, 7);
    arrow1.src =  "{$www_core_tpl_root}/images/arrow1.gif";
    var arrow2 = new Image(4, 7);
    arrow2.src =  "{$www_core_tpl_root}/images/arrow2.gif";
    </script>

<div id="menugradient">

<div id="bar" class="bar">
<table cellspacing="0" cellpadding="0" id="menu1" class="XulMenu">
  <tr>
        <td><img src="{$www_core_tpl_root}/images/adminmenu/nubs.gif" alt="" /></td>


{php}
echo get_html_div($adminmenu);
{/php}

  </tr>
</table>

 <script type="text/javascript">
    var menu1 = new XulMenu("menu1");
    menu1.arrow1 = "{$www_core_tpl_root}/images/adminmenu/arrow1.gif";
    menu1.arrow2 = "{$www_core_tpl_root}/images/adminmenu/arrow2.gif";
    menu1.init();
    </script>
    
    </div>
		
    <div id="search" class="XulMenu">
            
        <!--
        <input type="text" name="searchField" value="" />
        <select name="searchWhat"><option value="">Articles</option><option value="">Links</option><option value="">PHP Manual</option></select>
        <input type="button" value="Search" />
        //-->
           
        <a class="itembtn" href="{$hmpf-keinplan-www_root}/users/usercenter.php" >
        <img src="{$www_core_tpl_root}/images/adminmenu/user.gif" border="0" alt="user-image" />
        {$smarty.session.user.first_name} '{$smarty.session.user.nick}' {$smarty.session.user.last_name}</a>
        
        <a class="itembtn" href="{$hmpf-keinplan-www_root}/account/logout.php">
        <img src="{$www_core_tpl_root}/images/adminmenu/logout.gif" border="0" alt="logout-image" />Logout</a>           
    
    </div>	 
    
</div>
<!-- end: Menu- Kopfzeile 2 //-->