<?php
/**
 * This smarty function is part of "Koch Framework".
 *
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Renders the breadcrumb navigation.
 *
 * @link Koch_Breadcrumb::getTrail() is used for trail as default.
 *
 * @example
 * {breadcrumbs}
 *
 * @param array  $params
 * @param string $params['title'] If true, renders only the title. If false, renders title with link.
 * @param string $params['trail'] The trail array, default incomming via Koch_Breadcrumb::getTrail().
 * @param string $params['separator'] The separator element between Crumb Elements "&gt;" or "&raquo;".
 * @param string $params['length'] Defines the maximum length of title (rest is truncated).
 * @param object $smarty Smarty Render Engine
 * @return string
 */
function Smarty_function_breadcrumbs($params, $smarty)
{
    // handle trail params set directly to the smarty function call in the template
    if ($params['trail'] !== null && is_array($params['trail'])) {
        $trail = $params['trail'];
    } else {
        $trail = \Koch\View\Helper\Breadcrumb::getTrail();
    }

    #\Koch\Debug\Debug::firebug($trail);

    // is the seperator element set via the smarty function call?
    if ($params['separator'] !== null) {
        $separator = $params['separator'];
    } else { // no, take default seperator
        $separator = ' &gt; ';
    }

    if ($params['length'] !== null) {
        $length = (int) $params['length'];
    } else {
        $length = 0;
    }

    $links = array();

    $trailSize = count($trail);
    for ($i = 0; $i < $trailSize; $i++) {

        if ($length > 0) {
            $title = mb_substr($trail[$i]['title'], 0, $length);
        } else {
            $title = $trail[$i]['title'];
        }

        if (isset($trail[$i]['link']) && $i < $trailSize - 1) {
            // if parameter "title" (only) is not set, give links
            if (isset($params['title']) === false) {
                $links[] = sprintf('<a href="%s" title="%s">%s</a>',
                    htmlspecialchars($trail[$i]['link']),
                    htmlspecialchars($trail[$i]['title']),
                    htmlspecialchars($trail[$i]['title'])
                );
            }
            // if parameter "title" is set, render title only
            else {
                $links[] = $title;
            }
        } else {
            $links[] = $title;
        }
    }

    $breadcrumb_string = join($separator . ' ', $links);

    if (isset($params['assign']) === true) {
        $smarty->assign('breadcrumb',  $breadcrumb_string);
    } else {
        return $breadcrumb_string;
    }
}
