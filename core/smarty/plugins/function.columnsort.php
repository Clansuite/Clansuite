<?php 
/** 
* Smarty plugin 
* @package Smarty 
* @subpackage plugins 
*/ 

/** 
* Smarty {columnsort} function plugin 
* 
* Type:	 function 
* Name:	 columnsort 
* Purpose:  easy sorting of a html table by columns 
* @param array parameters (cid, html, selected_class, id, asc_image, desc_image) 
* @param Smarty 
* @return string|null 
* @author XML compliance patch by Vaccafoeda on the Smarty forum 
* @author TGKnIght - Modified to allow for unique ids and store in $_SESSION scope 
*/ 
/**
*   modified for clansuite.com by Jens-Andre Koch ( 06-Feb-2007 )
*
*   line 29+30: imagepath hardcoded 
*   and
*   line 76-84: asc_image and desc_image parameterdetection disabled 
*/
function smarty_function_columnsort($params, &$smarty) { 
	static $selected_class = NULL; 
	static $current_id = 0; 

    //static $sort_asc_image = null;
    //static $sort_desc_image = null;

    $sort_asc_image = WWW_ROOT . "/templates/core/images/icons/asc.png"; 
	$sort_desc_image = WWW_ROOT . "/templates/core/images/icons/desc.png"; 

	static $SMCS_id = 'default'; 

	if(isset($params['cid'])) { 
		if ($SMCS_id != $params['cid']) { 
				$current_id = 0; 
		} 

		$SMCS_id = $params['cid']; 
	} 

	// Retrieve the $_SESSION columnsort object. 
	if(!isset($_SESSION['SmartyColumnSort'][$SMCS_id])) { 
		$smarty->trigger_error('columnsort: SmartyColumnSort.class.php needs to be included for columnsort to work.'); 
		return; 
	} 
	$columnsort = $_SESSION['SmartyColumnSort'][$SMCS_id]; 

	// HTML 
	if(!isset($params['html'])) { 
		$smarty->trigger_error("columnsort: missing 'html' parameter."); 
		return; 
	} 
	$html = $params['html']; 
	
	// selected_class 
	if(isset($params['selected_class'])) { 
		$selected_class = $params['selected_class']; 
	} 

	// ID for column table 
	if(isset($params['id'])) { 
		$id = $params['id']; 

		// Increase current id with 1 to prepare for next value 
		$current_id = $id + 1; 
	} else { 
		$id = $current_id++; 
	} 
	
    /* disabled
	if(isset($params['asc_image']) && isset($params['desc_image'])) { 
		// Set asc and desc sort images (will be placed after the sorted column) 
		$sort_asc_image = $params['asc_image']; 
		$sort_desc_image = $params['desc_image']; 
	} else if(isset($params['asc_image']) || isset($params['desc_image']))	{ 
		$smarty->trigger_error('columnsort: Both "asc_image" and "desc_image" needs to be present, or none of them.'); 
	} 
    */
    
	// alt for image 
	if(isset($params['img_alt'])) { 
		 $img_alt = $params['img_alt']; 
	} else { 
		 $img_alt = ""; 
	} 
	

	// Get current sort order for current column id 
	$sort_order = _smarty_columnsort_sort_order($id, $columnsort['column_array'], $columnsort['default_sort'], $smarty); 

	if($sort_order === FALSE) { 
		$smarty->trigger_error('columnsort: too few columns in translate table!'); 
		return; 
	} 

	// The column is selected if the get vars exists and is the current column OR 
	// if the get vars does not exist and the current column is default. 
	if($columnsort['current_column'] !== NULL && $columnsort['current_column'] == $id) { 
		$selected = TRUE; 

		// Reverse sort order for the output. 
		if($columnsort['current_sort']) 
			$sort_order = strtolower($columnsort['current_sort']) == 'asc' ? 'desc' : 'asc'; 
	} else if($columnsort['current_column'] === NULL && $id == $columnsort['default_column']) { 
		$selected = TRUE; 

		// Reverse sort order for the output. 
		$sort_order = $sort_order == 'asc' ? 'desc' : 'asc'; 
	} else { 
		$selected = FALSE; 
	} 

	$columnsort['target_page'] .= (strpos($columnsort['target_page'], '?') !== FALSE ? '&' : '?'); 

    $url = $columnsort['target_page'] . $columnsort['column_var'] . "=$id&" . $columnsort['sort_var'] . "=$sort_order"; 

	// XML compliance patch by Vaccafoeda 
	$url = str_replace('&', '&', $url); 

	$class = $selected && $selected_class ? "class=\"$selected_class\"" : ''; 

	// If asc/desc image exists, append it. 
	if($selected && $sort_asc_image !== NULL) { 
		 $image_src = $sort_order == 'asc' ? $sort_desc_image : $sort_asc_image; 
		 $image = " <img src=\"$image_src\" alt=\"$img_alt\" border=\"0\" />"; 
	} else { 
		 $image = ""; 
	} 

	return "<a $class href=\"$url\"><div style=\"width:100%;padding:0px;margin:0px;text-align:center;\">$html $image</div></a>"; 
} 

function _smarty_columnsort_sort_order($id, $columns, $default_sort, &$smarty) { 
	if(!isset($columns[$id])) return FALSE; 

	if(!is_array($columns[$id])) return $default_sort; 

	if(count($columns[$id]) != 2) { 
		$smarty->trigger_error('columnsort: column array must be array("value", "asc|desc")'); 
		return FALSE; 
	} 

	return $columns[$id][1]; 
} 
?>