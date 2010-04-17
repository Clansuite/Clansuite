<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * This smarty function is part of "Clansuite - just an eSports CMS"
 * @link http://www.clansuite.com
 *
 * @author Jens-André Koch <jakoch@web.de>
 * @copyright Copyright (C) 2009 Jens-André Koch
 * @license GNU Public License (GPL) v2 or any later version
 * @version SVN $Id$
 *
 * Name:         doctype
 * Type:         function
 * Purpose: This TAG inserts the (x)html doctype header.
 *
 *
 * Parameters:
 * - doctype     = XHTMl or HTML
 * - level       = HTML  = Strict, Transitional, Frameset
 *                 XHTML = Strict, Transitional, Frameset, + 1.1, 1.1+, Basic1.1
 *
 * Example:
 * {doctype doctype=XHTML level=Transitional}
 *
 * @param array $params as described above (emmail, size, rating, defaultimage)
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_doctype($params, $smarty)
{
    if( !isset( $params['doctype'] ))
    {
        $smarty->trigger_error("Parameter 'familiy' not specified! familiy=HTML or XHTML.");
        return;
    }

    if( !isset( $params['level'] ))
    {
        $smarty->trigger_error("Parameter 'level' or 'htmlTemplate' not specified!");
        return;
    }

    /**
     * DOCTYPES Array
     *
     * @var array $DTDS contains serveral DOCTYPE definitions
     */

    $DTDS = array(
        'HTML' => array(
        'Strict' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">'
            )
        , 'Transitional' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'
            )
        , 'Frameset' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">'
            )
        )
    , 'XHTML' => array(
        'Strict' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'
            )
        , 'Transitional' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
            )
        , 'Frameset' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">'
            )
        , '1.1' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'
            )
        , '1.1+' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0 plus SVG 1.1//EN" "http://www.w3.org/2002/04/xhtml-math-svg/xhtml-math-svg.dtd">'
            )
        , 'Basic1.1' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">'
            )
        )
    );

    if (array_key_exists($params['doctype'], $DTDS) && array_key_exists($params['level'], $DTDS[$params['doctype']]))
    {
        $dtd = $DTDS[$params['doctype']][$params['level']]['signature'];
    }

    return $dtd."\n";
}
?>