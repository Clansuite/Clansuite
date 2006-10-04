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
    public $mod_page_title  = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loads necessary language files
    */

    function auto_run()
    {
        global $lang;
        
        $this->mod_page_title = $lang->t( ' Static Page &raquo; ' );
        
        switch ($_REQUEST['action'])
        {   
            default:
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show' );
                $this->show();
                break;
       
       }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
    * @desc Show the entrance - welcome message etc.
    */
    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
        
        $page = $_GET['page'];
        
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
            $this->output .= $lang->t('This static page does not exist.');
        }
    }
}
?>