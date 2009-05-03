<?php
/**
 * Project:     smarty_ajax: AJAX-enabled Smarty plugins
 * File:        function.ajax_call.php
 *
 * This software is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This software is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @link http://kpumuk.info/ajax/smarty_ajax/
 * @copyright 2006 Dmytro Shteflyuk
 * @author Dmytro Shteflyuk <kpumuk@kpumuk.info>
 * @package smarty_ajax
 * @version 0.1
 */

function smarty_function_ajax_call($params, &$smarty)
{
  $function = isset($params['function']) ? $params['function'] : '';
  $url = isset($params['url']) ? $params['url'] : $_SERVER['PHP_SELF'];
  $method = isset($params['method']) ? $params['method'] : 'get';
  $parameters = isset($params['params']) ? $params['params'] : '';
  $callback = isset($params['callback']) ? $params['callback'] : 'undefined';
  $params_func = isset($params['params_func']) ? $params['params_func'] : 'undefined';

  if ($parameters !== '') $parameters .= '&';
  $parameters .= 'f=' . $function;

  return 'SmartyAjax.call(\'' . $url . '\', \'' . $method .
    '\', \'' . $parameters . '\', ' . $callback . ', ' .
    $params_func . '); return false;';
}

?>