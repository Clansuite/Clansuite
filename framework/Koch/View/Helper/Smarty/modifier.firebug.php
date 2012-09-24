<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty Modifier to debug output the variable content to the firebug console.
 *
 * @example
 * {$variable|firebug}
 *
 * @param mixed Variable to firedebug
 *
 * @return string
 */
function Smarty_modifier_firebug($var)
{
    \Koch\Debug\Debug::firebug($var);

    // using firebug directly
    /*if (false === class_exists('FirePHP', false)) {
        include ROOT_LIBRARIES.'firephp/FirePHP.class.php';
    }
    $firephp = FirePHP::getInstance(true);
    $firephp->log($var); */
}
