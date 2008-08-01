<?php

if (!defined('IN_CS')){ die('Clansuite Framework not loaded. Direct Access forbidden.' );}

# This file contains version info only and is automatically updated. DO NOT EDIT.

define('CLANSUITE_VERSION',         '0.2');
define('CLANSUITE_VERSION_NAME',    'Trajan');
define('CLANSUITE_VERSION_STATE',   'alpha-dev');

/**
 * Detect SVN Revision Number from
 *
 * Author: Andy Dawson (AD7six) for cakephp.org
 * URL: http://bakery.cakephp.org/articles/view/using-your-application-svn-revision-number
 */
if (!defined('CLANSUITE_REVISION'))
{
    # File used: "root/.svn/entries"
    if (file_exists(ROOT . '.svn' . DS . 'entries'))
    {
        $svn = file(ROOT . '.svn' . DS . 'entries');
        if (is_numeric(trim($svn[3])))
        {
            $version = $svn[3];
        }
        else # pre 1.4 svn used xml for this file
        {
            $version = explode('"', $svn[4]);
            $version = $version[1];
        }
        define ('CLANSUITE_REVISION', trim($version));
        unset ($svn);
        unset ($version);
    }
    else # default if no svn data avilable
    {
        define ('CLANSUITE_REVISION', 0);
    }
}
?>