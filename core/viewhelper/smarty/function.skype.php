<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty skype status function plugin
 * Display skype status (unknown, offline, online, away, not_available, do_not_disturb, skype_me)
 * Type:     function<br>
 * Name:     skype_status<br>
 * Date:     2009.09.16.<br>
 *
 * Examples:
 * <pre>
 * {skype username=myusername}
 * <img src="{skype username=myusername}.jpg" alt="" />
 * </pre>
 *
 * Type:     function<br>
 * Name:     skype<br>
 * Purpose:  display skype status<br>
 * @author   László Kovács <info@laszlokovacs.com>
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 * @param array $params username parameter required
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_skype($params, &$smarty)
{
    if(empty($params['username']))
    {
        $smarty->trigger_error("skype: missing skype parameter");
        return;
    }

    $cUrl = curl_init();
    curl_setopt($cUrl, CURLOPT_URL, 'http://mystatus.skype.com/'.$params['username'].'.num');
    curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($cUrl, CURLOPT_TIMEOUT, 5);

    $status_code = trim(curl_exec($cUrl));
    curl_close($cUrl);

    $status_code = intval($status_code);

    switch ($status_code)
    {
        case 0:
            return "unknown";
        case 1:
            return "offline";
        case 2:
            return "online";
        case 3:
            return "away";
        case 4:
            return "not_available";
        case 5:
            return "do_not_disturb";
        case 6:
            return "offline";
        case 7:
            return "skype_me";
    }
}
?>