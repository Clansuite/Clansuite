<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**

 *
 * Smarty {moveit} outputfilter moves content to several tag positions
 *
 * The x marks the position, the content will be moved to.
 * pre_head_close = x</head>
 * post_body_open = <body>x
 * pre_body_close = x</body>
 *
 * @param string
 * @param Smarty
 * @return string
 */
function smarty_outputfilter_moveit($tpl_output, $smarty)
{
    // PRE_HEAD_CLOSE = x</head>
    $matches = array();
    preg_match_all('!@@@SMARTY:PRE_HEAD_CLOSE:BEGIN@@@(.*?)@@@SMARTY:PRE_HEAD_CLOSE:END@@@!is', $tpl_output, $matches);
    $tpl_output = preg_replace('!@@@SMARTY:PRE_HEAD_CLOSE:BEGIN@@@(.*?)@@@SMARTY:PRE_HEAD_CLOSE:END@@@!is', '', $tpl_output);
    $tpl_output = str_replace('</head>', implode("\n", array_keys(array_flip($matches[1])))."\n".'</head>', $tpl_output);

    // POST_BODY_OPEN = <body>x
    $matches = array();
    preg_match_all('!@@@SMARTY:POST_BODY_OPEN:BEGIN@@@(.*?)@@@SMARTY:POST_BODY_OPEN:END@@@!is', $tpl_output, $matches);
    $tpl_output = preg_replace('!@@@SMARTY:POST_BODY_OPEN:BEGIN@@@(.*?)@@@SMARTY:POST_BODY_OPEN:END@@@!is', '', $tpl_output);
    $tpl_output = str_replace('<body>', '<body>'."\n".implode("\n", array_keys(array_flip($matches[1]))), $tpl_output);

    // PRE_BODY_CLOSE = x</body>
    $matches = array();
    preg_match_all('!@@@SMARTY:PRE_BODY_CLOSE:BEGIN@@@(.*?)@@@SMARTY:PRE_BODY_CLOSE:END@@@!is', $tpl_output, $matches);
    $tpl_output = preg_replace('!@@@SMARTY:PRE_BODY_CLOSE:BEGIN@@@(.*?)@@@SMARTY:PRE_BODY_CLOSE:END@@@!is', '', $tpl_output);
    $tpl_output = str_replace('</body>', implode("\n", array_keys(array_flip($matches[1])))."\n".'</body>', $tpl_output);

    return $tpl_output;
}
