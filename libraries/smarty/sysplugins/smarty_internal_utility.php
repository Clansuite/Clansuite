<?php

/**
* Project:     Smarty: the PHP compiling template engine
* File:        smarty_internal_utility.php
* SVN:         $Id$
* 
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
* 
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
* 
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* 
* For questions, help, comments, discussion, etc., please join the
* Smarty mailing list. Send a blank e-mail to
* smarty-discussion-subscribe@googlegroups.com
* 
* @link http://www.smarty.net/
* @copyright 2008 New Digital Group, Inc.
* @author Monte Ohrt <monte at ohrt dot com> 
* @author Uwe Tews 
* @package Smarty
* @subpackage PluginsInternal
* @version 3-SVN$Rev$
*/

class Smarty_Internal_Utility {
  
    protected $smarty;

    function __construct($smarty) {
      $this->smarty = $smarty;
    }
    
    /**
    * Compile all template files
    * 
    * @param string $extension file extension
    * @param bool $force_compile force all to recompile
    * @param int $time_limit
    * @param int $max_errors
    * @return integer number of template files recompiled
    */
    function compileAllTemplates($extention = '.tpl', $force_compile = false, $time_limit = 0, $max_errors = null)
    {
        function _get_time()
        {
            $_mtime = microtime();
            $_mtime = explode(" ", $_mtime);
            return (double)($_mtime[1]) + (double)($_mtime[0]);
        } 
        // set default directory
        if ($dir_name === null) {
            $dir_name = $this->smarty->template_dir;
        } 
        // switch off time limit
        if (function_exists('set_time_limit')) {
            @set_time_limit($time_limit);
        } 
        $this->smarty->force_compile = $force_compile;
        $_count = 0;
        $_error_count = 0; 
        // loop over array of template directories
        foreach((array)$this->smarty->template_dir as $_dir) {
            $_compileDirs = new RecursiveDirectoryIterator($_dir);
            $_compile = new RecursiveIteratorIterator($_compileDirs);
            foreach ($_compile as $_fileinfo) {
                if (strpos($_fileinfo, '.svn') !== false) continue;
                $_file = $_fileinfo->getFilename();
                if (!substr_compare($_file, $extention, - strlen($extention)) == 0) continue;
                if ($_fileinfo->getPath() == substr($_dir, 0, -1)) {
                    $_template_file = $_file;
                } else {
                    $_template_file = substr($_fileinfo->getPath(), strlen($_dir)) . '\\' . $_file;
                } 
                echo '<br>', $_dir, '---', $_template_file;
                flush();
                $_start_time = _get_time();
                try {
                    $_tpl = $this->smarty->createTemplate($_template_file);
                    $_tpl->getCompiledTemplate();
                } 
                catch (Exception $e) {
                    echo 'Error: ', $e->getMessage(), "<br><br>";
                    $_error_count++;
                } 
                echo ' done in  ', _get_time() - $_start_time, ' seconds';
                if ($max_errors !== null && $_error_count == $max_errors) {
                    echo '<br><br>too many errors';
                    exit();
                } 
            } 
        } 
        return $_count;
    } 

