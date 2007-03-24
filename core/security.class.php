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
if (!defined('IN_CS')){ die('You are not allowed to view this page.' );}

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
     * @return 2xmd5 string
     * @todo note by vain: fmpov the double encodation is somehow security by obscurity ? 
     *       is there an alternative?
     */

    function generate_md5( $string = '' )
    {
        return md5(md5($string ) );
    }

    /**
     * This generates a double SHA1 encoded string (Hash)
     *
     * @param string
     * @return 2xsha1 string
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
     * Check for {$copyright} tag in $cfg->tpl_wrapper_file
     *
     * @param string $file contains the filename to check for copyright
     * @global $lang
     * @global $error
     * @todo in case there's no correc template wrapper found... check lines in the code below
     */

    function check_copyright( $file )
    {
        global $lang, $error;

        /**
         * check for existance of the main tpl_wrapper_file
         * (index.tpl related to the choosen template directory)
         */
        
        if (file_exists($file) )
        {
            /**
             * Check for a removal or out-commenting of Copyright Tag
             */
            
            $string = file_get_contents($file);
            
            preg_match('/\{\$copyright\}/' , $string ) ? '' : die($error->show( $lang->t('Copyright Violation'), $lang->t('You removed the copyright tag - that is not allowed and a violation of our rules! Please put {$copyright} in the main template file.'), 1) );
            preg_match('/\<\!\-\-[^>](.*)\{\$copyright\}(.*)\-\-\>/', $string ) ? die($error->show( $lang->t('Copyright Violation'), $lang->t('Do not try to fool the system by hiding the copyright tag!'), 1 ) ) : '';
        }
        else
        {
            /**
             * Error: Template File not found
             */
             
            die( $error->show( $lang->t('Template File not found !'),
                               $lang->t('The main template file of the choosen template was not found! <br /> Please ensure correct spelling and existence of (a) your template dir (b) the template filename (c) compared to the related settings.'),
                               1 ) );

           /* todo:
              in case there's no correct template wrapper file found,
              switch (a) to the default or (b) the next possible template dir
              if there is still nothing found, report error.

           */
        }
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