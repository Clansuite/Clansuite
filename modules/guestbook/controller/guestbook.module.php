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
    * @author     Jens-André Koch  <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * This is the Clansuite Module Class - Guestbook
 *
 * @author     Jens-André Koch <vain@clansuite.com>, Florian Wolf <xsign.dll@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards), Florian Wolf (2006-2007)
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Guestbook
 */
class Clansuite_Module_Guestbook extends Clansuite_Module_Controller
{
    /**
     * Module_Guestbook -> Execute
     */
    public function initializeModule()
    {
        parent::initModel('guestbook');
    }

    public function action_show()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/guestbook/show');

        # Defining initial variables
        # Pager Chapter in Doctrine Manual  -> http://www.phpdoctrine.org/documentation/manual/0_10?one-page#utilities
        $currentPage = $this->request->getParameter('page');
        $resultsPerPage = (int) $this->getConfigValue('resultsPerPage', '10');

        // Creating Pager Object with a Query Object inside
        $pager_layout = new Doctrine_Pager_Layout(
                        new Doctrine_Pager(
                            Doctrine_Query::create()
                                    ->select('i.*,g.*,u.nick')
                                    ->from('CsGuestbook g')
                                    ->leftJoin('g.CsImages i')
                                    ->leftJoin('g.CsUsers u')
                                    #->where('c.module_id = 7')
                                    #->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                    ->orderby('g.gb_added DESC'),
                                 # The following is Limit  ?,? =
                                 $currentPage, // Current page of request
                                 $resultsPerPage // (Optional) Number of results per page Default is 25
                             ),
                             new Doctrine_Pager_Range_Sliding(array(
                                 'chunk' => 5
                             )),
                             '?mod=guestbook&action=show&page={%page}'
                             );
        // Assigning templates for page links creation
        $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');
        $pager_layout->setSelectedTemplate('[{%page}]');

        // Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        // Fetching guestbook entries
        #var_dump($pager->getExecuted());
        $guestbook = $pager->execute(array(), Doctrine::HYDRATE_ARRAY);

        // if array contains data proceed, else show empty message
        if ( !is_array( $guestbook ) )
        {
            $error['gb_empty'] = '1';
        }
        else
        {
            // total number of guestbook entries by counting the array
            $number_of_guestbook_entries = count($guestbook);

            // Finally: assign total number of rows to SmartyPaginate
            #$SmartyPaginate->setTotal($number_of_guestbook_entries);
            // assign the {$paginate} to $tpl (smarty var)
            #$SmartyPaginate->assign($tpl);

            $bbcode = new Clansuite_Bbcode($this->getInjector());

            // Set 'not specified's
            foreach( $guestbook as $entry_key => $entry_value )
            {
                foreach( $entry_value as $key => $value )
                {
                    switch( $key )
                    {
                        case 'gb_comment':
                            if( empty($value) )
                                unset($guestbook[$entry_key][$key]);
                            else
                                $guestbook[$entry_key][$key] = $bbcode->parse($guestbook[$entry_key][$key]);
                            break;

                        case 'gb_text':
                            $guestbook[$entry_key][$key] = $bbcode->parse($guestbook[$entry_key][$key]);
                            break;

                        default:
                            $guestbook[$entry_key][$key] = empty($value) ? '<span class="not_specified">' . _('not specified') . '</span>' : $value;
                            break;
                    }
                }
            }
        }


        $view = $this->getView();

        $view->assign( 'guestbook', $guestbook);

        if(isset($error)){$view->assign( 'error' , $error );}

        $view->assign('pager', $pager);
        $view->assign('pager_layout', $pager_layout);

        $form = new Clansuite_Form('eingabe','post',$_SERVER['PHP_SELF']);
        $view->assign('form', $form);

        $this->display();
    }
}
?>