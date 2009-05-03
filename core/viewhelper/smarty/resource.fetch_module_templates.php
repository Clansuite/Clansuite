<?php
function smarty_fetch_module_templates($resource_type, $resource_name, &$template_source, &$template_timestamp, &$smarty)
{
    $resource_name = substr_replace($resource_name, '/templates', strpos($resource_name,'/'), 0);
    $template = ROOT_MOD.$resource_name;
    
    if (is_readable($template))
    {       
        $tpl = str_replace(ROOT, '', $template);
        $tpl = str_replace( '\\', '/',  $tpl );
        # single slash correction
        $tpl = str_replace("\\", "/",  $tpl);
        # get rid of double slashes
        $tpl = str_replace("//", "/",  $tpl);
        $tpl = str_replace("\\\\", "\\",  $tpl);

        $smarty->assign('templatename',  str_replace(ROOT, '', $tpl));
        $smarty->assign('templatepath', str_replace( '\\', '/', str_replace(ROOT, '', preg_replace('#^(.*)/([^/]+)$#',"\\1", $tpl ))));
        
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
