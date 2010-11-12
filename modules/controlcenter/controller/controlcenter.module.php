<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
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
 * Clansuite_Module_ControlCenter
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  ControlCenter
 */
class Clansuite_Module_ControlCenter extends Clansuite_Module_Controller
{
    public function initializeModule()
    {
        parent::initModel('menu');
    }

    public function action_show()
    {
        # Get Render Engine
        $view = $this->getView();

        $images = '';

        $view->assign( 'shortcuts', $images );
        $view->assign( 'newsfeed', $this->assignFeedContent());
        $view->assign( 'errorlog', $this->assignErrorlogInfos());

        $this->display();
    }

    public function action_bugs()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Report Bugs &amp; Issues'), '/controlcenter/bugs');

        $this->display();
    }

    public function action_about()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('About Clansuite'), '/controlcenter/about');

        $this->display();
    }

    public function action_about1()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('About Clansuite'), '/controlcenter/about');

        $this->display();
    }

    public function action_supportlinks()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Links for Help & Support'), '/help/admin/supportlinks');

        $this->display();
    }

    private function assignErrorlogInfos()
    {
        include ROOT_CORE . 'logger/file.logger.php';
        $errorlog_entries = null;
        $errorlog_entries = Clansuite_Logger_File::returnEntriesFromLogfile(3);

        $securityinfos = null;
        $securityinfos = '<b>'._('Errorlog').'</b><br />';

        if(strlen($errorlog_entries) > 0)
        {
            $securityinfos .= $errorlog_entries;
        }
        else
        {
            $securityinfos .= _('No Errorlog entries.');
        }

        return $securityinfos;
    }

    private function assignFeedContent()
    {
        $feedcontent = null;

        # get Feed Data (from Cache or File)
        $feedcontent = Clansuite_Feed::fetchRawRSS('http://groups.google.com/group/clansuite/feed/rss_v2_0_topics.xml');

        # try to read as xml
        if(is_null($feedcontent) == false)
        {
            if(class_exists('SimpleXMLElement'))
            {
                $xml = new SimpleXMLElement($feedcontent);
            }
            else
            {
                throw new Clansuite_Exception('SimpleXMLElement class does not exist!', 100);
            }
        }
        else
        {
            throw new Clansuite_Exception('Feed could not be read.', 100);
            #Clansuite_Logger::log('', $e);
            $xml = '';
        }

        # set output var
        $output = '';

        # process output
        $i = 0;
        $max_rss_items = $this->getConfigValue('news_rss_items', '5');

        foreach( $xml->channel->item as $items )
        {
            $i++;
            $output .= '<p><strong>#'.$i.' - <a href="' . $items->link . '">' . htmlspecialchars($items->title) . '</a></strong><br />';
            $output .= '<span style="font-size: 11px;">' . htmlspecialchars($items->pubDate) . '</span><br /></p>';

            # show 10 items @todo configvalue for itemnumber
            if ( $i == $max_rss_items )
            {
                break(0);
            }
        }

        return $output;
    }

    public function action_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), '/controlcenter/admin/settings');

        $settings = array();

        $settings['form']   = array(    'name' => 'controlcenter_settings',
                'method' => 'POST',
                'action' => '/controlcenter/settings_update');

        $settings['controlcenter'][] = array(
                'id' => 'show_box_shortcuts',
                'name' => 'show_box_shortcuts',
                'description' => _('Show Shortcuts'),
                'formfieldtype' => 'selectyesno',
                'value' => $this->getConfigValue('show_box_shortcuts', '1'));

        $settings['controlcenter'][] = array(
                'id' => 'show_box_news',
                'name' => 'show_box_news',
                'description' => _('Show News'),
                'formfieldtype' => 'selectyesno',
                'value' => $this->getConfigValue('show_box_news', '1'));

        $settings['controlcenter'][] = array(
                'id' => 'show_box_security',
                'name' => 'show_box_security',
                'description' => _('Show Security'),
                'formfieldtype' => 'selectyesno',
                'value' => $this->getConfigValue('show_box_security', '1'));

        $settings['controlcenter'][] = array(
                'id' => 'show_box_extensions',
                'name' => 'show_box_extensions',
                'description' => _('Show Extensions'),
                'formfieldtype' => 'selectyesno',
                'value' => $this->getConfigValue('show_box_extensions', '1'));

        $form = new Clansuite_Form($settings);

        # display formgenerator object
        #Clansuite_Debug::printR($form);

        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');

        # display form html
        #Clansuite_Debug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    public function action_settings_update()
    {
        # Incomming Data
        # @todo get post via request object, sanitize
        $data = $this->request->getParameter('controlcenter_settings');

        # Get Configuration from Injector
        $config = $this->getInjector()->instantiate('Clansuite_Config');

        # write config
        $config->confighandler->writeConfig( ROOT_MOD . 'controlcenter/controlcenter.config.php', $data);

        # clear the cache / compiled tpls
        $this->getView()->clearCache();

        # Redirect
        $this->response->redirectNoCache('/controlcenter', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>