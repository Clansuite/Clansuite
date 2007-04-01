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
if (!file_exists(CS_ROOT.'config.class.php')) 
{
	exit('The file \'config.class.php\' already exists which would mean that <strong>Clansuite</strong> '. $cs_version . ' is already installed. You should go <a href="../index.php">here</a> instead.');
}

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
        empty($_SESSION['admin_email']) ||
        empty($_SESSION['admin_nick']) ||
        empty($_SESSION['admin_pass']) )
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
    if( empty($_SESSION['db_type']) ||
        empty($_SESSION['db_host']) ||
        empty($_SESSION['db_username']) ||
        empty($_SESSION['db_pass']) ||
        empty($_SESSION['db_name']) ||
        empty($_SESSION['db_prefix']) )
    {
        header("Location: " . $_SERVER['PHP_SELF'] . "?step=3&error=fill_form");
        die();
    }

    /**
    * @desc Check DB Connection
    */
    try
    {
       $db = new PDO($_SESSION['db_type'].':host='.$_SESSION['db_host'].';dbname='.$_SESSION['db_name'], $_SESSION['db_username'],$_SESSION['db_pass']);
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
    $db->exec($sql);
    $error = $db->errorInfo();
    if( $error[2] != '' )
    {
        $errors = true;
    }

    $creates = array();
    /*
    preg_match_all('#CREATE TABLE([^;]*);#s', $sql, $creates);
    $output = '';

    foreach( $creates[0] as $key => $value )
    {
        $db->exec($value);
        $error = $db->errorInfo();
        if( $error[2] != '' )
        {
            $errors = true;
            $output .= '<div style="color: red; text-align: center; font-weight: bold; font-family: Verdana; font-size: 11px;">'.$error[2].'</div>';
        }
        else
        {
            $result = array();
            preg_match('#CREATE TABLE `(.*)`#', $value, $result);
            $output .= '<div style="color: green; text-align: center; font-weight: bold; font-family: Verdana; font-size: 11px;">'.$result[0].'</div>';
        }
    }
    */

    /**
    * @desc Load template for the finish
    */
    require( 'install-step4.php' );
}
//var_dump($_SESSION);
session_write_close()
?>
