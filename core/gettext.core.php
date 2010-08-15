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
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005 - onwards)
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
 * Clansuite_Gettext
 *
 * 1. Gettext extraction is normally performed by the "xgettext" tool.
 *    http://www.gnu.org/software/hello/manual/gettext/xgettext-Invocation.html
 *
 * 2. PHP as a platform is still missing essential features of the gettext toolchain.
 *    You wont' find a PECL extension for extraction NOR native PO/MO writing.
 *    Basically everything is missing - except the reading of compiled gettext files (--with-gettext).
 *
 * 3. The missing parts are implemented in PHP:
 *    a) gettext extractor basedon preg_matching.
 *       The extractor matches certain translation functions, like translate('term') or t('term') or _('term')
 *       and their counterparts in templates, often {t('term')} or {_('term')}.
 *    b) POT/PO/MO File Handling = reading and writing.
 *
 * The Clansuite_Gettext is based on and inspired by
 *  - Karel Klima's "GettextExtractor v2" (new BSD)
 *  - Drupals "translation_extraction" (GPL)
 *  - Matthias Bauer's work on PO/MO Filehandling for Wordpress during GSoC 2007 (GPL)
 *  - Heiko Rabe's "Codestyling Localization" Plugin for Wordpress (GPL)
 *
 * @author Karel Klíma
 * @author Jens-André Koch
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Gettext
 */
class Clansuite_Gettext_Extractor extends Clansuite_Gettext_Extractor_Tool
{
    /**
     * Setup mandatory filters
     *
     * @param string|bool $logToFile
     */
    public function __construct($logToFile = false)
    {
        # clean up
        $this->removeAllFilters();

        # set basic filters for php and smarty template files
        $this->setExtractor('php', 'PHP')
             ->setExtractor('tpl', 'Template');

        # register the functions to extract
        $this->getExtractor('PHP')->addFunction('translate')->addFunction('t')->addFunction('_');

        # register the placeholder to extract
        $this->getExtractor('Template')->addPrefix('_')->addPrefix('t');
    }
}

/**
 * Clansuite_Gettext_Extractor_Tool
 *
 * Gettext extraction is normally performed by the "xgettext" tool.
 * http://www.gnu.org/software/hello/manual/gettext/xgettext-Invocation.html
 *
 * This is a php implementation of a gettext extractor on basis of preg_matching.
 * The extractor matches certain translation functions, like translate('term') or t('term') or _('term').
 * and their counterparts in templates, often {t('term')} or {_('term')}.
 *
 * @author Karel Klíma
 * @author Jens-André Koch
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Gettext
 */
class Clansuite_Gettext_Extractor_Tool
{
    /**
     * @var resource
     */
    protected $logHandler;

    /**
     * @var array
     */
    protected $inputFiles = array();

    /**
     * @var array
     */
    protected $extractors = array(
        'php' => array('PHP'),
        'tpl' => array('PHP', 'Template')
    );

    /**
     * @var array
     */
    protected $comments = array(
        'Gettext keys exported by Clansuite_Gettext_Extractor'
    );

    /**
     * @var array
     */
    protected $meta = array(
        'Content-Type' => 'text/plain; charset=UTF-8',
        'Plural-Forms' => 'nplurals=2; plural=(n != 1);'
    );

    /**
     * @var array
     */
    protected $data = array();

    /**
     *  @var array
     */
    protected $extractorStore = array();

    /**
     * Log setup
     * @param string|bool $logToFile Bool or path of custom log file
     */
    public function __construct($logToFile = false)
    {
        # default log file
        if(false === $logToFile)
        {
            $this->logHandler = fopen(dirname(__FILE__) . '/extractor.log', "w");
        }
        else # custom log file
        {
            $this->logHandler = fopen($logToFile, "w");
        }
    }

    /**
     * Close the log hangdler if needed
     */
    public function __destruct()
    {
        if(is_resource($this->logHandler))
        {
            fclose($this->logHandler);
        }
    }

    /**
     * Writes messages into log or dumps them on screen
     *
     * @param string $message
     */
    public function log($message)
    {
        if(is_resource($this->logHandler))
        {
            fwrite($this->logHandler, $message . "\n");
        }
        else
        {
            echo $message . "\n";
        }
    }

