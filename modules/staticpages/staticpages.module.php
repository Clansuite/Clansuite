<?php
   /**
    * Clansuite - just an eSports CMS
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
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: news.module.php 2006 2008-05-07 09:08:40Z xsign $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite
 *
 * Module:  Static Pages
 *
 */
class Module_Staticpages extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_News -> Execute
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }

    /**
    * @desc Show a specific static page
    */
    function action_show()
    {
        $page = (string) $this->injector->instantiate('httprequest')->getParameter('page');

        // if no page is requested, show overview
        if(empty($page)) { return $this->action_overview(); }

        // Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show ' . $page), '/index.php?mod=staticpages&amp;action=show&page='. $page);

        // get inputfilter class
        $input = $this->injector->instantiate('input');

        // check if page was set and is sanitized
        if ( !empty($page) AND $input->check( $page, 'is_abc|is_int|is_custom', '_\s' ) )
        { 
            $result = Doctrine_Query::create()
                                    ->select('*')
                                    ->from('CsStaticPage')
                                    ->where('title = ?')
                                    ->execute( array( $page ), Doctrine::FETCH_ARRAY );

            if ( is_array( $result ) )
            {
                if ( empty($result['url']) )
                {
                    #$this->mod_page_title = $result['title'] . ' - ' . $result['description'];

                    # Get Render Engine
                    $smarty = $this->getView();
                    $smarty->assign('staticpagecontent', $result);

                    #$this->output .= $result['0']['html'];
                }
                /* static page is iframe??
                else
                {
                    $this->mod_page_title = $result['title'] . ' - ' . $result['description'];
                    if ( $result['iframe'] == 1 )
                    {
                        $this->output .= '<iframe width="100%" height="'. $result['iframe_height'] .'" frameborder="0" scrolling="auto" src="' . $result['url'] . '"></iframe>';
                    }
                    else
                    {
                        $this->output .= file_get_contents( $result['url'] );
                    }
                }*/
            }
            else
            {
                $this->output .= _('This static page does not exist.');
            }
        }
        else // page was not set or not sanitized
        {
            #$this->redirect('index.php?mod=staticpages&action=overview');
        }

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Static Pages
     * show all static pages
     */
    function action_overview()
    {
        // Set Pagetitle and Breadcrumbs
        trail::addStep( _('Overview'), '/index.php?mod=staticpages&amp;action=overview');

        // get all static pages without page content
        $result = Doctrine_Query::create()
                              ->select('id,title,description')
                              ->from('CsStaticPage')
                              ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                              ->execute();

        # Get Render Engine
        $smarty = $this->getView();

        if ( is_array($result) )
        {
            $smarty->assign('overview', $result);
            $this->setTemplate('overview.tpl');
        }
        else
        {
            echo _('No static pages found.');
        }

        # Prepare the Output
        $this->prepareOutput();
    }
}
?>