<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch © 2005 - onwards
    * http://www.clansuite.com/
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005 - onwards), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite
 *
 * Module:      Userslist
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-onwards)
 * @link
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    module
 * @subpackage  module_userslist
 */
class Module_Users extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Userslist -> Execute
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
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
        trail::addStep( _('Show'), '/index.php?mod=users&amp;action=show');

        // Defining initial variables
        $currentPage = $this->injector->instantiate('httprequest')->getParameter('page');
        $resultsPerPage = 25;

        /* @todo: news with status: draft, published, private, private+protected*/
        // Creating Pager Object with a Query Object inside
        $pager_layout = new Doctrine_Pager_Layout(
                        new Doctrine_Pager(
                            Doctrine_Query::create()
                                    ->select('u.user_id, u.nick, u.email, u.joined,, ug.*, g.name, g.color')
                                    ->from('CsUsers u')
                                    ->leftJoin('u.CsRelUserGroups ug')
                                   ->leftJoin('ug.CsGroups g')
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
        $userslist = $pager->execute(array(), Doctrine::FETCH_ARRAY);

        // Get Number of Rows
        $count = count($userslist);

        # Get Render Engine
        $smarty = $this->getView();
        
        // Assign $userslist array to Smarty for template output
        $smarty->assign('userslist', $userslist);

        // Pagination
        $smarty->assign_by_ref('pager', $pager);
        $smarty->assign_by_ref('pager_layout', $pager_layout);

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
    public function widget_lastregisteredusers($numberUsers, &$smarty)
    {
        
        # fetch specified num of last registered users 
        $last_registered_users = Doctrine_Query::create()
                                 ->select('u.user_id, u.email, u.nick, u.country')                                 
                                 ->from('CsUsers u')
                                 ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                 ->orderby('u.timestamp DESC')
                                 ->where('u.activated = 1')
                                 ->limit($numberUsers)
                                 ->execute();

        # assign        
        $smarty->assign('last_registered_users', $last_registered_users);
        
        # check for theme tpl / else take module tpl
        if($smarty->template_exists('users/lastregisteredusers_widget.tpl'))
        {
            echo $smarty->fetch('users/lastregisteredusers_widget.tpl');
        }
        else
        {
            echo $smarty->fetch('users/templates/lastregisteredusers_widget.tpl');
        }
    }
}

?>