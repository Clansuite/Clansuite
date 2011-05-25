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
    /**
     * usrer agent
     * @var string
     */
    protected $userAgentString;

    /**
     * browser name
     * @var string
     */
    protected $browserName;

    /**
     * browser typ
     * @var string
     */
    protected $browserTyp;

    /**
     * browser sub typ
     * @var string
     */
    protected $browserTypSub;

    /**
     * browser version
     * @var string
     */
    protected $browserVersion;

    /**
     * browser major version
     * @var string
     */
    protected $browserVersionMajor;

    /**
     * browser minor version
     * @var string
     */
    protected $browserVersionMinor;

    /**
     * browser release version
     * @var string
     */
    protected $browserVersionRelease;

    /**
     * browser build version
     * @var string
     */
    protected $browserVersionBuild;

    /**
     * operating system
     * @var string
     */
    protected $operatingSystem;

    /**
     * operating system name
     * @var string
     */
    protected $operatingSystemName;

    /**
     * operating system typ
     * @var string
     */
    protected $operatingSystemTyp;

    /**
     * operating system sub typ
     * @var string
     */
    protected $operatingSystemTypSub;

    /**
     * engine
     * @var string
     */
    protected $engine;

    /**
     * engine version
     * @var string
     */
    protected $engineVersion;

    const BROWSER_IE = 'Internet Explorer';
    const BROWSER_FIREFOX = 'Firefox';
    const BROWSER_OPERA = 'Opera';
    const BROWSER_CHROME = 'Google Chrome';
    const BROWSER_SAFARI = 'Safari';

    const TYPE_BOT='bot';
    const TYPE_BROWSER='browser';

    const SYSTEM_MOBIL = 'mobil';
    const SYSTEM_CONSOLE = 'console';


    /**
     * Constructor
     */
    public function __construct($userAgentString = null, UAP $UAP = null)
    {
        $this->configureFromUserAgentString($userAgentString, $UAP);
    }

    /**
     *  Configure the User Agent from a user agent string.
     *  @param  String  $userAgentString => the user agent string.
     *  @param  UAP  $UAP => the parser used to parse the string.   
     */
    public function configureFromUserAgentString($userAgentString, UAP $UAP = null)
    {
        if($UAP == null)
        {
            $UAP = new UAP();
        }
        $this->setUserAgentString($userAgentString);
        $this->fromArray($UAP->parse($userAgentString));
    }

    public function getBrowserInfo()
    {
        $aBrowser = array();

        $aBrowser['name'] = $this->getBrowserName();
        $aBrowser['version'] = $this->getBrowserVersion();
        $aBrowser['engine'] = $this->getEngine() .' ' . $this->getEngineVersion();
        $aBrowser['os'] = $this->getOperatingSystem() .' ' . $this->getOperatingSystemName();

        return $aBrowser;
    }

    # --------------- AS IS ---------------
    /**
     * isBot
     * @return bool
     */
    public function isBot()
    {
        return (bool) ($this->getBrowserTyp() == self::TYPE_BOT);
    }

    /**
     * isIE
     * @return bool
     */
    public function isIE()
    {
        return (bool) ($this->getBrowserName() == self::BROWSER_IE);
    }

    /**
     * isFirefox
     * @return bool
     */
    public function isFirefox()
    {
        return (bool) ($this->getBrowserName() == self::BROWSER_FIREFOX);
    }

    /**
     * isOpera
     * @return bool
     */
    public function isOpera()
    {
        return (bool) ($this->getBrowserName() == self::BROWSER_OPERA);
    }

    /**
     * isChrome
     * @return bool
     */
    public function isChrome()
    {
        return (bool) ($this->getBrowserName() == self::BROWSER_CHROME);
    }

    /**
     * isSafari
     * @return bool
     */
    public function isSafari()
    {
        return (bool) ($this->getBrowserName() == self::BROWSER_SAFARI);
    }

    /**
     * isMobilSystem
     * @return bool
     */
    public function isMobilSystem()
    {
        return (bool) ($this->getOperatingSystemTyp() == self::SYSTEM_MOBIL);
    }

    /**
     * isConsoleSystem
     * @return bool
     */
    public function isConsoleSystem()
    {
        return (bool) ($this->getOperatingSystemTyp() == self::SYSTEM_CONSOLE);
    }



    # --------------- BROWSER ---------------
    /**
     *  Get Browser name
     *  @param void.
     *  @return String - the browser name
     */
    public function getBrowserName()
    {
        return $this->browserName;
    }

    /**
     *  Set Browser name
     *  @param String - the browser name; 
     *  @return none.
     */
    public function setBrowserName($name)
    {
        $this->browserName = $name;
    }

    /**
     *  Get Browser typ (bot, browser...)
     *  @param void.
     *  @return String - the browser typ
     */
    public function getBrowserTyp()
    {
        return $this->browserTyp;
    }

    /**
     *  Set Browser typ
     *  @param String - the browser typ; 
     *  @return none.
     */
    public function setBrowserTyp($name)
    {
        $this->browserTyp = $name;
    }

    /**
     *  Get Browser sub typ (validator, pda...)
     *  @param void.
     *  @return String - the browser sub typ
     */
    public function getBrowserTypSub()
    {
        return $this->browserTypSub;
    }

    /**
     *  Set Browser sub typ
     *  @param String - the browser sub typ; 
     *  @return none.
     */
    public function setBrowserTypSub($name)
    {
        $this->browserTypSub = $name;
    }

    /**
     *  Get Browser version
     *  @param void.
     *  @return String - the browser version
     */
    public function getBrowserVersion()
    {
        return $this->browserVersion; 
    }

    /**
     *  Set Browser version
     *  @param String - the browser version; 
     *  @return none.
     */
    public function setBrowserVersion($version)
    {
        $this->browserVersion = $version;
    }

    /**
     *  Get Browser version major
     *  @param void.
     *  @return String - the browser version major
     */
    public function getBrowserVersionMajor()
    {
        return $this->browserVersionMajor; 
    }

    /**
     *  Set Browser version major
     *  @param String - the browser version major; 
     *  @return none.
     */
    public function setBrowserVersionMajor($version)
    {
        $this->browserVersionMajor = $version;
    }

    /**
     *  Get Browser version minor
     *  @param void.
     *  @return String - the browser version minor
     */
    public function getBrowserVersionMinor()
    {
        return $this->browserVersionMinor; 
    }

    /**
     *  Set Browser version minor
     *  @param String - the browser version minor; 
     *  @return none.
     */
    public function setBrowserVersionMinor($version)
    {
        $this->browserVersionMinor = $version;
    }

    /**
     *  Get Browser version release
     *  @param void.
     *  @return String - the browser version release
     */
    public function getBrowserVersionRelease()
    {
        return $this->browserVersionRelease; 
    }

    /**
     *  Set Browser version release
     *  @param String - the browser version release; 
     *  @return none.
     */
    public function setBrowserVersionRelease($value)
    {
        if($value === null || empty($value)) $value = 0;
        $this->browserVersionRelease = $value;
    }

    /**
     *  Get Browser version build
     *  @param void.
     *  @return String - the browser version build
     */
    public function getBrowserVersionBuild()
    {
        return $this->browserVersionBuild; 
    }

    /**
     *  Set Browser version build
     *  @param String - the browser version build; 
     *  @return none.
     */
    public function setBrowserVersionBuild($value)
    {
        if($value === null || empty($value)) $value = 0;
        $this->browserVersionBuild = $value;
    }

    # --------------- OPERATING SYSTEM ---------------
    /**
     *  Get the operating system
     *  @param void.
     *  @return String - the operating system
     */
    public function getOperatingSystem()
    {
        return $this->operatingSystem; 
    }

    /**
     *  Set Operating System ( windows, linux ...)
     *  @param String - the operating system.
     *  @return none.
     */
    public function setOperatingSystem($os)
    {
        $this->operatingSystem = $os;
    }

    /**
     *  Get the operating system name ( vista, 2000, 7 ...)
     *  @param void.
     *  @return String - the operating system name
     */
    public function getOperatingSystemName()
    {
        return $this->operatingSystemName; 
    }

    /**
     *  Set Operating System typ ( os, mobile...)
     *  @param String - the operating system typ.
     *  @return none.
     */
    public function setOperatingSystemTyp($value)
    {
        $this->operatingSystemTyp = $value;
    }

    /**
     *  Get the operating system typ
     *  @param void.
     *  @return String - the operating system typ
     */
    public function getOperatingSystemTyp()
    {
        return $this->operatingSystemTyp; 
    }

    /**
     *  Set Operating System sub typ ( device...)
     *  @param String - the operating system sub typ.
     *  @return none.
     */
    public function setOperatingSystemTypSub($value)
    {
        $this->operatingSystemTypSub = $value;
    }

    /**
     *  Get the operating system sub typ
     *  @param void.
     *  @return String - the operating system sub typ
     */
    public function getOperatingSystemTypSub()
    {
        return $this->operatingSystemTypSub; 
    }

    /**
     *  Set Operating System name
     *  @param String - the operating system name.
     *  @return none.
     */
    public function setOperatingSystemName($value)
    {
        $this->operatingSystemName = $value;
    }

    # --------------- ENGINE ---------------
    /**
     *  Get the Engine Name
     *  @param void.
     *  @return String the engine name
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     *  Set Engine name
     *  @param String - the engine name
     *  @return none.
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
    }

    /**
     *  Get the Engine version
     *  @param void.
     *  @return String the engine version
     */
    public function getEngineVersion()
    {
        return $this->engineVersion;
    }

    /**
     *  Set Engine version
     *  @param String - the engine version
     *  @return none.
     */
    public function setEngineVersion($version)
    {
        $this->engineVersion = $version;
    }

    # --------------- USER AGENT ---------------
    /**
     *  Get the User Agent String
     *  @param void.
     *  @return String the User Agent string
     */
    public function getUserAgentString()
    {
        return $this->userAgentString;
    }

    /**
     *  Set Engine name
     *  @param String - the engine name
     *  @return none.
     */
    public function setUserAgentString($userAgentString)
    {
        $this->userAgentString = $userAgentString;
    }

    # --------------- INFO ---------------
    public function __toString()
    {
        return $this->getFullName();
    }

    /**
     *  Returns a string combined browser name plus version
     *  @param void.
     *  @return browser name plus version
     */
    public function getFullName()
    {
        return $this->getBrowserName() . ' ' . $this->getBrowserVersion();
    }

    /**
     *  Convert the http user agent to an array.
     *  @param void.
     */
    public function toArray()
    {
        return array(
            'user_agent' => $this->getUserAgentString(),
            'browser_name' => $this->getBrowserName(),
            'browser_typ' => $this->getBrowserTyp(),
            'browser_typ_sub' => $this->getBrowserTypSub(),
            'browser_version' => $this->getBrowserVersion(),
            'browser_version_major' => $this->getBrowserVersionMajor(),
            'browser_version_minor' => $this->getBrowserVersionMinor(),
            'browser_version_release' => $this->getBrowserVersionRelease(),
            'browser_version_build' => $this->getBrowserVersionBuild(),
            'operating_system' => $this->getOperatingSystem(),
            'operating_system_name' => $this->getOperatingSystemName(),
            'operating_system_typ' => $this->getOperatingSystemTyp(),
            'operating_system_typ_sub' => $this->getOperatingSystemTypSub(),
            'engine' => $this->getEngine(),
            'engine_version' => $this->getEngineVersion()
        ); 
    }

    /**
     *  Configure the user agent from an input array.
     *  @param Array $data input data array
     */
    public function fromArray(array $data)
    {
        $this->setUserAgentString($data['user_agent']); 
        $this->setBrowserName($data['browser_name']); 
        $this->setBrowserTyp($data['browser_typ']); 
        $this->setBrowserTypSub($data['browser_typ_sub']); 
        $this->setBrowserVersion($data['browser_version']);
        $this->setBrowserVersionMajor($data['browser_version_major']);
        $this->setBrowserVersionMinor($data['browser_version_minor']);
        $this->setBrowserVersionRelease($data['browser_version_release']);
        $this->setBrowserVersionBuild($data['browser_version_build']);
        $this->setBrowserTyp($data['browser_typ']);
        $this->setOperatingSystem($data['operating_system']);
        $this->setOperatingSystemName($data['operating_system_name']);
        $this->setOperatingSystemTyp($data['operating_system_typ']);
        $this->setOperatingSystemTypSub($data['operating_system_typ_sub']);
        $this->setEngine($data['engine']);
        $this->setEngineVersion($data['engine_version']);
    }

    /**
     *  This method tells whether this User Agent is unknown or not.
     *  @param none.
     *  @return TRUE is the User Agent is unknown, FALSE otherwise.
     */
    public function isUnknown()
    {
        return empty($this->browserName); 
    }

}

