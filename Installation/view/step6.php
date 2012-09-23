<script src="assets/javascript/webtoolkit.sha1.js" type="text/javascript"></script>
<script>
    function hashLoginPassword(theForm) {
        if ((theForm.admin_password.value != '')) {
          theForm.admin_password.value = SHA1(theForm.admin_password.value);

          return true;
        }
    }
</script>
    <div id="content" class="narrowcolumn">
        <div id="content_middle">
            <div class="accordion">
                <h2 class="headerstyle">
                    <img src="assets/images/64px-Contact-new.svg.png" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP6_ADMINUSER']; ?>
                </h2>
                <?php if (!empty($error)) { ?>
                <div class="error_red">
                    <strong><?php echo $error ?></strong>
                </div>
                <?php } ?>
                <p><?php echo $language['STEP6_SENTENCE1']; ?></p>
                <p><?php echo $language['STEP6_SENTENCE2']; ?></p>
                <p><?php echo $language['STEP6_SENTENCE3']; ?></p>
                <form action="index.php" method="post" name="admin_form" id="admin_form" onsubmit="return hashLoginPassword(this);">
                <fieldset>
                    <legend><?php echo $language['STEP6_LEGEND']; ?></legend>
                    <ol class="formular">
                        <li>
                            <label class="formularleft" for="admin_name"><?php echo $language['STEP6_ADMIN_NAME']; ?></label>
                            <input class="formularright" type="text" id="admin_name"
                                   name="admin_name"
                                   title="<?php echo $language['STEP6_ADMIN_NAME_TOOLTIP']; ?>"
                                   value="<?php echo $values['admin_name']; ?>" />
                        </li>
                        <li>
                            <label class="formularleft" for="admin_password"><?php echo $language['STEP6_ADMIN_PASSWORD']; ?></label>
                            <input class="formularright" type="password" id="admin_password"
                                   autocomplete="off"
                                   name="admin_password"
                                   title="<?php echo $language['STEP6_ADMIN_PASSWORD_TOOLTIP']; ?>"
                                   value="<?php echo $values['admin_password']; ?>" />
                        </li>
                        <li>
                            <label class="formularleft" for="admin_email"><?php echo $language['STEP6_ADMIN_EMAIL']; ?></label>
                            <input class="formularright" type="text" id="admin_email"
                                   name="admin_email"
                                   title="<?php echo $language['STEP6_ADMIN_EMAIL_TOOLTIP']; ?>"
                                   value="<?php echo $values['admin_email']; ?>" />
                        </li>
                        <li>
                            <label class="formularleft" for="admin_language"><?php echo $language['STEP6_ADMIN_LANGUAGE']; ?></label>
                             <select class="formularright" id="admin_language"
                                     title="<?php echo $language['STEP6_ADMIN_LANGUAGE_TOOLTIP']; ?>"
                                     name="admin_language" style="width: 150px"
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
                                   // filename conversion to shorthand
                                   if ($file == 'German') { $language_shorthand = 'de_DE'; }
                                   if ($file == 'English') { $language_shorthand = 'en_EN'; }
                                   if ($file == 'Italian') { $language_shorthand = 'it_IT'; }

                                    if ($values['admin_language'] == $language_shorthand) { echo ' selected="selected"'; }
                                    echo '>';
                                    echo $file;
                                    echo "</option>\n";
                                } }
                            ?>
                            </select>
                        </li>
                    </ol>
                    </fieldset>
                    <div id="content_footer">
                        <div class="navigation">
                            <span class="font-10">
                                <?php echo $language['CLICK_NEXT_TO_PROCEED']; ?><br />
                                <?php echo $language['CLICK_BACK_TO_RETURN']; ?><br />
                            </span>
                            <div class="alignright">
                                <input type="submit" value="<?php echo $language['NEXTSTEP']; ?>" class="ButtonGreen" name="step_forward" />
                            </div>
                            <div class="alignleft">
                                <input type="submit" value="<?php echo $language['BACKSTEP']; ?>" class="ButtonRed" name="step_backward" />
                                <input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>" />
                                <input type="hidden" name="submitted_step" value="6" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
