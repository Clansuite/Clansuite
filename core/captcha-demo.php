<?php
/**
 * Example & Demonstration of Easy Captcha Class
 * @author Jens-André Koch <jakoch@web.de>
 * @license LGPL
 */

/*
 *  Start a Session 
 */
session_start();

if(isset($_POST['userstring']) && $_POST['userstring'] == $_SESSION['captchastr']){
$MESSAGE_TYPE = 'correct';
$MESSAGE = 'CODE CORRECT';
}
else {
$MESSAGE_TYPE = 'error';
$MESSAGE = 'CODE FALSE';
}
var_dump($_POST);

$CAPTCHA_IMAGE_URI = 'captcha.class.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Easy-Captcha-Class :: Demonstration of Usage </title>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1" />
<meta NAME="Description" CONTENT="Demonstration of Easy Captch Class." />
<style type="text/css">
#captcha-status	{ display: none;	}
#error 	 #captcha-status{ font-weight: bold;	color: red;		text-transform: capitalize;	}
#correct #captcha-status{ font-weight: bold;	color: green;	text-transform: capitalize;	}
</style>
</head>
<div id="<?php echo $MESSAGE_TYPE; ?>">
<span id="captcha-status"><?php echo $MESSAGE_TYPE; ?>: </span> <?php echo $MESSAGE; ?>
</div>
<div style="border:2px solid #666666; margin: 10px; padding: 10px; width:400px;">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <p><img src="<?php echo $CAPTCHA_IMAGE_URI; ?>" border="1">  </p>
      <p>Please enter the code shown and submit.
        <input type="text" size="15" maxlength="8" name="userstring" value="???" />
        <input type="submit" name="check" value="submit" />
      </p>
    </form>
  </div>
</body>
</html>  