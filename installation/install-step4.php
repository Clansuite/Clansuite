<?php
# Security Handler
if (defined('IN_CS') === false)
{
    die( 'Clansuite not loaded. Direct Access forbidden.' );
}
?>
    <div id="content" class="narrowcolumn">
        <div id="content_middle">
            <div class="accordion">
                <h2 class="headerstyle">
                    <img src="images/64px-Document-save.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP4_DATABASE']; ?>
                </h2>
                <?php if ($error != '' ) { ?>
                <fieldset class="error_red">
                    <legend>Error</legend>
                    <strong><?php echo $error ?></strong>
                </fieldset>
                <?php } ?>
                <p><?php echo $language['STEP4_SENTENCE1']; ?></p>
                <p><?php echo $language['STEP4_SENTENCE2']; ?></p>
                <p><?php echo $language['STEP4_SENTENCE3']; ?></p>
                <form action="index.php" method="post" accept-charset="UTF-8">
                    <fieldset>
                        <legend> Database Access Information</legend>
                        <input type="hidden" name="type" value="mysql" />
                        <ol class="formular">
                            <li>
                                <label class="formularleft" for="host"><?php echo $language['HOST']; ?></label>
                                <input class="formularright" type="text" id="host" name="config[database][host]" value="<?php echo $values['host']; ?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="type"><?php echo $language['DRIVER']; ?></label>
                                <input class="formularright" type="text" id="type" name="config[database][driver]" value="<?php echo $values['driver']; ?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="username"><?php echo $language['USERNAME']; ?></label>
                                <input class="formularright" type="text" id="username" name="config[database][username]" value="<?php echo $values['username']; ?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="password"><?php echo $language['PASSWORD']; ?></label>
                                <input class="formularright" type="text" id="password" name="config[database][password]" value="<?php echo $values['password']; ?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="name"><?php echo $language['NAME']; ?></label>
                                <input class="formularright" type="text" id="name" name="config[database][name]" value="<?php echo $values['name']; ?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="create_database"><?php echo $language['CREATE_DATABASE']; ?></label>
                                <input class="formularright" type="checkbox" id="create_database" name="config[database][create_database]"
                                <?php if($values['create_database'] == '1') echo 'checked=\"checked\"'; ?> />
                            </li>
                            <li>
                                <label class="formularleft" for="prefix"><?php echo $language['PREFIX']; ?></label>
                                <input class="formularright" type="text" id="prefix" name="config[database][prefix]" value="<?php echo $values['prefix']; ?>" />
                            </li>
                        </ol>
                    </fieldset>
                    <!--<p><?php echo $language['STEP4_SENTENCE4']; ?></p> -->
                    <!--<p><?php echo $language['STEP4_SENTENCE5']; ?></p> -->
                    <div id="content_footer">
                        <div class="navigation">
                            <span style="font-size:10px;">
                                <?php echo $language['CLICK_NEXT_TO_PROCEED']; ?><br />
                                <?php echo $language['CLICK_BACK_TO_RETURN']; ?>
                            </span>
                            <div class="alignright">
                                <input type="submit" value="<?php echo $language['NEXTSTEP']; ?>" class="ButtonGreen" name="step_forward" />
                            </div>
                            <div class="alignleft">
                                <input type="submit" value="<?php echo $language['BACKSTEP']; ?>" class="ButtonRed" name="step_backward" />
                                <input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>" />
                            </div>
                        </div><!-- div navigation end -->
                    </div> <!-- div content_footer end -->
                </form>
            </div> <!-- div accordion end -->
        </div> <!-- div content_middle end -->
    </div> <!-- div content end -->