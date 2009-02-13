<?php
require("../Absurd.php");

//libacts2 uses Exceptions to handle errors

try {
    $host = Absurd_TeamSpeak2::connect('tcp://127.0.0.1:51234');
    $server = $host->getServerByUdp(8767);
    $server->login('serveradmin', 'password');
    $server->remove();
} catch (Absurd_Network_Exception $e) {
    echo "Some network thing went wrong: ", $e->getMessage();
} catch (Absurd_TeamSpeak2_Exception $f) {
    echo "Some TS2 thing went wrong: ", $f->getMessage();
} catch (Exception $g) {
    echo "Something else went wrong: ", $g->getMessage();
}

