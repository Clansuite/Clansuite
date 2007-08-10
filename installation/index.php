<?php
    /**
     * Clansuite Installer
     * v0.2dev 10-august-2007 by vain
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
     * @author     Jens-Andre Koch <vain@clansuite.com>
     * @author     Florian Wolf <xsign.dll@clansuite.com>
     * @copyright  2006 Clansuite Group
     * @license    see COPYING.txt
     *
     * @link       http://gna.org/projects/clansuite
     * @since      File available since Release 0.1
     * @version    SVN: $Id$
     */
 

@set_time_limit(0);
@session_start();

/**
echo 'get:';        var_dump($_GET);
echo 'post:';       var_dump($_POST);
echo 'session:';    var_dump($_SESSION);
*/

// Security Handler
define('IN_CS', true);      

// Get site path
define ('CS_ROOT', getcwd() . DIRECTORY_SEPARATOR);

// The Clansuite version this script installs
$cs_version = '0.1';

// In Case config.class.php exists, exit
/* @todo: other solution pls - 'cause the webinstaller currently leeches from the svn snapshot!!!
if (file_exists(CS_ROOT.'config.class.php'))
{
	exit('The file \'config.class.php\' already exists which would mean that <strong>Clansuite</strong> '. $cs_version . ' is already installed. You should go <a href="../index.php">here</a> instead.');
}*/

/**
 * Transfer Post-Data to Session
 
if( !empty($_SESSION['config']) && !empty($_POST['config']) )
	$_SESSION['config'] = array_merge( $_SESSION['config'], $_POST['config'] );
	unset($_POST['config']);
if( !empty($_SESSION) && !empty($_POST) )
    $_SESSION = array_merge( $_SESSION, $_POST );
*/

/**
 * @desc Suppress Errors
 * E_STRICT forbids the shortage of "<?php echo $language->XY ?>" to "<?=$language->XY ?>"
 * so we use e_all ... this is just an installer btw :)
 */
#error_reporting(E_ALL);

#================
#     OUTPUT
#================

# INCLUDE THE HEADER!
include ('install-header.php');

if (isset($_POST['step']))
{
    $step = $_POST['step'];
}
else
{
    if(isset($_SESSION['step']))
    {
        $step = $_SESSION['step'];
    }
    else
    {
        $step = 1;
    }
}

/**
 * ==============================
 *    set default language var
 * ==============================
 */
if (isset($_GET['lang']) and !empty($_GET['lang']))
{   
    $_SESSION['lang'] = $lang =  htmlspecialchars($_GET['lang']);
}
else
{
    if(isset($_SESSION['lang']))
    {   
        $lang =  $_SESSION['lang'];
    }    
    if ($step == 1 and empty( $_SESSION['lang'])) 
    {        
        $_SESSION['lang'] = $lang = 'german';  
    }    
}

if (file_exists (CS_ROOT . '/languages/'.$lang.'.install.php')) 
{
	require_once (CS_ROOT . '/languages/'.$lang.'.install.php');
	$language = new language;
	$_SESSION['lang'] = $lang;	
} 
else 
{
    die('<span style="color:red">Language file missing: <strong>' . CS_ROOT . $lang . '.install.php</strong>.</span>');
}



/**
 * Switch to Intallation-functions based on "STEPS"
 */
$installfunction  = "installstep_$step"; # add step to function name
if(function_exists($installfunction))  # check if exists
{
    $_SESSION['step'] = $step;
    $_SESSION['progress'] = calc_progress($step, get_total_steps());        
    $installfunction($language); # lets rock! :P    
}

# INCLUDE THE FOOTER !!
require ('install-footer.php');

/**
 * Gets the total number of installations steps avaiable
 * by checking how many functions with name "installstep_x" exist
 *
 * @return $_SESSION['total_steps']
 */
function get_total_steps()
{   
    if(isset($_SESSION['total_steps'])){return $_SESSION['total_steps'];}
    for($i=1;function_exists('installstep_'.$i)==true;$i++){$_SESSION['total_steps']=$i;}
    return $_SESSION['total_steps'];
}

/**
 * Calculate Progress 
 * is used to display install-progress in percentages
 *
 * @return float progress-value
 */
