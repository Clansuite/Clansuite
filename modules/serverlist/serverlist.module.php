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
class module_serverlist
{
    public $output          = '';
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc First 
    function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loads necessary language files
    */
    function auto_run()
    {
        global $lang, $trail;
        
        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Serverlist'), '/index.php?mod=serverlist'); 
        
        switch ($_REQUEST['action'])
        {                   
            default:
            case 'show':
                $trail->addStep($lang->t('Show Gameservers & Details'), '/index.php?mod=serverlist&action=show'); 
                $this->show_servers();
                break;            
            
            case 'get_serverdetails':
                $trail->addStep($lang->t('Gets Serverdetails'), '/index.php?mod=serverlist&action=get_serverdetails'); 
                $this->get_serverdetails();
                break;
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
    * @desc Ajax Function to update the Serverdetails
    */
    function get_serverdetails() 
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
    
        // get serverdata from db
        $stmt = $db->prepare('SELECT * FROM '. DB_PREFIX .'serverlist WHERE server_id = ?');
        $stmt->execute( array ( $_GET['server_id'] ) );
        $serverdata = $stmt->fetch(PDO::FETCH_ASSOC); 
        $serverdata['server_id'] = $_GET['server_id'];
        
        // get severdetails from server
        $this->getServerdetails($serverdata);
        
        // output
        $tpl->display('serverlist/serverstats/'. $serverdata['csquery_engine'] . '.tpl');
        
        // suppress mainframe
        $this->suppress_wrapper = true;
   } 
    
    
   /**
   * @desc queryServer
   *
   * 1. load csQuery Class
   * 2. instatiante query object
   * 3. fetch
   * 4. or print debug/error dump, if fetch failed
   */
   function queryServer( $address, $port, $protocol )
   {
             
      // set path - csQuery_DIR
      define('csQuery_DIR'    , str_replace('\\', '/', dirname(__FILE__) ) . '/csquery/');
      include_once( csQuery_DIR . 'csQuery.php'); 
    
      if(!$address && !$port && !$protocol) {
        echo "No parameters given\n";
        return FALSE;
      }
    
      $gameserver = csQuery::createInstance( $protocol, $address, $port );
      if(!$gameserver) {
        echo "Could not instantiate csQuery class. Does the protocol you've specified exist?\n";
        return FALSE;
      }
     
     if(!$gameserver->query_server(TRUE, TRUE)) { // fetch everything
        // query was not succesful, dumping some debug info
        echo "<div>Error ".$gameserver->errstr."</div>\n";
        return FALSE;
      }
    
      return $gameserver;
    }
   
   
   /**
    * @desc Ajax Function to get Serverdetails
    */
    function getServerdetails($servers) 
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
        
        /* supported gameserver types:
         *
         * steam, q3a
         *
         * not yet supported:
         *
         * aa, atron, bf, bfv, bf2, cod, cod2, descent3, des3gs, d3, et, fear, halo,
         * hl_old, hl2, hd2, jedi, jedi2, mohaa, nolf, pk, ro, rtcw, rune, sof2,
         * swat, ts, ut, ut2003, ut2004, qw, q1, q2, q4, warsow,
         */
     
     
       // build server_object filled with rquested serverdata
       $server_object = $this->queryServer( $servers['ip'], $servers['port'], $servers['csquery_engine'] );
    
       #var_dump($server_object);
       
       // return an associative array of object variable names
       $server_data = get_object_vars($server_object); 
    
       /*
        *  prepare specific outputs
        */
       
       // 1. reassign serverid
       $server_data['server_id'] = $servers['server_id'];
       
       // 2.  time of request
       $timestamp = time();
       $server_data['time_of_request']= date("l F d H:i:s Y", $timestamp);
        
       // 3. mapfile
       
       // construct mapfile, get mapname, look for image
       // example: "halflife/de_dust.jpg"
       $server_data['mapfile'] = $server_data['gametype'] . '/' . $server_data['mapname'] . '.jpg';
	   
	   if (!(file_exists($$server_data['mapfile'])))
       { // set default map if no picture found
         $server_data['mapfile'] = 'unknown_map.png';
        } 
	       
          
            /*
             }
            else  // default values if no response
            {   
            $msg = 'No Response';
        	$servers['playerlist'] = '';
        	
        	$servers['hostname']    = $msg;
        	$servers['gamename']    = $msg . "<br>";
        	$servers['map']         = $phgdir . 'maps/no_response.jpg';
        	$servers['mapname']     = 'no response';
        	$servers['sets']        = '-';
        	$servers['htmlinfo']    = '<tr valign="top"><td align="left">No</td><td align="left">Response</td></tr>' . "\n";
        	$servers['htmldetail'] = '<tr valign="top"><td align="left">No</td><td align="left">Response</td></tr>' . "\n";
            
            // set serverstats_filename to noresponse.tpl   
            $servers['serverstats_filename'] = ROOT_TPL . '/standard/serverlist/serverstats/noresponse.tpl';
            }
       */
       $tpl->assign('serverdata', $server_data);
   
    }
    
     
    /**
    * @desc Shows All Servers with enabled ajax-fetch of Serverdetails
    */
    function show_servers()
    {
      global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input;
      
      // load smarty_ajax
      //require_once( ROOT_CORE . '/smarty/smarty_ajax.php');
      
      // register function avaiable for ajax
      // put function in to ajax calls array      
      //ajax_register('get_serverdetails');
      //ajax_process_call();
      
      // get Server from DB
      $stmt = $db->prepare('SELECT * FROM '. DB_PREFIX .'serverlist');
      $stmt->execute();
      $servers = $stmt->fetchALL(PDO::FETCH_ASSOC); 
      
      // assign servers-array to smarty
      $tpl->assign('servers', $servers);
    
    /**
    * @desc Handle the output - $lang-t() translates the text.
    */
    $this->output .= $tpl->fetch('serverlist/ajax_show.tpl');
    }
}


?>