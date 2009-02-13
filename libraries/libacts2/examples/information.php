<?php
require("../Absurd.php");

$host = Absurd_TeamSpeak2::connect('tcp://127.0.0.1:51234');

//Getting a single value
echo $host['total_server_version'];


//Getting the full array of information of an object
$fullInfo = $host->getNodeInfo();

print_r($fullInfo);

//This is possible with any object (Host, Server, Channel, Client)
$user = $host->getServerByUdp(8767)->getClientByName('Steven');

echo $user['ping'];