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

# Security Handler
if (defined('IN_CS') == false){ die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * This is the Clansuite Core Class for Security Handling
 *
 * It contains helper functions for encrypting and salting strings/passwords.
 * Password hashing is not "password encryption". An encryption is reversible.
 * A hash is not reversible. A salted hash is a combination of a string and a random value.
 * Both (salt and hash) are stored in database.
 *
 * @link http://www.schneier.com/cryptography.html Website of Bruce Schneier
 * @link http://www.php.net/manual/en/refs.crypto.php
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Security
 */
class Clansuite_Security
{
    private $config = null;

    /**
     * Init Class
     *
     * @param $config Clansuite_Config Object
     */
    function __construct( Clansuite_Config $config )
    {
        $this->config = $config;
    }

    /**
     * Checks whether a hashed password matches a stored salted+hashed password.
     *
     * @param  string $passwordhash  The incomming hashed password. There is never a plain-text password incomming.
     * @param  string $databasehash  The stored password. It's a salted hash.
     * @param  string $salt          The salt from db.
     *
     * @return boolean               true if the incomming hashed password matches the hashed+salted in db,
     *                               false otherwise
     */
    public function check_salted_hash( $passwordhash, $databasehash, $salt )
    {
        # combine incomming $salt and $passwordhash (which is already sha1)
        $salted_string =  $salt . $passwordhash;

        # get hash_algo from config and generate hash from $salted_string
        $hash = $this->generate_hash($this->config['login']['hash_algorithm'], $salted_string);

        # then compare
        return $databasehash === $hash;
    }

    /**
     * Convenience/Proxy Method for check_salted_hash()
     * Checks whether a hashed password matches a stored salted+hashed password.
     *
     * @param  string $passwordhash  The incomming hashed password.
     * @param  string $databasehash  The stored password. It's a salted hash.
     * @param  string $salt          The salt from db.
     *
     * @return boolean               true if the incomming hashed password matches the hashed+salted in db,
     *                               false otherwise
     */
    public function checkPassword()
    {
        return check_salted_hash( $passwordhash, $databasehash, $salt );
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
     *
     * @return $hash is an array, containing ['salt'] and ['hash']
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
     * When you have the "skein_hash" extension installed, we use "skein_hash".
     * When it's not possible to use hash() or skein_hash() for any reason, we use "md5" and "sha1".
     *
     * @link http://www.php.net/manual/en/ref.hash.php
     *
     * @param $string String to build a HASH from
     * @param $hash_type Encoding to use for the HASH (sha1, md5) default = sha1
     *
     * @return hashed string
     */
    public function generate_hash($hash_algo = null, $string = '')
    {
        # Get Config Value for Hash-Algo/Encryption
        if($hash_algo == null)
        {
            $hash_algo = $this->config['login']['hash_algorithm'];
        }
        else
        {
            $hash_algo = strtolower($hash_algo);
        }

        /**
         * check, if we can use skein_hash()
         *
         * therefore the php extension "skein" has to be installed.
         * website: http://www.skein-hash.info/downloads
         */
        if (extension_loaded('skein') and ($hash_algo == 'skein')) # function_exists('skein_hash')
        {
            # get the binary 512-bits hash of string
            return skein_hash($string, 512);
        }

        # check, if we can use hash()
        if (function_exists('hash'))
        {
            return hash($hash_algo, $string);
        }
        else
        {   # when hash() not available, do hashing the old way
            switch($hash_algo)
            {
                case 'md5':
                    return md5($string);
                    break;
                default:
                case 'sha1':
                    return sha1($string);
                    break;
            }
        }
    }

    /**
     * Get random string/salt of size $length
     * mt_srand() and mt_rand() are used to generate even better randoms, because of mersenne-twisting.
     *
     * @param integer $length Length of random string to return
     *
     * @return string Returns a string with random generated characters and numbers
     */
    public function generate_salt($length)
    {
        # set salt to empty
        $salt = '';
        # seed the randoms generator with microseconds since last "whole" second
        # Note: this is considered a week seeding, as of php5.3 with ext/openssl use openssl_random_pseudo_bytes()
        mt_srand((double) microtime()*1000000);
        # set up the random chars to choose from
        $chars = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
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