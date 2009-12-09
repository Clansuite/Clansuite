<?php

/*
* Smarty plugin
* -------------------------------------------------------------
* Type:     function
* Name:     sliding_page
* Purpose:  create a sliding-pager for page browsing
* Version:  0.1
* Date:     April 11, 2004
* Last Modified:    April 11, 2004
* Author:   Mario Witte <mario dot witte at chengfu dot net>
* HTTP:     http://www.chengfu.net/
* -------------------------------------------------------------
*/
function smarty_function_sliding_pager($params, $smarty)
{
    /*
    @param  mixed   $pagecount          - number of pages to browse
    @param  int     $linknum            - max. number of links to show on one page (default: 5)
    @param  int     $curpage            - current page number
    @param  string  baseurl             - baseurl to which the pagenumber will appended
    @param  string  url_append          - text to append to url after pagenumber, e.g. "html" (default: "")
    @param  string  txt_first           - text for link to first page (default: "&&")
    @param  string  txt_prev            - text for link to previous page (default: "&")
    @param  string  separator           - text to print between page numbers (default: "&|&")
    @param  string  txt_next            - text for link to next page (default: "&")
    @param  string  txt_last            - text for link to last page (default: "&&")
    @param  string  txt_skip            - text shown when page s are skipped (not shown) (default: "&...&")
    @param  string  css_class           - css class for the pager (default: "")
    @param  boolean link_current        - whether to link the current page (default: false)
    */
         
    /* Define all vars with default value */
    $linknum = 5;
    $url_append= '';
    $txt_first = '&&';
    $txt_prev  = '&';
    $separator = '&|&';
    $txt_next  = '&';
    $txt_last  = '&&';
    $txt_skip  = '&...&';
    $css_class = '';
    $link_current = false; 

    /* Import parameters */
    extract($params);

    /* Convert page count if array */
    if (is_array($pagecount)) $pagecount = sizeof($pagecount);

    /* Define additional required vars */
    $delta_l = 0;
    $delta_r = 0;
    if ($linknum % 2 == 0) {
        $delta_l = ($linknum / 2 ) - 1;
        $delta_r = $linknum / 2;
    } else {
        $delta_l = $delta_r = ($linknum - 1) / 2;
    }

    /* Check parameters */
    if (empty($pagecount)) {
        $smarty->trigger_error("sliding_pager: missing 'pagecount' parameter");
        return;
    }
    if (empty($curpage)) {
        $smarty->trigger_error("sliding_pager: missing 'curpage' parameter");
        return;
    }
    if (empty($baseurl)) {
        $smarty->trigger_error("sliding_pager: missing 'baseurl' parameter");
        return;
    }

    /* There is no 0th page */
    $curpage = $curpage == 0 ? 1 : $curpage;
   
    /* Internally we need an "array-compatible" index */
    $int_curpage = $curpage - 1;

    /* Paging needed? */
    if ($pagecount <= 1) {
        // No paging needed for one page
        return;
    }

    /* Build all page links (we'll delete some later if required) */
    $links = array();
    for($i = 0; $i < $pagecount; $i++) {
        $links[$i] = $i + 1;
    }

    /* Sliding needed? */
    if ($pagecount > $linknum) { // Yes
        if (($int_curpage - $delta_l) < 1) { // Delta_l needs adjustment, we are too far left
            $delta_l = $int_curpage - 1;
            $delta_r = $linknum - $delta_l - 1;
        }
        if (($int_curpage + $delta_r) > $pagecount) { // Delta_r needs adjustment, we are too far right
            $delta_r = $pagecount - $int_curpage;
            $delta_l = $linknum - $delta_r - 1;
        }
        if ($int_curpage - $delta_l > 1) { // Let's do some cutting on the left side
            array_splice($links, 0, $int_curpage - $delta_l);
        }
        if ($int_curpage + $delta_r < $pagecount) { // The right side will also need some treatment
            array_splice($links, $int_curpage + $delta_r + 2 - $links[0]);
        }
    }

    /* Build link bar */
    $retval = '';
    $css_class = $css_class ? 'class="'.$css_class.'"' : '';
    if ($curpage > 1) {
        $retval .= '<a href="'.$baseurl.'1'.$url_append.'" '.$css_class.'>'.$txt_first.'</a>';
        $retval .= $separator;
        $retval .= '<a href="'.$baseurl.($curpage - 1).$url_append.'" '.$css_class.'>'.$txt_prev.'</a>';
        $retval .= $separator;
    }
    if ($links[0] != 1) {
        $retval .= '<a href="'.$baseurl.'1'.$url_append.'" '.$css_class.'>1</a>';
        if ($links[0] == 2) {
            $retval .= $separator;
        } else {
            $retval .= $txt_skip;
        }
    }
    for($i = 0; $i < sizeof($links); $i++) {
        if ($links[$i] != $curpage or $link_current) {
            $retval .= '<a href="'.$baseurl.$links[$i].$url_append.'" '.$css_class.'>'.$links[$i].'</a>';
        } else {
            $retval .= $links[$i];
        }
        if ($i < sizeof($links) - 1) {
            $retval .= $separator;
        }
    }
    if ($links[sizeof($links) - 1] != $pagecount) {
        if ($links[sizeof($links) - 2] != $pagecount - 1) {
            $retval .= $txt_skip;
        } else {
            $retval .= $separator;
        }
        $retval .= '<a href="'.$baseurl.$pagecount.$url_append.'" '.$css_class.'>'.$pagecount.'</a>';
    }
    if ($curpage != $pagecount) {
        $retval .= $separator;
        $retval .= '<a href="'.$baseurl.($curpage + 1).$url_append.'" '.$css_class.'>'.$txt_next.'</a>';
        $retval .= $separator;
        $retval .= '<a href="'.$baseurl.$pagecount.$url_append.'" '.$css_class.'>'.$txt_last.'</a>';
    }
    return $retval;
}

/* vim: set expandtab: */
/* vim: set ts=4: */

?>