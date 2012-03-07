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
    public function _initializeModule()
    {
        # raise time limit for scanning and extraction
        @set_time_limit(900);
    }

    public static function createLanguagesDirIfNotExistant($module = '')
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
         * Gettext needs a "locale" and "LC_MESSAGES" folder: /languages/<ll_CC>/LC_MESSAGES/
         */

        # path to gettext messages folder
        $path = ROOT_MOD . $module . DS . 'languages' . DS . $locale . DS . 'LC_MESSAGES';

        if(false === is_dir($path))
        {
            # create dir
            if(false === mkdir($path, 0777, true))
            {
                throw new Clansuite_Exception('Gettext folder creation failed.');
            }
        }

        /**
         * Create gettext portable object file
         */

        # path to po file
        $file = $path . DS . $module . '.po';

        if(false === is_file($file))
        {
            # gettext is needed to fetch the po fileheader
            include ROOT_CORE . 'gettext.core.php';

            $fileheader = Clansuite_Gettext_Extractor_Tool::getPOFileHeader(true);

            # create file
            if(false === file_put_contents($file, $fileheader))
            {
                throw new Clansuite_Exception('Gettext PO file creation failed.');
            }
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
    public static function scanModule($module = '')
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

    /**
     * Scan Theme extracts gettext keys from a theme.
     *
     * @param string $theme_name
     * @param string $theme_type "frontend", "backend"
     */
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

    public function action_admin_list()
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

    /**
     * Edit the language items of a module
     *
     * Left Side => Right Side
     * English   => Target Language
     *
     */
    public function action_admin_edit()
    {
        if($this->request->getRequestMethod() == 'GET')
        {
            # get "module" and target "locale" for editing
            $module = $this->request->getParameter('module', 'GET');
            $locale = $this->request->getParameter('locale', 'GET');

            # transform "de-DE" to "de_DE" because locale dirs have underscores
            $locale = str_replace('-', '_', $locale);

            /**
             * We translate *from* english to the target language.
             *
             * English locale is the base for all translations.
             * This fetches all english language strings of the module.
             */
            include ROOT_CORE . 'gettext/po.gettext.php';
            $english_data = Gettext_PO_File::read( $this->getModulePOFilename($module, 'en_GB') );
            $english_data = $this->preparePODataForView($english_data);

            #Clansuite_Debug::printR($english_data);

            $this->getView()->assign('english_locale', $english_data);

            /**
            * We translate *to* the target locale.
            *
            * The fetches the locale to edit.
            */
            $target_locale_pofile = $this->getModulePOFilename($module, $locale);
            $target_locale_data = Gettext_PO_File::read( $target_locale_pofile );
            $target_locale_data = $this->preparePODataForView($target_locale_data);

            #Clansuite_Debug::printR($target_locale_data);

            $this->view->assign('target_locale', $target_locale_data);

            /**
             * Setup Form
             *
             * This form shows one text field per language string (gettext msgstr).
             * The english original string is displayed as formelement description text.
             *
             * Developer Note:
             * ---------------
             * If anyone wants to polish this with an implementation of a table view
             * with ajax live-editing or inline-editing of table cells, then
             * (a) you will make me happy and (b) if you contact me before
             * and discuss the implementation details, then i will pay for it.
             * Take the folling table as an good example for an gettext editor dialog:
             * http://www.gted.org/screenshots/entries_horizontal.gif
             *
             */

            $form = new Clansuite_Form('Edit Locale');
            $form->setLegend('Edit Locale');
            $form->setHeading('You are editing the "'.$locale.'" locale of the module "'.ucfirst($module).'".');

            # remove metadata from end of array
            array_pop($english_data);

            $i = 0;
            foreach($english_data as $data_set)
            {
                $msgid = htmlentities($data_set['msgid']);

                $form->addElement('text')
                        # use the gettext msgid as array key on $_POST
                        ->setName('locale_form['.$msgid.']')
                        ->setLabel('Phrase ' . $i)
                        # show the gettext msgid as description text
                        ->setDescription('"' . $msgid . '"');

                $i = $i + 1;
            }
            # add hidden formfields to transfers our target locale and module
            $form->addElement('hidden')->setName('locale')->setValue($locale);
            $form->addElement('hidden')->setName('module')->setValue($module);
            $form->addElement('buttonbar');

            $this->view->assign('form', $form->render());

            $this->display();
        }

        # update
        if($this->request->getRequestMethod() == 'POST')
        {
            $this->action_admin_update();
        }
    }

    public function action_admin_update()
    {
        if(false === ($this->request->getRequestMethod() == 'POST'))
        {
            return;
        }

        Clansuite_Debug::dump($this->request->getPost(), false);

        $locale_msgstr_array = $this->request->getParameterFromPost('locale_form');
        $locale = $this->request->getParameterFromPost('locale');
        $module = $this->request->getParameterFromPost('module');
        var_dump($locale_msgstr_array);

        /**
         * Fetch the po file data of the target locale.
         */
        include ROOT_CORE . 'gettext/po.gettext.php';
        $target_locale_pofile = $this->getModulePOFilename($module, $locale);
        $target_locale_data = Gettext_PO_File::read( $target_locale_pofile );

        /**
         * $locale_msgstr_array is the following relation:
         *
         * msgid is the string in english as an identifier.
         * msgstr is the translation string to use for the identifier.
         *
         * msgid  = 'house' (english)
         * msgstr = 'haus'  (german)
         */

        $added_counter = 0;
        $updated_counter = 0;

        foreach($locale_msgstr_array as $msgid => $msgstr)
        {
            # only add something, if we got a translation string for this msgid
            if($msgstr != '')
            {
                # if the msgstr already exists, then it's an update
                if(true === isset($target_locale_data[$msgid]))
                {
                    $updated_counter = $updated_counter + 1;
                }
                else
                {
                    # a new language string is added
                    $added_counter = $added_counter + 1;
                }

                $target_locale_data[$msgid]['msgid'] = $msgid;
                $target_locale_data[$msgid]['msgstr'] = $msgstr;

                # @todo add plural strings
                #$target_locale_data[$msgid]['msgstr'] = array(0 => $msgstr);
            }
        }

        Clansuite_Debug::dump($target_locale_data);

        /*$msg = sprintf('Locale %s of Module %s updated. Added %s new language items. Updated %s language items.',
                $locale, $module, $added_counter, $updated_counter);

        $this->setFlashmessage('success', $msg);*/
    }

    /**
     * Returns the po file path for a locale of a module.
     *
     * @param string $module Module
     * @param string $locale Locale (like de_DE; underscored)
     */
    public function getModulePOFilename($module, $locale)
    {
        return ROOT_MOD . $module.DS.'languages'.DS.$locale.DS.'LC_MESSAGES'.DS.$module.'.po';
    }

    public function preparePODataForView($po_data)
    {
        # remove the first array entry, which contains po file meta data
        array_shift($po_data);

        # count the total number of items to translate
        # and attach as meta data to the array
        $po_data['meta']['total_num_items'] = count($po_data);

        return $po_data;
    }

    public function action_admin_delete()
    {
        if($this->request->getRequestMethod() == 'GET')
        {
            $module = $this->request->getParameter('module', 'GET');
            $locale = $this->request->getParameter('locale', 'GET');

            $directory = ROOT_MOD . $module . DS . 'languages' . DS . $locale . DS;

            #Clansuite_Logger::log('Deleted language '.$directory.' of module '.$module, 'adminaction', INFO);

            # delete locale dir
            Clansuite_Functions::delete_dir_content($directory, false);
        }
    }

    /**
     *
     */
    public function action_admin_new()
    {
        Clansuite_Breadcrumb::add( _('Add language'), '/languages/admin/new');

        # handle get request
        if($this->request->getRequestMethod() == 'GET')
        {
            $module = $this->request->getParameter('modulename', 'GET');

            $form = new Clansuite_Form('languages_dropdown', 'post', WWW_ROOT . 'index.php?mod=languages&sub=admin&action=new');
            $form->setLegend(_('Select the language to add'));

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
    }

    /**
     *
     */
    public function action_admin_insert()
    {
        # Handle Post Request
        if($this->request->getRequestMethod() == 'POST' and
           $this->request->issetParameter('module', 'POST') and
           $this->request->issetParameter('locale', 'POST'))
        {
            # fetch incomming post parameters
            $module = $this->request->getParameter('module', 'POST');
            $locale = $this->request->getParameter('locale', 'POST'); # example: de_AT

            # create new language file
            self::createLanguage($module, $locale);

            $this->setFlashmessage('success', 'Yo!');

            # Redirect
            $this->response->redirectNoCache('/languages/admin', 10, 302, 'success#The language file has been successfully created.');
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

class Translate
{
    public $source_locale, $target_locale;
    public $source_pofile, $target_pofile;

    public $target_module;

    public function getTargetModule()
    {
        return $this->target_module;
    }

    public function setTargetModule($target_module)
    {
        $this->target_module = $target_module;
    }

    public function getSourceLocale()
    {
        return $this->source_locale;
    }

    public function setSourceLocale($source_locale)
    {
        $this->source_locale = $source_locale;
    }

    public function getTargetLocale()
    {
        return $this->target_locale;
    }

    public function setTargetLocale($target_locale)
    {
        $this->target_locale = $target_locale;
    }

    public function getSourcePofile()
    {
        return $this->source_pofile;
    }

    public function setSourcePofile($source_pofile)
    {
        $this->source_pofile = $source_pofile;
    }

    public function getTargetPofile()
    {
        return $this->target_pofile;
    }

    public function setTargetPofile($target_pofile)
    {
        $this->target_pofile = $target_pofile;
    }

}
?>