<?php
/**
 * Security Handler
 */
if (defined('IN_CS') == false){ die( 'Clansuite not loaded. Direct Access forbidden.' );}
?>
    <div id="content" class="narrowcolumn">
        <div id="content_middle">
            <div class="accordion">
                <h2 class="headerstyle">
                    <img src="images/64px-Tango_Globe_of_Letters.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP1_LANGUAGE_SELECTION']; ?>
                </h2>
                <p><strong><?php echo $language['STEP1_WELCOME']; ?></strong></p>
                <p><?php echo $language['STEP1_THANKS_CHOOSING']; ?></p>
                <p><?php echo $language['STEP1_APPINSTALL_STEPWISE']; ?></p>
                <p><?php echo $language['STEP1_CHOOSELANGUAGE']; ?></p>
                <form action="index.php" name="lang" method="post">
                    <p>
                        <input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>" />
                        <?php # @todo nur reloaden, wenn eine neue sprache ausgewaehlt ?>
                        <select name="lang" style="width: 160px"
                            onchange="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?lang='+this.options[this.selectedIndex].value;" >
                        <?php
                        echo '<option value="">- Select Language -</option>';
                        foreach (new DirectoryIterator('./languages/') as $file) {
                           // get each file not starting with dots ('.','..')
                           // or containing ".install.php"
                           if ((!$file->isDot()) && preg_match("/.gif$/",$file->getFilename()))
                           {
                              // the shortest way to show a selected item by vain :D
                              echo '<option style="padding-left: 40px; background-image: url(./languages/' . $file .'); background-position:5px 100%; background-repeat: no-repeat;"';
                              $file = substr($file->getFilename(), 0, -4);
                              if ($_SESSION['lang'] == $file) { echo ' selected="selected"'; }
                              echo '>';
                              echo $file;
                              echo "</option>\n";
                           }
                        }
                        echo "</select>\n";
                        ?>
                    </p>
                    <div id="content_footer">
                        <div class="navigation">
                            <span style="font-size:10px;"><?php echo $language['CLICK_NEXT_TO_PROCEED']; ?></span>
                            <div class="alignright">
                                <input type="submit" value="<?php echo $language['NEXTSTEP']; ?>" class="ButtonGreen" name="step_forward" />
                            </div>
                        </div> <!-- div navigation end -->
                    </div> <!-- div content_footer end -->
                </form>
            </div> <!-- div accordion end -->
        </div> <!-- div content_middle end -->
     </div> <!-- div content end -->