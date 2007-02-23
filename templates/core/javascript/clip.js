/**
 * File is part of Clansuite (http://www.clansuite.com)
 * COPYRIGHT: (c) 2005,2006 Jens-Andre Koch, Florian Wolf
 * LICENSE:   GPL2
 *
 * Example Use:
 * <a href="javascript:clip('element_1')">Clip it</a>
 * <span id="element_1" style="display: none;">Stuff</span>
 */
function clip(id){   
if(document.getElementById(id).style.display == 'none')
{document.getElementById(id).style.display = "block";}
else
{document.getElementById(id).style.display = "none";}
}