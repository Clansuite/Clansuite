<?php defined('IN_CS') or exit('Direct Access forbidden.');
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         {$name}.module.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Configuration of Clansuite Module Class - {$name}
    *               {$description}

    *
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *

    */

/**
 *  Security Handler
 */
if (!defined('IN_CS')) {ldelim} die('You are not allowed to view this page.'); {rdelim}

/**
 * Subfiles of the module
 * -----------------------
 * Subfiles are used to extend the usage of a module,
 * by shuffling functionality into a new file.
 *
 * For example:
 * If you have a module, that becomes beyond 3000 lines and you want to split that,
 * you can create a sub-module and shuffle off some of the functionality into the new file.
// So you've got the option to call the new submodule directly by its URL
 *
 * http://URL/index.php?mod=mymodule&sub=mysubmodule
 *
 * or from inside a template by using the {ldelim}mod{rdelim} - block
 * {ldelim}mod name="{$name}" func="show" sub="mysubmodule" params="myparams"{rdelim}
 *
 * $sub_files = array( 'sub_module_name' => array( 'file_name', 'class_name' ) );
 */

{if is_array($subs)}
{* DEBUG will be printed in file :) {$subs|var_dump} *}

$info['subs'] = array( {foreach key=key item=item from=$subs}
                        '{$key}' => array( '{$item.file_name}', '{$item.class_name}' ),
                       {/foreach}
                     );
{else}
$info['subs'] = array();
{/if}

/**
 * Infos
 * -----
 * These infos are BACKUP Infos! They do not alter the shown
 * infos in any way. Just in case somebody installed a module by
 * copy and paste into the module folder.
 * Those are used as a reference!
 * If you want to change the real values, so lookup the
 * module in the admin interface.
 */

$info['author']         = '{$author}';
$info['homepage']       = '{$homepage}';
$info['license']        = '{$license}';
$info['copyright']      = '{$copyright}';
$info['timestamp']      = {$timestamp};
$info['name']           = '{$name}';
$info['title']          = '{$title}';
$info['description']    = '{$description}';
$info['class_name']     = '{$class_name}';
$info['file_name']      = '{$file_name}';
$info['folder_name']    = '{$folder_name}';
$info['image_name']     = '{$image_name}';
$info['module_version']        = (float) {$module_version};
$info['clansuite_version']     = (float) {$cs_version};
$info['core']           = {$core};

/**
 * Admin Menu Entries
 */

$info['admin_menu'] = '{$admin_menu}';

?>