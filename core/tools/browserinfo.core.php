<?php
   /**
    * Clansuite - just an E-Sport CMS
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id: acm.class.php 4599 2010-08-27 21:01:58Z vain $
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Core Class for Browser information
 *
 * @author      Paul Brand <info@isp-tenerife.net>
 *
 * @category    Clansuite
 * @package     Tools
 * @subpackage  Browser
 */
class Clansuite_Browserinfo
{
    protected $useragent;
    protected $browsername = '';
    protected $browsernick = '';
    protected $browserversion = '';
    protected $browsermajorversion = '';
    protected $browserminorversion = '';
    protected $browserreleaseversion = '';
    protected $browserbuildversion = '';
    protected $csstype = '';
    protected $invalidbrowser = '';
    protected $iever = array();
    protected $mozver = array();
    protected $useros = '';
    protected $browserver = array('major' => 0, 'minor' => 0, 'release' => 0, 'build' => 0);

    public function __construct()
    {
        $this->useragent = $_SERVER['HTTP_USER_AGENT'];
        $this->browsername = 'Unknown';
        $this->useros = 'Unknown';
        $this->detect();
    }

    public function detect()
    {
        if(preg_match('/win/i', $this->useragent))
        {
            $this->useros = 'Windows';
        }
        if(preg_match('/mac/i', $this->useragent))
        {
            $this->useros = 'Macintosh';
        }
        if(preg_match('/unix/i', $this->useragent))
        {
            $this->useros = 'UNIX';
        }
        if(preg_match('/linux/i', $this->useragent))
        {
            $this->useros = 'Linux';
        }
        if(preg_match('/beos/i', $this->useragent))
        {
            $this->useros = 'BeOS';
        }

        # msie
        if(preg_match('/msie/i', $this->useragent) and false === $this->isOpera())
        {
            $this->browsername = 'Microsoft Internet Explorer'; #Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)
            $this->browsernick = 'msie';
            $this->browserversion = preg_replace('/(.*)(msie\s)([0-9]+\.[0-9]+)(;)(.*)/i', '$3', $this->useragent);
        }

        # opera
        if(preg_match('/opera/i', $this->useragent))
        {
            $this->browsername = 'Opera';
            $this->browsernick = 'opera';
            $this->browserversion = preg_replace('/(.*)(Opera[\s|\/])([0-9]+\.[0-9]+)(.*)/i', '$3', $this->useragent);
        }

        #Mozilla/Seamonkey Suites
        if(preg_match('/(rv:)|(seamonkey)/i', $this->useragent) and false === preg_match('/(netscape)|(navigator)/i', $this->useragent))
        {
            $this->browsername = ( preg_match('/seamonkey/i', $this->useragent) ) ? 'Seamonkey' : 'Mozilla';
            $srch = ( preg_match('/seamonkey/i', $this->useragent) ) ? '(seamonkey\/)' : '(rv:)([0-9\.]+)';
            $this->browserversion = preg_replace('/(.*)".$srch."(.*)/i', '$3', $this->useragent);
            $this->browsernick = ( preg_match('/seamonkey/i', $this->useragent) ) ? 'seamonkey' : 'mozilla';
        }

        ## Firefox/FireBird/Phoenix/GranParadiso
        if(preg_match('/(firefox)|(firebird)|(phoenix)|(GranParadiso)/i', $this->useragent))
        {
            $this->browsername = 'Phoenix';
            if(preg_match('/firebird/i', $this->useragent))
                $this->browsername = 'Firebird';
            if(preg_match('/firefox/i', $this->useragent))
                $this->browsername = 'Firefox';
            if(preg_match('/GranParadiso/i', $this->useragent))
                $this->browsername = 'GranParadiso';#ff3
            $this->browserversion = preg_replace('/(.*)(' . $this->browsername . '\/)(.*)/i', '$3', $this->useragent);
            $this->browsernick = strtolower($this->browsername);
        }

        # Flock -- Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.12) Gecko/20070531 Firefox/1.5.0.12 Flock/0.7.14
        if(preg_match('/flock/i', $this->useragent))
        {
            $this->browsername = 'Flock';
            $this->browsernick = strtolower($this->browsername);
            $this->browserversion = preg_replace('/(.*)(' . $this->browsername . '\/)(.*)/i', '$3', $this->useragent);
        }

        # Google Chrome -- Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.13 (KHTML, like Gecko) Chrome/9.0.597.98 Safari/534.13
        if(preg_match('/chrome/i', $this->useragent))
        {
            $this->browsername = 'Chrome';
            $this->browsernick = strtolower($this->browsername);
            $this->browserversion = preg_replace('/(.*)(' . $this->browsername . '\/)([0-9\.]+)(.*)/i', '$3', $this->useragent);
        }

        # Safari -- Mozilla/5.0 (Windows; U; Windows NT 5.1; de-DE) AppleWebKit/533.19.4 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4
        if(preg_match('/Safari/i', $this->useragent) and false === preg_match('/Chrome/i', $this->useragent))
        {
            $this->browsername = 'Safari';
            #$this->browserversion = preg_replace('/(.*)(safari)([0-9\.]+)/i', '$2', $this->useragent);
            $this->browserversion = preg_replace('/(.*)(version\/)([0-9\.]+)(.*)/i', '$3', $this->useragent);
            $this->browsernick = strtolower($this->browsername);
        }

        # NS 4, 3...
        if(preg_match('/mozilla\/4/i', $this->useragent) and $this->browsername == 'Unknown')
        {
            $this->browsername = 'Netscape';
            $this->browsernick = 'ns4';
            $this->browserversion = preg_replace('/(Mozilla\/)([0-9\.]+)(.*)/', '$2', $this->useragent);
        }

        # NS 6+
        if(preg_match('/(netscape)|(navigator)/i', $this->useragent))
        {
            $this->browsername = 'Netscape';
            if(preg_match('/navigator/i', $this->useragent))
                $this->browsername .= ' Navigator';
            $this->browserversion = preg_replace('/(.*)(.*\/)([0-9\.]+)/', '$3', $this->useragent);
            $this->browsernick = strtolower($this->browsername);
        }

        # Epiphany
        if(preg_match('/epiphany/i', $this->useragent))
        {
            $this->browsername = 'Epiphany';
            $this->browserversion = preg_replace('/(.*)(' . $this->browsername . '[\s|\/])([0-9\.]+)/i', '$3', $this->useragent);
            $this->browsernick = strtolower($this->browsername);
        }

        # Galeon
        if(preg_match('/galeon/i', $this->useragent))
        {
            $this->browsername = 'Galeon';
            $this->browserversion = preg_replace('/(.*)(' . $this->browsername . '[\s|\/])([0-9\.]+)/i', '$3', $this->useragent);
            $this->browsernick = strtolower($this->browsername);
        }

        # Konqueror
        if(preg_match('/Konqueror/i', $this->useragent))
        {
            $this->browsername = 'Konqueror';
            $this->browserversion = preg_replace('/(.*)(' . $this->browsername . '[\s|\/]([0-9\.]+))(.*)/i', '$3', $this->useragent);
            $this->browsernick = strtolower($this->browsername);
        }

        # AOL - complex & ugly
        if(preg_match('/(AOL)|(America Online)|(America)/i', $this->useragent))
        {
            $srch = ( preg_match('/aol/i', $this->useragent) ) ? 'aol' : 'america online browser';
            #$p = strpos( strtolower( $this->useragent ), $srch );
            #$tmpStr = substr( $this->useragent, $p, strlen( $this->useragent)); #AOL 9.0; Windows NT 5.1; InfoPath.1)
            #$tmpStr = preg_replace('/(aol [0-9\.]+)(.*)/i','$1', $tmpStr );
            $this->browsername = 'America On Line (AOL)';
            # /(.*)(aol )([\d\.\d]+)(.*)/i; 
            $this->browserversion = preg_replace('/(.*)(aol )([\d\.\d]+)(.*)/i', '$3', $this->useragent);
            $this->browsernick = 'aol';
        }

        # Lynx
        if(preg_match('/Lynx/i', $this->useragent))
        {
            $this->browsername = 'Lynx';
            $this->browserversion = preg_replace('/(lynx\/)([0-9a-z\.]+)(.*)/i', '$2', $this->useragent);
            $this->browsernick = strtolower($this->browsername);
        }

        # Camino/Chimera
        if(preg_match('/(Camino)|(Chimera)/i', $this->useragent))
        {
            $this->browsername = (preg_match('/Camino/i', $this->useragent)) ? 'Camino' : 'Chimera';
            $this->browserversion = preg_replace('/(.*)(' . $this->browsername . '[\s\/])([0-9\.]+)/i', '$3', $this->useragent);
            $this->browsernick = strtolower($this->browsername);
        }

        #K-Meleon
        if(preg_match('/(kmeleon)|(k-meleon)/i', $this->useragent))
        {
            $this->browsername = 'K-Meleon';
            $this->browserversion = preg_replace('/((.*)(K-Meleon\/)(.*)/i', '$3', $this->useragent);
            $this->browsernick = strtolower($this->browsername);
        }

        $tVer = preg_split('/\./', $this->browserversion);

        # Browser Major Version
        if(count($tVer) >= 1)
        {
            $this->browsermajorversion = $tVer[0];
            $this->browserver['major'] = $tVer[0];
        }
        else
        {
            $this->browsermajorversion = 0;
            $this->browserver['major'] = 0;
        }

        # Browser Minor Version
        if(count($tVer) >= 2)
        {
            $this->browserminorversion = $tVer[1];
            $this->browserver['minor'] = $tVer[1];
        }
        else
        {
            $this->browserminorversion = 0;
            $this->browserver['minor'] = 0;
        }

        # Browser Release Version
        if(count($tVer) >= 3)
        {
            $this->browserreleaseversion = $tVer[2];
            $this->browserver['release'] = $tVer[2];
        }
        else
        {
            $this->browserreleaseversion = 0;
            $this->browserver['release'] = 0;
        }

        # Browser Build Version
        if(count($tVer) >= 4)
        {
            $this->browserbuildversion = $tVer[3];
            $this->browserver['build'] = $tVer[3];
        }
        else
        {
            $this->browserbuildversion = 0;
            $this->browserver['build'] = 0;
        }
    }

