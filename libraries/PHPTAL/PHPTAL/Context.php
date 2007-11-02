<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//  
//  Copyright (c) 2004-2005 Laurent Bedubourg
//  
//  This library is free software; you can redistribute it and/or
//  modify it under the terms of the GNU Lesser General Public
//  License as published by the Free Software Foundation; either
//  version 2.1 of the License, or (at your option) any later version.
//  
//  This library is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
//  Lesser General Public License for more details.
//  
//  You should have received a copy of the GNU Lesser General Public
//  License along with this library; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//  
//  Authors: Laurent Bedubourg <lbedubourg@motion-twin.com>
//  

/**
 * This class handles template execution context.
 * @package phptal
 */
class PHPTAL_Context
{
    public static $USE_GLOBAL = false;
    
    public $__line = false;
    public $__file = false;
    public $__repeat;
    public $__xmlDeclaration;
    public $__docType;
    public $__nothrow;
    public $__translator;

    public function __construct()
    {
        $this->__repeat = new StdClass();
    }

    public function __clone()
    {
        $this->__repeat = clone($this->__repeat);
    }

	public function setParent(PHPTAL_Context $parent)
	{
		$this->_parentContext = $parent;
	}

    public function setGlobal(StdClass $globalContext)
    {
        $this->_globalContext = $globalContext;
    }

    public function pushContext()
    {
        if (self::$USE_GLOBAL) return $this;
        $res = clone $this;
        $res->setParent($this);
        return $res;
    }

    public function popContext()
    {
        if (self::$USE_GLOBAL) return $this;
        return $this->_parentContext;
    }

    /** 
     * Set output document type if not already set.
     *
     * This method ensure PHPTAL uses the first DOCTYPE encountered (main
     * template or any macro template source containing a DOCTYPE.
     */
    public function setDocType($doctype)
    {
		if ($this->_parentContext != null){
			return $this->_parentContext->setDocType($doctype);
		}
        if ($this->_parentContext != null){
            return $this->_parentContext->setDocType($doctype);
        }
        if (!$this->__docType){
            $this->__docType = $doctype;
        }
    }

    /**
     * Set output document xml declaration.
     *
     * This method ensure PHPTAL uses the first xml declaration encountered
     * (main template or any macro template source containing an xml
     * declaration).
     */
    public function setXmlDeclaration($xmldec)
    {
		if ($this->_parentContext != null){
			return $this->_parentContext->setXmlDeclaration($xmldec);
		}
        if ($this->_parentContext != null){
            return $this->_parentContext->setXmlDeclaration($xmldec);
        }
        if (!$this->__xmlDeclaration){
            $this->__xmlDeclaration = $xmldec;
        }
    }

    /** 
     * Activate or deactivate exception throwing during unknown path
     * resolution.
     */
    public function noThrow($bool)
    {
        $this->__nothrow = $bool;
    }

    /**
     * Returns true if specified slot is filled.
     */
    public function hasSlot($key)
    {
        return array_key_exists($key, $this->_slots);
    }

    /**
     * Returns the content of specified filled slot.
     */
    public function getSlot($key)
    {
        return $this->_slots[$key];
    }

    /**
     * Fill a macro slot.
     */
    public function fillSlot($key, $content)
    {
        $this->_slots[$key] = $content;
    }

    /**
     * Push current filled slots on stack.
     */
    public function pushSlots()
    {
        array_push($this->_slotsStack, $this->_slots);
        $this->_slots = array();
    }

    /**
     * Restore filled slots stack.
     */
    public function popSlots()
    {
        $this->_slots = array_pop($this->_slotsStack);
    }

    /**
     * Context setter.
     */
    public function __set($varname, $value)
    {
        if ($varname[0] == '_'){
            $e = 'Template variable error \'%s\' must not begin with underscore';
            $e = sprintf($e, $varname);
            throw new Exception($e);
        }
        $this->$varname = $value;
    }

    /**
     * Context getter.
     */
    public function __get($varname)
    {
        if ($varname == 'repeat')
            return $this->__repeat;

        if (isset($this->$varname)){
            return $this->$varname;
        }

        if (isset($this->_globalContext->$varname)){
            return $this->_globalContext->$varname;
        }
            
        if ($this->__nothrow)
            return null;
       
        $e = sprintf('Unable to find path %s', $varname); 
        throw new PHPTAL_Exception($e, $this->__file, $this->__line);
    }

