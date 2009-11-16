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
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

#clansuite_preprocessor::build_monolith();
#clansuite_preprocessor::empower_monolith();

/**
 * Clansuite Preprocesser
 *
 * 1) Includes
 * Purpose: Assembles all Core files into one monolithic file.
 * Performance Strategy : Include Tuning. Merge Files, for lower number of includes.
 *
 * @todo detect dependencies with get_required_files()
 *
 * 2) APC Compile Files
 */
class clansuite_preprocessor
{
    protected $monolith_filename = 'clansuite_monolith.php';

    public static function empower_monolith()
    {
        $content = file_get_contents(self::$monolith_filename);
        $new_content = '<?php '.$content;
        $new_content .= '?>';
        file_put_content(self::$monolith_filename, $new_content);
    }

    /**
     * Combines all clansuite files to ONE file
     */
    public static function build_monolith()
    {
        echo 'Guess what? Building a huge monolith! Ok, lets go...<br/>';

        if(is_file(self::$monolith_file)) { unlink(self::$monolith_file); }

    	# directory
    	$directory = '.';

        foreach( new DirectoryIterator($directory) as $phpfile )
        {
            # no dots, no dirs, not this file and not the target file
            if ( !$phpfile->isDot() && !$phpfile->isDir() &&
                 $phpfile->getFilename() != basename($_SERVER['PHP_SELF']) &&
                 $phpfile->getFilename() != self::$monolith_file )
            {
                echo 'Processing: '.$phpfile.'<br>';

                # get file content
                $content = file_get_contents(self::$monolith_file);

                # apply string modification (strips unnessecary things off)
                $new_content = self::remove_comments_from_string($content);
                #$new_content = self::strip_php_tags($new_content);
                #$new_content = self::strip_empty_lines($new_content);

                # write the modified content to the monolith file
                file_put_contents(self::$monolith_file, $new_content, FILE_APPEND);
            }
        }
        echo 'Monolith successfully build!';
    }

    /**
     * remove_comments_from_string, guess what? :)
     *
     * @param $sourcecode sourcecode-string to clean up
     * @return string
     */
    public static function remove_comments_from_string($sourcecode)
    {
        # check if sourcecode is set
        if(is_null($sourcecode))
        {
            return null;
        }

        # tokenize the sourcecode
        $tokens     = token_get_all($sourcecode);

        # reset sourcecode
        $stripped_sourcecode = '';

        # loop over all tokens
        foreach ($tokens as $token)
        {
            # if token is a string append to sourcecode
            if(is_string($token))
            {
                $stripped_sourcecode .= $token;
            }
            else
            {
                # identify elements
                list($token_element, $text) = $token;

                # filter out comments
                if($token_element != T_COMMENT && $token_element != T_DOC_COMMENT)
                {
                    # append only, if not comment or doc_comment
                    $stripped_sourcecode .= $text;
                }
            }
        }

        return $stripped_sourcecode;
    }

    /**
     * Strips Empty Lings
     *
     * @param $string sourcecode-string to clean up
     * @return string
     */
    public static function strip_empty_lines($string)
    {
    	$string = preg_replace("/[\r\n]+[\s\t]*[\r\n]+/", "\n", $string);
    	$string = preg_replace("/^[\s\t]*[\r\n]+/", "", $string);
    	return $string;
    }

    /**
     * Strips PHP Tags
     *
     * @param $string sourcecode-string to clean up
     * @return string
     */
    public static function strip_php_tags($string)
    {
        # remove php opening and closing tag from beginning and end
        $string = substr($string, strlen('<?php'.PHP_EOL));
        $string = substr($string, 0, -strlen('?>'.PHP_EOL));

        # remove clansuite security line from whole string
        $string = str_replace("if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.'); }".PHP_EOL, "", $string);
        $string = str_replace("if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}", '', $string);

        # remove php opening tag from whole string
        $string = str_replace('<?php', "", $string);

        return $string;
    }

    /**
     * Compile Files for APC ( Performance Strategy : File Priming)
     * The function runs through each directory and compiles each *.php file through apc_compile_file
     * @param string $dir start directory
     * @return void
     */
    public static function apc_compile_files($dir)
    {
        if (!function_exists('apc_compile_file'))
        {
            trigger_error('ERROR: apc_compile_file does not exist!');
            exit();
        }

        $dirs = glob($dir . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
        if (is_array($dirs) && count($dirs) > 0)
        {
            while(list(,$v) = each($dirs))
            {
                self::apc_compile_files($v); # ! recursion
            }
        }

        $files = glob($dir . DIRECTORY_SEPARATOR . '*.php');
        if (is_array($files) && count($files) > 0)
        {
            while(list(,$v) = each($files))
            {
                apc_compile_file($v);  # compile file with apc
            }
        }
    }
}
?>