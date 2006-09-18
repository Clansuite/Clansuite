<?php
/**
* {$name}
* {$description}
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
* @author     {$author}
* @copyright  {$copyright}
* @license    {$license}
* @version    SVN: $Id$
* @link       {$homepage}
*/

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{ldelim}
    die('You are not allowed to view this page statically.' );
{rdelim}

/**
* @desc Start module class
*/
class {$class_name}
{ldelim}
    public $output          = '';
    public $mod_page_title  = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loads necessary language files
    */

    function auto_run()
    {ldelim}
        
        global $lang;
        $params = func_get_args();
        
        // Construct Page Title        
        $this->mod_page_title = $lang->t( '{$title}' ) . ' &raquo; ';
        
        switch ($_REQUEST['action'])
        {ldelim}
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show' );
                $this->show();
                break;

            case 'instant_show':
                $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
                break;
                
            default:
                $this->show();
                break;
        {rdelim}
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    {rdelim}

    /**
    * @desc Show the entrance - welcome message etc.
    */
    function show()
    {ldelim}
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;
        
        /**
        * @desc Handle the output - $lang-t() translates the text.
        */
        $this->output .= $lang->t('You have created a new module, that currently handles this message');
    {rdelim}
    
    /**
    * @desc This content can be instantly displayed by adding {ldelim}mod name="{$name}" func="instant_show" params="mytext"{rdelim} into a template
    * @desc You have to add the lines as shown above into the case block: $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
    */
    function instant_show($my_text)
    {ldelim}
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;
        
        /**
        * @desc Handle the output - $lang-t() translates the text.
        */
        $this->output .= $lang->t($my_text);
    {rdelim}
{rdelim}
?>