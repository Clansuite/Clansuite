<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage Plugins
 */

/**
 * URL Generator for Smarty Templates
 *
 * Examples:
 * {link_to href="/news/show"}
 *
 * Type:     function<br>
 * Name:     a<br>
 * Purpose:  Generates the proper URL from the href parameter given<br>
 *
 * @author   Jens-Andr� Koch <vain@clansuite.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL 2 / any later version
 * @param array $params
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_link_to($params, $smarty)
{
    # method parameter "href"
    if(empty($params['href']))
    {
        $errormessage  = 'You are using the <font color="#FF0033">{link_to}</font> command, but the <font color="#FF0033">Parameter "href" is missing.</font>';
        $errormessage .= ' Try to append the parameter in the following way: <font color="#66CC00">href="/news/show"</font>.';
        trigger_error($errormessage);
        return;
    }
    else
    {
        # convert from internal slashed format to URL
        return Koch_Router::buildURL($params['href']);
    }
}
?>