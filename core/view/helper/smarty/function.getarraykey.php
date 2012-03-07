<?php
/**
 * Smarty plugin
 * @package Clansuite
 * @subpackage smarty_plugins
 */

/**
 * Smarty getarraykey function plugin
 *
 * Type:     function<br>
 * Name:     getarraykey<br>
 * Purpose:  searches an array for an key and returns value if found
 * @param string key array
 * @return string
 */
function smarty_function_getarraykey($params)
{
    return array_search_key($params['key'], $params['array']);
}

function array_search_key( $needle_key, $array )
{
    foreach($array as $key => $value)
    {
        if($key == $needle_key)
        { 
            return $value;
        }
        
        if(true === is_array($value))
        {
            if( ($result = array_search_key($needle_key,$value)) !== false)
            {
                return $result;
            }
        }
    }
  
    return false;
} 
?>