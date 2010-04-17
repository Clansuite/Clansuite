<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
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
    */

   /** =====================================================================
    *  WARNING: DO NOT MODIFY THIS FILE, UNLESS YOU KNOW WHAT YOU ARE DOING.
    *           READ THE DOCUMENTATION FOR INSTALLATION PROCEDURE.
    *  =====================================================================
    */

   /**
    * Clansuite Webinstaller
    * Installs Clansuite from a downloaded archive
    *
    * Script performs mainly 3 actions:
    *    a. download the clansuite archive [.zip] / [tar.gz]
    *       from a known server directly to the server where the script is running
    *    b. extract all files and folders
    *    c. forwards the user to the installation wizard of clansuite
    *
    * As this Webinstaller is a modified version of the pre-installer v2.2 for Gallery,
    * we like to thank for it and give credit to both developers
    * Bharat Mediratta and Andy Staudacher <ast@gmx.ch>. Thank You!
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @author     Bharat Mediratta and Andy Staudacher <ast@gmx.ch>
    * @copyright  2007 Clansuite Group
    * @license    see COPYING.txt
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @category    Clansuite
    * @package     Installation
    * @subpackage  Webinstaller
    *
    * HTML Written = Version 0.2 - 06 June 2007
    * HTML Document begins near Line #1000
    * @version    SVN: $Id$
    */

if(ini_get("safe_mode") == true and ini_get("open_basedir") == true)
{
   die('<i>ERROR</i> : <b>Clansuite Webinstaller is not able to perform a curl/wget/fopen/fsockopen command, <br> because \'Safe Mode\' and \'Open_BaseDir Restriction\' are enabled! <br> Shutting Down!</b>');
}
error_reporting(E_ALL);
# will have to effect if safemode on
set_time_limit(900);
ini_set("open_basedir",".:..:/usr/bin/");
ini_set("allow_url_fopen",1);
ini_set("memory_limit","64M");
ini_set("upload_max_filesize","64M");
# ini_set for php.ini only
#ini_set("safe_mode_exec_dir","/usr/bin/");
#ini_set("safe_mode", "off");

$webinstaller_version = 'Version : 0.3 - '. date("l, jS F Y",filemtime($_SERVER['SCRIPT_FILENAME']));

/*****************************************************************
 * C O N F I G U R A T I O N
 *****************************************************************/
/* Download Location */
$downloadUrls = array();
/* Hardcoded defaults / fallbacks (we try to find out these URLs during runtime) */

# Release Candidate
$downloadUrls['rc'] = 'http://download.gna.org/clansuite/clansuite';
# Stable Release */
$downloadUrls['stable'] = 'http://download.gna.org/clansuite/clansuite';
# Latest GNA Daily Snapshot without EXTERNALS
$downloadUrls['daily']= 'http://download.gna.org/clansuite/clansuite.tar.gz';
# Development Version
$downloadUrls['dev']= 'http://download.gna.org/clansuite/clansuite';

/* This page on www.clansuite.com lists the latest versions
/* So we scan gna.org/downloads for archives and add the daily-svn archiv.  */
#$versionCheckUrl = 'http://www.clansuite.com/versions/versions_check.php';

/* Local name of the clansuite archive (without extension ) [clansuite].zip */
$archiveBaseName = 'clansuite';
/* A list of folder permissions that are available for chmodding */
$folderPermissionList = array('777', '755', '555');
/* Archive extensions available for download */
$availableExtensions = array('zip', 'tar.gz');
/* Available versions of Clansuite */
$availableVersions = array('dev');

/*****************************************************************
 * M A I N
 *****************************************************************/
compatiblityFunctions();
$webInstaller = new WebInstaller();
$webInstaller->main();

/*****************************************************************
 * C L A S S E S
 *****************************************************************/

/**
 * WebInstaller Main Class
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Webinstaller
 */
class WebInstaller {

    function main()
    {

        /* Register all extract / download methods */
        $this->_extractMethods =  array(new UnzipExtractor(),
                new PhpUnzipExtractor(),
                new TarGzExtractor(),
                new PhpTarGzExtractor());

        $this->_downloadMethods = array(new CurlDownloader(),
                new WgetDownloader(),
                new FopenDownloader(),
                new FsockopenDownloader());

        /* Make sure we can write to the current working directory */
        if (!Platform::isDirectoryWritable())
        {
            render('results', array('failure' => 'Local working directory' . dirname(__FILE__) .
                            ' is not writeable!',
                    'fix' => 'ftp> chmod 777 ' . basename(dirname(__FILE__))));
            exit;
        }

        /**
         * Handle the delete request
         */
        if (isset($_GET['delete_webinstaller']))
        {
            unlink(__FILE__);
            header("Location:../index.php");
        }

        /* Handle the request */
        if (empty($_POST['command']))
        {
            $command = '';
        }
        else
        {
            $command = trim($_POST['command']);
        }

        /** Actions:
        *   -extract
        *   -download
        *   -chmod
        *   -rename
        *   Default: check capabilities of php / server
        */

        switch ($command) {

            case 'extract':

                /* Input validation / sanitation */
                if (empty($_POST['method'])) $method = '';
                else $method = trim($_POST['method']);
                if (!preg_match('/^[a-z]+extractor$/', $method)) $method = '';
                /* Handle the request */
                if (class_exists($method)) {
                    global $archiveBaseName;
                    $extractor = new $method;
                    if ($extractor->isSupported()) {
                    $archiveName = dirname(__FILE__) . '/' .
                        $archiveBaseName .  '.' . $extractor->getSupportedExtension();
                    if (is_file($archiveName))
                    {
                        if (empty($_POST['target_path'])) $target_path = '';
                        else $method = trim($_POST['target_path']);

                        if (isset($_POST['remove_path']) and $_POST['remove_path'] == 'clansuite/')
                        {
                            $remove_path = 'clansuite/';
                        }
                        else
                        {
                            $remove_path = '';
                        }

                        $results = $extractor->extract($archiveName, $target_path, $remove_path);

                        if ($results === true) {

                        /* Make sure the dirs and files were extracted successfully */
                        if (!$this->integrityCheck()) {
                            render('results', array('failure' => 'Extraction was successful, but integrity check failed'));
                        } else {
                            render('results', array('success' => 'Archive successfully extracted',
                                                    'clansuiteFolderName' => $this->findClansuiteFolder()
                                                    ));

                            /**
                             * Set the permissions in the clansuite dir
                             */
                            @chmod(dirname(__FILE__) . '/clansuite', 0777);
                        }
                        } else {
                        render('results', array('failure' => $results));
                        }
                    } else {
                        render('results', array('failure' => "Archive $archiveName does not exist in the current working directory"));
                    }
                    } else {
                    render('results', array('failure' => "Method $method is not supported by this platform!"));
                    }
                } else {
                    render('results', array('failure' => 'Extract method is not defined or does not exist!'));
                }

            break;

            case 'download':

                /* Input validation / sanitation */
                /* The download method */
                if (empty($_POST['method'])) $method = '';
                else $method = trim($_POST['method']);
                if (!preg_match('/^[a-z]+downloader$/', $method)) $method = '';
                /* ... archive extension */
                if (empty($_POST['extension'])) $extension = '';
                else $extension = trim($_POST['extension']);
                if (!preg_match('/^([a-z]{2,4}\.)?[a-z]{2,4}$/', $extension)) {
                    render('results', array('failure' => 'Filetype for download not defined, please retry'));
                    exit;
                }
                global $availableExtensions, $availableVersions;
                if (!in_array($extension, $availableExtensions)) $extension = 'zip';
                /* Clansuite version (stable, rc, nightly snapshot) */
                if (empty($_POST['version'])) $version = '';
                else $version = trim($_POST['version']);
                if (!in_array($version, $availableVersions)) $version = 'stable';
                if (class_exists($method)) {
                    $downloader = new $method;
                    if ($downloader->isSupported()) {
                    global $archiveBaseName;
                    $archiveName = dirname(__FILE__) . '/' . $archiveBaseName . '.' . $extension;
                    /* Assemble the downlod URL */
                    $url = $this->getDownloadUrl($version, $extension, $downloader);
                    $results = $downloader->download($url, $archiveName);
                    if ($results === true) {
                        if (is_file($archiveName)) {
                        @chmod($archiveName, 0777);
                        render('results', array('success' => 'File successfully downloaded'));
                        } else {
                        render('results', array('failure' => "Download failed, local file $archiveName does not exist"));
                        }
                    } else {
                        render('results', array('failure' => $results));
                    }
                    } else {
                    render('results', array('failure' => "Method $method is not supported by this platform!"));
                    }
                } else {
                    render('results', array('failure' => 'Method is not defined or does not exist!'));
                }

            break;

           case 'chmod':

                /* Input validation / sanitation */
                if (empty($_POST['folderName'])) $folderName = '';
                else $folderName = trim($_POST['folderName']);
                /* Remove trailing / leading slashes */
                $folderName = str_replace(array('/', "\\", '..'), '', $folderName);
                if (!preg_match('/^\w+(\.\w+)*$/', $folderName)) {
                    render('results', array('failure' => "Folder $folderName has invalid characters. Can only change the permissions of folders in the current working directory."));
                    exit;
                }
                $folderName = dirname(__FILE__) . DIRECTORY_SEPARATOR .  $folderName;
                if (!is_file($folderName)) {
                    render('results', array('failure' => "Folder $folderName does not exist!"));
                    exit;
                }
                if (empty($_POST['folderPermissions'])) $folderPermissions = '';
                else $folderPermissions= trim($_POST['folderPermissions']);
                /* Handle the request */
                global $folderPermissionList;
                if (in_array($folderPermissions, $folderPermissionList)) {
                    $folderPermissions = (string)('0' . (int)$folderPermissions);
                    $success = @chmod($folderName, octdec($folderPermissions));
                    if (!$success) {
                    render('results', array('failure' => "Attempt to change permissions of folder $folderName to $folderPermissions failed!"));
                    } else {
                    render('results', array('success' => "Successfully changed permissions of $folderName to $folderPermissions"));
                    }
                } else {
                    render('results', array('failure' => "Invalid permissions $folderPermissions"));
                }

            break;

            case 'rename':

                if (empty($_POST['folderName'])) $folderName = '';
                else $folderName = trim($_POST['folderName']);
                /* Remove trailing / leading slashes */
                $folderName = str_replace(array('/', "\\", '.'), '', $folderName);
                if (!preg_match('/^\w+$/', $folderName)) {
                    render('results', array('failure' => "Folder name $folderName has invalid characters. Can only rename within the current working directory."));
                    exit;
                }
                $folderName = dirname(__FILE__) . '/' .  $folderName;
                $oldFolderName = $this->findclansuiteFolder();
                if (empty($oldFolderName) or !is_file(dirname(__FILE__) . DIRECTORY_SEPARATOR . $oldFolderName)) {
                    render('results', array('failure' => "No Clansuite folder found in  the current working directory."));
                    exit;
                }
                $oldFolderName = dirname(__FILE__) . DIRECTORY_SEPARATOR . $oldFolderName;
                $success = @rename($oldFolderName, $folderName);
                if (!$success) {
                    render('results', array('failure' => "Attempt to rename $oldFolderName to $folderName failed!"));
                } else {
                    render('results', array('success' => "Successfully renamed $oldFolderName to $folderName"));
                }

            break;

            default:
            case 'before-download':

            /* Discover the capabilities of this PHP installation / platform */
            $capabilities = $this->discoverCapabilities();

            $capabilities['clansuiteFolderName'] = $this->findClansuiteFolder();

            if (!empty($capabilities['clansuiteFolderName']))
            {
                $statusMessage  = "Ready for installation! Clansuite found in the folder '" .
                $capabilities['clansuiteFolderName'] . "'.";
            }
            else if (!empty($capabilities['anyArchiveExists']))
            {
                $statusMessage = 'Archive ready for extraction.';
            }
            else
            {
                $statusMessage = 'There was no archive found in the current working directory! Please start to download.';
            }

            $capabilities['statusMessage'] = $statusMessage;

            /* Is there a ReleaseCandidate available?*/
            if (!empty($capabilities['downloadMethods'])) {
                foreach ($capabilities['downloadMethods'] as $dMethod) {
                    if ($dMethod['isSupported']) {
                        $capabilities['showRcVersion'] =
                            $this->shouldShowRcVersion(new $dMethod['command']);
                        break;
                    }
                }
            }
            render('options', $capabilities);
        }
    }