    /**
     * Exception factory
     *
     * @param string $message
     *
     * @throws Clansuite_Exception
     */
    protected function throwException($message)
    {
        if(empty($message))
        {
            $message = 'Something unexpected occured. See Clansuite_Gettext_Extractor log for details.';
        }

        $this->log($message);

        throw new Clansuite_Exception($message);
    }

    /**
     * Scans given files or directories and extracts gettext keys from the content
     *
     * @param string|array $resource
     *
     * @return Clansuite_Gettext_Extractor
     */
    public function scan($resource)
    {
        $this->inputFiles = array();

        if(false == is_array($resource))
        {
            $resource = array($resource);
        }

        foreach($resource as $item)
        {
            $this->log('Scanning '.$item);
            $this->scan($item);
        }

        $this->extract($this->inputFiles);

        return $this;
    }

    /**
     * Scans given files or directories (recursively) and stores extracted gettext keys in a buffer
     * @param string $resource File or directory
     */
    protected function scan($resource)
    {
        if(false == is_dir($resource) and false == is_file($resource))
        {
            $this->throwException('Resource ' . $resource . ' is not a directory or file.');
        }

        if(is_file($resource))
        {
            $this->inputFiles[] = realpath($resource);
            return;
        }

        # It's a directory
        $resource = realpath($resource);
        if(false === $resource)
        {
            return;
        }

        $iterator = dir($resource);

        if(false === $iterator)
        {
            return;
        }

        while(false !== ($entry = $iterator->read()))
        {
            if($entry == '.' || $entry == '..')
            {
                continue;
            }

            $path = $resource . '/' . $entry;

            if(false === is_readable($path))
            {
                continue;
            }

            if(is_dir($path))
            {
                $this->scan($path);
                continue;
            }

            if(is_file($path))
            {
                $info = pathinfo($path);

                if(false === isset($this->extractors[$info['extension']]))
                {
                    continue;
                }

                $this->inputFiles[] = realpath($path);
            }
        }

        $iterator->close();
    }

    /**
     * Extracts gettext keys from input files
     *
     * @param array $inputFiles
     *
     * @return array
     */
    protected function extract($inputFiles)
    {
        $inputFiles = array_unique($inputFiles);

        foreach($inputFiles as $inputFile)
        {
            if(false === file_exists($inputFile))
            {
                $this->throwException('Invalid input file specified: ' . $inputFile);
            }

            if(false === is_readable($inputFile))
            {
                $this->throwException('Input file is not readable: ' . $inputFile);
            }

            $this->log('Extracting data from file ' . $inputFile);

            foreach($this->extractors as $extension => $extractor)
            {
                # Check file extension
                $info = pathinfo($inputFile);

                if($info['extension'] !== $extension)
                {
                    continue;
                }

                $this->log('Processing file ' . $inputFile);

                foreach($extractors as $extractorName)
                {
                    $extractor = $this->getExtractor($extractorName);
                    $extractorData = $extractor->extract($inputFile);

                    $this->log(' Extractor ' . $extractorName . ' applied.');

                    $this->data = array_merge_recursive($this->data, $extractorData);
                }
            }
        }

        return $this->data;
    }

    /**
     * Gets an instance of a Clansuite_Gettext_Extractor
     *
     * @param string $extractor
     *
     * @return object implementing the Clansuite_Gettext_Extractor_Interface
     */
    public function getExtractor($extractor)
    {
        $extractor_classname = 'Clansuite_Gettext_Extractor_' . $extractor;

        if(isset($this->extractors[$extractor]))
        {
            return $this->extractors[$extractor];
        }

        if(false === class_exists($extractor_classname, false))
        {
            if(is_file($extractor_file))
            {
                # = /core/gettext/extractors/*NAME*.gettext.php
                $extractor_file = dirname(__FILE__) . '/extractors/' . $extractor . '.gettext.php';
                require_once $extractor_file;
            }
            else
            {
                $this->throwException('Filter file ' . $extractor_file . ' not found.');
            }

            if(false === class_exists($extractor_classname))
            {
                $this->throwException('File loaded, but Class ' . $extractor . ' not inside.');
            }
        }


        $this->extractors[$extractor] = new $extractor_classname;

        $this->log('Filter ' . $extractor . ' loaded.');

        return $this->extractors[$extractor];
    }

