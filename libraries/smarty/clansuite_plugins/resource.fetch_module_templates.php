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
        $smarty_obj->_current_tpl = $template;
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