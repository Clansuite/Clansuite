<?php
# Security Handler
if (defined('IN_CS') == false)
{ 
    die( 'Clansuite not loaded. Direct Access forbidden.' );
}
?>

<div id="sidebar">
<div id="stepbar">
<p><?php echo $language['MENU_HEADING']; ?></p>
<?php
for($i = 1; $i <= $total_steps; $i++)
{
    if($i < $step )
    {
        $classValue = 'step-pass';
    }
    elseif($i == $step )
    {
        $classValue = 'step-on';
    }
    elseif($i > $step )
    {
        $classValue = 'step-off';
    }

    echo '<div class="'.$classValue.'">'. $language['MENUSTEP'.$i] . '</div>';
}
?>
</div>
</div>