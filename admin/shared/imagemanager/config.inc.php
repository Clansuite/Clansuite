<?php
/**
 * Image Manager configuration file.
 * @author $Author: Wei Zhuo $
 * @version $Id: config.inc.php 27 2004-04-01 08:31:57Z Wei Zhuo $
 * @package ImageManager
 */

$IMConfig['base_dir'] = dirname(dirname(dirname(dirname(__FILE__))));
//$IMConfig['base_dir'] = $_SERVER['DOCUMENT_ROOT'].'/xampp/work/sugoclan/www/';
$IMConfig['base_url'] = 'http://127.0.0.1/xampp/work/sugoclan/www/';
/*
  TRUE - SAFE MODE = directory creation will not be possible,
		 only the GD library can be used, other libraries require
		 Safe Mode to be off.
 */
$IMConfig['safe_mode'] = false;
define('IMAGE_CLASS', 'GD');
define('IMAGE_TRANSFORM_LIB_PATH', 'C:/"Program Files"/ImageMagick-5.5.7-Q16/');
/* ==============  OPTIONAL SETTINGS ============== */
$IMConfig['thumbnail_prefix'] = '.';
$IMConfig['thumbnail_dir'] = '.thumbs';
$IMConfig['allow_new_dir'] = true;
$IMConfig['allow_upload'] = true;
$IMConfig['validate_images'] = true;
$IMConfig['default_thumbnail'] = 'img/default.gif';
$IMConfig['thumbnail_width'] = 96;
$IMConfig['thumbnail_height'] = 96;
$IMConfig['tmp_prefix'] = '.editor_';
?>