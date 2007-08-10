<?php date_default_timezone_set('Europe/Berlin'); ?>
   <div id="rightsidebar">
    		<ul>
    		    <li><h2>Installation Progress</h2></li>
    		    <li>Completed <b><?=$_SESSION['progress'] ?>%</b>
    		      <div id="progressbar">
    		            <?php 
    		            #note by vain: this fixes a 2pixel problem while displaying the progress bar at 100percent:P
    		            if ($_SESSION['step'] == 7 ) { $_SESSION['progress'] = $_SESSION['progress'] - 2; } 
    		            ?>
                        <div style="border: 1px solid white; height: 5px ! important; width: <?=$_SESSION['progress'] ?>px; background-color: rgb(181, 0, 22);"/>
                    </div>
                </li>
    		  	<li><h2>Clansuite Shortcuts</h2></li>
    		  	<li><strong><a href="http://www.clansuite.com/">Website</a></strong></li>
    		  	<li><strong><a href="http://www.clansuite.com/smf/">Forum</a></strong></li>
    		  	<li><strong><a href="http://www.clansuite.com/smf/">Installsupport</a></strong></li>
    		  	<!--<li><strong><a href="http://www.clansuite.com/wiki/">Help</a></strong></li>-->
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