    public function getVersionMajor()
    {
        return $this->browsermajorversion;
    }

    public function getVersionMinor()
    {
        return $this->browserminorversion;
    }

    public function getBuildVersion()
    {
        return $this->browserbuildversion;
    }

    public function getBrowserName()
    {
        return $this->browsername;
    }

    public function getBrowserNickname()
    {
        return $this->browsernick;
    }

    public function isIE($iVal = 0)
    {
        return ( preg_match('/MSIE/i', $this->useragent) and false === preg_match('/(opera)/i', $this->useragent) and $this->is_Ver($iVal) ) ? 1 : 0;
    }

    ## All Versions of Opera

    public function isOpera($iVal = 0)
    {
        return (preg_match('/(opera)/i', $this->useragent) and $this->is_Ver($iVal) ) ? 1 : 0;
    }

    # All versions Of Mozilla Suite

    public function isMozilla($iVal = 0)
    {
        return ( preg_match('/gecko/i', $this->useragent) and
        preg_match('/rv:/i', $this->useragent) and
        !preg_match('/(netscape)|(navigator)|(phoenix)|(firebird)|(firefox)|(kmeleon)|(k-meleon)|(camino)|(chimera)|(konqueror)|(galeon)|(epiphany)|(seamonkey)/i', $this->useragent) and
        $this->is_Ver($iVal) ) ? 1 : 0;
    }

