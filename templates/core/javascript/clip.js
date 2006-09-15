/*
 * DO NOT REMOVE THIS NOTICE
 *
 * PROJECT:   clansuite
 * VERSION:   0.1
 * COPYRIGHT: (c) 2005,2006 Jens-Andre Koch, Florian Wolf
 * LINK:      http://www.clansuite.com
 * LICENSE:   GPL2
 *
 *
 * Example Use:
 *
 * <a href="javascript:clip_span('1')">Clip it</a>
 *
 * <span id="span_1" style="display: none;">Stuff</span>
 *
 */

function clip_span(id)
{
    if(document.getElementById("span_" + id).style.display == 'none')
    {
        document.getElementById("span_" + id).style.display = "block";
    }
    else
    {
        document.getElementById("span_" + id).style.display = "none";
    }
}

function clip_id(id)
{
    if(document.getElementById(id).style.display == 'none')
    {
        document.getElementById(id).style.display = "block";
    }
    else
    {
        document.getElementById(id).style.display = "none";
    }
}