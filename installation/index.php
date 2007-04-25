<?php
/**
* Installer
*
* PHP versions 5.1.4
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
* @license    see COPYING.txt
* @version    SVN: $Id: index.php 156 2006-06-14 08:45:48Z xsign $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/

/**
 * Security Handler
 */
define('IN_CS', true);

// The Clansuite version this script installs
$cs_version = '0.1';

// Define Root Path
define('CS_ROOT', './');

// In Case config.class.php exists, exit
/* @todo: other solution pls - 'cause the webinstaller currently leeches from the svn snapshot!!!
if (file_exists(CS_ROOT.'config.class.php'))
{
	exit('The file \'config.class.php\' already exists which would mean that <strong>Clansuite</strong> '. $cs_version . ' is already installed. You should go <a href="../index.php">here</a> instead.');
}*/

/**
* @desc Alter php.ini settings
*/
ini_set('display_errors'                , true);
ini_set('zend.ze1_compatibility_mode'   , false);
ini_set('zlib.output_compression'       , true);
ini_set('zlib.output_compression_level' , '6');
ini_set('arg_separator.input'           , '&amp;');
ini_set('arg_separator.output'          , '&amp;');

// Turn off PHP time limit
@set_time_limit(0);

session_start();

/**
* @desc Generate the Session out of the POST data
*/
$_SESSION = array_merge( $_SESSION, $_POST );

/**
* @desc Suppress Errors
*/
//error_reporting(0);

/**
* @desc STEP 1
*/
if( !isset($_GET['step']) || $_GET['step'] == 1 )
{
    require( 'install-step1.php' );
}

/**
* @desc STEP 2
*/
if( $_GET['step'] == 2 )
{
    /**
    * @desc Check whether form is filled or not
    */

    if( empty($_SESSION['admin_firstname']) ||
        empty($_SESSION['admin_lastname']) ||
        empty($_SESSION['config']['from']) ||
        empty($_SESSION['admin_nick']) ||
        empty($_SESSION['admin_pass']) ||
        empty($_SESSION['config']['salt']) )
    {
        header("Location: " . $_SERVER['PHP_SELF'] . "?step=1&error=fill_form");
        die();
    }

    /**
    * @desc Load template for step 2
    */
    require( 'install-step2.php' );
}


/**
* @desc STEP 3
*/
if( $_GET['step'] == 3 )
{
    if( $_SESSION['ftp_no_data'] != 1 )
    {
        /**
        * @desc Check whether form is filled or not
        */
        if( empty($_SESSION['ftp_hostname']) ||
            empty($_SESSION['ftp_port']) ||
            empty($_SESSION['ftp_pass']) ||
            empty($_SESSION['ftp_username']) ||
            empty($_SESSION['ftp_folder']) )
        {
            header("Location: " . $_SERVER['PHP_SELF'] . "?step=2&error=fill_form");
            die();
        }

        /**
        * @desc Check the connection
        */
        $ftp_con = ftp_connect($_SESSION['ftp_hostname'], $_SESSION['ftp_port']);
        if( !ftp_con )
        {
            header("Location: " . $_SERVER['PHP_SELF'] . "?step=2&error=no_connection");
            die();
        }
        else
        {
            $ftp_login = ftp_login($ftp_con, $_SESSION['ftp_username'], $_SESSION['ftp_pass']);
            if( !$ftp_login )
            {
                header("Location: " . $_SERVER['PHP_SELF'] . "?step=2&error=no_connection");
                die();
            }
            else
            {
                ftp_close($ftp_con);
            }
        }
    }

    /**
    * @desc Load template for step 3
    */
    require( 'install-step3.php' );
}


/**
* @desc STEP 4 - FINISH
*/
if( $_GET['step'] == 4 )
{
    /**
    * @desc Check whether form is empty or not
    */
    if( empty($_SESSION['config']['db_type']) ||
        empty($_SESSION['config']['db_host']) ||
        empty($_SESSION['config']['db_username']) ||
        empty($_SESSION['config']['db_password']) ||
        empty($_SESSION['config']['db_name']) ||
        empty($_SESSION['config']['db_prefix']) )
    {
        header("Location: " . $_SERVER['PHP_SELF'] . "?step=3&error=fill_form");
        die();
    }

    /**
    * @desc Check DB Connection
    */
    try
    {
       $db = new PDO($_SESSION['config']['db_type'].':host='.$_SESSION['config']['db_host'].';dbname='.$_SESSION['config']['db_name'], $_SESSION['config']['db_username'],$_SESSION['config']['db_password']);
    }
    catch (PDOException $e)
    {
        $db = null;
        header("Location: " . $_SERVER['PHP_SELF'] . "?step=3&error=no_connection");
        die();
    }

    /**
    * @desc ALLRIGHT - EVERYTHING SEEMS FINE - GO ON!
    */
    $sql = file_get_contents('clansuite.sql');
    $sql = str_replace('CREATE TABLE `cs_', 'CREATE TABLE `' . $_SESSION['config']['db_prefix'], $sql);
    $db->exec($sql);
    $error = $db->errorInfo();
    if( $error[2] != '' )
    {
        $errors = true;
    }

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

        $salt = split('-', generate_salt() );
        $_SESSION['config']['salt'] = $salt;

        switch ( $_SESSION['config']['encryption'] )
        {
            case 'sha1':
                $hash = generate_sha1( $string );
                break;

            case 'md5':
                $hash = generate_md5( $string );
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
        return build_salted_hash( build_salted_hash( $string ) );
    }

    // Alter the standard admin user.
    $stmt = $db->prepare('UPDATE ' . $_SESSION['config']['db_prefix']. 'users SET nick = ?,password = ?,email = ?, joined = ?, activated = ? WHERE id = 1');
    $stmt->execute( array(  $_SESSION['admin_nick'],
                            db_salted_hash($_SESSION['admin_pass']),
                            $_SESSION['config']['from'],
                            time(),
                            1 ) );

    /**
    * @desc Handle the config update
    */
    $cfg_file = file_get_contents('config.class.php');
    foreach($_SESSION['config'] as $key => $value)
    {
        if( is_array($value) )
        {
            foreach( $value as $meta_key => $meta_value )
            {
                if( preg_match('#^[0-9]+$#', $meta_value) )
            	{
            	    $cfg_file = preg_replace( '#\$this->meta\[\''. $meta_key . '\'\][\s]*\=.*\;#', '$this->meta[\''. $meta_key . '\'] = ' . $meta_value . ';', $cfg_file );
            	}
            	else
            	{
                    $cfg_file = preg_replace( '#\$this->meta\[\''. $meta_key . '\'\][\s]*\=.*\;#', '$this->meta[\''. $meta_key . '\'] = \'' . $meta_value . '\';', $cfg_file );
            	}
            }
        }
        else
        {
            if( preg_match('#^[0-9]+$#', $value) )
            {
                $cfg_file = preg_replace( '#\$this->'. $key . '[\s]*\=.*\;#', '$this->'. $key . ' = ' . $value . ';', $cfg_file );
            }
            else
            {
                $cfg_file = preg_replace( '#\$this->'. $key . '[\s]*\=.*\;#', '$this->'. $key . ' = \'' . $value . '\';', $cfg_file );
            }
        }
    }

    file_put_contents( '../config.class.php', $cfg_file );


    /**
    * @desc Load template for the finish
    */
    require( 'install-step4.php' );
}
//var_dump($_SESSION);
session_write_close()
?>
