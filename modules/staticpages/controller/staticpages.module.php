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
    * @version    SVN: $Id: index.module.php 2625 2008-12-09 00:04:43Z vain $
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Module - Static Pages
 *
 */
class Clansuite_Module_Staticpages extends Clansuite_Module_Controller
{
    /**
     * Module_Staticpages -> Execute
     */
    public function initializeModule()
    {
        # read module config
        $this->getModuleConfig();

        # initialize related active-records
        parent::initModel('staticpages');
    }

    #  Show a specific static page

    public function action_show()
    {
        $page = (string) $this->request->getParameterFromGet('page');

        var_dump($page);

        // if no page is requested, show overview
        if(empty($page))
        {
            return $this->action_overview();
        }

        // Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show ' . $page), '/index.php?mod=staticpages&amp;action=show&page='. $page);

        // get inputfilter class
        #$input = $this->getInjector()->instantiate('Clansuite_Inputfilter');

        // check if page was set and is sanitized # and $input->check( $page, 'is_abc|is_int|is_custom', '_\s' )
        if ( !empty($page)  )
        {
            $result = Doctrine_Query::create()
                    ->select('*')
                    ->from('CsStaticPages')
                    ->where('title = ?')
                    ->execute( array( $page ), Doctrine::HYDRATE_ARRAY);

            #var_dump($result);

            if ( is_array( $result ) )
            {
                if ( empty($result['url']) )
                {
                    #$this->mod_page_title = $result['title'] . ' - ' . $result['description'];

                    # Get Render Engine
                    $view = $this->getView();
                    $view->assign('staticpagecontent', $result);

                    #$this->output .= $result['0']['html'];
                }
                /*else # static page is iframe??
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
                echo _('This static page does not exist.');
            }
        }
        else // page was not set or not sanitized

        {
            #$this->redirect('index.php?mod=staticpages&action=overview');
        }

        $this->display();
    }

    /**
     * Static Pages
     * show all static pages
     */
    public function action_overview()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Overview'), 'index.php?mod=staticpages&amp;action=overview');

        // get all static pages without page content
        $result = Doctrine_Query::create()
                ->select('id,title,description')
                ->from('CsStaticPages')
                ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                ->execute();

        if ( is_array($result) )
        {
            # Get Render Engine
            $this->getView()->assign('overview', $result);
            $this->setTemplate('overview.tpl');
        }
        else
        {
            echo _('No static pages found.');
        }

        $this->display();
    }
}
?>