    function discoverCapabilities()
    {
        global $archiveBaseName;
        $capabilities = array();

        $extractMethods = array();
        $extensions = array();
        $anyExtensionSupported = 0;
        $anyArchiveExists = 0;
        foreach    ($this->_extractMethods as $method)
        {
            $archiveName = $archiveBaseName . '.' . $method->getSupportedExtension();
            $archiveExists = is_file(dirname(__FILE__) . '/' . $archiveName);
            $isSupported = $method->isSupported();
            $extractMethods[] = array('isSupported' => $isSupported,
                    'name' => $method->getName(),
                    'command' => strtolower(get_class($method)),
                    'archiveExists' => $archiveExists,
                    'archiveName' => $archiveName);
            if (empty($extensions[$method->getSupportedExtension()]))
            {
                $extensions[$method->getSupportedExtension()] = (int) $isSupported;
            }
            if ($isSupported)
            {
                $anyExtensionSupported = 1;
            }
            if ($archiveExists)
            {
                $anyArchiveExists = 1;
            }
        }
        $capabilities['extractMethods'] = $extractMethods;
        $capabilities['extensions'] = $extensions;
        $capabilities['anyExtensionSupported'] = $anyExtensionSupported;
        $capabilities['anyArchiveExists'] = $anyArchiveExists;

        $downloadMethods = array();
        foreach    ($this->_downloadMethods as $method)
        {
            $downloadMethods[] = array('isSupported' => $method->isSupported(),
                    'name' => $method->getName(),
                    'command' => strtolower(get_class($method)));
        }
        $capabilities['downloadMethods'] = $downloadMethods;

        return $capabilities;
    }

    function findClansuiteFolder()
    {
        /* Search in the current folder for a clansuite folder */
        $basePath = dirname(__FILE__) . '/';

        # installtion in "clansuite/" directory
        if (is_file($basePath . 'clansuite') and
            is_file($basePath . 'clansuite/installation/index.php')) {
            return 'clansuite';
        }

        # installation without "clansuite/" directory
        if (is_file($basePath . 'index.php') and
            is_file($basePath . '/installation/index.php')) {
            return 'clansuite';
        }

        if (!Platform::isPhpFunctionSupported('opendir') or
            !Platform::isPhpFunctionSupported('readdir')) {
            return false;
        }

        $handle = opendir($basePath);
        if (!$handle) {
            return false;
        }
        while (($fileName = readdir($handle)) !== false) {
            if ($fileName == '.' or $fileName == '..') {
            continue;
            }
            if (is_file($basePath . $fileName . '/installation/index.php')) {
            return $fileName;
            }
        }
        closedir($handle);

        return false;
    }

    function integrityCheck()
    {
        /* TODO, check for the existence of modules, lib, themes, main.php */
        return true;
    }

    function getDownloadUrl($version, $extension, $downloader)
    {
        global $downloadUrls;

        /* Default to the last known good version */
        $url = $downloadUrls[$version];

        /* Try to get the latest version string */
        $currentDownloadUrls = $this->getLatestVersions($downloader);
        if (!empty($currentDownloadUrls[$version])) {
            $url = $currentDownloadUrls[$version];
        }

        $url .= '.' . $extension;

        return $url;
    }

    function getLatestVersions($downloader)
    {
        global $versionCheckUrl, $availableVersions;

        $tempFile = dirname(__FILE__) . '/availableVersions.txt';
        $currentVersions = array();
        /*
         * Fetch the version information from a remote server and if we already have it,
         * update it if it's older than an hour
         */
        if (!is_file($tempFile) or !(($stat = @stat($tempFile)) and
            isset($stat['mtime']) and $stat['mtime'] > time() - 3600)) {
            $downloader->download($versionCheckUrl, $tempFile);
        }
        /* Parse the fetched version information file */
        if (is_file($tempFile)) {
            $contents = @file($tempFile);
            if (is_array($contents)) {
            foreach ($contents as $line) {
                /* Each line is of the format key=value */
                $versionStrings = implode('|', $availableVersions);
                if (preg_match('/^(' . $versionStrings .
                    ')=((?:http|ftp):\/(?:\/(?:[A-Za-z0-9-_]+\.?)+)+)\s*/',
                       $line, $match)) {
                $currentVersions[$match[1]] = $match[2];
                }
            }
            }
        }

        return $currentVersions;
    }

    function shouldShowRcVersion($downloader)
    {
        /*
         * Only show the rc version (along with the stable and nightly) if we're in a
         * release candidate stage
         */
        $currentDownloadUrls = $this->getLatestVersions($downloader);
        return isset($currentDownloadUrls['rc']);
    }
}

/**
 * Plattform
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Webinstaller
 */
class Platform
{
    /* Check if a specific php function is available */
    function isPhpFunctionSupported($functionName)
    {
        if (in_array($functionName, split(',\s*', ini_get('disable_functions'))) or !function_exists($functionName))
        {
            return false;
        } else
        {
            return true;
        }
    }

    /* Check if a specific command line tool is available */
    function isBinaryAvailable($binaryName)
    {
        $binaryPath = Platform::getBinaryPath($binaryName);
        return !empty($binaryPath);
    }

    /* Return the path to a binary or false if it's not available */
    function getBinaryPath($binaryName)
    {
        if (!Platform::isPhpFunctionSupported('exec'))
        {
            return false;
        }

        /* First try 'which' */
        $ret = array();
        exec('which ' . $binaryName, $ret);
        if (strpos(join(' ',$ret), $binaryName) !== false and is_executable(join('',$ret))) {
            return $binaryName; // it's in the path
        }

        /* Try a bunch of likely seeming paths to see if any of them work. */
        $paths = array();
        if (!strncasecmp(PHP_OS, 'win', 3))
        {
            $separator = ';';
            $slash = "\\";
            $extension = '.exe';
            $paths[] = "C:\\Program Files\\$binaryName\\";
            $paths[] = "C:\\apps\$binaryName\\";
            $paths[] = "C:\\$binaryName\\";
        }
        else
        {
            $separator = ':';
            $slash = "/";
            $extension = '';
            $paths[] = '/usr/bin/';
            $paths[] = '/usr/local/bin/';
            $paths[] = '/bin/';
            $paths[] = '/sw/bin/';
        }
        $paths[] = './';

        foreach (explode($separator, getenv('PATH')) as $path) {
            $path = trim($path);
            if (empty($path)) {
            continue;
            }
            if ($path{strlen($path)-1} != $slash) {
            $path .= $slash;
            }
            $paths[] = $path;
        }

        /* Now try each path in turn to see which ones work */
        /* error silenced, because of open_basedir restriction */
        foreach ($paths as $path) {
            $execPath = $path . $binaryName . $extension;
            if (@is_file($execPath) and is_executable($execPath)) {
            /* We have a winner */
            return $execPath;
            }
        }

        return false;
    }

    /* Check if we can write to this directory (download, extract) */
    function isDirectoryWritable()
    {
        return is_writable(dirname(__FILE__));
    }

    function extendTimeLimit()
    {
        if (function_exists('apache_reset_timeout'))
        {
            @apache_reset_timeout();
        }
        @set_time_limit(600);
    }
}

