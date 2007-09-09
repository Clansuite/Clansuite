<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         security.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Security Handling
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
    * @version    SVN: $Id$
    */

/**
 * Security Handler
 */
if (!defined('IN_CS')){ die('Clansuite Framework not loaded. Direct Access forbidden.' );}

/**
 * This Clansuite Core Class for Security Handling
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  security
 */
class security
{
    /**
     * This generates a salt, a random combination of numbers like this "4-5-6-7-8-9"
     *
     * @return $salt
     */

    function generate_salt()
    {
        $salt = rnd(0,9).'-'.rnd(0,9).'-'.rnd(0,9).'-'.rnd(0,9).'-'.rnd(0,9).'-'.rnd(0,9);
        $salt .= '-'.rnd(0,9).'-'.rnd(0,9).'-'.rnd(0,9).'-'.rnd(0,9).'-'.rnd(0,9).'-'.rnd(0,9);
        return $salt;
    }

    /**
     * This generates a double MD5 encoded string (Hash)
     *
     * @param string
     * @return doubled md5 string
     * @todo note by vain: fmpov the double encoding is somehow security by obscurity ?
     *       is there an alternative?
     *       note by xsign: well - it does help to NOT gain the password. You habe to decrypt
     *       to an 32 digit password first, to get to the real password. So: no way to gain the real password.
     *       Anyway: The SALT will the all the job to crash every hacking attempt.
     */

    function generate_md5( $string = '' )
    {
        return md5(md5($string ) );
    }

    /**
     * This generates a double SHA1 encoded string (Hash)
     *
     * @param string
     * @return doubled sha1 string
     */

    function generate_sha1( $string = '' )
    {
        return sha1(sha1($string ) );
    }

    /**
     * This builds a salted Hash string (Cookie Hash)
     *
     * @global $cfg
     * @param string
     * @return $hash
     */

    function build_salted_hash( $string = '' )
    {
        global $cfg;

        $salt = split('-', $cfg->salt);

        switch ( $cfg->encryption )
        {
            case 'sha1':
                $hash = $this->generate_sha1( $string );
                break;

            case 'md5':
                $hash = $this->generate_md5( $string );
                break;
        }


        for ($x=0; $x<6; $x++)
        {
            $hash = str_replace( $salt[$x], $salt[$x+6], $hash );
        }

        return $hash;
    }

    /**
     * Build the DB salted Hash
     *
     * @param string
     * @return hash
     */

    function db_salted_hash( $string = '' )
    {
        return $this->build_salted_hash( $this->build_salted_hash( $string ) );
    }

    
    /**
     * Intruder Alert
     *
     * This will assign and report the userdata of the intruder and exit,
     * in case the intruder alert is triggered.
     *
     * @global $tpl
     * @global $lang
     * @todo 1) add logging! 2) should the intrusion alert be combined with session::session_security()?
     */

    function intruder_alert()
    {
        global $tpl, $lang;

        $tpl->assign('hacking_attempt'  , $lang->t('Possible Intrusion detected - The logging is active. A report will be generated.'));
        $tpl->assign('user_ip'          , $_SERVER['REMOTE_ADDR']);
        $tpl->assign('hostname'         , isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : '*** not supported by your apache ***' );
        $tpl->assign('user_agent'       , $_SERVER['HTTP_USER_AGENT']);

        $tpl->display('security_breach.tpl' );

        die();
    }
}
?>