<?php
/**
* Project:	  SmartyColumnSort: Auto-sorting of table columns for the Smarty Template Engine
* File:		  SmartyColumnSort.class.php
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*
* @copyright 2005 Andreas Söderlund
* @author Andreas Söderlund <gazoot [at] home [dot] se>
* @author Owen Cole <owenc [at] totalsales [dot] com>
* @package SmartyColumnSort
* @version 0.950
*/
class SmartyColumnSort {
	/**
	 * Class Constructor
	 * @param array $column_array An optional database column array, can also be supplied later using {@link SmartyColumnSort::setColumns()}.
	 * @param String $id (optional) A string to identify this column sorter object
	 * @author GET var patches by TGKnIght and jchum on the Smarty forum
	 */
	function SmartyColumnSort($column_array = NULL, $id = 'default') {
		//Only setup the variables if they do not already exist or
		//the $id wasn't set and is left as default
		if((!isset($_SESSION['SmartyColumnSort'][$id]) || $id == 'default') && $column_array) {
			$this->defaultSettings($column_array, $id);
		}

		$_SESSION['SmartyColumnSort'][$id]['target_page'] = $this->nextURL($id);
	}

	/**
	 * This method generates the next URL to be used as links
	 * @param String $id (optional) A string to identify this column sorter object
	 * @return String The prepared URL
	 */
	function nextURL ($id = 'default') {
		$nexturl = $_SERVER['PHP_SELF'].'?';
		foreach($_GET as $key => $value) {
			if($key != $_SESSION['SmartyColumnSort'][$id]['column_var'] && $key != $_SESSION['SmartyColumnSort'][$id]['sort_var']) {
				if(is_array($value)) {
					foreach($value AS $v) {
						$nexturl .= $key.'[]='.$v.'&';
					}
				} else {
					$nexturl .= $key.'='.$value.'&';
				}
			}
		}
		return substr($nexturl, 0, -1);
	}

	/**
	 * This method allows the session variables for a particular ID to be reset to default
	 * This method will be useful to call if the user wants to reset his columnsort view
	 * @param Array $column_array Holds all the columns that will be sorted
	 * @param String $id The id of the columnsort that will be set to default
	 */
	function defaultSettings ($column_array, $id = 'default') {
		$_SESSION['SmartyColumnSort'][$id]['column_var'] = $id.'Col';
		$_SESSION['SmartyColumnSort'][$id]['sort_var'] = $id.'Sort';

		//Assign the columns and set the default as the first column
		$this->setColumns($column_array, $id);
		$this->setDefault($column_array[0], 'asc', $id);

		$_SESSION['SmartyColumnSort'][$id]['current_column'] = $_SESSION['SmartyColumnSort'][$id]['default_column'];
		$_SESSION['SmartyColumnSort'][$id]['current_sort'] = $_SESSION['SmartyColumnSort'][$id]['default_sort'];
	}

	/**
	 * Set the default column and sort order for a column set.
	 *
	 * The column name must exist in the default column before default is set, so {@link SmartyColumnSort::setColumns()} must be set
	 * before this function is called.
	 *
	 * @param string $column A column name.
	 * @param string $sort_order Default sort order for columns. Can be either "asc" or "desc".
	 * @param String $id (optional) A string to identify this column sorter object
	 * @return bool TRUE if default was set, FALSE if not.
	 */
	function setDefault($column, $sort_order, $id = 'default') {
		if(!is_array($_SESSION['SmartyColumnSort'][$id]['column_array'])) {
			trigger_error('setColumns() must be called before setDefault()', E_USER_ERROR);
			return false;
		}

		$_SESSION['SmartyColumnSort'][$id]['default_column'] = NULL;
		$_SESSION['SmartyColumnSort'][$id]['default_sort'] = NULL;

		// Search for column id and set the key value.
		foreach($_SESSION['SmartyColumnSort'][$id]['column_array'] as $key => $value) {
			if(is_array($value)) $value = $value[0];

			if($column == $value) {
				$_SESSION['SmartyColumnSort'][$id]['default_column'] = $key;
				break;
			}
		}

		if($_SESSION['SmartyColumnSort'][$id]['default_column'] === NULL) {
			trigger_error("column '$column' not found in column array!", E_USER_ERROR);
			return false;
		}

		$sort_order = strtolower($sort_order);
		if($sort_order != 'asc' && $sort_order != 'desc') {
			trigger_error('sort_order must be "asc" or "desc"', E_USER_ERROR);
			return false;
		}

		$_SESSION['SmartyColumnSort'][$id]['default_sort'] = $sort_order;

		  $_SESSION['SmartyColumnSort'][$id]['current_column'] = $_SESSION['SmartyColumnSort'][$id]['default_column'];
		  $_SESSION['SmartyColumnSort'][$id]['current_sort'] = $_SESSION['SmartyColumnSort'][$id]['default_sort'];

		return true;
	}

