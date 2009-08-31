<?php
/**
 * Security Handler
 */
if (!defined('IN_CS')){ die( 'Clansuite not loaded. Direct Access forbidden.' );}
?>
    <div id="content" class="narrowcolumn">
        <div id="content_middle">
            <div class="accordion">
                <h2 class="headerstyle">
                    <img src="images/64px-Document-save.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP4_DATABASE']?>
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
                        <input type="hidden" name="db_type" value="mysql" />
                        <ol class="formular">
                            <li>
                                <label class="formularleft" for="db_host"><?php echo $language['DB_HOST']?></label>
                                <input class="formularright" type="text" id="db_host" name="config[database][db_host]" value="<?php echo $values['db_host']?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="db_type"><?php echo $language['DB_TYPE']?></label>
                                <input class="formularright" type="text" id="db_type" name="config[database][db_type]" value="<?php echo $values['db_type']?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="db_username"><?php echo $language['DB_USERNAME']?></label>
                                <input class="formularright" type="text" id="db_username" name="config[database][db_username]" value="<?php echo $values['db_username']?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="db_password"><?php echo $language['DB_PASSWORD']?></label>
                                <input class="formularright" type="text" id="db_password" name="config[database][db_password]" value="<?php echo $values['db_password']?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="db_name"><?php echo $language['DB_NAME']?></label>
                                <input class="formularright" type="text" id="db_name" name="config[database][db_name]" value="<?php echo $values['db_name']?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="db_create_database"><?php echo $language['DB_CREATE_DATABASE']?></label>
                                <input class="formularright" type="checkbox" id="db_create_database" name="config[database][db_create_database]"
                                <?php if($values['db_create_database'] == '1') echo 'checked=\"checked\"'; ?> />
                            </li>
                            <li>
                                <label class="formularleft" for="db_prefix"><?php echo $language['DB_PREFIX']?></label>
                                <input class="formularright" type="text" id="db_prefix" name="config[database][db_prefix]" value="<?php echo $values['db_prefix']?>" />
                            </li>
                        </ol>
                    </fieldset>
                    <!--<p><?php echo $language['STEP4_SENTENCE4']; ?></p> -->
                    <!--<p><?php echo $language['STEP4_SENTENCE5']; ?></p> -->
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