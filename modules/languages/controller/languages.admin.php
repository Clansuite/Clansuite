<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    * @author     Jens-André Koch <vain@clansuite.com>
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
 * Clansuite_Module_Languages_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Languages
 */
class Clansuite_Module_Languages_Admin extends Clansuite_Module_Controller
{
    public function initializeModule()
    {
        # raise time limit for scanning and extraction
        @set_time_limit(900);
    }

    public static function createLanguagesDirIfNotExistant($module)
    {
        if(false == is_dir(ROOT_MOD . $module . DS . 'languages'))
        {
            mkdir(ROOT_MOD . $module . DS . 'languages', 0777, true);
        }
    }

    public static function createLanguage($module, $locale)
    {
        self::createLanguagesDirIfNotExistant($module);

        /**
         * Create directory structure for gettext translations
         *
         * A "locale" and "LC_MESSAGES" folder are needed, like:
         * /languages/<ll_CC>/LC_MESSAGES/
         */
        $path = ROOT_MOD . $module . DS . 'languages' . DS . $locale . DS . 'LC_MESSAGES';
        if(false == is_dir($path))
        {
            mkdir($path, 0777, true);
        }

        /**
         * Create gettext portable object file - empty, to start the translation process
         */
        $file = $path . DS . $module . '.po';
        if(false === is_file($file))
        {
            include ROOT_CORE . 'gettext.core.php';
            $fileheader = Clansuite_Gettext_Extractor_Tool::getPOFileHeader(true);
            file_put_contents($file, $fileheader);
        }
    }

    /**
     * Scans a module for language strings inside php and template files.
     *
     * In these files all text messages are in english.
     * Translations are based on the english language portable object file.
     * This file is written to the locale directory en_GB for the module processed.
     * The path is ROOT/modules/{module}/languages/en_GB/LC_MESSAGES/{module}.po
     *
     * @param string $module_name Name of the module to scan for language strings.
     */
    public static function scanModule($module)
    {
        if(DEBUG)
        {
            self::createLanguagesDirIfNotExistant($module);
        }

        $gettext_extractor = new Clansuite_Gettext_Extractor();
        $gettext_extractor->multiScan( ROOT_MOD . $module );
        $gettext_extractor->save( ROOT_MOD . $module . '/languages/en_GB/LC_MESSAGES/'. $module . '.po' );
    }

    /**
     * Scans all modules for language strings
     *
     * @uses scanModule()
     * @uses Clansuite_ModuleInfoController::getModuleNames()
     */
    public static function scanAllModules()
    {
        $module_names = Clansuite_ModuleInfoController::getModuleNames();

        foreach($module_names as $module)
        {
            foreach($module as $name => $path)
            {
                self::scanModule($name);
            }
        }
    }

    /**
     * Scans a theme for language strings
     *
     * Via GET incoming
     *  - "type" (frontend/backend)
     *  - "name" (themename)
     */
    public function action_admin_scanTheme()
    {
        # name is the themename
        $theme_name = $this->request->getParameter('name', 'GET');

        # with type (frontend/backend), we know also the correct folder
        $theme_type = $this->request->getParameter('type', 'GET');

        ob_start();
        self::scanTheme($theme_name, $theme_type);
        $scan_log_content = ob_get_contents();
        ob_end_clean();

        # display scanner log
        $view = $this->getView();
        $view->assign('scan_log', $scan_log_content);
        $this->display();
    }

    public static function scanTheme($theme_name, $theme_type)
    {
        if(DEBUG)
        {
            self::createLanguagesDirIfNotExistant($theme_name);
        }

        $gettext_extractor = new Clansuite_Gettext_Extractor();
        $gettext_extractor->multiScan(ROOT_THEME . $theme_name);

        /**
         * All text messages of the system are in english.
         * Translations are based on the english language portable object file.
         * This file is written to the locale directory en_GB for each module processed.
         */

        # ROOT_THEMES/{theme_type}/{theme_name}/languages/en_GB/LC_MESSAGES/{theme_name}.po
        $path = array();
        $path[] = ROOT_THEMES;
        $path[] = $theme_type;
        $path[] = $theme_name;
        $path[] = 'languages/en_GB/LC_MESSAGES';
        $path[] = $theme_name . '.po';

        # In the next step we build that filepath string from an array.
        $path = implode(DS, $path);

        $gettext_extractor->save( $path );
    }