	/**
	 * Set the default GET var for sorting.
	 *
	 * @param string $column The GET var for column id
	 * @param string $sort The GET var for sort values
	 * @param String $id (optional) A string to identify this column sorter object
	 */
	function setVars($column, $sort, $id = 'default') {
		$_SESSION['SmartyColumnSort'][$id]['column_var'] = $column;
		$_SESSION['SmartyColumnSort'][$id]['sort_var'] = $sort;
	}

	/**
	 * Set the column array for the table.
	 *
	 * The column array is used to assign a column id to the links in the template, and
	 * to the database fields. The array has the following format:
	 * <code>
	 * $array = array('db_field', 'db_field2', array('db_field3', 'asc'));
	 * $columnsort->setColumns($array, $id);
	 * </code>
	 * Afterwards, set default field and order with the {@link SmartyColumnSort::setDefault()} function.
	 *
	 * @param array $array The array to use.
	 * @param String $id (optional) A string to identify this column sorter object
	 */
	function setColumns($array, $id = 'default') {
		$_SESSION['SmartyColumnSort'][$id]['column_array'] = $array;
	}

	/**
	 * Set the target page for the column URLs.
	 *
	 * @param string $page A link to another page. The column and sort GET vars will be appended to this link.
	 * @param String $id (optional) A string to identify this column sorter object
	 */
	function setTargetPage($page, $id = 'default') {
		$_SESSION['SmartyColumnSort'][$id]['target_page'] = $page;
	}

	/**
	 * Return the current sort order
	 *
	 * If no value exists (no GET vars), the default value from setDefault() is used.
	 * @param bool $halt_on_error (optional) If TRUE, function returns FALSE in case of illegal get vars. If FALSE, just return default value.
	 * @param String $id (optional) A string to identify this column sorter object
	 * @return mixed the current sort value.
	 */
	function sortOrder($halt_on_error = false, $id = 'default') {
		$sort = $this->_createFromGet($halt_on_error, $id);

		if($halt_on_error && $sort === false)
			return false;
		else
			return $sort;
	}

	/**
	 * Create sort query to be used in DB.
	 *
	 * @param bool $halt_on_error If TRUE, function returns FALSE in case of illegal get vars. If FALSE, just return default value.
	 * @param String $id (optional) A string to identify this column sorter object
	 */
	function _createFromGet($halt_on_error, $id = 'default') {

		if(!isset($_SESSION['SmartyColumnSort'][$id]['default_column'])) trigger_error('No default column set!', E_USER_ERROR);
		else if(!$_SESSION['SmartyColumnSort'][$id]['default_sort']) trigger_error('No default sort set!', E_USER_ERROR);

		// If both GET vars exists, fetch them.
		if(isset($_GET[$_SESSION['SmartyColumnSort'][$id]['column_var']]) && isset($_GET[$_SESSION['SmartyColumnSort'][$id]['sort_var']])) {

			$_SESSION['SmartyColumnSort'][$id]['current_column'] = $_GET[$_SESSION['SmartyColumnSort'][$id]['column_var']];
			$_SESSION['SmartyColumnSort'][$id]['current_sort'] = strtoupper($_GET[$_SESSION['SmartyColumnSort'][$id]['sort_var']]);

			$column = $_SESSION['SmartyColumnSort'][$id]['current_column'];
			$sort = $_SESSION['SmartyColumnSort'][$id]['current_sort'];

			$error = false;

			if(!is_numeric($column)) $error = true;
			else if($sort != 'DESC' && $sort != 'ASC') $error = true;

			if($error) {
				if($halt_on_error) {
					return false;
				} else {
					$column = $_SESSION['SmartyColumnSort'][$id]['default_column'];
					$sort = strtoupper($_SESSION['SmartyColumnSort'][$id]['default_sort']);
				}
			}
		} else {
			if($halt_on_error && (isset($_GET[$_SESSION['SmartyColumnSort'][$id]['column_var']]) || isset($_GET[$_SESSION['SmartyColumnSort'][$id]['sort_var']])))
				return false;

			$column = $_SESSION['SmartyColumnSort'][$id]['current_column'];
			$sort = strtoupper($_SESSION['SmartyColumnSort'][$id]['current_sort']);
		}

		// Translate get var to DB field name.
		if(!($column = $this->_translateColumns($column, $id))) {
			if($halt_on_error) {
				return false;
			} else {
				$column = $this->_translateColumns($_SESSION['SmartyColumnSort'][$id]['default_column'], $id);
				$sort = strtoupper($_SESSION['SmartyColumnSort'][$id]['default_sort']);
			}
		}

		return $column.' '.$sort;
	}

	/**
	 * @todo Add explanation of what this really does
	 * @param String $id (optional) A string to identify this column sorter object
	 */
	function _translateColumns($columnid, $id = 'default') {
		$array = &$_SESSION['SmartyColumnSort'][$id]['column_array'];

		if(!isset($array[$columnid])) return false;

		$value = $array[$columnid];

		// If value is an array, translated value is in 0, sort order in 1.
		if(!is_array($value)) return $value;
		return $value[0];
	}
}
?>
