<?php
/**
 * Security Handler
 */
if (defined('IN_CS') == false){ die( 'Clansuite not loaded. Direct Access forbidden.' );}
?>
<script src="javascript/webtoolkit.sha1.js" type="application/javascript"></script>
<script>
    function hashLoginPassword(theForm)
    {
        if( (theForm.admin_password.value  != '') )
        {
                theForm.admin_password.value  = SHA1(theForm.admin_password.value);
                return true;
        }
    }
</script>
    <div id="content" class="narrowcolumn">
        <div id="content_middle">
            <div class="accordion">
                <h2 class="headerstyle">
                    <img src="images/64px-Contact-new.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP6_ADMINUSER']?>
                </h2>
                <p><?php echo $language['STEP6_SENTENCE1']?></p>
                <p><?php echo $language['STEP6_SENTENCE2']?></p>
                <p><?php echo $language['STEP6_SENTENCE3']?></p>
                <form action="index.php" method="post" name="admin_form" id="admin_form" onsubmit="return hashLoginPassword(this);">
                    <ol class="formular">
                        <li>
                            <label class="formularleft" for="admin_name"><?php echo $language['STEP6_ADMIN_NAME']?></label>
                            <input class="formularright" type="text" id="admin_name" name="admin_name" value="<?php echo $values['admin_name']?>" />
                        </li>
                        <li>
                            <label class="formularleft" for="admin_password"><?php echo $language['STEP6_ADMIN_PASSWORD']?></label>
                            <input class="formularright" type="password" id="admin_password" autocomplete="off" name="admin_password" value="<?php echo $values['admin_password']?>" />
                        </li>
                        <li>
                            <label class="formularleft" for="admin_email"><?php echo $language['STEP6_ADMIN_EMAIL']?></label>
                            <input class="formularright" type="text" id="admin_email" name="admin_email" value="<?php echo $values['admin_email']?>" />
                        </li>
                        <li>
                            <label class="formularleft" for="admin_language"><?php echo $language['STEP6_ADMIN_LANGUAGE']?></label>
                             <select class="formularright" id="admin_language" name="admin_language" style="width: 150px"
                            <?php
                                echo '<option value="">- Select Language -</option>';
                                foreach (new DirectoryIterator('./languages/') as $file) {
                                   // get each file not starting with dots ('.','..')
                                   // or containing ".install.php"
                                   if ((!$file->isDot()) && preg_match("/.gif$/",$file->getFilename()))
                                   {
                                      echo '<option style="padding-left: 30px; background-image: url(./languages/' . $file .'); background-position:5px 100%; background-repeat: no-repeat;"';
                                      $file = substr($file->getFilename(), 0, -4);

                                      # filename conversion to shorthand
                                      if($file == 'german' ) { $language_shorthand = 'de_DE'; }
                                      if($file == 'english') { $language_shorthand = 'en_EN'; }

                                      if ($values['admin_language'] == $language_shorthand) { echo ' selected="selected"'; }
                                      echo '>';
                                      echo $file;
                                      echo "</option>\n";
                                   }
                                }
                            ?>
                            </select>
                        </li>
                    </ol>
                    <div id="content_footer">
                        <div class="navigation">
                            <span style="font-size:10px;">
                                <?php echo $language['CLICK_NEXT_TO_PROCEED']?><br />
                                <?php echo $language['CLICK_BACK_TO_RETURN']?>
                            </span>
                            <div class="alignright">
                                <input type="submit" value="<?php echo $language['NEXTSTEP']?>" class="ButtonGreen" name="step_forward" />
                            </div>
                            <div class="alignleft">
                                <input type="submit" value="<?php echo $language['BACKSTEP']?>" class="ButtonRed" name="step_backward" />
                                <input type="hidden" name="lang" value="<?php echo $_SESSION['lang']?>" />
                            </div>
                        </div><!-- div navigation end -->
                    </div> <!-- div content_footer end -->
                </form>
            </div> <!-- div accordion end -->
        </div> <!-- div content_middle end -->
    </div> <!-- div content end -->