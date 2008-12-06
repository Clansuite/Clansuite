<?php
abstract class Clansuite_Config_Base()
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
        return isset($this->config[$configkey]) ? $this->config[$configkey] : null;
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
        #if (isset($this->config[$configkey]) == true) {
        #       throw new Exception('Variable ' . $configkey . ' already set.');
        #}

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

    // hmm? why should configuration be unset?
    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
        return true;
    }
}
?>