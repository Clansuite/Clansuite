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
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * This is the Clansuite Core Class for Security Handling
 *
 * It contains helper functions for encrypting and salting strings/passwords.
 * The file itself and all functions got rewritten entirely for Release 0.2.
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 * @since      Class available since Release 0.2
 *
 * @package     clansuite
 * @category    core
 * @subpackage  security
 */
class Clansuite_Security
{
    private $config = null;

    /**
     * Init Class
     */
    function __construct( Clansuite_Config $config )
    {
        $this->config = $config;
    }

    /**
     * check_salted_hash()
     *
     * $passwordhash = password hashed from login formular
     * $databasehash = hash from db
     * $salt         = salt from db
     */
    public function check_salted_hash( $passwordhash, $databasehash, $salt )
    {
        # combine incomming $salt and $passwordhash (which is already sha1)
        $salted_string =  $salt . $passwordhash;

        # get hash_algo from config and generate hash from $salted_string
        $hash = $this->generate_hash($this->config['login']['encryption'], $salted_string);

        # then compare
        return $databasehash === $hash;
    }

    /**
     * This functions takes a clear (password) string and prefixes a random string called
     * "salt" to it. The new combined "salt+password" string is then passed to the hashing
     * method to get an hash return value.
     * So what’s stored in the database is Hash(password, users_salt).
     *
     * Why salting? 2 Reasons:
     * 1) Make Dictionary Attacks (pre-generated lists of hashes) useless
     *    The dictionary has to be recalculated for every account.
     * 2) Using a salt fixes the issue of multiple user-accounts having the same password
     *    revealing themselves by identical hashes. So in case two passwords would be the
     *    same, the random salt makes the difference while creating the hash.
     *
     * @param string A clear-text string, like a password "JohnDoe$123"
     * @return $hash is an array, containing ['salt'] and ['hash']
     * @access public
     */
    public function build_salted_hash( $string = '', $hash_algo = '')
    {
        # set up the array
        $salted_hash_array = array();
        # generate the salt with fixed length 6 and place it into the array
        $salted_hash_array['salt'] = $this->generate_salt(6);
        # combine salt and string
        $salted_string =  $salted_hash_array['salt'] . $string;
        # generate hash from "salt+string" and place it into the array
        $salted_hash_array['hash'] = $this->generate_hash($hash_algo, $salted_string);
        # return array with elements ['salt'], ['hash']
        return $salted_hash_array;
    }

    /**
     * This function generates a HASH of a given string using the requested hash_algorithm.
     * When using hash() we have several hashing algorithms like: md5, sha1, sha256 etc.
     * To get a complete list of available hash encodings use: print_r(hash_algos());
     * When it's not possible to use hash() for any reason, we use "md5" and "sha1".
     *
     * @param $string String to build a HASH from
     * @param $hash_type Encoding to use for the HASH (sha1, md5) default = sha1
     * @return hashed string
     * @link http://www.php.net/manual/en/ref.hash.php
     * @access public
     */
    public function generate_hash($hash_algo = null, $string = '')
    {
        # Get Config Value for Hash-Algo/Encryption
        if($hash_algo == null)
        {
            $hash_algo = $this->config['login']['encryption'];
        }

        # check, if we can use hash()
        if (function_exists('hash'))
        {
            return hash($hash_algo,$string);
        }
        else
        {   # when hash() not available, do hashing the old way
            switch($hash_algo)
            {
                case 'MD5':     return md5($string);
                                break;
                default:
                case 'SHA1':    return sha1($string);
                                break;
            }
        }
    }

    /**
     * Get random string/salt of size $length
     * mt_srand() and mt_rand() are used to generate even better
     * randoms, because of mersenne-twisting.
     *
     * @param integer $length Length of random string to return
     * @return string Returns a string with random generated characters and numbers
     * @access public
     */
    public function generate_salt($length)
    {
        # set salt to empty
        $salt = '';
        # seed the randoms generator with microseconds since last "whole" second
        # @todo: this is considered a week seeding, as of php5.3 with ext/openssl use openssl_random_pseudo_bytes()
        mt_srand((double)microtime()*1000000);
        # set up the random chars to choose from
        $chars = "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        # count the number of random_chars
        $number_of_random_chars = strlen($chars);
        # add a char from the random_chars to the salt, until we got the wanted $length
        for ($i=0; $i<$length; ++$i)        {
            # get a random char of $chars
            $char_to_add = $chars[mt_rand(0,$number_of_random_chars)];
            # ensure that a random_char is not used twice in the salt
            if(!strstr($salt, $char_to_add))
            {
                # finally => add char to salt
                $salt .= $char_to_add;
            }
        }
        return $salt;
    }
}
?>
