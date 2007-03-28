<?php
/**
* Themes manager
* Admin Control Center > Modul > Theme Manager
*
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
class module_admin_themes
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
        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Admin'), '/index.php?mod=admin');
        $trail->addStep($lang->t('Themes'), '/index.php?mod=admin&sub=themes');

        switch ($_REQUEST['action'])
        {
            default:
            case 'show':
                $trail->addStep($lang->t('Show'), '/index.php?mod=admin&sub=themes&action=show');
                $this->show();
                break;

            case 'edit':
                $trail->addStep($lang->t('Edit'), '/index.php?mod=admin&sub=themes&action=edit');
                $this->edit();
                break;

            case 'ajax_get':
                $this->ajax_get();
                break;

            case 'ajax_save':
                $this->ajax_save();
                break;
        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
    * @desc Show all avaialbe themes
    */
    function show()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;

        // 1. installed themes
            // a. check /templates/* dirs | exclude core | get theme.xml
            
            $counter = 0;
            foreach( glob( ROOT_TPL . "/*", GLOB_BRACE|GLOB_ONLYDIR) as $directory )
            {   
               // exclude 'core' dir 
               // todo insert exclude pattern into glob? 
               // {[!c]} (excludes all dirs starting with c
               if ($directory == ROOT_TPL . '/core') 
               { 
                //nothing
               }
               else
               {
                   // get template paths, dirty way!
                   // @todo clean up following command (cut path)
                   $trimmed_dir = strstr($directory, '/templates/');
                   //var_dump($trimmed_dir);
                   $theme_folders[$counter]['themename'] = $trimmed_dir;
                   $counter++;
               }
            }
            
            // b. get xml description file
            
            
            
            
            // c. get theme preview picture
            // d. choose this as default for all, choose this as my theme
            // e. preview theme

        // 2. browse themes @ clansuite.com
        // 3. auto-integrate oswd.com themes

        // 4.

    
        //$tpl->assign( 'info', $this->build_folder_tree( ROOT_TPL ) );
        $tpl->assign( 'info', $theme_folders );
        //"{*.png,*.txt}"
        $this->output .= $tpl->fetch( 'admin/themes/show.tpl' );
    }
    
  

    /**
    * @desc Build folder tree
    */
    function build_folder_tree( $path, $x = 0 )
    {
        global $cfg, $tpl;

        $result  = ''; //todo: $x is not suitable in recursion

        $file_count = 0;

        foreach( glob( $path . '/*', GLOB_BRACE) as $file )
        {
            if($file != '.' && $file != '..' && $file != '.svn')
            {
                if ( is_dir( $file ) )
                {
                    $result .= "\t<div class='folder' id='folder-$x'>\n";
                    $result .= '<img src="'. WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-node.gif" alt="Tree Node Icon" width="18" height="18" border="0" id="node-'. $file . $x .'" onclick="javascript: node_click(\''. $file . $x .'\')" />';
                    $result .= '<img src="'. WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-folder.gif" alt="Tree Folder Icon" width="18" height="18" border="0" />';
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
                        $result .= '<img src="'. WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-leaf.gif" width="18" height="18" border="0" alt="Tree Leaf Icon" />';
                        $result .= '<img class="pic" src="' . WWW_ROOT . '/' . $cfg->tpl_folder . '/core/admin/adminmenu/images/tree-doc.gif" border="0" width="16" height="16" alt="Tree Doc Icon" />';
                        $result .= '<span id="' . $file .'" class="text" onclick="getTemplateFile(this)">';
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

        $this->output .= $tpl->fetch('admin/themes/show.tpl');
    }

    /**
    * @desc Get the tpl
    */
    function ajax_get()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;

        $filename = $_POST['filename'];

        // todo ; security
        // 1. cut filename path down to /templates
        // 2. add template path + filename to prevent from fetching files outside of template-dir or upper dirs

        $this->output .= file_get_contents( $filename );
        $this->suppress_wrapper = true;
    }

    /**
    * @desc Save the tpl
    */
    function ajax_save()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;

        $filename   = $_POST['filename'];
        $content    = $_POST['content'];

        // todo ; security
        // 1. cut filename path down to /templates
        // 2. add template path + filename to prevent from saving files outside of template-dir or upper dirs

        file_put_contents( urldecode($filename), urldecode($content) );

        $this->output .= 'Saved!';
        $this->suppress_wrapper = true;
    }
}
?>