<?php
ob_start('ob_gzhandler');
//Example file which creates a simple TS2-Viewer for a whole host

//$address in format tcp://$serverAddress:$tcpQueryPort
$address = "tcp://voice.teamspeak.org:51235";
$udp     = 8767;
?>
<html>
<head>
<meta name="Content-Type" http-equiv="iso-8859-1">
<title>TS2-Viewer</title>
</head>
<body>
<pre>
<?php
require("../Absurd.php");

//Switch to Windows-Client-like sorting (default is Absurd_TeamSpeak2_Object::SORT_LINUX [faster])
define('LIBACTS2_SORTING_TYPE', Absurd_TeamSpeak2_Object::SORT_WINDOWS);

//Define a class which implements the Absurd_TeamSpeak2_Viewer
class SimpleTeamSpeak2Viewer implements Absurd_TeamSpeak2_Viewer
{
    public function displayObject(Absurd_TeamSpeak2_Object $object, array $moreSiblings)
    {
        if (count($moreSiblings)) {
            $lastIcon = array_pop($moreSiblings);
            foreach ($moreSiblings as $lvl) {
                echo ($lvl) ? '&#9474;' : ' ';
            }
            echo ($lastIcon) ? '&#9500;' : '&#9492;';
        }
        echo $object;
        if ($object instanceof Absurd_TeamSpeak2_Client) {
            echo ' (', implode(' ', $object->getFlags()), ')';
        } else if ($object instanceof Absurd_TeamSpeak2_Channel && $object['parent'] == -1) {
            echo ' (', implode('', $object->getFlags()), ')';
        }
        echo "\r\n";
    }
}

$starttime = microtime(true);
Absurd_TeamSpeak2::connect($address)->getServerByUdp($udp)->parseViewer(new SimpleTeamSpeak2Viewer());
$diff = microtime(true) - $starttime;
echo "\r\nGenerated in $diff s";
?>
</pre>
</body>
</html>