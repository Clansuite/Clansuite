<?php
/**
* serverlist
* List Gameservers
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
* @author     Jens Andre Koch
* @copyright  Clansuite Group
* @license    BSD
* @version    SVN: $Id$
* @link       
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
class module_serverlist_admin
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
        $this->mod_page_title = $lang->t( 'Serverlist' ) . ' &raquo; ';
        
        switch ($_REQUEST['action'])
        {
            default:
            case 'show':
                $this->mod_page_title .= $lang->t( 'Show Servers' );
                $this->show_servers();
                break;
                
            case 'lookup_server':
                $this->mod_page_title .= $lang->t( 'Show Servers' );
                $this->lookup_server();
                break;

            case 'create':
                $this->mod_page_title = $lang->t( 'Add a new Server' );
                $this->create_server();
                break;
    
            case 'edit':
                $this->mod_page_title = $lang->t( 'Edit Server' );
                $this->edit_server();
                break;
                
            case 'delete':
                $this->mod_page_title = $lang->t( 'Delete Server' );
                $this->delete_server();
                break;
            
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }


    /**
    * @desc Ajax Call to add a new Server
    */
    function add_new_server()
    {
        /*
        $_POST['gametype']
        
        $_POST['ip']
        $_POST['port']
        */
    }
    
    /**
    * @desc Ajax Call to add a new Server
    */
    function lookup_server()
    {
        /*
        $_POST['gametype']
        
        $_POST['ip']
        $_POST['port']
        
        1. initialite csQuery with gametype
        
        2. get serverdata (name) into $lookuparray
        
        3. tpl assign $lookuparray
        
        4. tpl output
        */
        
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
    
        // Einbettung in den Hauptframe unterdrcken
        $this->suppress_wrapper = true;
        
        // Get Server from DB
        $stmt = $db->prepare('SELECT * FROM '. DB_PREFIX .'serverlist WHERE server_id = ?');
        $stmt->execute( array ( $_GET['server_id'] ) );
        $serverdata = $stmt->fetch(PDO::FETCH_ASSOC); 
        $serverdata['server_id'] = $_GET['server_id'];
        
        // Severdetails einholen
        $this->getServerdetails($serverdata);
        
        #$tpl->display('serverlist/serverstats/'. $serverdata['gametype'] . '.tpl');
        echo 'true;You\'ve been successfully registered';
        $this->output .= 'test';
    }

    /**
    * @desc Show the entrance - welcome message etc.
    */
    function show_servers()
    {
       global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
       
       // Get Server from DB
       $stmt = $db->prepare('SELECT * FROM '. DB_PREFIX .'serverlist');
       $stmt->execute();
       $servers = $stmt->fetchALL(PDO::FETCH_ASSOC); 
       #var_dump($servers);
       
       // load smarty_ajax
       require_once( CORE_ROOT . '/smarty/smarty_ajax.php');
       
       ajax_register('lookup_server');
       ajax_register('add_server');
       ajax_process_call();
      
       // $newslist an Smarty übergeben und Template ausgeben
       $tpl->assign('servers', $servers);
     
    /**
    * @desc Handle the output - $lang-t() translates the text.
    */
    $this->output .= $tpl->fetch('serverlist/show_admin.tpl');
    }
    
    /**
    * @desc This content can be instantly displayed by adding {mod name="filebrowser" sub="admin" func="instant_show" params="mytext"} into a template
    * @desc You have to add the lines as shown above into the case block: $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
    */
    function instant_show($my_text)
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
        
        /**
        * @desc Handle the output - $lang-t() translates the text.
        */
        $this->output .= $lang->t($my_text);
    }
}
?>