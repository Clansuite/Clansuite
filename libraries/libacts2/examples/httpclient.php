<?php
require('../Absurd.php');

//Create a webinterface connection
$http = Absurd_TeamSpeak2::httpConnect('tcp://localhost:14534');


//Login as a serveradmin on server with UDP-Port 8767
$http->login('serveradmin', 'password', 8767);

//Login as a superadmin and select subserver with ID 1
$http->login('superadmin', 'password');
$http->select(1);

//Set the global webpost URL to http://localhost/webpost in the host settings
$http->writeHostSettings(array('webpost_posturl' => 'http://localhost/webpost'));

//Print the full array of Server Settings
print_r($http->readServerSettings());

//Prevent Server Admins from revoking other users Server Admin rights
$http->writeGroupPermissions(Absurd_TeamSpeak2_HTTPClient::SERVER_ADMIN, array('ugSA_upPrivilegeRevokeSA' => 0));