/**
 * DownloadMethod
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Webinstaller
 */

class DownloadMethod
{
    function download($url, $outputFile)
    {
        return false;
    }

    function isSupported()
    {
        return false;
    }

    function getName()
    {
        return '';
    }
}

/**
 * WgetDownloader
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Webinstaller
 */

class WgetDownloader extends DownloadMethod
{
    function download($url, $outputFile)
    {
        $status = 0;
        $output = array();
        $wget = Platform::getBinaryPath('wget');
        exec("$wget -O$outputFile $url ", $output, $status);
        if ($status)
        {
            $msg = 'exec returned an error status ';
            $msg .= is_array($output) ? implode('<br>', $output) : '';
            return $msg;
        }
        return true;
    }

    function isSupported()
    {
        return Platform::isBinaryAvailable('wget');
    }

    function getName()
    {
        return 'Download with Wget';
    }
}

/**
 * FopenDownloader
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Webinstaller
 */

class FopenDownloader extends DownloadMethod
{
    function download($url, $outputFile) {
        if (!Platform::isDirectoryWritable()) {
            return 'Unable to write to current working directory';
        }

        if (@ini_get('memory_limit') < 16)
            @ini_set('memory_limit', '16M');

        $start =time();

        Platform::extendTimeLimit();

        $fh = fopen($url, 'rb');
        if (empty($fh)) {
            return 'Unable to open url';
        }
        $ofh = fopen($outputFile, 'wb');
        if (!$ofh) {
            fclose($fh);
            return 'Unable to open output file in writing mode';
        }

        $failed = $results = false;
        while (!feof($fh) and !$failed) {
            $buf = fread($fh, 4096);
            if (!$buf) {
            $results = 'Error during download';
            $failed = true;
            break;
            }
            if (fwrite($ofh, $buf) != strlen($buf)) {
            $failed = true;
            $results = 'Error during writing';
            break;
            }
            if (time() - $start > 55) {
            Platform::extendTimeLimit();
            $start = time();
            }
        }
        fclose($ofh);
        fclose($fh);
        if ($failed) {
            return $results;
        }

        return true;
    }

    function isSupported()
    {
        $actual = ini_get('allow_url_fopen');
        if (in_array($actual, array(1, 'On', 'on')) and Platform::isPhpFunctionSupported('fopen'))
        {
            return true;
        }

        return false;
    }

    function getName()
    {
        return 'Download with PHP fopen()';
    }
}

/**
 * FsockopenDownloader
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Webinstaller
 */

class FsockopenDownloader extends DownloadMethod
{
    function download($url, $outputFile, $maxRedirects=10) {
        /* Code from WebHelper_simple.class */

        if ($maxRedirects < 0) {
            return "Error too many redirects. Last URL: $url";
        }

        $components = parse_url($url);
        $port = empty($components['port']) ? 80 : $components['port'];

        $errno = $errstr = null;
        $fd = @fsockopen($components['host'], $port, $errno, $errstr, 2);
        if (empty($fd)) {
            return "Error $errno: '$errstr' retrieving $url";
        }

        $get = $components['path'];
        if (!empty($components['query'])) {
            $get .= '?' . $components['query'];
        }

        $start = time();

        /* Read the web file into a buffer */
        $ok = fwrite($fd, sprintf("GET %s HTTP/1.0\r\n" .
                           "Host: %s\r\n" .
                           "\r\n",
                           $get,
                           $components['host']));
        if (!$ok) {
            return 'Download request failed (fwrite)';
        }
        $ok = fflush($fd);
        if (!$ok) {
            return 'Download request failed (fflush)';
        }

        /*
         * Read the response code. fgets stops after newlines.
         * The first line contains only the status code (200, 404, etc.).
         */
        $headers = array();
        $response = trim(fgets($fd, 4096));

        /* Jump over the headers but follow redirects */
        while (!feof($fd)) {
            $line = trim(fgets($fd, 4096));
            if (empty($line)) {
            break;
            }

            /* Normalize the line endings */
            $line = str_replace("\r", '', $line);
            list ($key, $value) = explode(':', $line, 2);
            if (trim($key) == 'Location') {
            fclose($fd);
            return $this->download(trim($value), $outputFile, --$maxRedirects);
            }
        }

        $success = false;
        $ofd = fopen($outputFile, 'wb');
        if ($ofd) {
            /* Read the body */
            $failed = false;
            while (!feof($fd) and !$failed) {
            $buf = fread($fd, 4096);
            if (fwrite($ofd, $buf) != strlen($buf)) {
                $failed = true;
                break;
            }
            if (time() - $start > 55) {
                Platform::extendTimeLimit();
                $start = time();
            }
            }
            fclose($ofd);
            if (!$failed) {
            $success = true;
            }
        } else {
            return "Could not open $outputFile in write mode";
        }
        fclose($fd);

        /* if the HTTP response code did not begin with a 2 this request was not successful */
        if (!preg_match("/^HTTP\/\d+\.\d+\s2\d{2}/", $response)) {
            return "Download failed with HTTP status: $response";
        }

        return true;
    }

    function isSupported() {
        return Platform::isPhpFunctionSupported('fsockopen');
    }

    function getName() {
        return 'Download with PHP fsockopen()';
    }
}

/**
 * CurlDownloader
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Webinstaller
 */

class CurlDownloader extends DownloadMethod
{
    function download($url, $outputFile) {
        $ch = curl_init();
        $ofh = fopen($outputFile, 'wb');
        if (!$ofh) {
            fclose($ch);
            return 'Unable to open output file in writing mode';
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FILE, $ofh);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20 * 60);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_exec($ch);

        $errorString = curl_error($ch);
        $errorNumber = curl_errno($ch);
        curl_close($ch);

        if ($errorNumber != 0) {
            if (!empty($errorString)) {
            return $errorString;
            } else {
            return 'CURL download failed';
            }
        }

        return true;
    }

    function isSupported() {
        foreach (array('curl_init', 'curl_setopt', 'curl_exec', 'curl_close', 'curl_error')
                as $functionName) {
            if (!Platform::isPhpFunctionSupported($functionName)) {
            return false;
            }
        }
        return true;
    }

    function getName() {
        return 'Download with PHP cURL()';
    }
}

/**
 * ExtractMethod
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Webinstaller
 */

class ExtractMethod
{
    # Extract the archive, add the is_file() in the calling function
    function extract()
    {
        return false;
    }

    # What archive types can we extract
    function getSupportedExtension()
    {
        return null;
    }

    # Check if we can use this method (e.g. if exec is available)
    function isSupported()
    {
        return false;
    }

    function getName()
    {
        return '';
    }
}

/**
 * UnzipExtractor
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Webinstaller
 */

class UnzipExtractor extends ExtractMethod
{
    function extract($fileName, $target_path, $remove_path) {
        $output = array();
        $status = 0;
        $unzip = Platform::getBinaryPath('unzip');
        exec($unzip . ' ' . $fileName, $output, $status);
        if ($status) {
            $msg = 'exec returned an error status ';
            $msg .= is_array($output) ? implode('<br>', $output) : '';
            return $msg;
        }
        return true;
    }

    function getSupportedExtension() {
        return 'zip';
    }

    function isSupported() {
        return Platform::isBinaryAvailable('unzip');
    }

    function getName() {
        return 'Extract .zip with unzip';
    }
}

/**
 * TargzExtractor
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Webinstaller
 */

class TargzExtractor extends ExtractMethod
{
    function extract($fileName, $target_path, $remove_path) {
        $output = array();
        $status = 0;
        $tar = Platform::getBinaryPath('tar');
        exec($tar . ' -xzf' . $fileName, $output, $status);
        if ($status) {
            $msg = 'exec returned an error status ';
            $msg .= is_array($output) ? implode('<br>', $output) : '';
            return $msg;
        }
        return true;
    }

    function getSupportedExtension() {
        return 'tar.gz';
    }

    function isSupported() {
        return Platform::isBinaryAvailable('tar');
    }

    function getName() {
        return 'Extract .tar.gz with tar';
    }
}

/**
 * PhpTargzExtractor
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Webinstaller
 */

class PhpTargzExtractor extends ExtractMethod
{
    function extract($fileName, $target_path, $remove_path) {
        return PclTarExtract($fileName, $target_path, $remove_path);
    }

    function getSupportedExtension() {
        return 'tar.gz';
    }

    function isSupported() {
        foreach (array('gzopen', 'gzclose', 'gzseek', 'gzread',
                   'touch', 'gzeof') as $functionName) {
            if (!Platform::isPhpFunctionSupported($functionName)) {
                return false;
            }
        }
        return true;
    }

    function getName() {
        return 'Extract .tar.gz with PHP functions';
    }
}

/**
 * PhpUnzipExtractor
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Webinstaller
 */

