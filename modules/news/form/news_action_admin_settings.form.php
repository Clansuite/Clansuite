<?php
class Clansuite_Form_News_Action_admin_settings extends Clansuite_Form
{
    /**
     * Constructor.
     *
     * This is just another way of instantiating a new form.
     *  $form = new Clansuite_Form(self::getFormdescription());
     */
    public function __construct()
    {
        # fill the settings array into the formgenerator
        parent::__construct(self::getFormdescription());
        
        $this->setupForm();
    }

    public static function getFormdescription()
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

    public function setupForm()
    {
        # add css
        $this->setClass('News');

        # add the buttonbar
        $this->addElement('buttonbar')->getButton('cancelbutton')->cancelURL = 'index.php?mod=news/admin';
        $this->addDecorator('fieldset')->setLegend(_('News Settings'));

        return $this;
    }
}
?>