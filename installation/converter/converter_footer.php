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
if (defined('IN_CS') == false){ die( 'Clansuite not loaded. Direct Access forbidden.' );}
# Set Timezone for date functions
date_default_timezone_set('Europe/Berlin');
?>

       <div id="rightsidebar">
                <ul>

                    <!-- Converter Progress BAR -->
                    <li><h2><?=$language['INSTALL_PROGRESS']?></h2></li>
                    <li><?=$language['COMPLETED']?> <b><?=$_SESSION['progress']?>%</b>
                      <div id="progressbar">
                            <?php
                            #note by vain: this fixes a 2pixel problem while displaying the progress bar at 100percent:P
                            if ($_SESSION['step'] == 7 ) { $_SESSION['progress'] = $_SESSION['progress'] - 2; }
                            ?>
                            <div style="border: 1px solid white; height: 5px ! important; width: <?=$_SESSION['progress']?>px; background-color: rgb(181, 0, 22);"/>
                        </div>
                    </li>

                    <!-- Change Language -->
                    <li><h2><?=$language['CHANGE_LANGUAGE']?></h2></li>
                    <li>
                        <?php # pruefen ob es die aktuelle sprache ist, die reloaded werden soll
                              # nur reloaden, wenn neue sprache ausgewaehlt                ?>
                        <select name="lang" style="width: 100px"
                                onchange="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?lang='+this.options[this.selectedIndex].value;" >
                        <?php
                        echo '<option value="">- Select Language -</option>';
                        foreach (new DirectoryIterator('./languages/') as $file) {
                           // get each file not starting with dots ('.','..')
                           // or containing ".converter.php"
                           if ((!$file->isDot()) && preg_match("/.gif$/",$file->getFilename()))
                           {
                              echo '<option style="padding-left: 30px; background-image: url(./languages/' . $file .'); background-position:5px 100%; background-repeat: no-repeat;"';
                              $file = substr($file->getFilename(), 0, -4);
                              if ($_SESSION['lang'] == $file) { echo ' selected="selected"'; }
                              echo '>';
                              echo $file;
                              echo "</option>\n";
                           }
                        }
                        ?>
                        </select>
                    </li>

                    <!-- Clansuite Shortcuts -->
                    <li><h2><?=$language['SHORTCUTS']?></h2></li>
                    <li><strong><a href="http://www.clansuite.com/">Website</a></strong></li>
                    <li><strong><a href="http://forum.clansuite.com/">Forum</a></strong></li>
                    <li><strong><a href="http://forum.clansuite.com/index.php?board=26">Convertersupport</a></strong></li>
                    <li><strong><a href="http://trac.clansuite.com/">Bugtracker</a></strong></li>
                    <li><strong><a href="teamspeak://clansuite.com:8000?channel=Clansuite%20Admins?subchannel=Clansuite%20Support">Teamspeak</a></strong></li>
                    <li><strong><a href="http://www.clansuite.com/toolbar/">Toolbar</a></strong></li>

                    <!-- Donate -->
                    <li><h2>Donate</h2></li>
                    <li>
                        <!-- PayPal Direct Image Button
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                            <input type="hidden" name="cmd" value="_s-xclick" />
                            <input type="image" src="https://www.paypal.com/de_DE/i/btn/x-click-but04.gif" name="submit" alt="Zahlen Sie mit PayPal - schnell, kostenlos und sicher!" />
                            <img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1" />
                            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----" />
                            </form>
                        -->
                    <!-- Pledige Campaign -->
                    <a href='http://www.pledgie.com/campaigns/6324'><img alt='Click here to lend your support to: Unterstützt Clansuite! and make a donation at www.pledgie.com !' src='http://www.pledgie.com/campaigns/6324.png?skin_name=eight_bit' border='0' /></a>
                    </li>

                    <!-- Link Us -->
                    <li><h2>Link us</h2></li>
                    <li><a href="http://www.clansuite.com/banner/" target="_blank"><img src="http://www.clansuite.com/website/images/banners/clansuite-crown-banner-80x31.png" alt="Clansuite 80x31 LOGO" /></a></li>

               </ul>
        </div>

    <!-- start:Fusszeile -->
    <div id="footer">
        <p style="filter:alpha(opacity=65); -moz-opacity:0.65;">
            <br />
            <?php $webinstaller_version = 'Version '. CONVERTER_VERSION .' - '. date("l, jS F Y",getlastmod()); ?>
            Clansuite Converter <?php echo $webinstaller_version; ?>
            <br />
            SVN: $Rev$ $Author$
            <br />
            &copy; 2005-<?=date("Y"); ?> by <a href="http://www.Jens-André-koch.de" target="_blank" style="text-decoration=none">Jens-Andr&#x00E9; Koch</a> &amp; Clansuite Development Team
        </p>
    </div>
    <!-- end:Fusszeile  -->    
</div><!-- end:page -->
</center>
</body>
</html>