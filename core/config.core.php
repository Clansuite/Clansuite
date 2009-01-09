<?php

/**
 * Clansuite_Factory
 * |
 * \-- Clansuite_Config_xyHandler
 *     |
 *     \-- Clansuite_Config
 */
class Clansuite_Config extends Clansuite_Config_Base implements ArrayAccess
{
    # object
    public $confighandler;

    # array
    public $config;

    function __construct($configfile = 'configuration/clansuite.config.php')
    {
        $this->confighandler = Clansuite_Config_Factory::getConfiguration($configfile);
        $this->config = $this->confighandler->toArray();
    }

    public function readConfig($configfile)
    {
        if( ! is_object($this->confighandler))
        {
            $this->confighandler = Clansuite_Config_Factory::getConfiguration($configfile);
        }

        # @todo check if confighandler is of that configfile, else readConfig
        # check object name auf teilstring configtype object(Clansuite_Config_INIHandler)
        return $this->confighandler->readConfig($configfile);
    }

    /**
     * Clansuite_Config
     *
     * @param object $filename Filename
     *
     * @return instance of Clansuite_Config
     */
    public static function getInstance($filename)
    {
    	static $instance;

        if ( ! isset($instance))
        {
            $instance = new Clansuite_Config($filename);
        }

        return $instance;
    }
}
?>