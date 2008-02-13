<?php
/**
 * Index Module
 *
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
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @license    GNU/GPL, see COPYING.txt
 *
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$Date$)
 *
 * @link       http://www.clansuite.com
 * @link       http://gna.org/projects/clansuite
 * @since      File available since Release 0.2
 *
 * @version    SVN: $Id$
 */

/**
 * Security Handler
 */
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * module INDEX controller
 * Purpose: A PageController which has many pages to deal with for the current Module.
 *
 * Class was rewritten for Version 0.2
 */
class module_index extends controller_base #implements clansuite_module
{
    function __construct(Phemto $injector=null)
    {
        parent::__construct(); # run constructor on controller_base
    }

    /**
     * Controller of Modul
     *
     * switches between $_REQUEST['action'] Vars to the functions
     */
    public function execute($request, $response)
    {
        $trail = $this->injector->instantiate('trail');

        // Set Pagetitle and Breadcrumbs
        $trail->addStep(T_('Index'), '/index.php?mod=index');

        switch ($request->getParameter('action'))
        {
            case 'mvc':
                $this->mvc();
                break;
            case 'show':
                $this->show();
                break;
            case 'index':
                $this->index();
                break;
            default:
                $this->show();
                break;
        }
    }

    /**
     *  Test the MVC Framework by calling "index.php?mod=index&action=mvc"
     */
    function mvc()
    {
        echo 'Clansuite Framework - MVC is working!';
        exit;
    }

    /**
     * forward index() to show()
     */

    function index()
    {
        # Load DBAL
        Clansuite_Doctrine::doctrine_bootstrap();

        # Load Models
        require ROOT . '/myrecords/generated/BaseCsNews.php';
        require ROOT . '/myrecords/CsNews.php';

        // Creating Pager Object with a Query Object inside
	    $pager = new Doctrine_Pager(
     	    Doctrine_Query::create()
     	    	->from( 'csnews n' )
     	        #->leftJoin( 'n.cat_id c' )
     	        ->orderby( 'n.news_id DESC' ),
     	    $currentPage, // Current page of request
     	    $resultsPerPage // (Optional) Number of results per page. Default is 25
 	    );
 	    var_dump($pager);

 	    echo 'Total of items found: ' . $pager->getNumResults();

        $this->setTemplate('index/show.tpl');
    }


    /**
     * Show the Index / Entrance -> welcome message etc.
     */

    function show()
    {
        // Example Usage of Dependency Injector as Registry
        #$config = $this->injector->getComponentInstance('configuration');

        /***
         * To set a Render Engine use the following method:
         * $this->setRenderEngine('smarty');
         *
         * You can define specific view_types linke (smarty, json, rss, php)
         *    -- or leave it away, then smarty is used as fallback!
         *
         */
        #$this->setRenderEngine('smarty');

        $this->output   .= 'action show called';

        /**
               * Usage of method: setTemplate($templatename)
               *
               * 1. you can specify a complete template-filename (including its path)
               * 2. if you NOT use this method, 
               * we try to automatically detect the template-file by using module and action as templatename.
               *
               * the template lookup will take place in the following paths:
               *    a) in the activated "layout theme" folder (according to the user-session) 
               *        example: usertheme = "standard" and $this->template = 'modulename/filename.tpl';
               *         then lookup of template in /standard/modulename/filename.tpl
               *    b) the modul-directory/templatefolder/rendererfolder/actionname.tpl
               *
               * As a result of this direct connection of URL to TPL, it's possible to
               * code in a very straightforward way:  index.php?mod=something&action=any
               * would result in a template-search in /modules/something/templates/any.tpl
               *
               * Even an empty module function would result in an rendering - a good starting point i guess!  
               *
               */
        #$this->setTemplate('index/show1.tpl');

        // Laden
        Clansuite_Doctrine::doctrine_bootstrap();

        require ROOT . '/myrecords/generated/BaseCsNews.php';
        require ROOT . '/myrecords/CsNews.php';

        # Show Loaded Models
        #print_r(Doctrine::getLoadedModels());


        // Speichern
        #$news = new CsNews();
        #var_dump($news);

        #$news['news_title'] = 'testingDoctrine'; # same as
        #$news->title = 'x';

        #$news->save();

        // Lesen

        // The normal find by primary key method
        $newsTable = Doctrine::getTable('CsNews');
        $news = $newsTable->findOneBynews_id(1);
        #var_dump($news);
        #print_r($news['news_title']);
        #print_r($news['title']);

        
        # Starting the View
        $this->setView($this->getRenderEngine());
        
        # Assign Stuff to the View
        # Get the Engine from the adapter
        $smarty = $this->view->getEngine();
        # assign directly to the engine
        $smarty->assign('news_title',$news['news_title']);
        
        $smarty->assign('news_object',$news->getData());
        
        # OR
        # use the adapter methods to assign indirectly to the view engine
        $this->view->assign('test', 'Hello test!');  

        # Prepare the Output
        $this->prepareOutput();
        #$response->setContent($view->render(parent::getTemplateName()));
    }
}


/** 
 * We don't need the following classes. 
 * They are here for understanding the MVC Pattern.
 */
 
/**
 * Purpose: View selects the Model for the choosen view(action)
 *          and assembles/prepares that view(action) with Model-Informations for Output
 *          When a Model-Object is fetched, the View calls a certain method on it to extract the data.
 *          Like $users = $userobject->findUserByID($id);
 *
 */
class module_index_view
{
     // instantiate the module_index_model
     // fetch data-object from the model via function
     // assign data-object to view        
}

/**
 * Purpose: Select Data from Database and return Model-Informations (complete objects) to the View-Layer
 *          Like return $user;
 */
class module_index_model
{
  /**
    public function findUserById($id)
    {
        //SQL logic to get User for certain id

        //returns User-ROW!
    }
    */
}
?>