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
                    <img src="images/64px-Document-save.svg.png" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP4_DATABASE']; ?>
                </h2>
                <?php if (!empty($error)) { ?>
                <div class="error_red">
                    <strong><?php echo $error ?></strong>
                </div>
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
                                <input class="formularright" type="text"
                                       id="host"
                                       name="config[database][host]"
                                       title="<?php echo $language['HOST_TOOLTIP']; ?>"
                                       value="<?php echo $values['host']; ?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="type"><?php echo $language['DRIVER']; ?></label>
                                <select class="formularright"
                                        id="driver"
                                        name="config[database][driver]"
                                        title="<?php echo $language['DRIVER_TOOLTIP']; ?>">
                                <?php
                                    $html  = '<option value="pdo_mysql" ';
                                    $html .= ($values['driver'] === 'pdo_mysql') ? 'selected="selected"' : '';
                                    $html .= '>MySQL</option>';
                                    /*
                                    $html .= '<option value="pdo_pgsql" ';
                                    $html .= ($values['driver'] === 'pdo_pgsql') ? 'selected="selected"' : '';
                                    $html .= '>PostgreSQL</option>';

                                    $html .= '<option value="pdo_sqlite" ';
                                    $html .= ($values['driver'] === 'pdo_sqlite') ?  'selected="selected"' : '';
                                    $html .= '>SQLite</option>';
                                    */
                                    echo $html;
                                 ?>
                                </select>
                            </li>
                            <li>
                                <label class="formularleft" for="user"><?php echo $language['USERNAME']; ?></label>
                                <input class="formularright" type="text" id="username"
                                       name="config[database][user]"
                                       title="<?php echo $language['USERNAME_TOOLTIP']; ?>"
                                       value="<?php echo $values['user']; ?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="password"><?php echo $language['PASSWORD']; ?></label>
                                <input class="formularright" type="text" id="password"
                                       autocomplete="off"
                                       name="config[database][password]"
                                       title="<?php echo $language['PASSWORD_TOOLTIP']; ?>"
                                       value="<?php echo $values['password']; ?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="dbname"><?php echo $language['NAME']; ?></label>
                                <input class="formularright" type="text" id="name"
                                       name="config[database][dbname]"
                                       title="<?php echo $language['NAME_TOOLTIP']; ?>"
                                       value="<?php echo $values['dbname']; ?>" />
                            </li>
                            <li>
                                <label class="formularleft" for="create_database"><?php echo $language['CREATE_DATABASE']; ?></label>
                                <input class="formularright" type="checkbox" id="create_database"
                                       name="config[database][create_database]"
                                       title="<?php echo $language['CREATEDB_TOOLTIP']; ?>"
                                <?php if($values['create_database'] == '1') echo 'checked=\"checked\"'; ?> />
                            </li>
                            <li>
                                <label class="formularleft" for="prefix"><?php echo $language['PREFIX']; ?></label>
                                <input class="formularright" type="text" id="prefix"
                                       name="config[database][prefix]"
                                       title="<?php echo $language['PREFIX_TOOLTIP']; ?>"
                                       value="<?php echo $values['prefix']; ?>" />
                            </li>
                        </ol>
                    </fieldset>
                    <!--Create Table and Entries? <p><?php echo $language['STEP4_SENTENCE4']; ?></p> -->
                    <!--Import Tables of another CMS? <p><?php echo $language['STEP4_SENTENCE5']; ?></p> -->
                    <div id="content_footer">
                        <div class="navigation">
                            <span style="font-size:10px;">
                                <?php echo $language['CLICK_NEXT_TO_PROCEED']; ?><br />
                                <?php echo $language['CLICK_BACK_TO_RETURN']; ?><br />
                            </span>
                            <div class="alignright">
                                <input type="submit" value="<?php echo $language['NEXTSTEP']; ?>" class="ButtonGreen" name="step_forward" />
                            </div>
                            <div class="alignleft">
                                <input type="submit" value="<?php echo $language['BACKSTEP']; ?>" class="ButtonRed" name="step_backward" />
                                <input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>" />
                                <input type="hidden" name="submitted_step" value="4" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>