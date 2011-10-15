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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

    /** =============================================================================
     *    WARNING: THIS FILE CONTAINS VERSION INFO ONLY AND IS AUTOMATICALLY UPDATED
     *             DURING THE BUILD PROCESS. DO NOT EDIT.
     *  =============================================================================
     */

/**
 * Clansuite_Version
 */
final class Clansuite_Version
{
    /**
     * Define constants for software version, versionname, state, revision numberas
     */
    public static function setVersionInformation()
    {
        /**
         * Define Clansuite software version, version name and state for usage throughout the system
         */
        define('CLANSUITE_VERSION',         '0.2.1');
        define('CLANSUITE_VERSION_NAME',    'Trajan');
        define('CLANSUITE_VERSION_STATE',   'alpha-dev');
        define('CLANSUITE_URL',             'http://www.clansuite.com');

        /**
         * Define Clansuite SVN Revision
         */
        # File used: "root/.svn/entries"
        if (is_file(ROOT . '.svn' . DS . 'entries') === true)
        {
            define ('CLANSUITE_REVISION', self::getRevisionNumberFromFile());
        }
        else # get revision number from the Subversion Rev-property, if no SVN data available
        {
            define ('CLANSUITE_REVISION', self::getRevisionNumber());
        }
    }

    /**
     * Doing deploys while caches are active is kind of tricky.
     * This method is used to invalidate the caches during deployment.
     *
     * We have two caches working together:
     * a) APC Shared Memory Opcode and User Cache
     *    We have one opcode cache for all php processes, which is a shared memory cache.
     *    The opcode cache is for the compiled PHP scripts, stored as opcode arrays.
     *    Additionally we have the user cache, which is used for storing application level-data.
     * b) Realpath + Stat Cache (php-fpm + cache)
     *    We have multiple realpath + stat caches, one per php process.
     *
     * The realpath cache (b) is used to determine the filesystem device/inode for a file.
     * These pieces of information are then used for building the cache index of APC (a).
     *
     * For each cache the deploy_version (= "current revision number") is stored to APC.
     * If new files are deployed, the shared cache and the realpath + stat cache have to be rebuild.
     * Otherwise the system will not see any changes and deliver old, because cached, files.
     * By comparing the stored DEPLOY_VERSION from shared memory with the one from this file,
     * we can determine, if a newer version was deloyed and then clear the caches accordingly.
     *
     * Warning / Note :
     * If your environment consists of several applications using APC, then updating one of them,
     * using this deployment approach will result in clearing the cache for all of them.
     * APC does not have application based cache segments to clear individually.
     * This problem is currently unadressed by APC.
     * So this approach fits to an environment, where only one application is deployed to several target machines.
     */
    public static function setVersionInformationToCaches()
    {
        /**
         * Currently we still rely on SVN, so we can use the SVN REVISION number as DEPLOY VERSION.
         * But you might consider writing the DEPLOY_VERSION value to this file during the build process.
         * So, when SCM moves from SVN to GIT, we have to set this by using a little deploy script.
         */
        define('DEPLOY_VERSION', CLANSUITE_REVISION);

        /**
         * APC
         */
        if(isset($_SERVER['SERVER_NAME']) === true)
        {
            $key = $_SERVER['SERVER_NAME'] . '_deploy_version';
            $cached_revision = apc_fetch($key);

            if ($cached_revision != DEPLOY_VERSION)
            {
                # clear opcode cache and user cache
                apc_clear_cache();
                apc_clear_cache('user');

                # if newer version arrived, store the revision number to apc
                if($cached_revision < DEPLOY_VERSION)
                {
                    apc_store($key, DEPLOY_VERSION);
                }
            }
        }

        /**
         * Realpath + stat cache is per process.
         */
        $key = 'php.pid_' . getmypid();
        $cached_revision = apc_fetch($key);

        if ($cached_revision != DEPLOY_VERSION)
        {
            # clear realpath and stat cache
            clearstatcache(true);

            # if newer version arrived, store the revision number to apc
            if($cached_revision < DEPLOY_VERSION)
            {
                apc_store($key, DEPLOY_VERSION);
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
        if (is_numeric(trim($svn[3])) === true)
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
    public static function getRevisionNumber()
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
?>