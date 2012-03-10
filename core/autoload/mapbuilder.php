<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\Autoload;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * MapBuilder builds a class map for usage with the autoloader.
 * A class map is a relationship of classname to filename.
 * The class map is used by the autoloader as a lookup table
 * to determine the file to load.
 */
class MapBuilder
{

    /**
     * Builds a class map file.
     *
     * @param array|string $dirs One or multiple directories to scan for PHP files.
     * @param string $mapfile Path to the classmap file to be written.
     */
    public static function build($dirs, $mapfile)
    {
        $classmap = array();

        $files = self::getPHPFiles($dirs);

        foreach($files as $file)
        {
            $classname = self::extractClassname($file);

            $classmap[$classname] = $file;
        }

        self::writeMapFile($classmap, $mapfile);
    }

    /**
     * Iterates over all PHP files in a directory and returns array with filenames.
     *
     * @param array|string $dirs One or multiple directories to scan for PHP files.
     * @return array Array with PHP files.
     */
    public static function getPHPFiles($dirs)
    {
        if(is_array($dirs))
        {
            foreach($dirs as $dir)
            {
                self::getPHPFiles();
            }
        }

        if(is_string($dir))
        {
            $dir = new \RecursiveIteratorIterator(
                            new \RecursiveDirectoryIterator($dir)
            );
        }

        $php_files = array();

        foreach($dir as $file)
        {
            # skip dirs
            if(!$file->isFile())
            {
                continue;
            }

            $file = $file->getRealPath();

            # skip non PHP files
            if(pathinfo($file, PATHINFO_EXTENSION) !== 'php')
            {
                continue;
            }

            $php_files[] = $file;
        }

        return $php_files;
    }

    /**
     * Extract classname and namespace of the given file.
     *
     * @param string $file The file to extract the classname from.
     * @return array Found classnames.
     */
    public static function extractClassname($file)
    {
        # tokenize the content of the file
        $contents = file_get_contents($file);
        $tokens = token_get_all($contents);

        $classes = array();
        $namespace = '';
        $total_num_tokens = count($tokens);

        for($i = 0, $max = $total_num_tokens; $i < $max; $i++)
        {
            $token = $tokens[$i];

            if(is_string($token) === true)
            {
                continue;
            }

            $classname = '';

            if($token[0] === T_NAMESPACE)
            {
                $namespace = '';

                // extract the namespace
                while(($t = $tokens[++$i]) and (is_array($t) === true))
                {
                    if(in_array($t[0], array(T_STRING, T_NS_SEPARATOR)) === true)
                    {
                        $namespace .= $t[1];
                    }
                }

                $namespace .= '\\';
            }

            if(($token[0] === T_CLASS) or ($token[0] === T_INTERFACE))
            {
                // extract the classname
                while(($t = $tokens[++$i]) and (is_array($t) === true))
                {
                    if(T_STRING === $t[0])
                    {
                        $classname .= $t[1];
                    }
                    elseif($classname !== '' and T_WHITESPACE == $t[0])
                    {
                        break;
                    }
                }

                $classes[] = ltrim($namespace . $classname, '\\');
            }
        }

        return $classes;
    }

    /**
     * Writes the classmap array to file
     * @param array $classmap Array containing the classname to file relation.
     * @param string $mapfile Path to the classmap file to be written.
     */
    public static function writeMapFile($classmap, $mapfile)
    {
        $map_array = var_export($classmap, true);

        $content = sprintf("<?php\n// Autoloader Classmap generated by Koch Framework.\nreturn %s;\n?>", $map_array);

        file_put_contents($mapfile, $content);
    }

}

?>