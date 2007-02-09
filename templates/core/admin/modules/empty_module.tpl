<?php
/**
* Modulename:   {$name}
* Description:  {$description}
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

// Security Handler
if (!defined('IN_CS')) {ldelim} die('You are not allowed to view this page.' ); {rdelim}

// Begin of class {$class_name}
class {$class_name}
{ldelim}
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc General Function Hook of {$name}-Modul
    *
    * 1. Set Pagetitle and Breadcrumbs
    * 2. $_REQUEST['action'] determines the switch
    * 3. function title is added to page title, to complete the title
    * 4. switch-functions are called
    *
    * @return: array ( OUTPUT, ADDITIONAL_HEAD, SUPPRESS_WRAPPER )
    *
    */

    function auto_run()
    {ldelim}

        global $lang,$trail;
        $params = func_get_args();

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('{$title}'), '/index.php?mod={$name}');

        //
        switch ($_REQUEST['action'])
        {ldelim}

            default:
            case 'show':
                $trail->addStep($lang->t('Show'), '/index.php?mod={$name}&action=show');
                $this->show();
                break;

            case 'instant_show':
                $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
                break;

        {rdelim}

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    {rdelim}


     /**
    * @desc Function: Show
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
    * @desc Function: instant_show
    *
    * This content can be instantly displayed by adding this into a template:
    * {ldelim}mod name="{$name}" func="instant_show" params="mytext"{rdelim}
    *
    * You have to add the lines as shown above into the case block:
    * $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
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