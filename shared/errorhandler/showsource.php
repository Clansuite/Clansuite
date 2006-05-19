<?php
/**
* @project mygosuLib
* @package ErrorHandler
* @version 2.0.0
* @license BSD
* @copyright (c) 2003,2004 Cezary Tomczak
* @link http://gosu.pl/software/mygosulib.html
*/

$file = @$_GET['file'];
$line = @$_GET['line'];
$prev = @$_GET['prev'] ? $_GET['prev'] : 10;
$next = @$_GET['next'] ? $_GET['next'] : 10;

showSource($file, $line, $prev, $next);

/**
* Show source part of the file
* @param string $file Filename
* @param int $line Line to read
* @param int $prev How many lines before main line to read
* @param int $next How many lines after main line to read
* @return string
* @access public
* @package ErrorHandler
*/
function showSource($file, $line, $prev = 10, $next = 10) {
    
    if (!(file_exists($file) && is_file($file))) {
        return trigger_error("showSource() failed, file does not exist `$file`", E_USER_ERROR);
        return false;
    }
    
    //read code
    ob_start();
    highlight_file($file);
    $data = ob_get_contents();
    ob_end_clean();
    
    //seperate lines
    $data  = explode('<br />', $data);
    $count = count($data) - 1;
    
    //count which lines to display
    $start = $line - $prev;
    if ($start < 1) {
        $start = 1;
    }
    $end = $line + $next;
    if ($end > $count) {
        $end = $count + 1;
    }
    
    //color for numbering lines
    $highlight_default = ini_get('highlight.default');
    
    //displaying
    echo '<table cellspacing="0" cellpadding="0"><tr>';
    echo '<td style="vertical-align: top;"><code style="background-color: #FFFFCC; color: #666666;">';
    
    for ($x = $start; $x <= $end; $x++) {
        echo '<a name="'.$x.'"></a>';
        echo ($line == $x ? '<font style="background-color: red; color: white;">' : '');
        echo str_repeat('&nbsp;', (strlen($end) - strlen($x)) + 1);
        echo $x;
        echo '&nbsp;';
        echo ($line == $x ? '</font>' : '');
        echo '<br />';
    }
    echo '</code></td><td style="vertical-align: top;"><code>';
    while ($start <= $end) {
        echo '&nbsp;' . $data[$start - 1] . '<br />';
        ++$start;
    }
    echo '</code></td>';
    echo '</tr></table>';
    
    if ($prev != 10000 || $next != 10000) {
        echo '<br>';
        echo '<a style="font-family: tahoma; font-size: 12px;" href="'.@$_SERVER['PHP_SELF'].'?file='.urlencode($file).'&line='.$line.'&prev=10000&next=10000#'.($line - 15).'">View Full Source</a>';
    }

}

?>