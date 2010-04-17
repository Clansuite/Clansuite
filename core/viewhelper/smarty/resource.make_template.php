<?php
function smarty_make_template($resource_type, $resource_name, &$template_source, &$template_timestamp, $smarty_obj)
{
    if( $resource_type == 'file' ) {
        if ( ! is_readable ( $resource_name )) {
            // create the template file, return contents.
            $template_source = "This is a new template.";
            if( !function_exists('smarty_core_write_file') ) { include SMARTY_CORE_DIR . 'core.write_file.php' ); }
            smarty_core_write_file( array( 'filename'=>$smarty_obj->template_dir . DIRECTORY_SEPARATOR . $resource_name, 'contents'=>$template_source ), $smarty_obj );
            return true;
        }
    } else {
        // not a file
        return false;
    }
}
?>