    /**
    * Delete compiled template file
    * 
    * @param string $resource_name template name
    * @param string $compile_id compile id
    * @param integer $exp_time expiration time
    * @return integer number of template files deleted
    */
    function clearCompiledTemplate($resource_name = null, $compile_id = null, $exp_time = null)
    {
        $_compile_id =  isset($compile_id) ? preg_replace('![^\w\|]+!','_',$compile_id) : null;
        $_dir_sep = $this->smarty->use_sub_dirs ? DS : '^';
        if (isset($resource_name)) {
            $_resource_part_1 = $resource_name . '.php';
            $_resource_part_2 = $resource_name . '.cache' . '.php';
        } else {
            $_resource_part = '';
        } 
        $_dir = $this->smarty->compile_dir;
        if ($this->smarty->use_sub_dirs && isset($_compile_id)) {
            $_dir .= $_compile_id . $_dir_sep;
        } 
        if (isset($_compile_id)) {
            $_compile_id_part = $this->smarty->compile_dir . $_compile_id . $_dir_sep;
        } 
        $_count = 0;
        $_compileDirs = new RecursiveDirectoryIterator($_dir);
        $_compile = new RecursiveIteratorIterator($_compileDirs, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($_compile as $_file) {
            if (strpos($_file, '.svn') !== false) continue;
            if ($_file->isDir()) {
                if (!$_compile->isDot()) {
                    // delete folder if empty
                    @rmdir($_file->getPathname());
                } 
            } else {
                if ((!isset($_compile_id) || (strlen((string)$_file) > strlen($_compile_id_part) && substr_compare((string)$_file, $_compile_id_part, 0, strlen($_compile_id_part)) == 0)) &&
                        (!isset($resource_name) || (strlen((string)$_file) > strlen($_resource_part_1) && substr_compare((string)$_file, $_resource_part_1, - strlen($_resource_part_1), strlen($_resource_part_1)) == 0) ||
                            (strlen((string)$_file) > strlen($_resource_part_2) && substr_compare((string)$_file, $_resource_part_2, - strlen($_resource_part_2), strlen($_resource_part_2)) == 0))) {
                    if (isset($exp_time)) {
                        if (time() - @filemtime($_file) >= $exp_time) {
                            $_count += @unlink((string) $_file) ? 1 : 0;
                        } 
                    } else {
                        $_count += @unlink((string) $_file) ? 1 : 0;
                    } 
                } 
            } 
        } 
        return $_count;
    }
    
    function testInstall()
    {
        echo "<PRE>\n";
    
        echo "Smarty Installation test...\n";
    
        echo "Testing template directory...\n";
    
        foreach((array)$this->smarty->template_dir as $template_dir) {
            if (!is_dir($template_dir))
                echo "FAILED: $template_dir is not a directory.\n";
            elseif (!is_readable($template_dir))
                echo "FAILED: $template_dir is not readable.\n";
            else
                echo "$template_dir is OK.\n";
        } 
    
        echo "Testing compile directory...\n";
    
        if (!is_dir($this->smarty->compile_dir))
            echo "FAILED: $this->smarty->compile_dir is not a directory.\n";
        elseif (!is_readable($this->smarty->compile_dir))
            echo "FAILED: $this->smarty->compile_dir is not readable.\n";
        elseif (!is_writable($this->smarty->compile_dir))
            echo "FAILED: $this->smarty->compile_dir is not writable.\n";
        else
            echo "{$this->smarty->compile_dir} is OK.\n";
    
        echo "Testing plugins directory...\n";
    
        foreach((array)$this->smarty->plugins_dir as $plugin_dir) {
            if (!is_dir($plugin_dir))
                echo "FAILED: $plugin_dir is not a directory.\n";
            elseif (!is_readable($plugin_dir))
                echo "FAILED: $plugin_dir is not readable.\n";
            else
                echo "$plugin_dir is OK.\n";
        } 
    
        echo "Testing cache directory...\n";
    
        if (!is_dir($this->smarty->cache_dir))
            echo "FAILED: $this->smarty->cache_dir is not a directory.\n";
        elseif (!is_readable($this->smarty->cache_dir))
            echo "FAILED: $this->smarty->cache_dir is not readable.\n";
        elseif (!is_writable($this->smarty->cache_dir))
            echo "FAILED: $this->smarty->cache_dir is not writable.\n";
        else
            echo "{$this->smarty->cache_dir} is OK.\n";
    
        echo "Testing configs directory...\n";
    
        if (!is_dir($this->smarty->config_dir))
            echo "FAILED: $this->smarty->config_dir is not a directory.\n";
        elseif (!is_readable($this->smarty->config_dir))
            echo "FAILED: $this->smarty->config_dir is not readable.\n";
        else
            echo "{$this->smarty->config_dir} is OK.\n";
    
        echo "Tests complete.\n";
    
        echo "</PRE>\n";
    
        return true;
    } 
    
}