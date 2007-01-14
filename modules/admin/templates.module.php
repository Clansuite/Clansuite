<?php
/**
* templates
* This is the Admin Control Panel
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
* @author     Jens-André Koch, Florian Wolf
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
* @desc Start module class
*/
class module_admin_templates
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
        $params = func_get_args();
        
        // Construct Page Title        
        $this->mod_page_title = $lang->t( 'Administration of Templates' ) . ' &raquo; ';
        
        switch ($_REQUEST['action'])
        {
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show all templates' );
                $this->show();
                break;
                
            case 'edit':
                $this->mod_page_title .= $lang->t( 'Edit a template' );
                $this->edit();
                break;
                
            case 'ajax_get':
                $this->ajax_get();
                break;

            case 'ajax_save':
                $this->ajax_save();
                break;
                                
            default:
                $this->mod_page_title .= $lang->t( 'Show all templates' );
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
        
        /**
        * @desc Handle the output - $lang-t() translates the text.
        */

        $tpl->assign( 'folder_tree', $this->build_folder_tree( TPL_ROOT ) );
        $this->output .= $tpl->fetch( 'admin/templates/show.tpl' );
    }
    
    /**
    * @desc Build folder tree
    */
    function build_folder_tree( $path, $x = 0 )
    {
        global $cfg;
        
        $result  = '';
        
        $file_count = 0;
        
        foreach( glob( $path . '/*', GLOB_BRACE) as $file )
        {   
            if($file != '.' && $file != '..' && $file != '.svn')
            {
                if ( is_dir( $file ) )
                {
                    $result .= "\t<div class='folder' id='folder-$x'>\n";
                    $result .= '<img src="'. WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-node.gif" width="18" height="18" border="0" id="node-'. $file . $x .'" onclick="javascript: node_click(\''. $file . $x .'\')">';
                    $result .= '<img src="'. WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-folder.gif" width="18" height="18" border="0">';
                    $result .= preg_replace( '#^(.*)/#', '', $file);
                    $result .= '<div class="section" id="section-'. $file . $x .'" style="display: none">';
                    $x++;
                    $result .= $this->build_folder_tree( $file, $x );
                    $result .= '</div>';

                }
                else
                {
                    if ( preg_match( '/(.*)\.tpl$/', $file ) || preg_match( '/(.*)\.js$/', $file ) || preg_match( '/(.*)\.css$/', $file ) || preg_match( '/(.*)\.html$/', $file ) || preg_match( '/(.*)\.htm$/', $file ) )
                    {
                        $result .= "\t<div class=\"doc\">\n";
                        $file_count++;
                        $result .= '<img src="'. WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-leaf.gif" width="18" height="18" border="0">';
                        $result .= '<img class="pic" src="' . WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-doc.gif" border="0" width="16" height="16">';
                        $result .= '<span class="text" onclick="return sendAjaxRequest(\'get\', \'' . $file . '\', \'index.php?mod=admin&sub=templates&action=ajax_get\');">';
                        $result .= preg_replace( '#^(.*)/#', '', $file);
                        $result .= '</span>';
                        $result .= '</div>';
                    }
                }
            }
        }
        if( $file_count < 0 )
        {
            $result .= "</div>\n";
        }
        $result .= "</div>\n";
        return $result;
    }
    
    /**
    * @desc Edit a template
    */
    function edit()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
        
        /**
        * @desc Handle the output - $lang-t() translates the text.
        */
        
        $this->output .= $tpl->fetch('admin/templates/show.tpl');
    }
    
    /**
    * @desc Get the tpl
    */
    function ajax_get()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
        
        $tpl_path = $_POST['tpl_path'];
        $this->output .= file_get_contents( $tpl_path );
        $this->suppress_wrapper = true;
    }
    
    /**
    * @desc Save the tpl
    */
    function ajax_save()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
        
        $tpl_path   = $_POST['tpl_path'];
        $content    = $_POST['content'];
        file_put_contents( urldecode($tpl_path), urldecode($content) );
        $this->suppress_wrapper = true;
    }
}
?>