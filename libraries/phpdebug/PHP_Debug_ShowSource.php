<?php

/**
 * This an exemple of showsource file.
 * 
 * It uses the Pear package TEXT_Highlighter
 * 
 * /!\ Don't forget to securise this script /!\ 
 *   - By Ip
 *   - By allowed path (as the isAllowedPath() function below)
 * 
 * @package PHP_Debug
 * @since V2.0.0 - 26 apr 2006
 * @filesource
 * 
 * @version    CVS: $Id: PHP_Debug_ShowSource.php,v 1.2 2008/09/24 12:34:44 c0il Exp $
 */

// View source configuration (to modify with your settings)
$view_source_options = array(
    'PEAR_ROOT' => 'W:/var/www/php/PEAR',
    'CSS_ROOT' => 'css',
    'ALLOWED_PATH' => array(
        'E:\Works\Projets-DEV\phpdebug\\',
        '/var/www-protected/php-debug.com/www/',
    )
);

// Files that are allowed to be viewed
$pathPattern = '/^{$path}(.*)(.php)$/';

// Additional include path for Pear (to adapt to your configuration )
//set_include_path($options['PEAR_ROOT'] . PATH_SEPARATOR. get_include_path());
// End //

//Include Pear 
require_once 'PEAR.php';

//Include Debug_Renderer_HTML_Table_Config to get the configuration
require_once 'PHP/Debug.php';
require_once 'PHP/Debug/Renderer/HTML/TableConfig.php';
$options = PHP_Debug_Renderer_HTML_TableConfig::singleton()->getConfig();

//Include the class definition of highlighter
require_once 'Text/Highlighter.php';
require_once 'Text/Highlighter/Renderer/Html.php';

/**
 * Security test
 */
function isPathAllowed($file) {

    global $view_source_options, $pathPattern;
    $allowed = false;

    $file = get_magic_quotes_gpc() ? stripslashes($file) : $file;

    foreach ($view_source_options['ALLOWED_PATH'] as $path) {
        $pattern = str_replace(
            '{$path}', 
            regPath(preg_quote($path)), 
            $pathPattern
        );
        if (preg_match($pattern, $file)) {
            $allowed = true;
        }
    }
    return $allowed;
}

// Add your ip restriction here
function isIpAllowed() {
	return true;
}

// Transform path for regex
function regPath($path) {
	return str_replace(
        array(
            '/',
            '-',
        ),
        array(
            '\/',
            '\-',
        ),
        $path
    );
}

// Build the array options for the HTML renderer to get the nice file numbering
$rendOptions = array( 
    'numbers' => $options['HTML_TABLE_view_source_numbers'],
    'tabsize' => $options['HTML_TABLE_view_source_tabsize'],
);


// Finish parser object creation 
$renderer = new Text_Highlighter_Renderer_Html($rendOptions);
$phpHighlighter = Text_Highlighter::factory('PHP');
$phpHighlighter->setRenderer($renderer);

// Now start output, header
$header = str_replace(
    '<title>PEAR::PHP_Debug</title>', 
    '<title>PEAR::PHP_Debug::View_Source::'. $_GET['file']. '</title>', 
    $options['HTML_TABLE_simple_header']);
echo $header;
echo '
    <link rel="stylesheet" type="text/css" media="screen" href="'. $view_source_options['CSS_ROOT'] .'/view_source.css" />
  </head>
  <body>
';

// Security check
if (isPathAllowed($_GET['file']) && isIpAllowed()) {
    if(file_exists($_GET['file'])) { 
        echo
        '<div>
            <span class="hl-title">'.
                (get_magic_quotes_gpc() ? stripslashes($_GET['file']) : $_GET['file']).'
            </span>
        </div>';
        echo $phpHighlighter->highlight(file_get_contents((get_magic_quotes_gpc() ? stripslashes($_GET['file']) : $_GET['file'])));
    } else {
    	echo '<h2>File does not exists</h2>';
    }
} else {
	echo '<h1>Sorry, your are not allowed to access this path</h1>';
}

// Footer
echo $options['HTML_TABLE_simple_footer'];