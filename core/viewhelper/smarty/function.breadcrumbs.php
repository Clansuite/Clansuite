<?php
    function smarty_function_breadcrumbs($params, $smarty)
    {
        if (isset($params['trail']) && is_array($params['trail']))
        {
        	$trail = $params['trail'];
        }
        else
        {
        	$trail = array();
        }

        if (isset($params['separator']))
        {
        	$separator = $params['separator'];
        }
        else
        {
        	$separator = ' &gt; ';
        }

        $length = (int) $params['length'];

        $links = array();

        $trailSize = count($trail);
        for ($i = 0; $i < $trailSize; $i++)
        {

            if ($length > 0)
            {
                $title = substr($trail[$i]['title'], 0, $length); 
            }
            else
            {
                $title = $trail[$i]['title'];
            }

            if (isset($trail[$i]['link']) && $i < $trailSize - 1)
            {
                // if parameter heading is not set, give links
                if (!isset($params['title']))
                {
                    $links[] = '<a href="'. $trail[$i]['link'] .'" title="'. htmlspecialchars($trail[$i]['title']). '">'. $title .'</a>';
                }
                // if heading is set, just titles
                else
                {
                    $links[] = $title;
                }
            }
            else
            {
                $links[] = $title;
            }
        }

        #$breadcrumb_string = join($separator . "\n", $links);
        $breadcrumb_string = join($separator . ' ', $links);

        if (isset($params['assign']))
        {
        	$smarty->assign('breadcrumb',  $breadcrumb_string);
        }
        else
        {
        	return $breadcrumb_string;
        }
    }
?>