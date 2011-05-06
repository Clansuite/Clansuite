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
    * @copyright  Jens-André Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

class Clansuite_Form_News_Action_admin_settings extends Clansuite_Form
{
    /**
     * Constructor.
     *
     * This is just another way of instantiating a new form.
     *  $form = new Clansuite_Form(self::getFormDescription());
     */
    public function __construct()
    {
        # fill the settings array into the formgenerator
        parent::__construct(self::getFormDescription());

        # then add some additional stuff this form
        $this->setupForm();
    }

    /**
     * Returns a form description array
     *
     * @return array A form describing Array.
     */
    public static function getFormDescription()
    {
        $settings = array();

        $settings['form']   = array(    'name' => 'news_settings',
                                        'method' => 'POST',
                                        'action' => '/news/admin/settings_update');

        $settings['news'][] = array(    'id' => 'resultsPerPage_show',
                                        'name' => 'resultsPerPage_show',
                                        'label' => 'News Items',
                                        'description' => _('Newsitems to show in Newsmodule'),
                                        'formfieldtype' => 'text',
                                        'value' => Clansuite_Module_Controller::getConfigValue('resultsPerPage_show', '3'));

        $settings['news'][] = array(    'id' => 'items_newswidget',
                                        'name' => 'items_newswidget',
                                        'label' => 'LatestNews Widget Items',
                                        'description' => _('Newsitems to show in LatestNews Widget'),
                                        'formfieldtype' => 'text',
                                        'value' => Clansuite_Module_Controller::getConfigValue('items_newswidget', '5'));

        $settings['news'][] = array(    'id' => 'resultsPerPage_fullarchive',
                                        'name' => 'resultsPerPage_fullarchive',
                                        'label' => 'Newsarchive Items',
                                        'description' => _('Newsitems to show in Newsarchive'),
                                        'formfieldtype' => 'text',
                                        'value' => Clansuite_Module_Controller::getConfigValue('resultsPerPage_fullarchive', '3'));

        $settings['news'][] = array(    'id' => 'resultsPerPage_adminshow',
                                        'name' => 'resultsPerPage_adminshow',
                                        'label' => 'Admin News Items',
                                        'description' => _('Newsitems to show in the administration area.'),
                                        'formfieldtype' => 'text',
                                        'value' => Clansuite_Module_Controller::getConfigValue('resultsPerPage_adminshow', '10'),
                                        'validationrules' => array('int'),
                                        'errormessage' => 'Please use digits!');

        $settings['news'][] = array(    'id' => 'resultsPerPage_archive',
                                        'name' => 'resultsPerPage_archive',
                                        'label' => 'Newsarchive Widget Items',
                                        'description' => _('Newsitems to show in Newsarchive'),
                                        'formfieldtype' => 'text',
                                        'value' => Clansuite_Module_Controller::getConfigValue('resultsPerPage_archive', '3'));

        $settings['news'][] = array(    'id' => 'feed_format',
                                        'name' => 'feed_format',
                                        'label' => 'Newsfeed Format',
                                        'description' => _('Set the default format of the news feed. You can chose among these options: RSS2.0, MBOX, OPML, ATOM, HTML, JS'),
                                        'formfieldtype' => 'multiselect',
                                        'value' => array( 'selected' => Clansuite_Module_Controller::getConfigValue('feed_format', 'RSS2.0'),
                                                          'RSS2.0'   => 'RSS2.0',
                                                          'MBOX'     => 'MBOX',
                                                          'OPML'     => 'OPML',
                                                          'ATOM'     => 'ATOM',
                                                          'HTML'     => 'HTML',
                                                          'JS'       => 'JS'));

        $settings['news'][] = array(    'id' => 'feed_items',
                                        'name' => 'feed_items',
                                        'label' => 'Newsfeed Items',
                                        'description' => _('Sets the default number of feed items.'),
                                        'formfieldtype' => 'text',
                                        'value' => Clansuite_Module_Controller::getConfigValue('feed_items', '10'));

        return $settings;
    }

    /**
     * Sets up the Form by adding some additional form properties or elements.
     * Then finally returns the form.
     *
     * @return Clansuite_Form_News_Action_admin_settings
     */
    public function setupForm()
    {
        # add css
        $this->setClass('News');

        # add the buttonbar
        $this->addElement('buttonbar')->getButton('cancelbutton')->setCancelURL('index.php?mod=news/admin');
        $this->addDecorator('fieldset')->setLegend(_('News Settings'));

        # triggers __toString() and renders the form
        return $this;
    }
}
?>