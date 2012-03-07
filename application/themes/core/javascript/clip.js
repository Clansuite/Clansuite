/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
 *
 * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 *
 * @link       http://www.clansuite.com
 * @link       http://gna.org/projects/clansuite
 *
 * @version    SVN: $Id$
 */

/**
 * @example
 * <a href="javascript:clip('element_1')">Clip it</a>
 * <span id="element_1" style="display: none;">Stuff</span>
 */
function clip(id)
{
    if(document.getElementById(id).style.display == 'none'){
        document.getElementById(id).style.display = "block";
    }else{
        document.getElementById(id).style.display = "none";
    }
}

/**
 * Handles the there is no attribut "target" xhtml strict problems
 *
 * @example
 * <a href="http://www.clansuite.com/" rel="external">External Link to clansuite.com</a>
 */
function setExternalLinks()
{
    if (!document.getElementsByTagName)
    {
        return null;
    }

    var anchors = document.getElementsByTagName("a");

    for (var i=0;i < anchors.length;i++)
    {
        var anchor = anchors[i];
        if (anchor.getAttribute("href") && anchor.getAttribute("rel") == "external")
        {
            anchor.setAttribute("target", "blank");
        }
    }
}
window.onload = setExternalLinks;