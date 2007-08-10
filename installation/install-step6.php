    <div id="sidebar" id="leftsidebar">
        <div id="stepbar">
            Installationsschritte
            <div class="step-pass"> [1] Sprachauswahl</div>
            <div class="step-pass"> [2] Systemcheck</div>
            <div class="step-pass"> [3] GNU/GPL Lizenz</div>
            <div class="step-pass"> [4] Datenbank</div>
            <div class="step-pass"> [5] Konfiguration</div>
            <div class="step-on">   [6] Administrator</div>
            <div class="step-off">  [7] Abschluss</div>
        </div>
    </div>

    <div id="content" class="narrowcolumn">
    
        <div id="content_footer">
                    
            <div class="accordion">
        	   <h2 class="headerstyle">
        	       <img src="images/64px-Contact-new.svg.png" border="0" align="absmiddle"> 
        	       <?=$language['STEP6_ADMINUSER'] ?>
        	   </h2>
        			
        	   <p>Systemcheck...</p>

            <div class="navigation">
            			<div class="alignleft">
            			 <form action="index.php" name="lang" method="post">
                            <input type="submit" value="<?=$language['BACKSTEP'] ?>" class="button" name="Button2"/>
                            <input type="hidden" name="lang" value="<?=$_SESSION['lang'] ?>">
                            <input type="hidden" name="step" value="<?=$_SESSION['step']-1; ?>">
                         </form>
                        </div>
                        
            			<div class="alignright">
            			 <form action="index.php" name="lang" method="post">
                            <input type="submit" value="<?=$language['NEXTSTEP'] ?>" class="button" name="Button2"/>
                            <input type="hidden" name="lang" value="<?=$_SESSION['lang'] ?>">
                            <input type="hidden" name="step" value="<?=$_SESSION['step']+1; ?>"> 
            			 </form>
            			</div>
                 </div><!-- div navigation end -->
           
        	</div> <!-- div accordion end -->   
    
        </div> <!-- div content_footer end -->

    </div> <!-- div content end -->