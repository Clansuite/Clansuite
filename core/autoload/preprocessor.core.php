<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Preprocesser
 *
 * 1) Includes
 * Purpose: Assembles all Core files into one monolithic file.
 * Performance Strategy : Include Tuning. Merge Files, for lower number of includes.
 *
 * @todo detect dependencies, either with get_required_files() or extension inclued()
 *
 * 2) APC Compile Files
 */
class Clansuite_Preprocessor
{
    protected $monolith_filename = 'clansuite_monolith.php';

    /**
     * Wraps php tags around the content of the monolith
     */
    public static function empower_monolith()
    {
        $content = '<?php ' . file_get_contents(self::$monolith_filename) . '?>';
        file_put_contents(self::$monolith_filename, $content);
    }

    /**
     * Combines all files to ONE file
     *
     * @return boolean True, on successful build.
     */
    public static function build_monolith()
    {
        # remove existing monolith
        if(is_file(self::$monolith_file) === true)
        {
            unlink(self::$monolith_file);
        }

        # this directory
        $directory = '.';

        $iterator = new DirectoryIterator($directory);

        foreach($iterator as $phpfile)
        {
            # no dots, no dirs, not this file and not the target file
            if($phpfile->isDot() === false and
               $phpfile->isDir() === false and
               $phpfile->getFilename() != basename($_SERVER['PHP_SELF']) and
               $phpfile->getFilename() != self::$monolith_file)
            {
                #echo 'Processing: ' . $phpfile . '<br>';

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

        #echo 'Monolith successfully build!';
        return true;
    }

    /**
     * Removes any comments from string
     *
     * @param $sourcecode The sourcecode string to clean up.
     * @return string The sourcecode string without comments.
     */
    public static function remove_comments_from_string($sourcecode)
    {
        # check if sourcecode is set
        if($sourcecode === null)
        {
            return null;
        }

        # ensure token_get_all is available
        if (false === function_exists('token_get_all'))
        {
            return $sourcecode;
        }

        # tokenize the sourcecode
        $tokens = token_get_all($sourcecode);

        # init return var
        $stripped_sourcecode = '';

        # loop over all tokens
        foreach($tokens as $token)
        {
            # if token is a string append to sourcecode
            if(is_string($token) === true)
            {
                $stripped_sourcecode .= $token;
            }
            else
            {
                $token_element = '';
                $text = '';

                # identify elements
                list($token_element, $text) = $token;

                # filter out comments
                if($token_element != T_COMMENT and $token_element != T_DOC_COMMENT)
                {
                    # append only, if not comment or doc_comment
                    $stripped_sourcecode .= $text;
                }
            }
        }

        return $stripped_sourcecode;
    }

    /**
     * Strips new lines by replacing them with a single newline.
     *
     * @param $string sourcecode-string to clean up
     * @return string
     */
    public static function strip_empty_lines($string)
    {
        $string = preg_replace('/[\r\n]+[\s\t]*[\r\n]+/', "\n", $string);
        $string = preg_replace('/^[\s\t]*[\r\n]+/', '', $string);
        return $string;
    }

    /**
     * Strips all openening and closing PHP Tags.
     *
     * @param $string sourcecode-string to clean up
     * @return string
     */
    public static function strip_php_tags($string)
    {
        # remove php opening and closing tag from beginning and end
        $string = substr($string, strlen('<?php' . PHP_EOL));
        $string = substr($string, 0, -strlen('?>' . PHP_EOL));

        # remove clansuite security line from whole string
        # @todo remove 4 lines when "if (defined('IN_CS')" is found
        $string = str_replace("if (defined('IN_CS') === false){ die('Clansuite not loaded. Direct Access forbidden.'); }" . PHP_EOL, "", $string);
        $string = str_replace("if (defined('IN_CS') === false) { die('Clansuite not loaded. Direct Access forbidden.'); }", '', $string);

        # remove php opening tag from whole string
        $string = str_replace('<?php', '', $string);

        return $string;
    }
}
?>