    public function action_admin_show()
    {
        # get themes
        $themes = Clansuite_Theme::getThemeDirectories();

        # get modules
        #$modules = Clansuite_ModuleInfoController::getModuleNames(true);
        $modules = Clansuite_ModuleInfoController::loadModuleInformations();
        # pop the counter off the end
        array_pop($modules);

        $view = $this->getView();
        $view->assign('themes', $themes);
        $view->assign('modules', $modules);
        $view->assign('cores', array()); # @todo fetch core language items
        $this->display();
    }

    /**
     * Scans php/tpl files of a module and collects data into a gettext PO file
     */
    public function action_admin_scanmodule()
    {
        $module = $this->request->getParameter('modulename', 'GET');

        # scan module and buffer the log output
        ob_start();
        self::scanModule($module);
        $scan_log_content = ob_get_contents();
        ob_end_clean();

        # display scanner log
        $view = $this->getView();
        $view->assign('scan_log', $scan_log_content);
        
        $this->setFlashmessage('success', _('The module was scanned successfully and the Portable Object File was updated.'));
        $this->response->redirectNoCache('/languages/admin', 10, 302, 'success#The module has been successfully created.');
    }

    /**
     * Scans php/tpl files of all modules and collects the data into gettext PO files
     */
    public function action_admin_scanallmodules()
    {
        # scan modules and buffer the log output
        ob_start();
        self::scanAllModules();
        $scan_log_content = ob_get_contents();
        ob_end_clean();

        # display scanner log
        $view = $this->getView();
        $view->assign('scan_log', $scan_log_content);
        $this->display();
    }

    public function action_admin_readlanguage()
    {

    }

    public function action_admin_editlanguage()
    {

    }

    public function action_admin_deletelanguage()
    {

    }

    /**
     * Handles post request byitself and forwards to action for the get request.
     */
    public function action_admin_addlanguage()
    {
        #Clansuite_Breadcrumb::add( _('Add language'), '/languages/admin/addlanguage');

        # handle get request
        if($this->request->getRequestMethod() == 'GET')
        {
            $module = $this->request->getParameter('modulename', 'GET');

            $form = new Clansuite_Form('languages_dropdown', 'post', WWW_ROOT . 'index.php?mod=languages&sub=admin&action=addlanguage');
            $form->setDecoratorAttributesArray(array('form' => array('fieldset' => array('legend' => _('Select the language to add')))));
            # $_POST['locale']
            $form->addElement('selectlocale')->setDescription('Use the dropdown to select a locale by name or abbreviation.');
            # $_POST['module']
            $form->addElement('hidden')->setName('module')->setValue($module);
            $form->addElement('buttonbar');

            $view = $this->getView();
            $view->assign('modulename', $module);
            $view->assign('form_languages_dropdown', $form->render());

            $this->display();
        }
        else
        {
            # Handle Post Request
            if($this->request->getRequestMethod() == 'POST' and
               $this->request->issetParameter('module', 'POST') and $this->request->issetParameter('locale', 'POST'))
            {
                # fetch incomming post parameters
                $module = $this->request->getParameter('module', 'POST');
                $locale = $this->request->getParameter('locale', 'POST'); # example: de_AT

                # create new language file
                self::createLanguage($module, $locale);

                $this->setFlashmessage('success', 'Yo!');

                # Redirect
                #$this->response->redirectNoCache('/languages/admin', 10, 302, 'success#The language file has been successfully created.');
            }
        }
    }

    /**
     * Fetch translation via Google Translation API
     *
     * @link http://code.google.com/intl/de-DE/apis/ajaxlanguage/documentation/reference.html
     * @deprecated This API is deprecated by Google. Need to find other API translate service.
     */
    public function ajax_action_admin_translate_google()
    {
        # get the incomming string to translate
        $message = htmlspecialchars($_POST['message']); # msgid

        # get the incomming target language
        $targetlanguage = htmlspecialchars($_POST['targetlanguage']);

        # prepare $message string
        $search = array('\\\\\\\"', '\\\\\"','\\\\n', '\\\\r', '\\\\t', '\\\\$','\\0', "\\'", '\\\\');
        $replace = array('\"', '"', "\n", "\r", "\\t", "\\$", "\0", "'", "\\");
	       $message = str_replace( $search, $replace, $message );

        # remote fetch
        $google_api_url = 'http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&format=html';
        $translated_string = Clansuite_RemoteFetch::fetch($google_api_url."&q=".urlencode($message)."&langpair=en%7C".$targetlanguage);

        # if google answered, output the translated string in json format
        if($translated_string)
        {
            $this->getView('json')->assign($translated_string);
            $this->display();
        }
        else
        {
            $this->setFlashmessage('error', 'The Google Translation Service is not available.');
            $this->redirectToReferer();
        }
    }
}
?>