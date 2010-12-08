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


    /**
     * Constructor
     *
     * This object is injected via DI.
     */
    public function __construct()
    {
    }

    /* -----------------------------------------------------------------------------
       Private Functions
     -----------------------------------------------------------------------------*/

    /*
     * ----------------------------------------------------------------------------
     * checkPermission
     * ----------------------------------------------------------------------------
     * Checked for one Modul the Action Permission
     *
     * $permission = $modulname.$actionname
     */
    public static function checkPermission( $permission )
    {
        if( $permission == '' )
            return false;

        if( $_SESSION['user']['rights'] == '' )
            return false;

        $perms = self::extractRightSession( $_SESSION['user']['rights'] );
        #Clansuite_Debug::printR($perms);

        if( count($perms) >0 )
        {
            foreach( $perms as $key => $value )
            {
                #Clansuite_Debug::printR($value);
                if( $value == $permission)
                {
                    return true;
                }
            }
            return false;
        }
        else {
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
        if( $permstring !== '' )
        {
            return strtr(base64_encode(addslashes(gzcompress(serialize($permstring),9))), '+/=', '-_,');
        }
        else {
            return '';
        }
    }

    /*
     * ----------------------------------------------------------------------------
     * extractRightSession
     * ----------------------------------------------------------------------------
     * Make the Right-String for Session
     */
    public static function extractRightSession( $permstring = '' )
    {
        if( $permstring == '' )
            return array();

        $permstr = unserialize(gzuncompress(stripslashes(base64_decode(strtr($permstring, '-_,', '+/=')))));
        #Clansuite_Debug::printR($permstr);

        $perms = explode( ',', $permstr );
        #Clansuite_Debug::printR($perms);

        return $perms;
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



    /* -----------------------------------------------------------------------------
       Private Functions
     -----------------------------------------------------------------------------*/

    /*
     * ----------------------------------------------------------------------------
     * getPermissions
     * ----------------------------------------------------------------------------
     */
    private static function getPermissions($roleid, $userid = 0)
    {
        if( $roleid == '' )
            return '';

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