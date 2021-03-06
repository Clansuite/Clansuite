<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-AndrÃ© Koch Â© 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Clansuite\Modules\Staticpages\Controller;

use Clansuite\Core\Mvc\ModuleController;

/**
 * Clansuite Module - Static Pages
 *
 */
class StaticpagesController extends ModuleController
{
    /**
     * Module_Staticpages -> Execute
     */
    public function _initializeModule()
    {
        // read module config
        $this->getModuleConfig();
    }

    //  Show a specific static page

    public function actionShow()
    {
        $page = (string) $this->request->getParameterFromGet('page');

        var_dump($page);

        // if no page is requested, show overview
        if (empty($page)) {
            return $this->action_overview();
        }

        // Set Pagetitle and Breadcrumbs
        \Koch\View\Helper\Breadcrumb::add( _('Show ' . $page), '/index.php?mod=staticpages/show&page='. $page);

        // get inputfilter class
        #$input = $this->getInjector()->instantiate('Clansuite_Inputfilter');

        // check if page was set and is sanitized // and $input->check( $page, 'is_abc|is_int|is_custom', '_\s' )
        if ( !empty($page)  ) {
            $result = Doctrine_Query::create()
                    ->select('*')
                    ->from('CsStaticPages')
                    ->where('title = ?')
                    ->execute( array( $page ), Doctrine::HYDRATE_ARRAY);

            #var_dump($result);

            if ( is_array( $result ) ) {
                if ( empty($result['url']) ) {
                    #$this->mod_page_title = $result['title'] . ' - ' . $result['description'];

                    // Get Render Engine
                    $view = $this->getView();
                    $view->assign('staticpagecontent', $result);

                    #$this->output .= $result['0']['html'];
                }
                /*else { // static page is iframe??
                    $this->mod_page_title = $result['title'] . ' - ' . $result['description'];
                    if ($result['iframe'] == 1) {
                        $this->output .= '<iframe width="100%" height="'. $result['iframe_height'] .'" frameborder="0" scrolling="auto" src="' . $result['url'] . '"></iframe>';
                    } else {
                        $this->output .= file_get_contents( $result['url'] );
                    }
                }*/
            } else {
                echo _('This static page does not exist.');
            }
        } else // page was not set or not sanitized

        {
            #$this->redirect('index.php?mod=staticpages&action=overview');
        }

        $this->display();
    }

    /**
     * Show all static pages
     */
    public function actionList()
    {
        // Set Pagetitle and Breadcrumbs
        \Koch\View\Helper\Breadcrumb::add(_('List'), '/staticpages/list');

        // get all static pages without page content
        $dql = 'SELECT id, title, description FROM \Entity\Staticpages';
        $results = $this->doctrine_em->createQuery($dql)->getResult();

        if (is_array($result)) {
            $this->getView()->assign('overview', $result);
        } else {
            echo _('No static pages found.');
        }

        $this->display();
    }
}