    public function isFF($iVal = 0)
    {
        return ( preg_match('/(phoenix)|(firebird)|(firefox)/i', $this->useragent) and $this->is_Ver($iVal)) ? 1 : 0;
    }

    # any mozilla 5

    public function isMoz($iVal = 0)
    {
        return ( preg_match('/mozilla\/5/i', $this->useragent) and
        !preg_match('/(opera)/i', $this->useragent) and
        $this->is_Ver($iVal) ) ? 1 : 0;
    }

    # only NS4

    public function isNS4()
    {
        return ( preg_match('/Mozilla\/4.[0-9]+/i', $this->useragent) and
        !preg_match('/(msie)|(opera)/i', $this->useragent) ) ? 1 : 0;
    }

    # Mozilla/5.0 safari/  (Mac) Gecko/

    public function isSafari($iVal = 0)
    {
        return ( preg_match('/(safari)/i', $this->useragent) and
        !preg_match('/(chrome)/i', $this->useragent) and
        $this->is_Ver($iVal) ) ? 1 : 0;
    }

    # Mozilla/5.0 Chrome/  (Google Chrome)

    public function isChrome($iVal = 0)
    {
        return ( preg_match('/(chrome)/i', $this->useragent) and
        $this->is_Ver($iVal) ) ? 1 : 0;
    }

    # Mozilla/5.0 Camino/  (Mac) Gecko/

    public function isCamino($iVal = 0)
    {
        return ( preg_match('/(camino)|(chimera)/i', $this->useragent) and $this->is_Ver($iVal) ) ? 1 : 0;
    }

    # Mozilla/5.0 (compatible; Konqueror/2.0.1; X11); 
    # Supports MD5-Digest; Supports gzip encoding, Mozilla/5.0 (compatible; Konqueror/2.2.1; Linux)

    public function isKonqueror($iVal = 0)
    {
        return ( preg_match('/konqueror/i', $this->USERAGENT) and $this->is_Ver($iVal) ) ? 1 : 0;
    }

    # gecko

    public function isGecko()
    {
        return (preg_match('/(gecko)|(konqueror)/i', $this->useragent) ) ? 1 : 0;
    }

    # ====================================================================

    public function geckoVer()
    {
        return preg_replace('/((.*)(rv:)([0-9\.]+)(.*)/', '$3', $this->useragent);
    }

