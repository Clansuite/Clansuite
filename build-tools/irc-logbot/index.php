<?php

    include("header.inc.php");

  # array with name of months
     $monate = array("01" => "Januar",
                     "02" => "Februar",
                     "03" => "M&auml;rz",
                     "04" => "April",
                     "05" => "Mai",
                     "06" => "Juni",
                     "07" => "Juli",
                     "08" => "August",
                     "09" => "September",
                     "10" => "Oktober",
                     "11" => "November",
	             "12" => "Dezember");
    
    $back_year = 0;
    $back_monat = 0;
  
  
    $date = $_GET['date'];
    if (isset($date) && preg_match("/^\d\d\d\d-\d\d-\d\d$/", $date)) {
?>

    <p>
     <a href="./">Index</a>
    </p>

    <h2>IRC Log for <?php echo($date); ?></h2>
    <p>
     Timestamps are in GMT/BST.
    </p>
    <p>
    
<?php
        readfile($date . ".log");
?>
    </p>
<?php
    }
    else {
        $dir = opendir(".");
        while (false !== ($file = readdir($dir))) {
            if (strpos($file, ".log") == 10) {
                $filearray[] = $file;
            }
        }
        closedir($dir);
        
        rsort($filearray);
?>
    <ul>
    Year: <a href='#2009'>2009</a> 
	  <a href='#2008'>2008</a>
<?php
        
        
        foreach ($filearray as $file) {
            $file = substr($file, 0, 10);

	$jahr = substr($file, 0, 4);
	if($jahr != $back_year) {
			          echo "</blockquote></blockquote><h3><a name='". $jahr."'>".$jahr."</a></h3>";
				  
				  echo '<blockquote>Monate:';
				  foreach($monate as $monats_nr => $monats_name)
				  {?>
				      <a href="#<?php echo $monats_nr; ?>"><?php echo $monats_name;?></a>
				  <?php
				  }
				  ?> </blockquote> <?php
				  $back_year = $jahr; 
				}

	$monat = substr($file, 5, 2);
	if($monat != $back_monat)
	{
			?>
			<blockquote><h4><a name="<?php echo $monat;?>"><?php echo $monate[$monat];?></a></h4>
			<blockquote>
			<?php
			$back_monat = $monat;
	}
       
?>		
        <li><a href="<?php echo($_SERVER['PHP_SELF'] . "?date=" . $file); ?>"><?php echo substr($file,8,2).' '.$monate[$monat].' '.$jahr; ?></a></li>
	
<?php
	if($monat != $back_monat && $jahr != $back_year){ echo '</blockquote>'; }
        }
?>
    </ul>
<?php
    }

    include("footer.inc.php");

?>