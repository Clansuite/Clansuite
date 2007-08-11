<?php date_default_timezone_set('Europe/Berlin'); ?>
   <div id="rightsidebar">
    		<ul>
    		    <!-- Installation Progress BAR -->
    		    <li><h2><?=$language['INSTALL_PROGRESS']?></h2></li>
    		    <li><?=$language['COMPLETED']?> <b><?=$_SESSION['progress']?>%</b>
    		      <div id="progressbar">
    		            <?php 
    		            #note by vain: this fixes a 2pixel problem while displaying the progress bar at 100percent:P
    		            if ($_SESSION['step'] == 7 ) { $_SESSION['progress'] = $_SESSION['progress'] - 2; } 
    		            ?>
                        <div style="border: 1px solid white; height: 5px ! important; width: <?=$_SESSION['progress']?>px; background-color: rgb(181, 0, 22);"/>
                    </div>
                </li> 
    		    
    		    <!-- Clansuite Shortcuts -->
    		    <li><h2><?=$language['SHORTCUTS']?></h2></li>
    		  	<li><strong><a href="http://www.clansuite.com/">Website</a></strong></li>
    		  	<li><strong><a href="http://www.clansuite.com/smf/">Forum</a></strong></li>
    		  	<li><strong><a href="http://www.clansuite.com/smf/">Installsupport</a></strong></li>
    		  	<!--<li><strong><a href="http://www.clansuite.com/wiki/">Help</a></strong></li>-->
    		 
    		    <!-- Change Language -->
    		  	<li><h2><?=$language['CHANGE_LANGUAGE']?></h2></li>
    		    <li>    		    
        		    <?php # pruefen ob es die aktuelle sprache ist, die reloaded werden soll
                          # nur reloaden, wenn neue sprache ausgewaehlt                ?>
                    <select name="lang" style="width: 100px"
                            onchange="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?lang='+this.options[this.selectedIndex].value;" >
                    <?php
                    echo '<option value="">- Select Language -</option>';
                    foreach (new DirectoryIterator('./languages/') as $file) {
                       // get each file not starting with dots ('.','..') 
                       // or containing ".install.php"
                       if ((!$file->isDot()) && preg_match("/.gif$/",$file->getFilename())) 
                       {
                          // the shortest way to show a selected item by vain :D
                          echo '<option style="padding-left: 30px; background-image: url(./languages/' . $file .'); background-repeat: no-repeat;"'; 
                          $file = substr($file->getFilename(), 0, -4);
                          if ($lang == $file) { echo ' selected'; }
                          echo '>';
                          echo $file;
                          echo '</option>\n';
                       }
                    }
                    echo "</select>\n";
                    ?>     
    		    </li>   
    		 </ul>
    </div>
<hr />                
<!-- Fusszeile -->
    <div id="footer">
        <p style="filter:alpha(opacity=65); -moz-opacity:0.65;">
       	    <br />
            &copy; 2005-<?=date("Y"); ?> by Jens-Andr&#x00E9; Koch, Florian Wolf & Clansuite Development Team<br />
         </p>
    </div><!-- Fusszeile ENDE -->
</div><!-- PAGE ENDE -->

</body>
</html>