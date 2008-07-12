<?php
function smarty_fetch_module_templates($resource_type, $resource_name, &$template_source, &$template_timestamp, &$smarty_obj)
{
    //var_dump($smarty_obj->template_dir);
    $resource_name = substr_replace($resource_name, '/templates', strpos($resource_name,'/'), 0);
    $template = ROOT_MOD.$resource_name;
    
    #echo $template; exit;
    
    if (is_readable($template))
    {
        #return false;
        #var_dump($template);
        #$smarty_obj->_current_tpl = $template;
        #$smarty_obj->assign('_current_tpl', str_replace( ROOT, '', $template));
        $smarty_obj->_current_tpl = str_replace(ROOT, '', $template);
        $smarty_obj->_current_tpl = str_replace( '\\', '/',  $smarty_obj->_current_tpl );
        # single slash correction
        $smarty_obj->_current_tpl = str_replace("\\", "/",  $smarty_obj->_current_tpl);
        # get rid of double slashes
        $smarty_obj->_current_tpl = str_replace("//", "/",  $smarty_obj->_current_tpl);
        $smarty_obj->_current_tpl = str_replace("\\\\", "\\",  $smarty_obj->_current_tpl);

        $smarty_obj->assign('_current_tpl',  str_replace(ROOT, '', $smarty_obj->_current_tpl));
        $smarty_obj->assign('_current_path', str_replace( '\\', '/', str_replace(ROOT, '', preg_replace('#^(.*)/([^/]+)$#',"\\1", $smarty_obj->_current_tpl ))));
        
        $template_source    = file_get_contents($template);
        $template_timestamp = filemtime($template);
        return true;
    } 
    else 
    {
        return false;
    }
}
?>