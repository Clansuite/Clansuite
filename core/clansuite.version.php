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
    *
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

    /** =============================================================================
     *    WARNING: THIS FILE CONTAINS VERSION INFO ONLY AND IS AUTOMATICALLY UPDATED.
     *             DO NOT EDIT.
     *  =============================================================================
     */

/**
 * Clansuite_Version
 *
 */
class Clansuite_Version
{
    function __construct()
    {
        define('CLANSUITE_VERSION',         '0.2');
        define('CLANSUITE_VERSION_NAME',    'Trajan');
        define('CLANSUITE_VERSION_STATE',   'alpha-dev');

        # Define Clansuite SVN Revision
        if (!defined('CLANSUITE_REVISION'))
        {
            # File used: "root/.svn/entries"
            if (file_exists(ROOT . '.svn' . DS . 'entries'))
            {
                define ('CLANSUITE_REVISION', self::getRevisionNumberFromFile());
            }
            else # get revision number from the Subversion Rev-property, if no SVN data available
            {
                define ('CLANSUITE_REVISION', self::getRevisionNumber());
            }
        }
    }

    /**
     * Detect SVN Revision Number from File
     *
     * Author: Andy Dawson (AD7six) for cakephp.org
     * URL: http://bakery.cakephp.org/articles/view/using-your-application-svn-revision-number
     */
    public static function getRevisionNumberFromFile()
    {
        $svn = file(ROOT . '.svn' . DS . 'entries');
        if (is_numeric(trim($svn[3])))
        {
            $version = $svn[3];
        }
        else # pre 1.4 svn used xml for this file
        {
            $version = explode('"', $svn[4]);
            $version = $version[1];
        }

        unset($svn);

        return trim($version);
    }

    /**
     * Returns revision number from Subversion Rev-property
     */
    function getRevisionNumber()
    {
        # $Rev$ is substituted by SVN on commit
        $svnrevision = '$Rev$';

        # cut left:  "$Rev: "
        $svnrevision = substr($svnrevision, 6);

        # cut right: " $"
        $svnrevision = substr($svnrevision , 0, -2);

        return $svnrevision;
    }
}

# autocall this call when loaded
new Clansuite_Version;
?>