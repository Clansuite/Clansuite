<?php
    function smarty_function_breadcrumbs($params, &$smarty)
    {
        if (isset($params['trail']) && is_array($params['trail']))
            $trail = &$params['trail'];
        else
            $trail = array();
 
        if (isset($params['separator']))
            $separator = $params['separator'];
        else
            $separator = ' &gt; ';
 
        $length = (int) $params['length'];
 
        $links = array();
 
        $trailSize = count($trail);
        for ($i = 0; $i < $trailSize; $i++) {
            if ($length > 0) {
                require_once $smarty->_get_plugin_filepath('modifier', 'truncate');
                $title = smarty_modifier_truncate($trail[$i]['title'], $length);
            }
            else
                $title = $trail[$i]['title'];
 
            if (isset($trail[$i]['link']) && $i < $trailSize - 1)
                $links[] = '<a href="' . $trail[$i]['link'] . '" title="'
                         . htmlSpecialChars($trail[$i]['title'])
                         . '">' . $title . '</a>';
            else
                $links[] = $title;
        }
 
        return join($separator . "\n", $links);
    }
?>