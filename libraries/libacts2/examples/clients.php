<?php
require("../Absurd.php");

$host = Absurd_TeamSpeak2::connect('tcp://127.0.0.1:51234');

//There are a few special functions to work with clients namely
//"kick", "ban", "remove", "setPrivileges" and in some way "message"
//which can be applied also to Channels, Servers or even Hosts
//to affect all clients which somehow belong to the node

//Will kick all users on all servers of the hostsystem
$host->kick('Testing recursion...');

//Will send all users on the server a message
$host->getServerByUdp(8767)->message('Hello there from libacts2');