class PhpUnzipExtractor extends ExtractMethod
{
    function extract($fileName, $target_path, $remove_path)
    {
        if(!empty($target_path))
        {
            $baseFolder = $target_path;
        }
        else
        {
            $baseFolder = dirname($fileName);
        }

        if (!($zip = zip_open($fileName)))
        {
            return "Could not open the zip archive $fileName";
        }
        $start = time();
        while ($zip_entry = zip_read($zip))
        {
            if (zip_entry_filesize($zip_entry))
            {
                # get from zip entry name: path and name
                $zip_path = dirname(zip_entry_name($zip_entry));
                $zip_name = zip_entry_name($zip_entry);

                # cut off the subdirectory "/clansuite"
                if (isset($remove_path) and ($remove_path == 'clansuite/'))
                {
                    $complete_path = $baseFolder . DIRECTORY_SEPARATOR . substr($zip_path, 10);
                    $complete_name = $baseFolder . DIRECTORY_SEPARATOR . substr($zip_name, 10);
                }
                else
                {
                    $complete_path = $baseFolder . DIRECTORY_SEPARATOR . $zip_path;
                    $complete_name = $baseFolder . DIRECTORY_SEPARATOR . $zip_name;
                }

                if(!file_exists($complete_path)) {
                    $tmp = '';
                    foreach(explode('/',$complete_path) AS $k)
                    {
                        $tmp .= $k.'/';
                        if(!file_exists($tmp))
                        {
                            @mkdir($tmp, 0777);
                        }
                    }
                }
                if (zip_entry_open($zip, $zip_entry, "r"))
                {
                    if ($fd = fopen($complete_name, 'w'))
                    {
                        fwrite($fd, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
                        fclose($fd);
                    }
                    else echo "fopen($dir_atual.$complete_name) error<br>";
                    zip_entry_close($zip_entry);
                }
                else
                {
                    echo "zip_entry_open($zip,$zip_entry) error<br>";
                    return false;
                }
            }

            if (time() - $start > 55)
            {
                Platform::extendTimeLimit();
                $start = time();
            }
        }
        zip_close($zip);

        return true;
    }

    function getSupportedExtension() {
        return 'zip';
    }

    function isSupported() {
        foreach (array('mkdir', 'zip_open', 'zip_entry_name', 'zip_read', 'zip_entry_read',
                'zip_entry_filesize', 'zip_entry_close', 'zip_close', 'zip_entry_close')
                as $functionName) {
            if (!Platform::isPhpFunctionSupported($functionName)) {
            return false;
            }
        }
        return true;
    }

    function getName() {
        return 'Extract .zip with PHP functions';
    }
}

function render($renderType, $args=array()) {
    global $archiveBaseName, $folderPermissionList, $webinstaller_version;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
     <title>Clansuite :: Webinstaller</title>
    <link rel="shortcut icon" href="http://www.clansuite.com/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="http://www.clansuite.com/website/css/installation.css" />
    <link rel="stylesheet" type="text/css" href="http://www.clansuite.com/website/css/standard.css" />
    <link rel="stylesheet" type="text/css" href="http://www.clansuite.com/website/css/kubrick.css" />
    <style type="text/css">
    div.error, div.warning {
        margin: 20px 10px 0;
        padding: 5px;
        background: #ffc;
        border: 1px solid #f00;
        text-align: center;
    }
    div.error {
        background: #f90;
    }
    span.warning {
        color: #f90;
    }
    span.success {
        color: green;
        font-weight: bold;
    }
    </style>
    <script type="text/javascript">
    function BlockToggle(objId, togId, text) {
        var o = document.getElementById(objId), t = document.getElementById(togId);
        if (o.style.display == 'none') {
            o.style.display = 'block';
            t.innerHTML = text + "<div style=\"margin-right: 5px; margin-top: -25px; float:right;\"><img src='http://www.clansuite.com/website/images/up.gif' alt='UP' align='top' /></div>";
        } else {
            o.style.display = 'none';
            t.innerHTML = text + "<div style=\"margin-right: 5px; margin-top: -25px; float:right;\"><img src='http://www.clansuite.com/website/images/dn.gif' alt='DOWN' align='top' /></div>";
        }
    }
    </script>
</head>
<body>
<center>
<div id="page"> <!-- Page START -->
    <div id="header">                                                                          <!--  HeaderRotDunkel.jpg -->
        <div id="headerimg" style="background-image: url('http://www.clansuite.com/website/images/kubrickheader-installation.png');">
            <span>
                <img style="margin: 46px 38px 0pt; position:relative;" src="http://www.clansuite.com/website/images/clansuite-joker.gif"  alt="Clansuite Joker Logo" />
            </span>
            <div class="description" style="font-size: 20px; margin-left: 190px; margin-top: -95px;">Webinstallation</div>
          </div>
    </div>
    <hr />
     <div id="sidebar">
        <div id="stepbar">
            <?php # define strings for on and off toggle
                  $on = 'on">&raquo; ';
                  $off = 'off">';

                  /* Step based on command for handling the request */
                  if (empty($_POST['command']))
                  {
                      $step_cmd = 'intro';
                  }
                  else
                  {
                      $step_cmd = trim($_POST['command']);
                  }

                  if (!empty($args['anyArchiveExists']))
                  {
                     # if Archive was found, jump to Extract Step
                     $step_cmd = 'download';
                  }

                  if (!empty($args['clansuiteFolderName']) AND !empty($args['anyArchiveExists']))
                  {
                     # if the Clansuite folder was found, the archive was extracted
                     # jump to installation area
                     $step_cmd = 'installation';
                  }
            ?>
            <p>Webinstallation</p>
            <?php #echo 'Debug Step: '.$step_cmd; ?>
            <div class="step-<?php if($step_cmd == 'intro' OR $step_cmd == ''){ print $on; } else { print $off; } ?>Welcome</div>
            <div class="step-<?php if($step_cmd == 'before-download'){ print $on; } else { print $off; } ?>Download</div>
            <div class="step-<?php if($step_cmd == 'download'){ print $on; } else { print $off; } ?>Extract</div>
            <div class="step-<?php if($step_cmd == 'extract' OR $step_cmd == 'installation'){ print $on; } else { print $off; } ?>Installation</div>
        </div>
    </div>
    <?php #echo 'Arguments: ' . var_dump($args); /** DEBUG */?>
    <div id="content" class="narrowcolumn">
        <div id="content_middle">

            <!-- WARNING MESSAGE -->
            <fieldset class="error_red">
                <legend>Security Warning</legend>
                <p><b>Delete the file (<?php print basename(__FILE__) ?>) when you are done!</b></p>
            </fieldset>

            <?php /**-------------------------------------------------*/ ?>

            <?php if (!empty($args['statusMessage'])): ?>
            <!-- Status Message -->
            <br />
            <fieldset class="error_beige">
                <legend>Status</legend>
                <div class="box"><?php print $args['statusMessage']; ?></div>
            </fieldset>
            <?php endif; ?>

            <?php /**-------------------------------------------------*/ ?>

            <?php if ($renderType == 'results'): ?>
            <!-- Results -->
            <div style="margin: 0 15px">
                <h2 class="headerstyle">Result</h2>
                <?php if (!empty($args['failure'])): ?>
                <div class="error">
                    <?php print $args['failure']; ?>
                    <?php if (!empty($args['fix'])): ?>
                    <div class="suggested_fix">
                        <h2> Suggested fix: </h2>
                        <?php print $args['fix']; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($args['success'])): ?>
                <span class="success">
                    <?php print $args['success']; ?>
                </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php /**-------------------------------------------------*/ ?>

            <?php /** #echo $renderType;
                  if ($renderType == 'options'): ?>
            <!-- Show available and unavailable options -->
            <?php if (empty($args['anyExtensionSupported'])): ?>
            <div class="error">
                <h2>This platform has not the ability to extract any of our archive types!</h2>
                <span>
                <?php $first = true; foreach ($args['extensions'] as $ext => $supported): ?>
                <?php if (!$supported): ?><span class="disabled"><?php endif; ?>
                <?php if (!$first) print ', '; else $first = false; ?>
                <?php print $ext; ?>
                <?php if (!$supported): ?></span><?php endif; ?>
                <?php endforeach; ?>
                </span>
            </div>
            <?php endif; ?>
            <?php endif; */?>

            <?php /**-------------------------------------------------*/ ?>

            <?php if($step_cmd == 'intro' OR $step_cmd == ''): ?>
            <!-- WELCOME AND INSTRUCTIONS -->
            <div id="page-instructions" style="margin: 0 15px">
                <h2 id="toggler" class="headerstyle" style="cursor: pointer" onclick="BlockToggle('toggle-instructions', 'toggler', 'Instructions')">Instructions <div style="margin-right: 5px; margin-top: -25px; float:right;"><img src="http://www.clansuite.com/website/images/dn.gif" alt="DOWN" align="top" /></div></h2>
                <div id="toggle-instructions" style="display: none">
                    <h3>This webinstaller gets the Clansuite web application on your server.</h3>
                    <p>
                         It's an alternative to the common, but time consuming way of uploading all
                         files via FTP or uploading and extract the archive via ssh terminal access to install a certain application. We hope
                         this will ease the process of installation, take work out of your hands and finally safe you some time!
                    </p>
                    <h3>Installation Steps in Detail:</h3>
                    <ol>
                        <li>
                            <h4>Download of the latest Clansuite Archive</h4>
                            The latest archive of clansuite is fetched from the official
                            download server directly to your webserver.<br />
                            After this step you will find a [.tar.gz] or [.zip] package on your server.
                        </li>
                        <li>
                            <h4>Extraction of Clansuite Archive</h4>
                            All files and folders are extracted from this archive.<br />
                            After this step you will find all files and folders of clansuite on your server.
                        </li>
                        <li>
                            <h4>Installation</h4>
                            Follow the link to the Clansuite installation wizard.<br />
                            It will guide you through the final installation steps to get
                            Clansuite running on this server.
                        </li>
                    </ol>
                    <h3>Permission Drawback</h3>
                    <p>
                        One possible Problem which arises by running this webinstaller is:
                        because it runs as a process of the webserver, all files it creates
                        are owned by the webserver process. So if you want to modify those
                        files yourself, you need to get the webserver to change the permissions
                        on them so that you have access.<br /><br />
                        Use the following functions to achive this:
                    </p>
                    <ul>
                        <li>
                            <h4>Change permissions</h4>
                            Clansuite files have been extracted by the webserver
                            and not by yourself,  so files and folders are not owned by you. That means for example, that you
                            are not allowed to access files and folders or rename the folders manually,
                            unless you change the permissions.
                        </li>
                        <li>
                            <h4>Rename the Clansuite folder</h4>
                            The default folder is  &quot;clansuite/&quot;. If you want it to be rather &quot;cs/&quot; or
                            &quot;somethingelse/&quot; use this function to rename it.
                        </li>
                        <li>
                            <h4>Deleting Clansuite</h4>
                            If you want to delete a Clansuite installation that was extracted by this script, use the Uninstaller Tool which can be found at:
                            <a href="http://www.clansuite.com/">Clansuite Uninstaller</a>
                        </li>
                    </ul>
                </div> <!-- end toggle-instructions -->
             </div> <!-- end instructions -->
             <?php printNavigationButtons('intro','before-download'); ?>
        <?php endif; # END intro + welcome ?>

