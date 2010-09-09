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
    * 
    *
    * @version    SVN: $Id$
    */
    
# Define security constant
if (defined('IN_CS') === false)
{ 
    die( 'Clansuite not loaded. Direct Access forbidden.' );
}

# initialize variables / arrays
$i = 1;
$j = 1;
$array_matrix_system_version = array();

# setup iterator for /import directory
$dirIterator = new DirectoryIterator('./import/');
foreach ($dirIterator as $cms)
{
    # each directory element but not (.,..)
    if ((!$cms->isDot()) and $cms->isDir())
    {
        #$array_matrix_systems_version[$i] = array();
        $array_matrix_systems_version[$cms->getFilename] = $cms->getFilename();
        $array_matrix_systems[] = $cms->getFilename();

        # setup iterator for /impot/xy-system/ (version) directory
        $dirIterator2 = new DirectoryIterator('./import/'.mb_strtolower($cms).'/');
        foreach ($dirIterator2 as $version)
        {
            # each directory element but not (.,..)
            if ((!$version->isDot()) && $version->isDir())
            {
                # okay, version dir found
                $array_matrix_systems_version[$i][$j] = $version->getFilename();
                $j++;
            }
            else
            {
                # no version dir found
                $array_matrix_systems_version[$i][$j] = 'no version found';
            }
        }
        $i++;
    }
}
# JSON Encode for Javascript Array Handling
$cmsArray = json_encode($array_matrix_systems_version);
?>
    <div id="content" class="narrowcolumn">
        <div id="content_middle">
            <div class="accordion">
                <h2 class="headerstyle">
                    <img src="../images/64px-Tango_Globe_of_Letters.svg.png" border="0" style="vertical-align:middle" alt="installstep image" />
                    <?php echo $language['STEP2_HEADING']; ?>
                </h2>
                <p><strong><?php echo $language['STEP2_SELECT_OLD_CMS_AND_VERSION']; ?></strong></p>
                
                <fieldset>
                <legend> CMS and Version </legend>
                
                <form action="index.php" name="cms_selection" method="post">
                    <p>
                        <script type="text/javascript">
                        //<![CDATA[ Quickforms Hierachical Menu Javascript
                        function _hs_findOptions(ary, keys)
                        {

                            var key = keys.shift();
                            if (!key in ary) {
                                return {};
                            } else if (0 == keys.length) {
                                return ary[key];
                            } else {
                                return _hs_findOptions(ary[key], keys);
                            }
                        }

                        function _hs_findSelect(form, groupName, selectIndex)
                        {
                            if (groupName+'['+ selectIndex +']' in form) {
                                return form[groupName+'['+ selectIndex +']'];
                            } else {
                                return form[groupName+'['+ selectIndex +'][]'];
                            }
                        }

                        function _hs_unescapeEntities(str)
                        {
                            var div = document.createElement('div');
                            div.innerHTML = str;
                            return div.childNodes[0] ? div.childNodes[0].nodeValue : '';
                        }

                        function _hs_replaceOptions(ctl, optionList)
                        {
                            var j = 0;
                            ctl.options.length = 0;
                            for (i in optionList) {
                                var optionText = (-1 == String(optionList[i]).indexOf('&'))? optionList[i]: _hs_unescapeEntities(optionList[i]);
                                ctl.options[j++] = new Option(optionText, i, false, false);
                            }
                        }

                        function _hs_setValue(ctl, value)
                        {
                            var testValue = {};
                            if (value instanceof Array) {
                                for (var i = 0; i < value.length; i++) {
                                    testValue[value[i]] = true;
                                }
                            } else {
                                testValue[value] = true;
                            }
                            for (var i = 0; i < ctl.options.length; i++) {
                                if (ctl.options[i].value in testValue) {
                                    ctl.options[i].selected = true;
                                }
                            }
                        }

                        function _hs_swapOptions(form, groupName, selectIndex)
                        {
                            var hsValue = [];
                            for (var i = 0; i <= selectIndex; i++) {
                                hsValue[i] = _hs_findSelect(form, groupName, i).value;
                            }

                            _hs_replaceOptions(_hs_findSelect(form, groupName, selectIndex + 1),
                                               _hs_findOptions(_hs_options[groupName][selectIndex], hsValue));
                            if (selectIndex + 1 < _hs_options[groupName].length) {
                                _hs_swapOptions(form, groupName, selectIndex + 1);
                            }
                        }

                        function _hs_onReset(form, groupNames)
                        {
                            for (var i = 0; i < groupNames.length; i++) {
                                try {
                                    for (var j = 0; j <= _hs_options[groupNames[i]].length; j++) {
                                        _hs_setValue(_hs_findSelect(form, groupNames[i], j), _hs_defaults[groupNames[i]][j]);
                                        if (j < _hs_options[groupNames[i]].length) {
                                            _hs_replaceOptions(_hs_findSelect(form, groupNames[i], j + 1),
                                                               _hs_findOptions(_hs_options[groupNames[i]][j], _hs_defaults[groupNames[i]].slice(0, j + 1)));
                                        }
                                    }
                                } catch (e) {
                                    if (!(e instanceof TypeError)) {
                                        throw e;
                                    }
                                }
                            }
                        }

                        function _hs_setupOnReset(form, groupNames)
                        {
                            setTimeout(function() { _hs_onReset(form, groupNames); }, 25);
                        }

                        function _hs_onReload()
                        {
                            var ctl;
                            for (var i = 0; i < document.forms.length; i++) {
                                for (var j in _hs_defaults) {
                                    if (ctl = _hs_findSelect(document.forms[i], j, 0)) {
                                        for (var k = 0; k < _hs_defaults[j].length; k++) {
                                            _hs_setValue(_hs_findSelect(document.forms[i], j, k), _hs_defaults[j][k]);
                                        }
                                    }
                                }
                            }

                            if (_hs_prevOnload) {
                                _hs_prevOnload();
                            }
                        }

                        var _hs_prevOnload = null;
                        if (window.onload) {
                            _hs_prevOnload = window.onload;
                        }
                        window.onload = _hs_onReload;

                        var _hs_options = {};
                        var _hs_defaults = {};

                        _hs_options['cmsArray'] = [ <?php echo $cmsArray; ?> ];
                        _hs_defaults['cmsArray'] = ['0', '0'];
                        //]]>
                        </script>

                        <?php # SELECT FOR cmsArray[0] ?>

                        <select name="cmsArray[0]" onchange="_hs_swapOptions(this.form, 'cmsArray', 0);" style="width: 160px" />
                        <option value="">- Select System -</option>
                        <?php
                        $z = 1;
                        foreach($array_matrix_systems as $cms_name)
                        {
                              echo '<option value="'.$z.'" style="padding-left: 40px; background-image: url(./import/'. $cms_name .'/favicon.ico); background-position:5px 100%; background-repeat: no-repeat;"';
                              if ($_SESSION['cmsArray[0]'] == ucfirst($cms_name)) { echo ' selected="selected"'; }
                              echo '>' . ucfirst($cms_name);
                              echo "</option>\n";
                              $z++;
                        }
                        echo "</select>\n";
                        ?>

                        <?php # SELECT FOR cmsArray[1] ?>

                        <select name="cmsArray[1]" style="width: 160px" />
                        <option value="">- Select Version -</option>
                        <?php
                        foreach($array_matrix_systems_version as $version_name => $value)
                        {
                            var_dump($version_name);
                            echo $value;
                            echo '<option style="padding-left: 40px; background-image: url(./import/'. $cms_name .'/favicon.ico); background-position:5px 100%; background-repeat: no-repeat;"';
                            if ($_SESSION['cmsArray[1]'] == $version_name) { echo ' selected="selected"'; }
                            echo '>' . $version_name;
                            echo "</option>\n";
                        }
                        echo "</select>\n";
                        ?>
                    </fieldset>
                    </p>
                    <p><?php echo $language['STEP2_REQUEST_CONVERTER']; ?><a href="http://forum.clansuite.com/index.php?board=26">Converter-Forum</a>.</p>
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