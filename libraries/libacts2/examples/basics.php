<?php
require("../Absurd.php");

//Connection to a TeamSpeak Host with IP-Adress 127.0.0.1 and TCP-Queryport 51234 with maximum timeout of 5s
$host = Absurd_TeamSpeak2::connect('tcp://127.0.0.1:51234', 5);

//------------------------------------------------------------------------------------//

//If you like to split up the adress you may try this
$address = '127.0.0.1';
$tcpport = 51234;

$host = Absurd_TeamSpeak2::connect("tcp://$address:$tcpport");

//Authenticate as SuperAdmin on this host
$host->login('superadmin', 'password');


//Selecting a SubServer and authtenicating as a ServerAdmin on it
$server = $host->getServerByUdp(8767);
$server->login('serveradmin', 'password');
