<?php
/**
 * Project:     smarty_ajax: AJAX-enabled Smarty plugins
 * File:        smarty_ajax.php
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

$ajax_export_list = array();

function ajax_register() {
  global $ajax_export_list;

  $n = func_num_args();
  for ($i = 0; $i < $n; $i++) {
  	$ajax_export_list[] = func_get_arg($i);
  }
}

function ajax_process_call() {
  global $ajax_export_list;

  if (!isset($_REQUEST['f'])) return;
  $function = $_REQUEST['f'];
  if (false !== array_search($function, $ajax_export_list)) call_user_func($function);
  exit();
}

?>