    private $_slots = array();
    private $_slotsStack = array();
	private $_parentContext = null;
    private $_globalContext = null;
}

// emulate property_exists() function, this is slow but much better than
// isset(), use next release of PHP5 as soon as available !
if (!function_exists('property_exists')){
    function property_exists($o, $property)
    {
        return array_key_exists($property, get_object_vars($o));
    }
}


/**
 * Resolve TALES path starting from the first path element.
 *
 * The TALES path : object/method1/10/method2
 * will call : phptal_path($ctx->object, 'method1/10/method2')
 *
 * The nothrow param is used by phptal_exists() and prevent this function to
 * throw an exception when a part of the path cannot be resolved, null is
 * returned instead.
 */
function phptal_path($base, $path, $nothrow=false)
{//{{{
    $parts   = split('/', $path);
    $current = true;

    while (($current = array_shift($parts)) !== null){
        // object handling
        if (is_object($base)){
            // look for method
            if (method_exists($base, $current)){
                $base = $base->$current();
                continue;
            }
            
            // look for variable
            if (property_exists($base, $current)){
                $base = $base->$current;
                continue;
            }
            
            // look for isset (priority over __get)
            if (method_exists($base, '__isset')){
                if ($base->__isset($current)){
                    $base = $base->$current;
                    continue;
                }
            }
            // ask __get and discard if it returns null
            else if (method_exists($base, '__get')){
                $tmp = $base->$current;
                if (!is_null($tmp)){
                    $base = $tmp;
                    continue;
                }
            }

            // magic method call
            if (method_exists($base, '__call')){
                $base = $base->$current();
                continue;
            }

            // emulate array behaviour
            if (is_numeric($current) && method_exists($base, '__getAt')){
                $base = $base->__getAt($current);
                continue;
            }
            
            if ($nothrow)
                return null;

            $err = 'Unable to find part "%s" in path "%s"';
            $err = sprintf($err, $current, $path);
            throw new Exception($err);
        }

        // array handling
        if (is_array($base)) {
            // key or index
            if (array_key_exists((string)$current, $base)){
                $base = $base[$current];
                continue;
            }

            // virtual methods provided by phptal
            if ($current == 'length' || $current == 'size'){
                $base = count($base);
                continue;
            }

            if ($nothrow)
                return null;

            $err = 'Unable to find array key "%s" in path "%s"';
            $err = sprintf($err, $current, $path);
            throw new Exception($err);
        }

        // string handling
        if (is_string($base)) {
            // virtual methods provided by phptal
            if ($current == 'length' || $current == 'size'){
                $base = strlen($base);
                continue;
            }

            // access char at index
            if (is_int($current)){
                $base = $base[$current];
                continue;
            }
        }

        // if this point is reached, then the part cannot be resolved
        
        if ($nothrow)
            return null;
        
        $err = 'Unable to find part "%s" in path "%s"';
        $err = sprintf($err, $current, $path);
        throw new Exception($err);
    }

    return $base;
}

function phptal_true($ctx, $path)
{
    $ctx->noThrow(true);
    $res = phptal_path($ctx, $path, true);
    $ctx->noThrow(false);
    return $res != null;
}

/** 
 * Returns true if $path can be fully resolved in $ctx context. 
 */
function phptal_exists($ctx, $path)
{
    // special note: this method may requires to be extended to a full
    // phptal_path() sibling to avoid calling latest path part if it is a
    // method or a function...
    $ctx->noThrow(true);
    $res = phptal_path($ctx, $path, true);
    $ctx->noThrow(false);
    return !is_null($res);
}

function phptal_isempty($var)
{
	return $var === null || $var === false || $var === '';
}

function phptal_escape($var, $ent, $encoding)
{
    if (is_object($var)){
        return htmlspecialchars($var->__toString(), $ent, $encoding);
    }
    if (is_string($var)){
        return htmlspecialchars($var, $ent, $encoding);
    }
    if (is_bool($var)){
        return (int)$var;
    }
    return $var;	
}
?>
