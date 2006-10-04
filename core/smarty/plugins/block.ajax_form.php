<?php
/**
 * Project:     smarty_ajax: AJAX-enabled Smarty plugins
 * File:        block.ajax_form.php
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

function smarty_block_ajax_form($params, $content, &$smarty, &$repeat)
{
  if ($content !== null)
  {
    $url = isset($params['url']) ? $params['url'] : $_SERVER['PHP_SELF'];
    $method = isset($params['method']) ? $params['method'] : 'get';
    $parameters = isset($params['params']) ? $params['params'] : '';
	$onsubmit = isset($params['onsubmit']) ? $params['onsubmit'] : '';

    return '<form method="' . $method . '" action="' . $url .
      '" onsubmit="' . $onsubmit . ';SmartyAjax.submit(this, \'' . $parameters . '\'' .
      (isset($params['callback']) ? ', ' . $params['callback'] : '') .
      '); return false;"' .
      (isset($params['id']) ? ' id="' . $params['id'] . '"' : '') .
      '>' . $content . '</form>';
  }
}

?>