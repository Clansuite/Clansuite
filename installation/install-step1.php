    <div id="sidebar">
        <div id="stepbar">
            <?=$language['MENU_HEADING']?>
            <div class="step-on"><?=$language['MENUSTEP1']?> </div>
            <div class="step-off"><?=$language['MENUSTEP2']?></div>
            <div class="step-off"><?=$language['MENUSTEP3']?></div>
            <div class="step-off"><?=$language['MENUSTEP4']?></div>
            <div class="step-off"><?=$language['MENUSTEP5']?></div>
            <div class="step-off"><?=$language['MENUSTEP6']?></div>
            <div class="step-off"><?=$language['MENUSTEP7']?></div>
        </div>
    </div>
    <div id="content" class="narrowcolumn">
        <div id="content_footer">
            <div class="accordion">
                <h2 class="headerstyle">
        	        <img src="images/64px-Tango_Globe_of_Letters.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
        	        <?=$language['STEP1_LANGUAGE_SELECTION']?>
        	    </h2>
        	    <p><strong><?=$language['STEP1_WELCOME']?></strong></p>
        	    <p><?=$language['STEP1_APPINSTALL_STEPWISE']?></p>
        	    <p><?=$language['STEP1_CHOOSELANGUAGE']?></p>
        	    <form action="index.php" name="lang" method="post">
        	        <p>
        	            <input type="hidden" name="lang" value="<?=$_SESSION['lang']?>" />

                <?php # @todo: nur reloaden, wenn eine neue sprache ausgewaehlt ?>
                        <select name="lang" style="width: 160px"
                            onchange="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?lang='+this.options[this.selectedIndex].value;" >
                <?php
                echo '<option value="">- Select Language -</option>';
                foreach (new DirectoryIterator('./languages/') as $file) {
                   // get each file not starting with dots ('.','..')
                   // or containing ".install.php"
                   if ((!$file->isDot()) && preg_match("/.install.php$/",$file->getFilename()))
                   {
                      $file = substr($file->getFilename(), 0, -12);
                      // the shortest way to show a selected item by vain :D
                      echo '<option';
                      if ($lang == $file) { echo ' selected="selected"'; }
                      echo '>';
                      echo $file;
                      echo "</option>\n";
                   }
                }
                echo "</select>\n";
                ?>
                    </p>
        	        <div class="navigation">
        	            <hr />
        	            <span style="font-size:10px;"><?=$language['CLICK_NEXT_TO_PROCEED']?></span>
                        <div class="alignright">
                            <input type="submit" value="<?=$language['NEXTSTEP']?>" class="button" name="ButtonNext" />
                            <input type="hidden" name="step" value="<?=$_SESSION['step']+1 ?>" />
                        </div>
                    </div><!-- div navigation end -->
                </form>
        	</div> <!-- div accordion end -->
        </div> <!-- div content_footer end -->
    </div> <!-- div content end -->