    /**
     * Assigns a filter to an extension
     *
     * @param string $extension
     * @param string $extractor
     *
     * @return Clansuite_Gettext_Extractor
     */
    public function setExtractor($extension, $extractor)
    {
        if(isset($this->extractor[$extension]) and in_array($extractor, $this->extractor[$extension]))
        {
            return $this;
        }

        $this->extractor[$extension][] = $extractor;

        return $this;
    }

    /**
     * Removes all filter settings in case we want to define a brand new one
     *
     * @return Clansuite_Gettext_Extractor
     */
    public function removeAllFilters()
    {
        $this->extractor = array();

        return $this;
    }

    /**
     * Adds a comment to the top of the output file
     *
     * @param string $value
     *
     * @return Clansuite_Gettext_Extractor
     */
    public function addComment($value)
    {
        $this->comments[] = $value;

        return $this;
    }

    /**
     * Gets a value of a meta key
     *
     * @param string $key
     */
    public function getMeta($key)
    {
        if(isset($this->meta[$key]))
        {
            return $this->meta[$key];
        }
        else
        {
            return null;
        }
    }

    /**
     * Sets a value of a meta key
     *
     * @param string $key
     * @param string $value
     *
     * @return Clansuite_Gettext_Extractor
     */
    public function setMeta($key, $value)
    {
        $this->meta[$key] = $value;

        return $this;
    }

    /**
     * Saves extracted data into gettext file
     *
     * @param string $outputFile
     * @param array $data
     *
     * @return Clansuite_Gettext_Extractor
     */
    public function save($outputFile, $data = null)
    {
        if($data === null)
        {
            $data = $this->data;
        }

        # Output file permission check
        if(file_exists($outputFile) and false == is_writable($outputFile))
        {
            $this->throwException('ERROR: Output file is not writable!');
        }

        $handle = fopen($outputFile, "w");

        fwrite($handle, $this->formatData($data));

        fclose($handle);

        $this->log('Output file '.$outputFile.' created.');

        return $this;
    }

    /**
     * Formats fetched data to gettext syntax
     *
     * @param array $data
     *
     * @return string
     */
    protected function formatData($data)
    {
        $output = array();

        foreach($this->comments as $comment)
        {
            $output[] = '# ' . $comment;
        }

        $output[] = '# Created: ' . date('c');
        $output[] = 'msgid ""';
        $output[] = 'msgstr ""';

        foreach($this->meta as $key => $value)
        {
            $output[] = '"' . $key . ': ' . $value . '\n"';
        }

        $output[] = '';

        ksort($data);

        foreach($data as $key => $files)
        {
            ksort($files);

            foreach ($files as $file)
            {
                $output[] = '# ' . $file;
                $output[] = 'msgid "' . $this->addSlashes($key) . '"';
                $output[] = 'msgstr "' . $this->addSlashes($key) . '"';
                $output[] = '';

                /* TODO: really export plurals?
                if(preg_match($this->pluralMatchRegexp, $key, $matches))
                {
                    $output[] = 'msgid_plural "' . addslashes($key) . '"';
                    # $output[] = 'msgid_plural ""';
                    $output[] = 'msgstr[0] "' . addslashes($key) . '"';
                    $output[] = 'msgstr[1] "' . addslashes($key) . '"';
                }
                else
                {
                    $output[] = 'msgstr "' . addslashes($key) . '"';
                }
                */
            }
        }

        return join("\n", $output);
    }

    /**
     * Escapes the given string, so it does not break the gettext syntax.
     *
     * @param string $string
     *
     * @return string
     */
    public function addSlashes($string)
    {
        return addcslashes($string, '"');
    }
}

/**
 * Clansuite_Gettext_Extractor_Interface
 */
interface Clansuite_Gettext_Extractor_Interface
{
    public function extract();
}
?>