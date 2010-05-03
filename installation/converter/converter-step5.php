<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */
    
# Define security constant
if (defined('IN_CS') == false)
{ 
    die( 'Clansuite not loaded. Direct Access forbidden.' );
}
?>
    <div id="content" class="narrowcolumn">
        <div id="content_middle">
            <div class="accordion">
                <h2 class="headerstyle">
                    <img src="../images/64px-Tango_Globe_of_Letters.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP5_HEADING']; ?>
                </h2>
                <p><strong><?php echo $language['STEP1_WELCOME']; ?></strong></p>
                <p><?php echo $language['STEP1_APPINSTALL_STEPWISE']; ?></p>
                <p><?php echo $language['STEP1_CHOOSELANGUAGE']; ?></p>
                <form action="index.php" name="lang" method="post">
                    <p>
                        <input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>" />
                        <?php # @todo nur reloaden, wenn eine neue sprache ausgewaehlt ?>
                        <select name="lang" style="width: 160px"
                            onchange="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?lang='+this.options[this.selectedIndex].value;" />
                        <?php
                        echo '<option value="">- Select Language -</option>';
                        $dirIterator = new DirectoryIterator('./languages/');
                        foreach ($dirIterator as $file) {
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