/**
 * -------------------------------------------------------------
 * User Agent Parser (UAP)
 * -------------------------------------------------------------
 */
class UAP
{
    const TYPE_UNKNOW = 'unknow';

    /**
     *  Parse a user agent string.
     *
     *  @param  (String) $userAgentString - defaults to $_SERVER['USER_AGENT'] if empty
     *  @return Array(
     *                'user_agent'     => 'mozilla/5.0 (windows; u; windows nt 5.1; de; rv:1.9.2.15) gecko/20110303 firefox/3.6.15 ( .net clr 3.5.30729)',
     *                'browser_name'     => 'firefox',
     *                'browser_type'     => 'browser',
     *                'browser_type_sub'     => '',
     *                'browser_version'  => '3.6',
     *                'browser_version_major'  => '3',
     *                'browser_version_minor'  => '6',
     *                'browser_version_release'  => '15',
     *                'browser_version_build'  => '',
     *                'operating_system' => 'windows'
     *                'operating_system_name' => 'xp'
     *                'operating_system_typ' => 'os'
     *                'operating_system_typ_sub' => 'os'
     *                'engine' => 'gecko'
     *                'engine_version' => '1.9.2.15'
     *               );
     */
    public function parse($userAgentString = null)
    {
        if(!$userAgentString)
        {
            $userAgentString = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
        }

        //parse quickly with medium accuracy
        $informations = $this->doParse($userAgentString); 

        # debug
        #var_dump( $informations );

        return $informations;
    }

