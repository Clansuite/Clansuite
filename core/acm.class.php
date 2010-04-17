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
    *
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false) { die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * Clansuite Core Class for Access Control Management
 *
 * The Access Control Management System of Clansuite is based on and inspired by Sensei.
 * Fmpov Sensei was an experimental CMS to test Doctrine. By now (2009) i consider the
 * Sensei Project as dead (last SVN-commit 2007) and to save some of the work done,
 * i "forked" the ACL System and modified it for Clansuite.
 * The original implementation was done by :
 * @author      Janne Vanhala <jpvanhal@cc.hut.fi>
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @copyright   LGPL
 * Thanks and credit to both authors.
 *
 * @author      Jens-André Koch <vain@clansuite.com>
 * @copyright   Jens-André Koch (2009-onwards)
 * @license     GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  ACM
 */
class Clansuite_ACM
{
    /**
     * removeAccess
     * defines unified access removal strategy for users and groups
     *
     * Removes an access rule defined for this user.
     *
     * @param Clansuite_User|Clansuite_Group $record
     * @param string $resource     name of the resource
     * @param string $permission   name of the permission
     *
     * @return boolean True on success, false otherwise.
     */
    public function removeAccess(Doctrine_Record $record, $resource = null, $permission = null)
    {
        # ensure $record is either object of type User or Group
        if ($record instanceof Clansuite_User)
        {
           $accessClass = 'Clansuite_Acl_UserAccess';
           $linkField   = 'user_id';
        }
        elseif ($record instanceof Clansuite_Group)
        {
           $accessClass = 'Clansuite_Acl_GroupAccess';
           $linkField   = 'group_id';
        }
        else
        {
           throw new Clansuite_Exception('Unknown object given to ACM.');
        }

        if ($permission === null)
        {
            $permission = $this->getOption('global_permission');
        }

        if ($resource === null)
        {
            $resource = $this->getOption('global_resource');
        }

        $rows = Doctrine_Query::delete()
              ->from($accessClass . ' a')
              ->where('a.' . $linkField . ' = ? AND a.resource_name = ? AND a.permission_name = ?')
              ->execute(array($record->id, $resource, $permission));

        return (bool) $rows;
    }

    /**
     * setAccess
     * defines unified access setting strategy for users and groups
     *
     * Sets an access rule for given user/group.
     *
     * Defines whether given user/group is allowed or denied to access given resource
     * with given permission.
     *
     * @param Clansuite_User|Clansuite_Group $record
     * @param string $resource        name of the resource
     * @param string $permission      name of the permission
     * @param boolean $allow          defines whether this user has access to
     *                                given resource with given permission or not
     */
    public function setAccess(Doctrine_Record $record, $resource = null, $permission = null, $allow)
    {
        if ($record instanceof Clansuite_User)
        {
            $accessClass = 'Clansuite_Acl_UserAccess';
            $linkField   = 'user_id';
        }
        elseif ($record instanceof Clansuite_Group)
        {
            $accessClass = 'Clansuite_Acl_GroupAccess';
            $linkField   = 'group_id';
        }
        else
        {
            throw new Clansuite_Acl_Exception('Unknown object given.');
        }

        if ($permission === null)
        {
            $permission = $this->getOption('global_permission');
        }
        if ($resource === null)
        {
            $resource = $this->getOption('global_resource');
        }

        $global_access = ($permission === $this->getOption('global_permission')) ||
                  ($resource === $this->getOption('global_resource'));

        $conn = $record->getTable()->getConnection();

        $access = Doctrine_Query::create()
                  #->select('')
                  ->from($accessClass . ' a')
                  ->where('a.resource_name = ? AND a.permission_name = ? AND a.' . $linkField . ' = ?')
                  ->fetchOne(array($resource, $permission, $record->id), Doctrine::HYDRATE_ARRAY);

        if ( $access == false)
        {
            if ( $global_access == false)
            {
                $link = Doctrine_Query::create()
                          #->select('*') #autoadded
                          ->from('Clansuite_Acl_ResourcePermission p')
                          ->where('p.resource = ? AND p.permission = ?')
                          ->fetchOne(array($resource, $permission), Doctrine::HYDRATE_ARRAY);

                if ( ! $link )
                {
                    throw new Clansuite_Acl_Exception('Resource ' . $resource . ' does not have link to permission '. $permission);
                }
            }

            $access = new $accessClass();
            $access->resource_name = $resource;
            $access->permission_name = $permission;
            $access->$linkField = $record;
        }

        $access->allow = $allow;
        $access->save();

        return $access;
    }

    /**
     * Checks whether a user or a group has access to given resource with given
     * permission.
     *
     * @param Clansuite_User|Clansuite_Group $record  A user or group object
     * @param string $resource Name of the resource. If null, global resource
     * is assumed.
     * @param string $permission Name of the permission. If null, global
     * permission is assumed.
     * @return boolean|null
     */
    public function hasAccess(Doctrine_Record $record, $resource = null, $permission = null)
    {
        if ( ! $record instanceof Clansuite_User &&
             ! $record instanceof Clansuite_Group)
        {
            throw new Clansuite_Acl_Exception('Unknown object given.');
        }

        $defPerm = $this->getOption('global_permission');
        $defRes  = $this->getOption('global_resource');

        $accessType = array('resource'   => null,
                            'permission' => null,
                            'global'     => null);

        foreach ($record['Access'] as $access)
        {
            if ($access['resource_name'] === $resource)
            {
                if ($access['permission_name'] === $permission)
                {
                    return $access['allow'];
                }
                elseif ($access['permission_name'] === $defPerm)
                {
                    $accessType['resource'] = $access->allow;
                }
            }
            elseif ($access['resource_name'] === $defRes)
            {
                if ($access['permission_name'] === $permission)
                {
                    $accessType['permission'] = $access->allow;
                }
                elseif ($access['permission_name'] === $defPerm)
                {
                    $accessType['global'] = $access->allow;
                }
            }
        }

        foreach ($accessType as $k => $v)
        {
            if ($v !== null)
            {
                return $v;
            }
        }

        return null;
    }

    /**
     * Returns true, if there is a link between a resource and a permission.
     *
     * @param string $resource     Name of the resource
     * @param string $permission   Name of the permission
     * @return bool                True if a link exists, false otherwise.
     */
    public function hasResourcePermissionLink($resource, $permission)
    {
        $link = Doctrine_Query::create()
                #->select()
                ->from('Clansuite_Acl_ResourcePermission p')
                ->where('p.resource = ? AND p.permission = ?')
                ->fetchOne(array($resource, $permission), Doctrine::HYDRATE_ARRAY);

        return (bool) $link;
    }
}
?>