<?php
   /**
    * Clansuite - just an E-Sport CMS
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
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id: acm.class.php 4599 2010-08-27 21:01:58Z vain $
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Core Class for Role and User Based Access Control Management
 *
 * @author      Paul Brand <info@isp-tenerife.net>
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  ACL
 */
class Clansuite_ACL
{
    /*
     * Roles Container
     * @var array
     */
    private static $_roles = array();

    /*
     * Resources Container
     * @var array
     */
    private static $_resources = array();

    /*
     * Rules Container
     * @var array
     */
    private static $_rules = array();

    /*
     * Rules Container
     * @var array
     */
    private static $_rulesOverflow = array();

    /*
     * Permission Container
     * @var array
     */
    private static $perms = array();

    private static $compress_permissions = false;

    /**
     * Constructor
     *
     * This object is injected via DI.
     */
    public function __construct()
    {
    }

    /**
     * checkPermission
     *
     * Checks if the user has a certain permission
     * Proxy Method for Clansuite_ACL::checkPermission()
     *
     * Two values are necessary the modulname and the name of the permission,
     * which is often the actionname.
     *
     * @param $modulename string The modulename, e.g. 'news'.
     * @param $permission string The permission name, e.g. 'action_show'.
     * @return boolean True if the user has the permission, false otherwise.

     */
    public static function checkPermission( $modul_name, $permission_name )
    {
        # if we got no modulname or permission, we have no access
        if( $module_name == '' or $permission_name == '' )
        {
            return false;
        }
        else
        {
            # combine the module and permission name to a string
            $permission = '';
            $permission = $module_name .'.'. $permission_name;
        }

        $permissions = self::extractRightsFromSession();

        if(count($permissions) > 0)
        {
            foreach($permissions as $key => $value)
            {
                if($value == $permission)
                {
                    return true;
                }
            }
            return false;
        }
        else
        {
            return false;
        }
    }

    /*
     * ----------------------------------------------------------------------------
     * createRightSession
     * ----------------------------------------------------------------------------
     * Make the Right-String for Session
     */
    public static function createRightSession( $roleid, $userid = 0 )
    {
        $permstring = self::getPermissions( $roleid, $userid );

        # return compressed permission string
        if( $permstring !== '' and self::$compress_permissions === true)
        {
            return strtr(base64_encode(addslashes(gzcompress(serialize($permstring),9))), '+/=', '-_,');
        }
        # return uncompress permission string
        elseif( $permstring !== '')
        {
            return $permstring;
        }
        else
        {
            return '';
        }
    }

    /**
     * extractRightsFromSession
     *
     * The Permissions/Rights Session value is found in
     * $_SESSION['user']['rights']
     * and contains a
     * - base64_encoded and
     * - gzcompressed and
     * - compacted array value.
     * This method will revert the string to a proper array.
     *
     * @return array Permissions array.
     */
    public static function extractRightsFromSession()
    {
        if(empty($_SESSION['user']['rights']))
        {
            return array();
        }
        elseif(self::$compress_permissions === true)
        {

            # revert the session permission string to a proper array
            $permstring = unserialize(gzuncompress(stripslashes(base64_decode(strtr($_SESSION['user']['rights'], '-_,', '+/=')))));

            $permstring = explode(',', $permstring);

            return $permstring;
        }
        else
        {
            return $_SESSION['user']['rights'];
        }
    }


    /*
     * ----------------------------------------------------------------------------
     * getRoleList
     * ----------------------------------------------------------------------------
     *
     * give an array for column header or checkboxes
     *  e.g. if $title = false
     *    [1] = root
     *    [2] = admin
     *    [3] = member
     *    [4] = guest
     *    [5] = bot
     *  or if $title = true
     *    [1] = Supervisor
     *    [2] = Administrator
     *    [3] = User
     *    [4] = Guest
     *    [5] = Searchengine
     */
    public static function getRoleList( $title = false)
    {
        if(false === $title )
        {
            $field = 'name';
        }
        else {
            $field = 'title';
        }

        foreach( self::$_roles as $role )
        {
            $alist[] = $role[$field];
        }

        return $alist;
    }

    /*
     * ----------------------------------------------------------------------------
     * getPermissions
     * ----------------------------------------------------------------------------
     */
    private static function getPermissions($roleid, $userid = 0)
    {
        if( $roleid == '' )
        {
            return '';
        }

        # --- initialize ---
        $permstring = '';
        $_perms = $uRules = array();

        # --- read acl-data ---
        $Actions = self::getAclDataActions();
        $Rules = self::getAclDataRules();
        if( $userid >0 )
        {
            $uRules = self::getAclDataURules($userid);
        }

        # --- prepare actions ---
        foreach( $Actions as $act )
        {
            $_actions[$act['action_id']] = $act['modulname'] . '.' . $act['action'];
        }

        # --- create permission array only for the given role_id ---
        foreach( $Rules as $rule )
        {
            if( $rule['role_id'] == $roleid )
            {
                $_perms[ $_actions[$rule['action_id']] ] = 1;
            }
        }

        # --- create/overide group-permissions width user-permissions ---
        if( $userid >0 )
        {
            if( count( $uRules ) >0 )
            {
                // @todo

            }
        }


        # prepare permissionstring for session
        foreach( $_perms as $key => $value )
        {
            $permstring .= $key.',';
        }

        $permstring = mb_substr( $permstring, 0, strlen($permstring)-1);
        #Clansuite_Debug::printR($permstring);

        return $permstring;
    }


    /*
     * ----------------------------------------------------------------------------
     * createAclDataRoles
     * ----------------------------------------------------------------------------
     */
    private static function createAclDataRoles()
    {
        $roles = Doctrine_Query::create()
                        ->select( 'r.*' )
                        ->from( 'CsAclRoles r')
                        ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                        ->orderby('r.sort DESC')
                        ->execute( array() );

        return $roles;
    }

    /*
     * ----------------------------------------------------------------------------
     * getAclDataActions
     * ----------------------------------------------------------------------------
     */
    private static function getAclDataActions()
    {
        $actions = Doctrine_Query::create()
                        ->select( 'a.*' )
                        ->from( 'CsAclActions a')
                        ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                        ->orderby('a.modulname ASC')
                        ->execute( array() );

        return $actions;
    }

    /*
     * ----------------------------------------------------------------------------
     * getAclDataRules
     * ----------------------------------------------------------------------------
     */
    private static function getAclDataRules()
    {
        $rules = Doctrine_Query::create()
                        ->select( 'u.*' )
                        ->from( 'CsAclRules u')
                        ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                        ->where('u.access = 1')
                        ->orderby('u.action_id ASC')
                        ->execute( array() );

        return $rules;
    }

    /*
     * ----------------------------------------------------------------------------
     * getAclDataURules
     * ----------------------------------------------------------------------------
     */
    private static function getAclDataURules()
    {
        $urules = Doctrine_Query::create()
                        ->select( 'r.*' )
                        ->from( 'cs_acl_urules r')
                        ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                        ->orderby('r.module_id ASC')
                        ->execute( array() );

        return $urules;
    }

}
?>