        <?php /**-------------------------------------------------*/ ?>

        <?php if($step_cmd == 'before-download'):  ?>
            <!-- DOWNLOAD SECTION -->
            <div id="page-1-download" style="margin: 0 15px">
                <h2 class="headerstyle">Download of Clansuite Archive</h2>
                <?php if (!empty($args['downloadMethods']) and !empty($args['anyExtensionSupported'])): ?>
                <form id="downloadForm" action="" method="post">
                    <span class="subtitle">Select the Clansuite version:</span>
                    <table class="choice">
                        <tr>
                            <td>
                                <select name="version" style="width: auto;">
                                    <?php /*
                                    <option value="stable" selected="selected">Latest stable version (recommended)</option>
                                    <?php if (!empty($args['showRcVersion'])): ?>
                                    <option value="rc">Latest release candidate for the next stable version</option>
                                    <?php endif; ?>
                                    <option value="nightly">Latest daily SVN snapshot (bleeding edge and dev)</option>
                                    */ ?>
                                    <option value="dev">Development Version 0.2-alpha1</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <span class="subtitle">Select a download method:</span>
                    <table class="choice">
                    <?php $first = true;
                    foreach ($args['downloadMethods'] as $method):
                        $disabled = empty($method['isSupported']) ? 'disabled="disabled"' : '';
                        $notSupported = empty($method['isSupported']) ? 'not supported by this platform' : '&nbsp;';
                        $checked = '';
                        if ($first and !empty($method['isSupported']))
                        {
                            $checked = ' checked="checked"';
                            $first = false;
                        }
                        printf('<tr><td><input type="radio" name="method" %s value="%s"%s /></td><td>%s</td><td>%s</td></tr>',
                                $disabled, $method['command'], $checked, $method['name'], $notSupported);
                    endforeach; ?>
                    </table>
                    <span class="subtitle">Select an archive type:</span>
                    <table class="choice">
                    <?php
                    $first = true;
                    foreach ($args['extensions'] as $ext => $supported):
                        $disabled = empty($supported) ? 'disabled="disabled"' : '';
                        $message = empty($supported) ? 'not supported by this platform' : '&nbsp;';
                        $checked = '';
                        if ($first and $supported)
                        {
                            $checked = ' checked="checked"';
                            $first = false;
                        }
                        printf('<tr><td><input type="radio" name="extension" value="%s" %s%s /></td><td>%s</td><td>%s</td></tr>',
                                $ext, $disabled, $checked, $archiveBaseName . '.' . $ext, $message);
                    endforeach; ?>
                    </table>
                    <input type="hidden" name="command" value="download" />
                    <input type="submit" value="Download" onclick="this.disabled=true;this.form.submit();" />
                <!-- </form> -->
                <?php  elseif (!empty($args['anyExtensionSupported'])): ?>
                <div class="warning">
                    This platform does not support any of our download / transfer methods. You can upload
                    the clansuite archiv as [.tar.gz] or [.zip] via FTP and extract it then with this tool.
                </div>
                <?php elseif (!empty($args['downloadMethods'])): ?>
                <div class="warning">
                    This platform cannot extract archives, therefore downloading is also disabled.
                </div>
                <?php else: ?>
                <div class="warning">
                    This platform does not support any of our download methods.
                </div>
                <?php endif; ?>
            </div>
            <?php printNavigationButtons('before-download','download'); ?>
            <?php endif; ?>

            <?php /**-------------------------------------------------*/ ?>

            <?php  if($step_cmd == 'download'): ?>
            <!-- EXTRACTION METHODS -->
            <div id="page-2-extraction" style="margin: 0 15px">
                <h2 class="headerstyle">Extraction</h2>
                <?php if (!empty($args['anyExtensionSupported'])): ?>
                <form id="extractForm" action="" method="post">
                    <table class="choice">
                    Wählen Sie eine der folgenden Methoden zum entpacken aus:
                    <?php
                    $first = true;
                    foreach ($args['extractMethods'] as $method):
                        $disabled = 'disabled="disabled"';
                        if (empty($method['isSupported']))
                        {
                            $message = 'not supported by this platform';
                        }
                        elseif (!$method['archiveExists'])
                        {
                            $message = '<span class="warning">first download the ' . $method['archiveName'] . ' archive</span>';
                        }
                        else
                        {
                            $message = '<span class="success">ready for extraction!</span>';
                            $disabled = '';
                        }
                        $checked = '';
                        if ($first and empty($disabled) and !empty($method['isSupported']))
                        {
                            $checked = ' checked="checked"';
                            $first = false;
                        }
                        printf('<tr><td><input type="radio" name="method" %s value="%s" %s /></td><td>%s</td><td>%s</td></tr>',
                                $disabled, $method['command'], $checked, $method['name'], $message);
                     endforeach; ?>
                     </table>
                     <br />
                     <table class="choice">
                        <tr><td>
                     <input type="checkbox" checked="checked" id="CheckboxRemoveClansuiteDir" name="remove_path" value="on" onclick="CheckboxToggle();">
                     <input type="hidden" id="hidden_remove_path" name="remove_path" value="" />

                     Möchten Sie Clansuite in ein Verzeichnis names "clansuite" installieren?
                     <br /><br />
                     Aktueller Installationspfad: <br/> <font size="2"> <b>

                     <?php
                        # pathname without "clansuite/"
                        $pathname= dirname(__FILE__).DIRECTORY_SEPARATOR;
                        $pathname = str_replace("\\", "/", $pathname);

                        # pathname with "clansuite/"
                        $with_pathname = dirname(__FILE__).DIRECTORY_SEPARATOR.'clansuite'.DIRECTORY_SEPARATOR;
                        $with_pathname = str_replace("\\", "/",   $with_pathname);
                     ?>

                     <div id="installPath"><?php echo $with_pathname ?></div>

                     </b>

                    <script type = "text/javascript">
                        function CheckboxToggle()
                        {
                            if ( document.getElementById('CheckboxRemoveClansuiteDir').checked )
                            {
                                document.getElementById("installPath").innerHTML="<?php echo $with_pathname ?>";
                                document.getElementById('CheckboxRemoveClansuiteDir').value = 'on';
                                document.getElementById('hidden_remove_path').value = 'on';
                            }
                            else
                            {
                                document.getElementById("installPath").innerHTML="<?php echo $pathname ?>";
                                document.getElementById('CheckboxRemoveClansuiteDir').value = 'off';
                                document.getElementById('hidden_remove_path').value = 'clansuite/';
                            }
                       }
                    </script>
                    <br /><br />
                    </td></tr>
                    </table>
                    <input type="hidden" name="command" value="extract" />
                    <input type="submit" value="Extract" onclick="this.disabled=true;this.form.submit();" />
                <!-- </form> -->
                <?php /** else:  ?>
                <div class="warning">
                    Oops! - This platform cannot extract archives.
                    <h4>Steps:</h4>
                    <ol>
                       <li>Do it the old way - download archive, extract files and upload them manually!</li>
                       <li>Or ask your webhoster to extract the archive for you.</li>
                       <li>Ask on Clansuite Board for installation help.</li>
                    </ol>
                </div>
                <?php */ endif; ?>
            </div> <!-- end page-2-extraction -->
            <?php printNavigationButtons('before-download','extract'); ?>
            <?php endif; #  end extract ?>

            <?php /**-------------------------------------------------*/ ?>

            <?php if($step_cmd == 'extract' OR $step_cmd == 'installation'): ?>
            <!-- LINK TO INSTALLER -->
            <div id="page-3-installation" style="margin: 0 15px">
                <h2 class="headerstyle">Installation of Clansuite</h2>

                <!-- PATH TO CLANSUITE INSTALLER -->
                <?php if (!empty($args['clansuiteFolderName'])): ?>
                <p>
                    <span style="font-size: 12px; font-weight:bold; ">
                    The Webinstaller has successfully extracted the archive.
                    <br /><br />
                    Please proceed to the
                    <a href="<?php print $args['clansuiteFolderName'] . '/installation/index.php'; ?>">
                    Clansuite Installation Wizard</a>!
                    </span>
                </p>

                <!-- CHANGE PERMISSIONS -->
                   <?php
                   $display = !empty($args['clansuiteFolderName']) ? 'style="display: none;"' : '';
                   $folderName = empty($args['clansuiteFolderName']) ? 'clansuite' : $args['clansuiteFolderName']; ?>
                   <!-- <div class="box"> -->

                   <h2 id="chmod-toggler" class="headerstyle" style="cursor: pointer"
                       onclick="BlockToggle('chmod-toggle', 'chmod-toggler', 'Change folder permissions')">Change folder permissions <div style="margin-right: 5px; margin-top: -25px; float:right;"><img src="http://www.clansuite.com/website/images/dn.gif" alt="DOWN" align="top" /></div></h2>
                   <div id="chmod-toggle" <?php print $display; ?>>

