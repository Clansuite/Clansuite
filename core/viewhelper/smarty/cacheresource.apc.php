<?php

/**
 * Smarty Plugin CacheResource APC
 *
 * Implements APC resource for the HTML cache
 *
 * @package Smarty
 * @subpackage CacheResource
 * @author Monte Ohrt
 */

/**
 * This class does contain all necessary methods for the HTML cache with APC
 *
 * @example
 * $smarty->cache->loadResource('apc');
 * $smarty->setCaching(true);
 */
class Smarty_CacheResource_APC
{

    function __construct($smarty)
    {
        $this->smarty = $smarty;

        # test if APC is present
        if(!function_exists('apc_cache_info'))
        {
            throw new Exception('APC Template Caching Error: APC is not installed');
        }
    }

    /**
     * Returns the filepath of the cached template output
     *
     * @param object $_template current template
     * @return string the cache filepath
     */
    public function getCachedFilepath($_template)
    {
        return md5($_template->getTemplateFilepath() . $_template->cache_id . $template->compile_id);
    }

    /**
     * Returns the timpestamp of the cached template output
     *
     * @param object $_template current template
     * @return integer |booelan the template timestamp or false if the file does not exist
     */
    public function getCachedTimestamp($_template)
    {
        apc_fetch($this->getCachedFilepath($_template), $success);
        return $success ? time() : false;
    }

    /**
     * Returns the cached template output
     *
     * @param object $_template current template
     * @return string |booelan the template content or false if the file does not exist
     */
    public function getCachedContents($_template)
    {
        $_cache_content = apc_fetch($this->getCachedFilepath($_template));
        $_smarty_tpl = $_template;
        ob_start();
        eval("?>" . $_cache_content);
        return ob_get_clean();
    }

    /**
     * Writes the rendered template output to cache file
     *
     * @param object $_template current template
     * @return boolean status
     */
    public function writeCachedContent($_template, $content)
    {
        return apc_store($this->getCachedFilepath($_template), $content, $_template->cache_lifetime);
    }

    /**
     * Empty cache folder
     *
     * @param integer $exp_time expiration time
     * @return integer number of cache files deleted
     */
    public function clearAll($exp_time = null)
    {
        return apc_clear_cache('user');
    }

    /**
     * Empty cache for a specific template
     *
     * @param string $resource_name template name
     * @param string $cache_id cache id
     * @param string $compile_id compile id
     * @param integer $exp_time expiration time
     * @return integer number of cache files deleted
     */
    public function clear($resource_name, $cache_id, $compile_id, $exp_time)
    {
        return apc_delete(md5($resource_name . $cache_id . $compile_id));
    }

}

?>