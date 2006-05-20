<?php
// unsortiert
// hilfsfunktionen
// todo: abhändigkeiten auflösen und verkleinern bzw. entsprechend der funktion
// einzeln filen

function include_header($title)
{ global $User, $MainPage;

$MainPage->assign('title', $title);

}

/**
* Debug-Funktion for Globale Variablen
*
* TODO: ARRAY-Inhalt einer GLOBALEN VARIABLE ausgeben
*/
	function DebugGlobals()
	{
		GLOBAL $GLOBALS;
		if($GLOBALS)
		{
			reset($GLOBALS);
			while(list($key, $val) = each($GLOBALS))
				$TMP1 .= "GLOBALS: [ ".$key."=".$val." ] <br>";
			echo "<p> Debug Array for all GLOBALS | <pre>",print_r($TMP1),"</pre>";
		}	
	}

function stripQuotes(&$data) {
    if (is_array($data)) {
        foreach ($data as $k => $v) {
            stripQuotes($data[$k]);
        }
    } else {
        $data = stripslashes($data);
    }
}

if (ini_get('magic_quotes_gpc')) {
    stripQuotes($_GET);
    stripQuotes($_POST);
    stripQuotes($_COOKIE);
}


// Get real remote address
// Author: Cezary Tomczak [www.gosu.pl]
// Unroutable address spaces  10.x.x.x , 172.16.x.x-172.31.x.x , 192.168.x.x
function getRemoteAddr() {
    $ip = false;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    if ($ip) {
        $a = explode('.', $ip);
        if (count($a) != 4) $ip = false;
        foreach ($a as $k => $v) {
            $a[$k] = (int) $v;
            if (!is_numeric($v)) $ip = false;
        }
        if ($ip && $a[0] == 10) $ip = false;
        if ($ip && $a[0] == 172) {
            if ($a[1] >= 16 && $a[1] <= 31) $ip = false;
        }
        if ($ip && $a[0] == 192 && $a[1] == 168) $ip = false;
    }
    if (!$ip) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function getRemoteHost() {
    $ip = getRemoteAddr();
    return ($host = @getHostByAddr($ip)) != $ip ? $host : '';
}


// +----------------------------------------------------------------+
// | Class to access ini values at format "section_name.property",  |
// | for example $myconf->get("system.name") returns                |
// | a property "name" in section "system":                         |
// | Author: dimk@pisem.net 14-Jul-2005 06:33                       |
// | Posted @ php-manual : http://de2.php.net/parse_ini_file        |
// +----------------------------------------------------------------+

class Settings {

public $properties = array();

   function Settings() {
       $this->properties = parse_ini_file(_SETTINGS_FILE, true);
   }

   function get($name) {
       if(strpos($name, ".")) {
           list($section_name, $property) = explode(".", $name);
           $section =& $this->properties[$section_name];
           $name = $property;
       } else {
           $section =& $properties;
       }

       if(is_array($section) && isset($section[$name])) {
           return $section[$name];
       }
       return false;
   }

}







// +----------------------------------------------------------------+
// | Uploading files.                                               |
// | Author: Cezary Tomczak [www.gosu.pl]                           |
// | Free for any use as long as all copyright messages are intact. |
// +----------------------------------------------------------------+

class Upload {
    private $chmod = 604;
    public $tmp, $filename, $type, $size, $error;
    function Upload($name) {
        if (isset($_FILES[$name])) {
            $this->tmp      = @$_FILES[$name]['tmp_name'];
            $this->filename = @$_FILES[$name]['name'];
            $this->type     = @$_FILES[$name]['type'];
            $this->size     = @$_FILES[$name]['size'];
            $this->error    = @$_FILES[$name]['error'];
        }
        $this->filename = basename($this->filename);
        $this->size = (int) $this->size;
    }
    function isValid() {
        if ( (!file_exists($this->tmp) || !is_file($this->tmp))
            || !is_uploaded_file($this->tmp) || $this->size == 0 || !$this->filename ||
            (isset($this->error) && UPLOAD_ERR_OK !== $this->error) )
        {
            return false;
        }
        return true;
    }
    // Returns for example: '.gif' or null
    function getExtension() {
        $s = $this->filename;
        $ext = null;
        if (($pos = strrpos($s, '.')) !== false) {
            $len = strlen($s) - $pos;
            $ext = substr($s, -$len);
        }
        return $ext;
    }
    function moveTo($path, $chmod = null) {
        if (!isset($chmod)) { $chmod = $this->chmod; }
        if (isset($chmod) && !is_numeric($chmod)) { return trigger_error('Upload::moveTo() failed, chmod must be a decimal number, for example 604', E_USER_ERROR); }
        $ret = move_uploaded_file($this->tmp, $path);
        if ($ret && isset($chmod)) {
            $chmod = octdec('0' . $chmod);
            $ret = chmod($path, $chmod);
        }
        return $ret;
    }
}

// +----------------------------------------------------------------+
// | Getters for GET, POST, COOKIE variables.                       |
// | Author: Cezary Tomczak [www.gosu.pl]                           |
// | Free for any use as long as all copyright messages are intact. |
// +----------------------------------------------------------------+

function get($name) {
    if (func_num_args() > 1) {
        $name = func_get_args();
    }
    if (is_array($name)) {
        $ret = array();
        foreach ($name as $v) {
           $ret[$v] = get($v);
        }
        return $ret;
    }
    if (isset($_GET[$name])) {
        return $_GET[$name];
    }
    return null;
}

function post($name) {
    if (func_num_args() > 1) {
        $name = func_get_args();
    }
    if (is_array($name)) {
        $ret = array();
        foreach ($name as $v) {
           $ret[$v] = post($v);
        }
        return $ret;
    }
    if (isset($_POST[$name])) {
        return $_POST[$name];
    }
    return null;
}

function cookie($name) {
    if (isset($_COOKIE[$name])) { return $_COOKIE[$name]; }
    else { return null; }
}


function unhtmlspecialchars( $string )
{
  $string = str_replace ( '&amp;', '&', $string );
  $string = str_replace ( '&#039;', '\'', $string );
  $string = str_replace ( '&quot;', '"', $string );
  $string = str_replace ( '&lt;', '<', $string );
  $string = str_replace ( '&gt;', '>', $string );
  $string = str_replace ( '&uuml;', 'ï¿½', $string );
  $string = str_replace ( '&Uuml;', 'ï¿½', $string );
  $string = str_replace ( '&auml;', 'ï¿½', $string );
  $string = str_replace ( '&Auml;', 'ï¿½', $string );
  $string = str_replace ( '&ouml;', 'ï¿½', $string );
  $string = str_replace ( '&Ouml;', 'ï¿½', $string );   
  return $string;
}

?>