<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.fckeditor.php
 * Type:     function
 * Name:     fckeditor
 * Purpose:  outputs a FCKeditor instance
 * -------------------------------------------------------------
 */
function smarty_function_fckeditor($params, $smarty)
{
    // include FCKeditor class
    include FCKEDITOR_PATH . '/fckeditor.php';
    $oFCKeditor = new FCKeditor($params['name']);
    $oFCKeditor->BasePath = '/lib/fckeditor/';
    $oFCKeditor->Value = $params['value'];
    
    // get FCKeditor HTML code
    ob_start();
    $oFCKeditor->Create();
    $buffer = ob_get_contents();
    ob_end_clean();
    
    // add wrapper div
    $buffer = '<div class="editor">' . $buffer . '</div>';
    
    return $buffer;
}

?>
