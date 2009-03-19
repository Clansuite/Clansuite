<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
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
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: view_smarty.class.php 2530 2008-09-18 23:12:04Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' ); }

/**
 * Clansuite Filter - Session Security
 *
 * Purpose:
 * This Filter ensures the session integrity.
 * It will destroy the current session and redirect to login, on the following conditions:
 * 
 * 1) IP changed
 * 2) Browser changed
 * 3) Host changed
 * 4) wrong passwords where tried a number of times
 *
 * @package clansuite
 * @subpackage filters
 * @implements FilterInterface
 */
class session_security implements Clansuite_FilterInterface 
{
    private $config     = null;

    function __construct(Clansuite_Config $config)
    {
       $this->config     = $config;
    }

    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        /**
         * 1. Check for IP
         */

        if ($this->config['session']['check_ip'] == true)
        {
            if ( !isset($_SESSION['client_ip']) )
            {
                $_SESSION['client_ip'] = $_SERVER['REMOTE_ADDR'];
            }
            elseif ($_SERVER['REMOTE_ADDR'] != $_SESSION['client_ip'])
            {
                session_destroy(session_id());
                
                $this->response->redirect('index.php?mod=login');
            }
        }
        
        /**
         * 2. Check for Browser
         */

        if ($this->config['session']['check_browser'] == true)
        {
            if ( !isset($_SESSION['client_browser']) )
            {
                $_SESSION['client_browser'] = $_SERVER["HTTP_USER_AGENT"];
            }
            elseif ( $_SERVER["HTTP_USER_AGENT"] != $_SESSION['client_browser'] )
            {
                session_destroy(session_id());
                
                $this->response->redirect('index.php?mod=login');
            }
        }
        
        /**
         * 3. Check for Host Address
         */

        if ($this->config['session']['check_host'] == true)
        {
            if( !isset( $_SESSION['client_host'] ) )
            {
                $_SESSION['client_host'] = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
            }
            else if ( gethostbyaddr($_SERVER["REMOTE_ADDR"]) != $_SESSION['client_host'] )
            {
                session_destroy(session_id());
                
                $this->response->redirect('index.php?mod=login');
            }
        }        
        
        /**
         * 4. Check maximal password tries
         */
        
        # take the initiative, if maximal_password_tries is enabled (greater 0)in Clansuite_Config
        # or pass to the next filter / do nothing
        /*if($this->config['session']['maximal_password_tries'] > 0)
        {
            # if PW_TRIES is lower than the configvalue
            if($_SESSION['PW_TRIES'] < $this->config['session']['maximal_password_tries'])
            {
                # check, if a form field input $_POST['password'] exists
                if(true == $this->request->issetParameter('POST','password')) 
                {
                    # if PW_TRIES does not exist, it's the first try of a password
                    if(!isset($_SESSION['PW_TRIES']))
                    {
                        $_SESSION['PW_TRIES'] = 1;
                    }

                    # if PW_TRIES exists, and is lower or equal to max_tries configvalue, then increase it
                    if($_SESSION['PW_TRIES'] <= $this->config['session']['maximal_password_tries'])
                    {
                        $_SESSION['PW_TRIES']++;
                    }

                    # @todo
                    # EVENT => check the password provided, if invalid show the password form again here.
                }
                else
                {
                    # reset our session variables.
                    unset($_SESSION['PW_TRIES']);
                }
            }
        }// else => bypass */
    }
}
?>