<?php
abstract class Clansuite_Config_Base
{
    /**
     * Returns $this->config Object as Array
     *
     * @access   public
     * @return   config array
     */
    public function toArray()
    {
        $array = array();
        $array = $this->config;
        return $array;
    }

    /**
     * Gets a config file item based on keyname
     *
     * @access   public
     * @param    string    the config item key
     * @return   void
     */
    public function __get($configkey)
    {
        if ( isset($this->config[$configkey]) )
        {
            return $this->config[$configkey];
        }
        else
        {
            return null;
        }
    }

    /**
     * Set a config file item based on key:value
     *
     * @access   public
     * @param    string    the config item key
     * @param    string    the config item value
     * @return   void
     *
     */
    public function __set($configkey, $configvalue)
    {
        $this->config[$configkey] = $configvalue;
        return true;
    }

    // method that will allow 'isset' to work on these variables
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    // method to allow 'unset' calls to work on these variables
    public function __unset($key)
    {
        unset($this->config[$key]);
        #echo 'Variable was unset:'. $key;
    }

    /**
     * Implementation of SPL ArrayAccess
     */
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
        return true;
    }
}
?>