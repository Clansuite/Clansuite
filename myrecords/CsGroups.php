<?php

/**
 * CsGroups
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    Clansuite
 * @subpackage Database
 * @author     Clansuite - just an eSports CMS <vain at clansuite dot com>
 * @version    SVN: $Id: Builder.php 4601 2010-08-28 20:41:25Z vain $
 */
class CsGroups extends BaseCsGroups
{
    public function loadAccesses()
    {
        $query = new Doctrine_Query();
        $query->from('CsGroups g')
              ->leftJoin('g.Access ga ON ga.group_id = g.id OR ga.group_id IN (SELECT g2.id FROM Sensei_Group g2 WHERE g2.lft < g.lft AND g2.rgt > g.rgt)')
              ->where('g.id = ?')
              ->execute(array($this->getInvoker()->id));
    }
    
    /**
     * hasAccess
     *
     * @param string $resource      name of the resource
     * @param string $permission    name of the permission
     * @return boolean              whether or not this group has access to 
     *                              given resource with given permission 
     */
    public function hasAccess($resource, $permission) 
    {
        $acl = $this->locate('acl');
        
        if ( ! isset($this->getInvoker()->Access)) {
            $this->loadAccesses();
        } 

        if (null !== ($ret = $acl->hasAccess($this->getInvoker(), $resource, $permission))) {
            return $ret;
        }
        
        return false;   
    }
    
    /**
     * allowAccess
     * gives this user access to given permission and given resource  
     *
     * @param string $resource      name of the resource
     * @param string $permission    name of the permission
     * @return Sensei_Acl_UserAccess
     */
    public function allowAccess($resource = null, $permission = null)
    {
        return $this->setAccess($resource, $permission, true);
    }
    
    /**
     * denyAccess
     * denies the access for this user to use given resource with given 
     * permission  
     *
     * @param string $resource      name of the resource
     * @param string $permission    name of the permission
     * @return Sensei_Acl_UserAccess
     */
    public function denyAccess($resource = null, $permission = null)
    {
        return $this->setAccess($resource, $permission, false);
    }
    
    /**
     * Sets an access rule for this group.
     * Allows or denies members of this group to access given resource with
     * given permission.
     *
     * @param string $resource        name of the resource
     * @param string $permission      name of the permission
     * @param boolean $allow          defines whether members of this group have
     *                                access to given resource with given
     *                                permission or not
     * @return Sensei_Acl_UserAccess
     */
    protected function setAccess($resource = null, $permission = null, $allow)
    {
        return $this->locate('acl')->setAccess($this->getInvoker(), $resource, $permission, $allow);
    }
    /**
     * Removes an access rule defined for this group.
     *
     * @param string $resource     name of the resource
     * @param string $permission   name of the permission
     *
     * @return boolean True on success, false otherwise.
     */
    public function removeAccess($resource = null, $permission = null)
    {
        return $this->locate('acl')->removeAccess($this->getInvoker(), $resource, $permission);
    }

}