                   <?php if (!empty($args['clansuiteFolderName'])): ?>
                 <p>
                    Change the permissions of your Clansuite Folder: <b>777</b> makes the folder writeable for everybody. That is needed such that you can move
                    clansuite or rename the directory with an FTP program. <b>555</b> makes it readable for
                    everybody, which is required to have an operational Clansuite installation.
                </p>
                <p>
                    For <b>security</b> purposes, it is recommended that you change the folder permissions
                    back to <b>555</b> once Clansuite is running. Only if you are running PHP-CGI, clansuite
                    might already owned by your user and no permission changes are required.
                </p>
                <form id="chmodForm" action="" method="post">
                    Folder name:
                    <input type="text" name="folderName" size="20" value="<?php print $folderName; ?>" />
                    Permissions:
                    <select name="folderPermissions">
                    <?php foreach($folderPermissionList as $perm): ?>
                        <option value="<?php print $perm; ?>"><?php print $perm; ?></option>
                    <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="command" value="chmod" />
                    <input type="submit" value="Change Permissions" onclick="this.disabled=true;this.form.submit();" />
                </form>
                <?php else: # of change permissions folder ?>
                <div class="warning">
                    There is no Clansuite folder in the current working directory.
                </div>
                <?php endif; # of change permissions folder ?>
                </div> <!-- end chmod-toggle div -->

                <!-- RENAME FOLDER-->
                <h2 id="rename-toggler" class="headerstyle" style="cursor: pointer"
                    onclick="BlockToggle('rename-toggle', 'rename-toggler', 'Rename folder')">Rename folder <div style="margin-right: 5px; margin-top: -25px; float:right;"><img src="http://www.clansuite.com/website/images/dn.gif" alt="DOWN" align="top" /></div></h2>
                <div id="rename-toggle" <?php print $display; ?>>

                <?php if (!empty($args['clansuiteFolderName'])): ?>
                <p>
                    Quickly rename the clansuite folder. You can do that with your FTP program as well.
                </p>
                <form id="renameForm" action="" method="post">
                    Rename folder to:
                    <input type="text" name="folderName" size="20" value="<?php print $folderName; ?>" />
                    <input type="hidden" name="command" value="rename" />
                    <input type="submit" value="Rename Folder" onclick="this.disabled=true;this.form.submit();" />
                </form>
                </div> <!-- end rename-toggle div -->
                <?php else: # of rename folder ?>
                <div class="warning">
                    There is no Clansuite folder in the current working directory.
                </div>
                <?php endif; # of rename folder ?>

                <?php endif; ?>
                </div> <!-- Close page-3-installation -->
                <?php printNavigationButtons('intro','installation'); ?>
                <?php endif; ?>

        </div> <!-- Close Content-Middle -->

        <!-- <div id="content_footer">

        </div> --> <!-- div content_footer end -->

    </div> <!-- Close Content -->

    <div id="rightsidebar">
        <ul>
            <!-- Clansuite Webinstaller Icon -->
            <li style="margin: 0px 0 20px 10px">
                <img src="http://home.gna.org/clansuite/Clansuite-Toolbar-Icon-64-white-webinstall.png"
                     alt="Clansuite Webinstaller Logo"
                     style="border: 3px groove #333333;"
                 />
            </li>

            <!-- Clansuite Shortcuts -->
            <li><h2>Clansuite Shortcuts</h2></li>
            <li><strong><a href="http://www.clansuite.com/">Website</a></strong></li>
            <li><strong><a href="http://forum.clansuite.com/">Forum</a></strong></li>
            <li><strong><a href="http://forum.clansuite.com/index.php?board=4">Installsupport</a></strong></li>
            <li><strong><a href="http://trac.clansuite.com/">Bugtracker</a></strong></li>
            <li><strong><a href="teamspeak://clansuite.com:8000?channel=clansuite%20Admins?subchannel=clansuite%20Support">Teamspeak</a></strong></li>
            <li><strong><a href="http://www.clansuite.com/toolbar/">Toolbar</a></strong></li>

            <!-- Donate -->
            <li><h2>Donate</h2></li>
              <li>
                <!-- PayPal Direct Image Button
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                    <input type="hidden" name="cmd" value="_s-xclick" />
                    <input type="image" src="https://www.paypal.com/de_DE/i/btn/x-click-but04.gif" name="submit" alt="Zahlen Sie mit PayPal - schnell, kostenlos und sicher!" />
                    <img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1" />
                    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHTwYJKoZIhvcNAQcEoIIHQDCCBzwCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYB0LEEuVZOTu++bRevqW4bD4mdoGvWnTwCQ4urr8cax4ilsFehU4sl729m3S9QtPQv0B7CFhtWGxJ7pXhx3cQ35nTzobkxCYRYy01Aw0Gkmlxnc+6Rz7lIjAKOnL6U9Ftr7iCJH74c6ryJSlI8QB9dsqUi2YBsgfljyx5w/bunS9TELMAkGBSsOAwIaBQAwgcwGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIXabqVcNbyPOAgaiz4LIIs8323fnbtieAP3ump4WwZ7rItgWlTYEj4DnK3zhL8nj78XevGVKQ3PjAHGHPIqvqHeP8QEgUWtW4B7cnRGZyPGF6eXOPnNGAfDpALa4us2I38klL3HI207q5ob+2Rz/9gu5wLccfDcWfyi5aTBVzWcozcyIwyhaOgZP8z1JzVj26uYhqZwPOryQ6KmvUa//K9+6RyEyttVo51/EtejO1zX/KNsKgggOHMIIDgzCCAuygAwIBAgIBADANBgkqhkiG9w0BAQUFADCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wHhcNMDQwMjEzMTAxMzE1WhcNMzUwMjEzMTAxMzE1WjCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAMFHTt38RMxLXJyO2SmS+Ndl72T7oKJ4u4uw+6awntALWh03PewmIJuzbALScsTS4sZoS1fKciBGoh11gIfHzylvkdNe/hJl66/RGqrj5rFb08sAABNTzDTiqqNpJeBsYs/c2aiGozptX2RlnBktH+SUNpAajW724Nv2Wvhif6sFAgMBAAGjge4wgeswHQYDVR0OBBYEFJaffLvGbxe9WT9S1wob7BDWZJRrMIG7BgNVHSMEgbMwgbCAFJaffLvGbxe9WT9S1wob7BDWZJRroYGUpIGRMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbYIBADAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4GBAIFfOlaagFrl71+jq6OKidbWFSE+Q4FqROvdgIONth+8kSK//Y/4ihuE4Ymvzn5ceE3S/iBSQQMjyvb+s2TWbQYDwcp129OPIbD9epdr4tJOUNiSojw7BHwYRiPh58S1xGlFgHFXwrEBb3dgNbMUa+u4qectsMAXpVHnD9wIyfmHMYIBmjCCAZYCAQEwgZQwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tAgEAMAkGBSsOAwIaBQCgXTAYBgkqhkiG9w0BCQMxCwYJKoZIhvcNAQcBMBwGCSqGSIb3DQEJBTEPFw0wNzExMjMxNzU2NDdaMCMGCSqGSIb3DQEJBDEWBBT0m+M8uOJiEOLXaidOMaMQ39p/HzANBgkqhkiG9w0BAQEFAASBgJeXD93po4s9fSwc9U10wtURG34U1WcaiTJFVUPGUTwY80/IgBDyC7swVhXdGMq6sNLaQwb9f0DLvVHyYIMVHSEN90imprm9A7TzohMWKE695ypas6sQI1NOGxxC/lGwJnfib+k7II053TIDAM2ezZtnqpaF/ub0F+7aXcuusPp5-----END PKCS7-----" />
                    </form>
                -->
                <!-- Pledige Campaign -->
                <a href='http://www.pledgie.com/campaigns/6324'><img alt='Click here to lend your support to: Unterstützt Clansuite! and make a donation at www.pledgie.com !' src='http://www.pledgie.com/campaigns/6324.png?skin_name=eight_bit' border='0' /></a>
            </li>
            <li><h2>Link us</h2></li>
            <li><a href="http://www.clansuite.com/banner/" target="_blank"><img src="http://www.clansuite.com/website/images/banners/clansuite-crown-banner-80x31.png" alt="Clansuite 80x31 LOGO" /></a></li>
        </ul>
    </div>
    <hr />
    <!-- Fusszeile -->
    <div id="footer">
         <p style="filter:alpha(opacity=65); -moz-opacity:0.65;">
            <br />
            Clansuite Webinstaller <?php echo $webinstaller_version; ?>
            <br />
            SVN: $Rev$ $Author$
            <br />
            &copy; 2005-<?php echo date("Y"); ?> by <a href="http://www.jens-andre-koch.de" target="_blank" style="text-decoration=none">Jens-Andr&#x00E9; Koch</a> &amp; Clansuite Development Team
         </p>
       </div><!-- Fusszeile ENDE -->
</div><!-- PAGE ENDE -->

</body>
</html>
</center>
</body>
</html>
<?php
}

/**
 * If necessary, define some functions for backwards compatibility.
 */
function compatiblityFunctions() {
    /* On MS Windows, function is_executable() was introduced in PHP 5.0.0. */
    if (!function_exists('is_executable')) {
    function is_executable($file) {
        $stats = stat($file);
        /*
         * If stat doesn't work for some reason, assume it's executable.
         * 0000100 is the is_executable bit. Windows returns true for .exe files.
         */
        return empty($stats['mode']) or $stats['mode'] & 0000100;
    }
    }
}

function printNavigationButtons($back_cmd, $forward_cmd)
{
?>
    <div class="navigation">
        <span style="font-size:10px;">
            Click Next to proceed!
            <br />
            Click Back to return!
        </span>

        <div class="alignright">
             <?php if ($back_cmd == 'intro'): ?>
                <form action="<?php print basename(__FILE__); ?>" method="post">
             <?php endif; ?>
                <input type="submit" value="Next" class="ButtonGreen" name="step_forward" />
                <input type="hidden" name="command" value="<?php print $forward_cmd; ?>" />
            </form>
        </div>

        <div class="alignleft">
            <form action="<?php print basename(__FILE__); ?>" method="post">
                <input type="submit" value="Back" class="ButtonRed" name="step_backward" />
                <input type="hidden" name="command" value="<?php print $back_cmd; ?>" />
            </form>
        </div>
    </div><!-- div navigation end -->
<?php
}


