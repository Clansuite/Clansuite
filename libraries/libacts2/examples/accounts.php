<?php
require("../Absurd.php");

$host = Absurd_TeamSpeak2::connect('tcp://127.0.0.1:51234');
$server = $host->getServerByUdp(8767);

//Superadmin (belong to $host) and Serveradmin accounts (belong to $server)

//Create new SuAdmin
$host->registerAccount('newsuperadmin', 'passwd');

//Create new registered user account
$server->registerAccount('newserveradmin', 'passwd');

//Change the password of the SuAdmin
$host->getAccountByName('newsuperadmin')->changePassword('newpass');

//Set the ServerAdmin flag for the registered user
$server->getAccountByName('newserveradmin')->setServerAdmin(true);

//Delete the SuAdmin account
$host->getAccountByName('newsuperadmin')->delete();