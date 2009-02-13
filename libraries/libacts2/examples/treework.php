<?php
require("../Absurd.php");

//We begin with the root of tree which is the Host
$host = Absurd_TeamSpeak2::connect('tcp://127.0.0.1:51234');

//To get the Server we do e.g.
$server = $host->getServerByUdp(8767);

//To get the channel with ID 1 we could either do now
$channel = $server->getChannelById(1);

//or without the line $server = $host->getServerByUdp(8767); we could directly use
$channel = $host->getServerByUdp(8767)->getChannelById(1);

//we even could use
$channel = Absurd_TeamSpeak2::connect('tcp://127.0.0.1:51234')->getServerByUdp(8767)->getChannelById(1);

//To go through all children of a node (Host, Server or Channel) we do
foreach ($host as $server) {
    echo $server;
}
//that will list us all servers of a hostsystem

//To go recursively (including also children of children and children of children of ...)
$iterator = new RecursiveIteratorIterator($server, RecursiveIteratorIterator::SELF_FIRST);
foreach ($iterator as $node) {
    echo str_repeat(' ', $iterator->getDepth()), $node, "\n";
}
