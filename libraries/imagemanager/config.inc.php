<?php
/**
 * Image Manager configuration file.
 * @author $Author: Wei Zhuo $
 * @version $Id: config.inc.php 27 2004-04-01 08:31:57Z Wei Zhuo $
 * @package ImageManager
 */

$IMConfig['base_dir'] = 'D:/Homepage/clansuite.com/uploads/images/';
$IMConfig['base_url'] = '/uploads/images/';
/* ==============  AUTO SETTINGS ============== */
$IMConfig['image_class'] = (function_exists("gd_info")) ? 'GD' : '';
$IMConfig['image_transform_lib_path'] = (defined('PHP_BINDIR')) ?  str_replace( "\\", "/",PHP_BINDIR) : '';
$IMConfig['safe_mode'] = (ini_get('safe_mode') == "1" || strtolower(ini_get('safe_mode')) == "on") ? true : false;
/* ==============  OPTIONAL SETTINGS ============== */
$IMConfig['thumbnail_prefix']   = '_';
$IMConfig['thumbnail_dir']      = 'thumbs';
$IMConfig['allow_new_dir']      = true;
$IMConfig['allow_upload']       = true;
$IMConfig['validate_images']    = true;
$IMConfig['default_thumbnail']  = 'img/default.gif';
$IMConfig['thumbnail_width']    = 96;
$IMConfig['thumbnail_height']   = 96;
$IMConfig['tmp_prefix']         = 'editor_';
/* ==============  Insert image defaults ============== */
$IMConfig['style']    = 	'';
$IMConfig['class']    = 	'';
$IMConfig['vspace']   = 	'';
$IMConfig['hspace']   = 	'';
$IMConfig['border']   = 	'0';
$IMConfig['align']    = 	'';
$IMConfig['insertas'] = 	'2';

?>