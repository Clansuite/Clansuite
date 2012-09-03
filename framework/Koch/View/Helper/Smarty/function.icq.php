<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty icq function plugin
 * Display ICQ status online/offline/unknown
 *
 * Examples:
 * <pre>
 * {icq icq=123456}
 * {icq icq=123456 title="username"}
 * </pre>
 *
 * Type:     function<br>
 * Name:     icq<br>
 * Purpose:  display icq status<br>
 *
 * @param array $params icq and title parameters required
 * @param Smarty $smarty
 * @return string
 */
function Smarty_function_icq($params, $smarty)
{
    // be sure icq parameter is present
    if (empty($params['number'])) {
        trigger_error('icq: missing the icq "number" as parameter');

        return;
    }

    $icq = $params['number'];
    $title = htmlspecialchars($params['title']);

    $html = '';
    $html .= '<span style="white-space: nowrap;">';
    $html .= '<a href="http://wwp.icq.com/scripts/contact.dll?msgto='.$icq.'">';
    $html .= '<img src="http://web.icq.com/scripts/online.dll?icq=' . $icq . '&amp;img=5" alt="'.$title.'" /></a>'.$title.'</span>';

    return $html;
}
