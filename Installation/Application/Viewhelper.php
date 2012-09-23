<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Clansuite\Installation\Application;

class Viewhelper
{
    /**
     * Renders a language selection dropdown formelement.
     *
     * @param $language Array with Translation Strings.
     */
    public static function renderLanguageDropdown($language)
    {
        // pruefen ob es die aktuelle sprache ist, die reloaded werden soll
        // nur reloaden, wenn neue sprache ausgewaehlt
        ?>
        <input type="hidden" name="lang" value="<?php echo $_SESSION['lang']; ?>" />
        <select title="<?php echo $language['SELECT_LANGUAGE']; ?>" name="lang" style="width: 100px"
                onchange="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?lang='+this.options[this.selectedIndex].value;" >
        <?php
        echo '<option value="">- Select Language -</option>';
        foreach (new \DirectoryIterator('./Languages/') as $file) {
           // get each php file in Languages folder, exclude stuff starting with dots
           if ((!$file->isDot()) && preg_match("/.php$/",$file->getFilename())) {
              // get the filename without extension
              $file = substr($file->getFilename(), 0, -4);
              // build image name
              $flag_image = strtolower($file).'.png';
              // if an image exists, add it as inline css style
              if (is_file('./Languages/' . $flag_image)) {
                echo '<option class="language-toggle" style="background-image: url(./Languages/' . $flag_image .');"';
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
    }
}
