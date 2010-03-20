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
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Module : Users
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards), Florian Wolf (2006-2007)
 * @link
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Users
 */
class Clansuite_Module_Users extends Clansuite_Module_Controller implements Clansuite_Module_Interface
{
    /**
     * Module_Userslist -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # read module config
        $this->config->readConfig( ROOT_MOD . '/users/users.config.php');
    }

    /**
     * Action -> Show
     * Displayes Overview of all registered Users
     *
     * URL = /index.php?mod=userslist&action=show
     *
     * @access public
     */
    public function action_show()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show'), '/index.php?mod=users&amp;action=show');

        // Defining initial variables
        $currentPage = $this->injector->instantiate('Clansuite_HttpRequest')->getParameter('page');
        $resultsPerPage = 25;

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
        $view->assign('pager', $pager);
        $view->assign('pager_layout', $pager_layout);

        # specifiy the template manually
        #$this->setTemplate('userslist/templates/show.tpl');
        # Prepare the Output
        $this->prepareOutput();
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
        # set cfg value, or set the the incomming value or the default value for the number of user to display 
        $numberUsers = $this->getConfigValue('items_lastregisteredusers', $numberUsers, '5');
        
        # get smarty as the view
        $view = $this->getView();
        
        # fetch specified num of last registered users
        $last_registered_users = Doctrine_Query::create()
                                 ->select('u.user_id, u.email, u.nick, u.country, u.joined')
                                 ->from('CsUsers u')
                                 ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                 ->orderby('u.joined DESC')
                                 ->where('u.activated = 1')
                                 ->limit($numberUsers)
                                 ->execute();

        # assign
        $view->assign('last_registered_users', $last_registered_users);
    }

   /**
     * widget_useronline
     *
     * Displayes the specified number of news in the news_widget.tpl.
     * This is called from template-side by adding:
     * {load_module name="news" action="widget_news" items="2"}
     *
     * @param $numberNews Number of Newsitems to fetch
     * @param $smarty Smarty Render Engine Object
     * @returns content of news_widget.tpl
     */
    public function widget_usersonline()
    {
        $view = $this->getView();
        
        /*
        $usersonline = Doctrine_Query::create()
                          ->select('')
                          ->from('')
                          ->leftJoin('')
                          ->leftJoin('')
                          ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                          ->orderby('')
                          #->limit()
                          ->execute( array());*/
        
        $usersonline = '@todo Query';

        $view->assign('usersonline', $usersonline);


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

        $random_user = Doctrine_Query::create()
                 ->select('u.nick, u.email, u.country, u.joined, RANDOM() rand')
                 ->from('CsUsers u')
                 ->orderby('rand')
                 ->limit(1)
                 ->execute()
                 ->getFirst()
                 ->toArray();

        $view->assign('random_user', $random_user);
    }
}
?>