function calc_progress($this_is_step,$of_total_steps) 
{
   $this_is_step--;
   return round(100/($of_total_steps-1)*$this_is_step, 0);
}

// STEP 1 - Language Selection
function installstep_1($language){    require 'install-step1.php' ;}
// STEP 2 - System Check
function installstep_2($language){    require 'install-step2.php' ;}
// STEP 3 - System Check
function installstep_3($language){    require 'install-step3.php' ;}
// STEP 4 - System Check
function installstep_4($language){    require 'install-step4.php' ;}
// STEP 5 - System Check
function installstep_5($language){    require 'install-step5.php' ;}
// STEP 6 - System Check
function installstep_6($language){    require 'install-step6.php' ;}
// STEP 7 - System Check
function installstep_7($language){    require 'install-step7.php' ;}

#########
#########

/**
 * @desc STEP 2 - System Check
 */
if( $_POST['step'] == 20 )
{
    /**
    * @desc Check whether form is filled or not
    */
    print_r($_SESSION);
    if( empty($_SESSION['admin_firstname']) ||
        empty($_SESSION['admin_lastname']) ||
        empty($_SESSION['config']['from']) ||
        empty($_SESSION['admin_nick']) ||
        empty($_SESSION['admin_pass']) ||
        empty($_SESSION['config']['encryption']) )
    {
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=fill_form");
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
if( $_POST['step'] == 30 )
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
        header("Location: " . $_SERVER['PHP_SELF'] . "?step=2&error=fill_form");
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
        header("Location: " . $_SERVER['PHP_SELF'] . "?step=2&error=no_connection");
        die();
    }

    /**
    * @desc Load template for step 3
    */
    require( 'install-step3.php' );
}


/**
 * @desc STEP 4 - FINISH
 */
if( $_POST['step'] == 40 )
{

    if( $_SESSION['ftp_data'] == 1 )
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
            header("Location: " . $_SERVER['PHP_SELF'] . "?step=3&error=fill_form");
            die();
        }

        /**
         * @desc Check the connection
         */
        $ftp_con = ftp_connect($_SESSION['ftp_hostname'], $_SESSION['ftp_port']);
        if( !ftp_con )
        {
            header("Location: " . $_SERVER['PHP_SELF'] . "?step=3&error=no_connection");
            die();
        }
        else
        {
            $ftp_login = ftp_login($ftp_con, $_SESSION['ftp_username'], $_SESSION['ftp_pass']);
            if( !$ftp_login )
            {
                header("Location: " . $_SERVER['PHP_SELF'] . "?step=3&error=no_connection");
                die();
            }
            else
            {
                ftp_close($ftp_con);
            }
        }
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
        header("Location: " . $_SERVER['PHP_SELF'] . "?step=2&error=no_connection");
        die();
    }

    /**
     * @desc ALLRIGHT - EVERYTHING SEEMS FINE - GO ON!
     */
    $sql = file_get_contents('clansuite.sql');
    $sql = str_replace('CREATE TABLE `cs_', 'CREATE TABLE `' . $_SESSION['config']['db_prefix'], $sql);

    $stmt = $db->query($sql);
    $error = $stmt->errorInfo();
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
        $salt = rand(0,9).'-'.rand(0,9).'-'.rand(0,9).'-'.rand(0,9).'-'.rand(0,9).'-'.rand(0,9);
        $salt .= '-'.rand(0,9).'-'.rand(0,9).'-'.rand(0,9).'-'.rand(0,9).'-'.rand(0,9).'-'.rand(0,9);
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
    $stmt2 = $db->prepare('UPDATE ' . $_SESSION['config']['db_prefix']. 'users SET `nick` = ?, `password` = ?, `email` = ?, `joined` = ?, `activated` = ? WHERE `user_id` = 1');
    $stmt2->execute( array(  $_SESSION['admin_nick'],
                            db_salted_hash($_SESSION['admin_pass']),
                            $_SESSION['config']['from'],
                            time(),
                            1 ) );
                            print_r($stmt2->errorInfo());

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

#echo 'Debug Language Values';
#var_dump($language);
session_write_close()
?>