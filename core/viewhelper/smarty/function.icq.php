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
 * {icq icq=152450351}
 * {icq icq=152450351 title="Techi"}
 * </pre>
 *
 * Type:     function<br>
 * Name:     icq<br>
 * Purpose:  display icq status<br>
 * @author   Michal Vrchota <michal.vrchota@seznam.cz>
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 * @param array $params icq and title parameters required
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_icq($params, $smarty)
{
    # be sure icq parameter is present
    if(empty($params['number']))
    {
        $smarty->trigger_error("icq: missing number as parameter");
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
?>