/* --------------------------------------------------------------------
        Not all servers include the php service of tar handling,
      so we serve a shortened 3rd party code only for tar.gz extraction.
---------------------------------------------------------------------- */

// --------------------------------------------------------------------------------
// PhpConcept Library - Tar Module 1.3
// --------------------------------------------------------------------------------
// License GNU/GPL - Vincent Blavet - August 2001
// http://www.phpconcept.net
// --------------------------------------------------------------------------------
// Note:
//    Small changes have been made by Andy Staudacher <ast@gmx.ch> to incorporate
//    the code in this script. Code to create new archives has been removed,
//    we only need to extract archives. Date: 2006/02/03
// --------------------------------------------------------------------------------
  // ----- Global variables
  $g_pcltar_version = "1.3";

  // --------------------------------------------------------------------------------
  // Function : PclTarExtract()
  // Description :
  //   Extract all the files present in the archive $p_tarname, in the directory
  //   $p_path. The relative path of the archived files are keep and become
  //   relative to $p_path.
  //   If a file with the same name already exists it will be replaced.
  //   If the path to the file does not exist, it will be created.
  //   Depending on the $p_tarname extension (.tar, .tar.gz or .tgz) the
  //   function will determine the type of the archive.
  // Parameters :
  //   $p_tarname : Name of an existing tar file.
  //   $p_path : Path where the files will be extracted. The files will use
  //             their memorized path from $p_path.
  //             If $p_path is "", files will be extracted in "./".
  //   $p_remove_path : Path to remove (from the file memorized path) while writing the
  //                    extracted files. If the path does not match the file path,
  //                    the file is extracted with its memorized path.
  //                    $p_path and $p_remove_path are commulative.
  //   $p_mode : 'tar' or 'tgz', if not set, will be determined by $p_tarname extension
  // Return Values :
  //   Same as PclTarList()
  // --------------------------------------------------------------------------------
  function PclTarExtract($p_tarname, $p_path="./", $p_remove_path="", $p_mode="")
  {
    $v_result=1;

    // ----- Extract the tar format from the extension
    if (($p_mode == "") or (($p_mode!="tar") and ($p_mode!="tgz")))
    {
      if (($p_mode = PclTarHandleExtension($p_tarname)) == "")
      {
    return 'Extracting tar/gz failed, cannot handle extension';
      }
    }

    // ----- Call the extracting fct
    $p_list = array();
    if (($v_result = PclTarHandleExtract($p_tarname, 0, $p_list, "complete", $p_path, $p_mode, $p_remove_path)) != 1)
    {
      return 'Extracting Tar.gz failed';
    }

    return true;
  }
  // --------------------------------------------------------------------------------

