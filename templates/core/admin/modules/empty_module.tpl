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
{literal}{{/literal}
    die('You are not allowed to view this page statically.' );
{literal}}{/literal}

/**
* @desc Start module index class
*/
class {$class_name}
{literal}{{/literal}
    public $output          = '';
    public $mod_page_title  = '';
    public $additional_head = '';

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loads necessary language files
    */

    function auto_run()
    {literal}{{/literal}
        global $lang;
        
        $this->mod_page_title = $lang->t( '{$name}' );
        
        switch ($_REQUEST['action'])
        {literal}{{/literal}
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show' );
                $this->show();
                break;

            default:
                $this->show();
                break;
        {literal}}{/literal}
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head );
    {literal}}{/literal}

    /**
    * @desc Show the entrance - welcome message etc.
    */

    function show()
    {literal}{{/literal}
        global $cfg, $db, $tpl, $error, $lang, $functions, $security;
        
        $this->output .= 'You have created a new module, that currently handles this message';
    {literal}}{/literal}
{literal}}{/literal}
?>