    /**
     *  Detect quickly informations from the user agent string.
     *
     *  @param  (String) $userAgentString => user agent string.
     *  @return (Array)  $information     => user agent informations directly in array.
     */     
    public function doParse($userAgentString)
    {
        $userAgent = array(
            'string' => $this->cleanUserAgentString($userAgentString),
            'browser_name' => null,
            'browser_typ' => null,
            'browser_typ_sub' => null,
            'browser_version' => null,
            'browser_version_major' => null,
            'browser_version_minor' => null,
            'browser_version_release' => null,
            'browser_version_build' => null,
            'operating_system' => null,
            'operating_system_name' => null,
            'operating_system_typ' => null,
            'operating_system_typ_sub' => null,
            'engine' => null,
            'engine_version' => null
        );

        $userAgent['user_agent'] = $userAgent['string'];

        if(empty($userAgent['string']))
        {
            return $userAgent;
        }

        # --------------- Parse Browser ---------------
        $found = false;
        $tmp_array = array();

        foreach ($this->getListBrowsers() as $name => $elements)
        {
            # ----- read browser ----
            $exprReg = $elements['search'];
            foreach ($exprReg as $expr)
            {
                if(preg_match($expr, $userAgent['string'], $tmp_array))
                {
                    $userAgent['browser_name'] = $name;

                    $userAgent['browser_typ'] = $elements['type'];
                    if (isset($elements['subtype']))
                    {
                        $userAgent['browser_typ_sub'] = $elements['subtype'];
                    }
                    $found = true;

                    # ----- read version ----
                    if (isset($elements['vparam']))
                    {
                        $pattern = '';
                        $pv = $elements['vparam'];
                        $pattern = '|.+\s'.$pv.'([0-9a-z\+\-\.]+).*|';
                        $userAgent['browser_version'] = preg_replace($pattern, '$1', $userAgent['string']);
                        $tVer = preg_split("/\./", $userAgent['browser_version']);
                        $userAgent['browser_version_major'] = $tVer[0];
                        $userAgent['browser_version_minor'] = $tVer[1];
                        if( isset($tVer[2])) $userAgent['browser_version_release'] = $tVer[2];
                        if( isset($tVer[3])) $userAgent['browser_version_build'] = $tVer[3];
                    }
                    else
                    {
                        $userAgent['browser_version'] = self::TYPE_UNKNOW;
                    }

                    # ----- read engine ----
                    if (isset($elements['engine']))
                    {
                        $userAgent['engine'] = $elements['engine'];
                    }
                    else 
                    {
                        $userAgent['engine'] = self::TYPE_UNKNOW;
                    }

                    # ----- read engine version -----
                    $pattern = '';
                    if (isset($elements['eparam']))
                    {
                        $pe = $elements['eparam'];
                        $pattern = '|.+\s'.$pe.'([0-9\.]+)(.*).*|';
                        $userAgent['engine_version'] = preg_replace($pattern, '$1', $userAgent['string']);
                    }
                    else
                    {
                        $userAgent['engine_version'] = self::TYPE_UNKNOW;
                    }
                }
            }
        }

        if(false === $found)
        {
            $userAgent['browser_typ'] = self::TYPE_UNKNOW;
        }


        # --------------- Parse Operating System ---------------
        $found = false;
        $tmp_array = array();
        foreach ($this->getListOperatingSystems() as $name => $elements)
        {
            $exprReg = $elements['search'];
            foreach ($exprReg as $expr)
            {
                if(preg_match($expr, $userAgent['string'], $tmp_array))
                {
                    $userAgent['operating_system'] = $name;
                    if (isset($tmp_array) && isset($tmp_array[1]))
                    {
                        if (isset($elements['subsearch']))
                        {
                            foreach ($elements['subsearch'] as $sub => $expr)
                            {
                                if(preg_match($expr, $tmp_array[1]))
                                {
                                    $userAgent['operating_system_name'] = $sub;
                                }
                            }
                        }
                        if($userAgent['operating_system_name'] === null)
                        {
                            $userAgent['operating_system_name'] = (string) $tmp_array[1];
                        }
                    }
                    elseif (isset($elements['addsearch']))
                    {
                        foreach ($elements['addsearch'] as $sub => $expr)
                        {
                            if(preg_match($expr, $userAgent['string']))
                            {
                                $userAgent['operating_system_name'] = $sub;
                            }
                        }
                    }
                    if (isset($elements['type']))
                    {
                        $userAgent['operating_system_typ'] = $elements['type'];
                    }
                    else 
                    {
                        $userAgent['operating_system_typ'] = self::TYPE_UNKNOW;
                    }

                    if (isset($elements['subtype']))
                    {
                        $userAgent['operating_system_typ_sub'] = $elements['subtype'];
                    }

                    $found = true;
                }
            }
        }

        if(false === $found)
        {
            $userAgent['operating_system_typ'] = self::TYPE_UNKNOW;
        }

        return $userAgent;
    }