    public function ShowBrowserInfo()
    {
        return $this->browsername . ' ' . $this->browserversion;
    }

    public function isDom()
    {
        return ($this->isGecko() || $this->isIE('>=5') || $this->isOpera('>=7')) ? 1 : 0;
    }

    # Unknown browser is defind as a bot.

    public function isBot()
    {
        return ( $this->browsername == 'Unknown' ) ? 1 : 0;
    }

    ## >= version passed, eg Clansuite_BrowserInfo::isVer(5) will check for 5+

    public function isVer($iVal)
    {
        $v = ($iVal) ? $iVal : 0;
        return ( floatval($this->browserversion) >= $v) ? 1 : 0;
    }

    # now you can use  
    #    Clansuite_BrowserInfo::is_Ver('>=3);
    #    Clansuite_BrowserInfo::is_Ver('<=3');

    public function is_Ver($iVal = 0)
    {
        $iVal = preg_replace('/(\s/', '', $iVal);
        $funct = preg_replace('/([^\<\>=!]/', '', $iVal);
        $v = preg_replace('/([^0-9\.]/', '', $iVal);
        if($iVal > 0)
        {
            switch(strtolower($funct))
            {
                case '<':
                    return ( floatval($this->browserversion) < $v ) ? 1 : 0;
                    break;
                case '>':
                    return ( floatval($this->browserversion) > $v ) ? 1 : 0;
                    break;
                case '<=':
                    return ( floatval($this->browserversion) <= $v ) ? 1 : 0;
                    break;
                case '>=':
                    return ( floatval($this->browserversion) >= $v ) ? 1 : 0;
                    break;
                case '!=':
                case '<>':
                case '><':
                    return ( floatval($this->browserversion) != $v ) ? 1 : 0;
                    break;
                default: # equal by default
                    return ( floatval($this->browserversion) == $v ) ? 1 : 0;
                    break;
            }
        }
        return ( floatval($this->browserversion) >= $v) ? 1 : 0;
    }

    #so you can check the WHOLE version              

    public function checkVer($iVal)
    {
        $tVer = preg_split('/\./', $iVal); #0,1,2,3 eg ff 1.5.0.2 or 1.5.0.7
        #usually has 1.0 version - not much beyond that
        $release = 1;     # release always passes
        $build = 1;        # build always passes 
        $major = ( floatval($this->browsermajorversion) >= $tVer[0]) ? 1 : 0;
        $minor = ( floatval($this->browserminorversion) >= $tVer[1]) ? 1 : 0;
        # now, only pass release & build if defined.
        if($tVer[2])
            $release = ( floatval($this->browserreleaseversion) >= $tVer[2]) ? 1 : 0;
        if($tVer[3])
            $build = ( floatval($this->browserbuildversion) >= $tVer[3]) ? 1 : 0;
        return ( $major and $minor and $release and $build ) ? 1 : 0;
    }

    public function isValidBrowser()
    {
        return ( $this->browsername and $this->browsername != 'Unknown' ) ? 1 : 0;
    }

    /**
     * opacity 
     * 
     * iVal's in Percentages (50(%))  - better way of remembering & doing opacitiy's
     * 
     * @param $iVal string Opacity Value, e.g. 50%
     */
    public function opacity($iVal)
    {
        $opa = '';
        $iVal = intval(preg_replace('/[^0-9]/', '', $iVal));
        $o = $iVal / 100;
        
        if($o == 1)
        {
            $o.= '.0';
        }
        else
        {
            $o .= '0';
        }

        if($this->isDOM())
        {
            if($this->isIE())
            {
                $opa = 'filter:alpha(opacity=' . intval($iVal) . ');';
            }
            elseif($this->isKonqueror() || $this->isSafari())
            {
                $opa = '-khtml-opacity:' . $o . ';';
            }
            elseif($this->isMozilla() and floatval($this->browserversion) < 1.7)
            {
                $opa = 'moz-opacity:' . $o . ';';
            }
            else
            {
                $opa = 'opacity:' . $o . ';';
            }
        }
        return $opa;
    }

    /**
     * transparentpng
     * 
     * A better way of remembering ie < 7 quirks for transparent pngs.
     * 
     * @param $png image
     */
    public function transparentpng($png)
    {
        $img = getimagesize($png);

        if($this->isIE() and $this->browserversion < 7)
        {
            $cla = 'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src="' . $png . '", sizingMethod="image")';
        }
        else
        {
            $cla = 'background:url("' . $png . '") 0 0 no-repeat;';
        }
        $cla .= ' width:' . $img[0] . 'px; height:' . $img[1] . 'px;';

        return $cla;
    }
}
?>