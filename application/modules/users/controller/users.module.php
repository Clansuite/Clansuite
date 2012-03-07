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
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Users
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Users
 */
class Clansuite_Module_Users extends Clansuite_Module_Controller
{
    public function _initializeModule()
    {
        $this->getModuleConfig();
    }

    /**
     * Displayes Overview of all registered Users
     */
    public function action_list()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/users/show');

        // Defining initial variables
        $currentPage    = (int) $this->request->getParameter('page');
        $resultsPerPage = (int) self::getConfigValue('resultsPerPage', '10');

        /* @todo news with status: draft, published, private, private+protected*/
        // Creating Pager Object with a Query Object inside
        $pager_layout = new Doctrine_Pager_Layout(
                new Doctrine_Pager(
                Doctrine_Query::create()
                ->select('u.user_id, u.nick, u.email, u.joined, g.name, g.color, p.icq')
                ->from('CsUsers u')
                #->leftJoin('u.CsRelUserGroup ug')
                ->leftJoin('u.CsGroups g')
                ->leftJoin('u.CsProfiles p')
                #->setHydrationMode(Doctrine::HYDRATE_NONE)
                ->orderby('u.user_id ASC'),
                # The following is Limit  ?,? =
                $currentPage, // Current page of request
                $resultsPerPage // (Optional) Number of results per page Default is 25
                ),
                new Doctrine_Pager_Range_Sliding(array(
                        'chunk' => 5
                )),
                '?mod=news&action=show&page={%page}'
        );

        // Assigning templates for page links creation
        $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');
        $pager_layout->setSelectedTemplate('[{%page}]');

        // Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        // Fetching userslist
        $userslist = $pager->execute(array(), Doctrine::HYDRATE_ARRAY);

        // Get Number of Rows
        $count = count($userslist);

        # Get Render Engine
        $view = $this->getView();

        // Assign $userslist array to Smarty for template output
        $view->assign('userslist', $userslist);

        // Pagination
        $view->assignGlobal('pager', $pager);
        $view->assignGlobal('pager_layout', $pager_layout);

        # specifiy the template manually
        #$view->setTemplate('userslist/view/show.tpl');

        $this->display();
    }

    /**
     * widget_lastregisteredusers
     *
     * Displayes the specified number of last registered Users in the lastregisteredusers_widget.tpl.
     * This is called from template-side by adding:
     * {load_module name="users" action="widget_users" items="3"}
     *
     * @param $numberUser Number of Users to fetch
     * @param $smarty Smarty Render Engine Object
     * @returns content of users_widget.tpl
     */
    public function widget_lastregisteredusers($numberUsers)
    {
        # set cfg value,
        # or set the the incomming value
        # or the default value for the number of user to display
        $numberUsers = self::getConfigValue('items_lastregisteredusers', $numberUsers, '5');

        # fetch specified num of last registered users
        $last_registered_users = $this->getModel('Entities\User')->getLastRegisteredUsers($numberUsers);

        # assign data to view
        $this->getView()->assign('last_registered_users', $last_registered_users);
    }

    /**
     * widget_useronline
     *
     * @returns content of widget_usersonline.tpl
     */
    public function widget_usersonline()
    {
        $usersonline = 0;
        $guests = 0;

        $this->getView()->assign('usersonline', $usersonline);
        $this->getView()->assign('guests', $guests);

    }

    /**
     * widget RandomUser
     *
     * Displayes a random user  widget_randomuser.tpl.
     * This is called from template-side by adding:
     * {load_module name="users" action="widget_randomuser"}
     *
     * @param $smarty Smarty Render Engine Object
     * @returns direct smarty assign of randomuser data
     */
    public function widget_randomuser($numberNews)
    {
        $view = $this->getView();

        $random_user = $this->doctrine_em->createQuery('
                SELECT u.user_id, u.nick, u.email, u.country, u.joined, RAND() rand
                FROM Entities\User u
                ORDER BY rand')
                ->setMaxResults(1)
                ->getArrayResult();

        $view->assign('random_user', $random_user['0']);
    }

    public function widget_usercenter()
    {
        $view = $this->getView();

        $view->assign('usercenter', 'some personal items');
    }
}
?>