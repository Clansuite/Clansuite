<?php

require("../mail.class.php");

// Instantiate mailer 
$mail = new mailer;

// add stuff to mail
$mail->AddAddress("jakoch@web.de", "Jens-André Koch");
$mail->Subject = "Betreffzeile";
$mail->Body    = "EMail Body mit Text";
$mail->AddAttachment("c:/temp/11-10-00.zip", "new_name.zip");  // optional name

if(!$mail->Send())
{
   echo "There was an error sending the message";
   exit;
}

echo "Message was sent successfully";

?>