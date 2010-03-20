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
    * @link       http://gna.org/projects/
    *
    * @version    SVN: $Id: index.module.php 2625 2008-12-09 00:04:43Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Module Gallery Administration
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Gallery
 */
class Module_Gallery_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    public function __construct(Phemto $injector=null)
    {
        parent::__construct(); # run constructor on controller_base
    }

    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
		parent::initRecords('gallery');
    }

    /**
     * Module_Gallery_Admin - action_admin_show
     *
     */
    public function action_admin_show()
    {

        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show'), '/index.php?mod=gallery&amp;sub=admin&amp;action=show');

        $album = CsGalleryAlbum::getGalleryAlbums();


        # Get Render Engine
        $view = $this->getView();


        #$view->assign('', $news->toArray());
        $view->assign('album', $album);
        #$view->assign('newscategories', $newscategories);

        // Return true if it's necessary to paginate or false if not
        #$view->assign('pagination_needed',$pager->haveToPaginate());

        // Pagination
        #$view->assign('pager', $pager);
        #$view->assign('pager_layout', $pager_layout);

        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        # specifiy the template manually
        #$this->setTemplate('news/admin_show.tpl');
        # Prepare the Output
        $this->prepareOutput();

    }

    public function action_admin_create_album()
    {
    	# Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Create Album'), '/index.php?mod=gallery&amp;sub=admin&amp;action=create_album');

        # Get Render Engine
        $view = $this->getView();

       	# instantiate Clansuite_HttpRequest object
    	$request = $this->injector->instantiate('Clansuite_HttpRequest');

    	# get valid $_POST params
    	$album['name'] 			= $request->getParameter('album_name');
    	$album['description']	= $request->getParameter('album_description');
        $album['position'] 		= $request->getParameter('album_position');
        $album['thumb']			= $request->getParameter('album_thumb');

        # create new gallery album - return int
        $id = CsGalleryAlbum::createNewAlbum($album);

        # assign result to smarty
       	$view->assign('id', $id);

        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        # specifiy the template manually
        $this->setTemplate('admin_create_album.tpl');

        # Prepare the Output
        $this->prepareOutput();

    }

    public function action_admin_update_album()
    {
    	# set pagetitle and breadcrumbs
        Clansuite_Trail::addStep( _('Update Album'), '/index.php?mod=gallery&amp;sub=admin&amp;action=update_album');

        # get render engine
        $view = $this->getView();

		# instantiate Clansuite_HttpRequest object
    	$request = $this->injector->instantiate('Clansuite_HttpRequest');

    	# get valid $_POST params
    	$id = $request->getParameter('id');

    	# get all album fields of given $id - retrun array
        $album = CsGalleryAlbum::getAlbumById($id);

        # assign result to smarty
       	$view->assign('album', $album);

        # set layout template
        $this->getView()->setLayoutTemplate('index.tpl');

        # specifiy the template manually
        $this->setTemplate('admin_update_album.tpl');

        # prepare the output
        $this->prepareOutput();
    }
}
?>