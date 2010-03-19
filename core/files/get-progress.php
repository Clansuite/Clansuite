<?php

/**
 * This returns the Uploadprogress via APC as a rounded number or json array.
 *
 * At first we prevent caching in the browser. The headers have to be updated each time,
 * else the progress bar gets stuck. Then we use the uniqueID to call the apc_fetch method.
 * It will return an data-array for the file tracked. The next step is to calculate the
 * "percentage" value. It's done by using "current" and "total" from the data-array.
 * It's also possible to return the whole status array json encoded. 
 *
 * @return $int upload progress value with range 1-100.
 */

header('Expires: Mon, 19 Apr 1980 12:00:00 GMT');
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $status = apc_fetch('upload_'.$_POST['APC_UPLOAD_PROGRESS']);
    #echo round($status['current']/$status['total']*100);    
    $status['done']=1;
    # direct encoding of the whole data-array with json
    echo json_encode($status);
}
elseif(isset($_GET['uniqueID']))
{
    $status = apc_fetch('upload_' . $_GET['uniqueID']);   
    #echo round($status['current']/$status['total']*100);
    
    # direct encoding of the whole data-array with json
    echo json_encode($status);
}

# debugging the status array
# var_dump($status); exit;
?>