<?php

function smarty_function_icon($params, &$smarty)
{
    #clansuite_xdebug::printR($params);

    extract($params);

    /*
    if (empty($src) xor empty($name) )
    {
        $smarty->trigger_error('assign_array: the parameter "src" or "name" is missing. Please add src="www_path_to_your_file" or name="imagename" to your template.');
        return;
    }*/

    # transform name into a valid image src
    if ( empty($src) and (empty($name) == false))
    {
        $src = WWW_ROOT_THEMES_CORE . '/images/icons/'.$name.'.png';
    }

    # we got no height, set it to zero
    if (empty($height))
    {
        $height = 0;
    }

    # we got no width, ok then its zero again
    if (empty($width))
    {
        $width = 0;
    }

    # we got no height nor width. well let's detect it automatically then.
    if (($height == 0) or ($width == 0))
    {
        $currentimagesize = getimagesize($src);
        $width = $currentimagesize[0];
        $height= $currentimagesize[1];
    }

    # we got no alternative text. let's add a default text with $name;
    if(empty($alt))
    {
        $alt = 'Clansuite Icon: '.$name;
    }

    # no extra attributes to add, then let it be an empty string
    if(empty($extra))
    {
        $extra = '';
    }

    $html = "<img src='$src' height='$height' width='$width' alt='$alt' $extra />";

    return $html;
}
?>