    /**
     *  Make user agent string lowercase, and replace browser aliases.
     *
     *  @param String $userAgentString => the dirty user agent string. 
     *  @param String $userAgentString => the clean user agent string.
     */
    public function cleanUserAgentString($userAgentString)
    {
        //clean up the string
        $userAgentString = trim(strtolower($userAgentString));  

        //replace browser names with their aliases
        #$userAgentString = strtr($userAgentString, $this->getListBrowserAliases()); 

        //replace engine names with their aliases
        #$userAgentString = strtr($userAgentString, $this->getListEngineAliases()); 

        return $userAgentString;
    }

    # -----------------------------------------------------------
    #  GET Browsers + OS  - Definitions
    # -----------------------------------------------------------

    /**
     *  Get browsers list
     *  
     *  @param void.
     *  @return Array of browsers
     *
     * @todo file catching for performance
     */
    protected function getListBrowsers()
    {
        $aList = array();

        include 'browser/bot.php';
        foreach( $bot as $name =>$row )
        {
            $aList[$name] = $row;
        }

        include 'browser/browser.php';
        foreach( $browser as $name =>$row )
        {
            $aList[$name] = $row;
        }

/*
        include 'browser/mobile.php';
        foreach( $mobile as $name =>$row )
        {
            $aList[$name] = $row;
        }

        include 'browser/console.php';
        foreach( $console as $name =>$row )
        {
            $aList[$name] = $row;
        }
*/

        #var_dump($aList);

        return $aList;

    }

    # --------------------- OPERATING SYSTEM ---------------------
    /**
     *  Get operating system list
     *  
     *  @param void.
     *  @return array => the operating system.
     */
    protected function getListOperatingSystems()
    {

        $aList = array();

        include 'browser/os.php';
        foreach( $os as $name =>$row )
        {
            $aList[$name] = $row;
        }

        #var_dump($aList);

        return $aList;

    }

}
?>