// --------------------------------------------------------------------------------
// ***** UNDER THIS LINE ARE DEFINED PRIVATE INTERNAL FUNCTIONS *****
// *****                                                        *****
// *****       THESES FUNCTIONS MUST NOT BE USED DIRECTLY       *****
// --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclTarHandleExtract()
  // Description :
  // Parameters :
  //   $p_tarname : Filename of the tar (or tgz) archive
  //   $p_file_list : An array which contains the list of files to extract, this
  //                  array may be empty when $p_mode is 'complete'
  //   $p_list_detail : An array where will be placed the properties of  each extracted/listed file
  //   $p_mode : 'complete' will extract all files from the archive,
  //             'partial' will look for files in $p_file_list
  //             'list' will only list the files from the archive without any extract
  //   $p_path : Path to add while writing the extracted files
  //   $p_tar_mode : 'tar' for GNU TAR archive, 'tgz' for compressed archive
  //   $p_remove_path : Path to remove (from the file memorized path) while writing the
  //                    extracted files. If the path does not match the file path,
  //                    the file is extracted with its memorized path.
  //                    $p_remove_path does not apply to 'list' mode.
  //                    $p_path and $p_remove_path are commulative.
  // Return Values :
  // --------------------------------------------------------------------------------
  function PclTarHandleExtract($p_tarname, $p_file_list, &$p_list_detail, $p_mode, $p_path, $p_tar_mode, $p_remove_path)
  {
    $v_result=1;
    $v_nb = 0;
    $v_extract_all = true;
    $v_listing = false;

    // ----- Check the path
    /*
    if (($p_path == "") or ((substr($p_path, 0, 1) != "/") and (substr($p_path, 0, 3) != "../")))
      $p_path = "./".$p_path;
    */

    $isWin = (substr(PHP_OS, 0, 3) == 'WIN');

    if(!$isWin)
    {
        if (($p_path == "") or ((substr($p_path, 0, 1) != "/") and (substr($p_path, 0, 3) != "../")))
      $p_path = "./".$p_path;
    }
    // ----- Look for path to remove format (should end by /)
    if (($p_remove_path != "") and (substr($p_remove_path, -1) != '/'))
    {
      $p_remove_path .= '/';
    }
    $p_remove_path_size = strlen($p_remove_path);

    // ----- Study the mode
    switch ($p_mode) {
      case "complete" :
      // ----- Flag extract of all files
      $v_extract_all = true;
      $v_listing = false;
      break;
      case "partial" :
      // ----- Flag extract of specific files
      $v_extract_all = false;
      $v_listing = false;
      break;
      case "list" :
      // ----- Flag list of all files
      $v_extract_all = false;
      $v_listing = true;
      break;
      default :
      return false;
    }

    // ----- Open the tar file
    if ($p_tar_mode == "tar")
    {
      $v_tar = fopen($p_tarname, "rb");
    }
    else
    {
      $v_tar = @gzopen($p_tarname, "rb");
    }

    // ----- Check that the archive is open
    if ($v_tar == 0)
    {
      return false;
    }

    $start = time();

    // ----- Read the blocks
    while (!($v_end_of_file = ($p_tar_mode == "tar"?feof($v_tar):gzeof($v_tar))))
    {
      // ----- Clear cache of file infos
      clearstatcache();

    if (time() - $start > 55) {
       Platform::extendTimeLimit();
       $start = time();
    }

      // ----- Reset extract tag
      $v_extract_file = false;
      $v_extraction_stopped = 0;

      // ----- Read the 512 bytes header
      if ($p_tar_mode == "tar")
    $v_binary_data = fread($v_tar, 512);
      else
    $v_binary_data = gzread($v_tar, 512);

      // ----- Read the header properties
      $v_header = array();
      if (($v_result = PclTarHandleReadHeader($v_binary_data, $v_header)) != 1)
      {
    // ----- Close the archive file
    if ($p_tar_mode == "tar")
      fclose($v_tar);
    else
      gzclose($v_tar);

    // ----- Return
    return $v_result;
      }

      // ----- Look for empty blocks to skip
      if ($v_header["filename"] == "")
      {
    continue;
      }

      // ----- Look for partial extract
      if ((!$v_extract_all) and (is_array($p_file_list)))
      {
    // ----- By default no unzip if the file is not found
    $v_extract_file = false;

    // ----- Look into the file list
    $size = sizeof($p_file_list);
    for ($i=0; $i<$size; $i++)
    {
      // ----- Look if it is a directory
      if (substr($p_file_list[$i], -1) == "/")
      {
        // ----- Look if the directory is in the filename path
        if ((strlen($v_header["filename"]) > strlen($p_file_list[$i])) and (substr($v_header["filename"], 0, strlen($p_file_list[$i])) == $p_file_list[$i]))
        {
          // ----- The file is in the directory, so extract it
          $v_extract_file = true;

          // ----- End of loop
          break;
        }
      }

      // ----- It is a file, so compare the file names
      else if ($p_file_list[$i] == $v_header["filename"])
      {
        // ----- File found
        $v_extract_file = true;

        // ----- End of loop
        break;
      }
    }

    // ----- Trace
    if (!$v_extract_file)
    {
    }
      }
      else
      {
    // ----- All files need to be extracted
    $v_extract_file = true;
      }

      // ----- Look if this file need to be extracted
      if (($v_extract_file) and (!$v_listing))
      {
    // ----- Look for path to remove
    if (($p_remove_path != "")
        and (substr($v_header["filename"], 0, $p_remove_path_size) == $p_remove_path))
    {
      // ----- Remove the path
      $v_header["filename"] = substr($v_header["filename"], $p_remove_path_size);
    }

    // ----- Add the path to the file
    if (($p_path != "./") and ($p_path != "/"))
    {
      // ----- Look for the path end '/'
      while (substr($p_path, -1) == "/")
      {
        $p_path = substr($p_path, 0, strlen($p_path)-1);
      }

      // ----- Add the path
      if (substr($v_header["filename"], 0, 1) == "/")
          $v_header["filename"] = $p_path.$v_header["filename"];
      else
        $v_header["filename"] = $p_path."/".$v_header["filename"];
    }

    // ----- Check that the file does not exists
    if (is_file($v_header["filename"]))
    {
      // ----- Look if file is a directory
      if (is_dir($v_header["filename"]))
      {
        // ----- Change the file status
        $v_header["status"] = "already_a_directory";

        // ----- Skip the extract
        $v_extraction_stopped = 1;
        $v_extract_file = 0;
      }
      // ----- Look if file is write protected
      else if (!is_writeable($v_header["filename"]))
      {
        // ----- Change the file status
        $v_header["status"] = "write_protected";

        // ----- Skip the extract
        $v_extraction_stopped = 1;
        $v_extract_file = 0;
      }
      // ----- Look if the extracted file is older
      else if (filemtime($v_header["filename"]) > $v_header["mtime"])
      {
        // ----- Change the file status
        $v_header["status"] = "newer_exist";

        // ----- Skip the extract
        $v_extraction_stopped = 1;
        $v_extract_file = 0;
      }
    }

    // ----- Check the directory availability and create it if necessary
    else
    {
      if ($v_header["typeflag"]=="5")
        $v_dir_to_check = $v_header["filename"];
      else if (!strstr($v_header["filename"], "/"))
        $v_dir_to_check = "";
      else
        $v_dir_to_check = dirname($v_header["filename"]);

      if (($v_result = PclTarHandlerDirCheck($v_dir_to_check)) != 1)
      {
        // ----- Change the file status
        $v_header["status"] = "path_creation_fail";

        // ----- Skip the extract
        $v_extraction_stopped = 1;
        $v_extract_file = 0;
      }
    }

    // ----- Do the extraction
    if (($v_extract_file) and ($v_header["typeflag"]!="5"))
    {
      // ----- Open the destination file in write mode
      if (($v_dest_file = fopen($v_header["filename"], "wb")) == 0)
      {
        // ----- Change the file status
        $v_header["status"] = "write_error";

        // ----- Jump to next file
        if ($p_tar_mode == "tar")
          fseek($v_tar, ftell($v_tar)+(ceil(($v_header['size']/512))*512));
        else
          gzseek($v_tar, gztell($v_tar)+(ceil(($v_header['size']/512))*512));
      }
      else
      {
        // ----- Read data
        $n = floor($v_header["size"]/512);
        for ($i=0; $i<$n; $i++)
        {
          if ($p_tar_mode == "tar")
        $v_content = fread($v_tar, 512);
          else
        $v_content = gzread($v_tar, 512);
          fwrite($v_dest_file, $v_content, 512);
        }
        if (($v_header["size"] % 512) != 0)
        {
          if ($p_tar_mode == "tar")
        $v_content = fread($v_tar, 512);
          else
        $v_content = gzread($v_tar, 512);
          fwrite($v_dest_file, $v_content, ($v_header["size"] % 512));
        }

        // ----- Close the destination file
        fclose($v_dest_file);

        // ----- Change the file mode, mtime
        @touch($v_header["filename"], $v_header["mtime"]);
        //chmod($v_header[filename], DecOct($v_header[mode]));
      }

      // ----- Check the file size
      clearstatcache();
      if (filesize($v_header["filename"]) != $v_header["size"])
      {
        // ----- Close the archive file
        if ($p_tar_mode == "tar")
          fclose($v_tar);
        else
          gzclose($v_tar);

        // ----- Return
        return false;
      }
    }

    else
    {
      // ----- Jump to next file
      if ($p_tar_mode == "tar")
        fseek($v_tar, ftell($v_tar)+(ceil(($v_header["size"]/512))*512));
      else
        gzseek($v_tar, gztell($v_tar)+(ceil(($v_header["size"]/512))*512));
    }
      }

      // ----- Look for file that is not to be unzipped
      else
      {
    // ----- Jump to next file
    if ($p_tar_mode == "tar")
      fseek($v_tar, ($p_tar_mode=="tar"?ftell($v_tar):gztell($v_tar))+(ceil(($v_header[size]/512))*512));
    else
      gzseek($v_tar, gztell($v_tar)+(ceil(($v_header[size]/512))*512));
      }

      if ($p_tar_mode == "tar")
    $v_end_of_file = feof($v_tar);
      else
    $v_end_of_file = gzeof($v_tar);

      // ----- File name and properties are logged if listing mode or file is extracted
      if ($v_listing or $v_extract_file or $v_extraction_stopped)
      {
    // ----- Log extracted files
    if (($v_file_dir = dirname($v_header["filename"])) == $v_header["filename"])
      $v_file_dir = "";
    if ((substr($v_header["filename"], 0, 1) == "/") and ($v_file_dir == ""))
      $v_file_dir = "/";

    // ----- Add the array describing the file into the list
    $p_list_detail[$v_nb] = $v_header;

    // ----- Increment
    $v_nb++;
      }
    }

    // ----- Close the tarfile
    if ($p_tar_mode == "tar")
      fclose($v_tar);
    else
      gzclose($v_tar);

    // ----- Return
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclTarHandleReadHeader()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function PclTarHandleReadHeader($v_binary_data, &$v_header)
  {
    $v_result=1;

    // ----- Read the 512 bytes header
    /*
    if ($p_tar_mode == "tar")
      $v_binary_data = fread($p_tar, 512);
    else
      $v_binary_data = gzread($p_tar, 512);
    */

    // ----- Look for no more block
    if (strlen($v_binary_data)==0)
    {
      $v_header['filename'] = "";
      $v_header['status'] = "empty";

      return $v_result;
    }

    // ----- Look for invalid block size
    if (strlen($v_binary_data) != 512)
    {
      $v_header['filename'] = "";
      $v_header['status'] = "invalid_header";

      // ----- Return
      return false;
    }

    // ----- Calculate the checksum
    $v_checksum = 0;
    // ..... First part of the header
    for ($i=0; $i<148; $i++)
    {
      $v_checksum+=ord(substr($v_binary_data,$i,1));
    }
    // ..... Ignore the checksum value and replace it by ' ' (space)
    for ($i=148; $i<156; $i++)
    {
      $v_checksum += ord(' ');
    }
    // ..... Last part of the header
    for ($i=156; $i<512; $i++)
    {
      $v_checksum+=ord(substr($v_binary_data,$i,1));
    }

    // ----- Extract the values
    $v_data = unpack("a100filename/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1typeflag/a100link/a6magic/a2version/a32uname/a32gname/a8devmajor/a8devminor", $v_binary_data);

    // ----- Extract the checksum for check
    $v_header["checksum"] = octdec(trim($v_data["checksum"]));
    if ($v_header["checksum"] != $v_checksum)
    {
      $v_header["filename"] = "";
      $v_header["status"] = "invalid_header";

      // ----- Look for last block (empty block)
      if (($v_checksum == 256) and ($v_header["checksum"] == 0))
      {
    $v_header["status"] = "empty";
    // ----- Return
    return $v_result;
      }

      // ----- Return
      return false;
    }
    // ----- Extract the properties
    $v_header["filename"] = trim($v_data["filename"]);
    $v_header["mode"] = octdec(trim($v_data["mode"]));
    $v_header["uid"] = octdec(trim($v_data["uid"]));
    $v_header["gid"] = octdec(trim($v_data["gid"]));
    $v_header["size"] = octdec(trim($v_data["size"]));
    $v_header["mtime"] = octdec(trim($v_data["mtime"]));
    if (($v_header["typeflag"] = $v_data["typeflag"]) == "5")
    {
      $v_header["size"] = 0;
    }
    /* ----- All these fields are removed form the header because they do not carry interesting info
    $v_header[link] = trim($v_data[link]);
    TrFctMessage(__FILE__, __LINE__, 2, "Linkname : $v_header[linkname]");
    $v_header[magic] = trim($v_data[magic]);
    TrFctMessage(__FILE__, __LINE__, 2, "Magic : $v_header[magic]");
    $v_header[version] = trim($v_data[version]);
    TrFctMessage(__FILE__, __LINE__, 2, "Version : $v_header[version]");
    $v_header[uname] = trim($v_data[uname]);
    TrFctMessage(__FILE__, __LINE__, 2, "Uname : $v_header[uname]");
    $v_header[gname] = trim($v_data[gname]);
    TrFctMessage(__FILE__, __LINE__, 2, "Gname : $v_header[gname]");
    $v_header[devmajor] = trim($v_data[devmajor]);
    TrFctMessage(__FILE__, __LINE__, 2, "Devmajor : $v_header[devmajor]");
    $v_header[devminor] = trim($v_data[devminor]);
    TrFctMessage(__FILE__, __LINE__, 2, "Devminor : $v_header[devminor]");
    */

    // ----- Set the status field
    $v_header["status"] = "ok";

    // ----- Return
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclTarHandlerDirCheck()
  // Description :
  //   Check if a directory exists, if not it creates it and all the parents directory
  //   which may be useful.
  // Parameters :
  //   $p_dir : Directory path to check (without / at the end).
  // Return Values :
  //    1 : OK
  //   -1 : Unable to create directory
  // --------------------------------------------------------------------------------
  function PclTarHandlerDirCheck($p_dir)
  {
    $v_result = 1;

    // ----- Check the directory availability
    if ((is_dir($p_dir)) or ($p_dir == ""))
    {
      return 1;
    }

    // ----- Look for file alone
    /*
    if (!strstr("$p_dir", "/"))
    {
      TrFctEnd(__FILE__, __LINE__,  "'$p_dir' is a file with no directory");
      return 1;
    }
    */

    // ----- Extract parent directory
    $p_parent_dir = dirname($p_dir);

    // ----- Just a check
    if ($p_parent_dir != $p_dir)
    {
      // ----- Look for parent directory
      if ($p_parent_dir != "")
      {
    if (($v_result = PclTarHandlerDirCheck($p_parent_dir)) != 1)
    {
      return $v_result;
    }
      }
    }

    // ----- Create the directory
    if (!@mkdir($p_dir, 0777))
    {
      // ----- Return
      return false;
    }

    // ----- Return
    return $v_result;
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PclTarHandleExtension()
  // Description :
  // Parameters :
  // Return Values :
  // --------------------------------------------------------------------------------
  function PclTarHandleExtension($p_tarname)
  {
    // ----- Look for file extension
    if ((substr($p_tarname, -7) == ".tar.gz") or (substr($p_tarname, -4) == ".tgz"))
    {
      $v_tar_mode = "tgz";
    }
    else if (substr($p_tarname, -4) == ".tar")
    {
      $v_tar_mode = "tar";
    }
    else
    {
      $v_tar_mode = "";
    }

    return $v_tar_mode;
  }
  // --------------------------------------------------------------------------------

/* ---------- END 3rd Party code for tar.gz extraction --------------------- */
?>
