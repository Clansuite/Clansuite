<?php
/**
* static
* Static Pages store HTML content
*
* PHP >= version 5.1.4
*
* LICENSE:
*
*    This program is free software; you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation; either version 2 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @author     Florian Wolf <xsign.dll@clansuite.com>
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @link       http://gna.org/projects/clansuite
*
* @author     Florian Wolf
* @copyright  Clansuite Group
* @license    GPL v2
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

/**
* @desc Start module index class
*/
class module_static
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loads necessary language files
    */

    function auto_run()
    {
        global $lang, $trail;
        
        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Static Pages'), '/index.php?mod=static');
               
        switch ($_REQUEST['action'])
        {   
            default:
            case 'show':
                $trail->addStep($lang->t('Show Page'), '/index.php?mod=static&action=show');
                $this->show();
                break;
                
            case 'overview':
                $trail->addStep($lang->t('Overview'), '/index.php?mod=static&action=show');
                $this->overview();
                break;
       
       }
        
        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
    * @desc Show a specific static page
    */
    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $trail;
        
        $page = $_GET['page'];
        $trail->addStep($lang->t($page), '/index.php?mod=static&action=show&page='. $page);
        
        if ( !empty($page) AND $input->check( $page, 'is_abc|is_int|is_custom', '_\s' ) )
        {
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'static_pages WHERE title = ?' );
            $stmt->execute( array( $page ) );
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ( !is_array( $result ) )
            {
                $this->output .= $lang->t('This static page does not exist.');
            }
            else
            {
                if ( empty($result['url']) )
                {
                    $this->mod_page_title = $result['title'] . ' - ' . $result['description'];
                    $this->output .= $result['html'];
                }
                else
                {
                    $this->mod_page_title = $result['title'] . ' - ' . $result['description'];
                    if ( $result['iframe'] == 1 )
                    {
                        $this->output .= '<iframe width="100%" height="'. $result['iframe_height'] .'" frameborder="0" scrolling="auto" src="' . $result['url'] . '"></iframe>';
                    }
                    else
                    {
                        $this->output .= file_get_contents( $result['url'] );
                    }
                }
            }
        }
        else
        {
            $functions->redirect('index.php?mod=static&action=overview');
        }
    }
    
    
     /**
    * @desc Show an overview of all static pages (Static Page Index)
    */
    function overview()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
        
        // get all static pages without page content
        $stmt = $db->prepare( 'SELECT id, title, description FROM ' . DB_PREFIX . 'static_pages' );
        $stmt->execute();
        $result = $stmt->fetchALL(PDO::FETCH_NAMED);
        
        if ( !is_array( $result ) )
            {
                $this->output .= $lang->t('No static pages found.');
            }
         else
            {
               $tpl->assign('overview', $result);
               $this->output .= $tpl->fetch('staticpages/overview.tpl');
            }
        
    }
    
    
}
?>