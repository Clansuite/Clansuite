<?php
?>
    <div id="content" class="narrowcolumn">
        <div id="content_middle">
            <div class="accordion">
                <h2 class="headerstyle">
                    <img src="assets\images/64px-Tango_Globe_of_Letters.svg.png" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP1_LANGUAGE_SELECTION']; ?>
                </h2>
                <p><strong><?php echo $language['STEP1_WELCOME']; ?></strong></p>
                <p><?php echo $language['STEP1_THANKS_CHOOSING']; ?></p>
                <p><?php echo $language['STEP1_APPINSTALL_STEPWISE']; ?></p>
                <p><?php echo $language['STEP1_CHOOSELANGUAGE']; ?></p>
                <form action="index.php" name="lang" method="post">
                    <p>
                        <input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>" />
                        <?php // @todo nur reloaden, wenn eine neue sprache ausgewaehlt ?>
                        <select title="<?php echo $language['SELECT_LANGUAGE']; ?>" name="lang" style="width: 160px"
                            onchange="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?lang='+this.options[this.selectedIndex].value;" >
                        <?php
                        echo '<option value="">- Select Language -</option>';
                        foreach (new DirectoryIterator('./Languages/') as $file) {
                           // get each php file in Languages folder, exclude stuff starting with dots
                           if ((!$file->isDot()) && preg_match("/.php$/",$file->getFilename())) {
                              // get the filename without extension
                              $file = substr($file->getFilename(), 0, -4);
                              // build image name
                              $flag_image = strtolower($file).'.png';
                              // if an image exists, add it as inline css style
                              if (is_file('./Languages/' . $flag_image)) {
                                echo '<option style="padding-left: 30px; background-image: url(./Languages/' . $flag_image .'); background-position:5px 100%; background-repeat: no-repeat;"';
                              } else {
                                echo '<option';
                              }
                              if ($_SESSION['lang'] == $file) { echo ' selected="selected"'; }
                              echo ' value=' . $file .'>';
                              echo $file;
                              echo "</option>\n";
                           }
                        }
                        echo "</select>\n";
                        ?>
                    </p>
                    <div id="content_footer">
                        <div class="navigation">
                            <span class="font-10"><?php echo $language['CLICK_NEXT_TO_PROCEED']; ?></span>
                            <div class="alignright">
                                <input type="submit" value="<?php echo $language['NEXTSTEP']; ?>" class="ButtonGreen" name="step_forward" />
                                <input type="hidden" name="submitted_step" value="1" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
     </div>
