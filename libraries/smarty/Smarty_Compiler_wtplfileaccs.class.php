<?php

/**
 * Extension of the original Template compiling class
 *
 * This just adds 2 lines of code and slightly modifies 2 lines (from the
 * Smarty_Compiler class in the Smarty_Compiler.class.php file) in one function and
 * one line in another function; search for "Brett" below to see what was changed.
 *
 * These changes were essential (as far as I could tell) to allow the names of
 * included templates to be retrieved later (post-compilation) as associated with
 * their content (in this case, I wanted to have doc_raw be able to selectively
 * indicate in its comments which css, scripts, etc. were being added in which
 * files, since when stringing them all together, it would otherwise be cumbersome
 * if not impossible to detect which style, etc. came from which template)
 *
 * @package SmartyDocB
 * @subpackage Smarty
 *
 * @copyright   brettz9 2006-2007
 * @author	    brettz9 <brettz9@yahoo.com>
 * @license     LGPL license.txt
 *
 * @version     SVN: $Id$
 */
 
class Smarty_Compiler_wtplfileaccs extends Smarty_Compiler 
{
    
    /**
     * compile custom function tag
     *
     * @param string 
     * @param string 
     * @param string
     * @param string
     *
     * @return string
     */    
    function _compile_block_tag($tag_command, $tag_args, $tag_modifier, &$output)
    {

        if (substr($tag_command, 0, 1) == '/') 
        {
            $start_tag = false;
            $tag_command = substr($tag_command, 1);
        } else
            $start_tag = true;

        $found = false;
        $have_function = true;

       /*
         * First we check if the block function has already been registered
         * or loaded from a plugin file.
         */
        if (isset($this->_plugins['block'][$tag_command])) {
            $found = true;
            $plugin_func = $this->_plugins['block'][$tag_command][0];

            if (!is_callable($plugin_func)) {
                $message = "block function '$tag_command' is not implemented";
                $have_function = false;
            }
        }
        /*
         * Otherwise we need to load plugin file and look for the function
         * inside it.
         */
        else if ($plugin_file = $this->_get_plugin_filepath('block', $tag_command)) {
            $found = true;

            include_once $plugin_file;

            $plugin_func = 'smarty_block_' . $tag_command;
            if (!function_exists($plugin_func)) {
                $message = "plugin function $plugin_func() not found in $plugin_file\n";
                $have_function = false;
            } else {
                $this->_plugins['block'][$tag_command] = array($plugin_func, null, null, null, true);

            }
        }

        if (!$found) {
            return false;
        } else if (!$have_function) {
            $this->_syntax_error($message, E_USER_WARNING, __FILE__, __LINE__);
            return true;
        }

        /*
         * Even though we've located the plugin function, compilation
         * happens only once, so the plugin will still need to be loaded
         * at runtime for future requests.
         */
        $this->_add_plugin('block', $tag_command);

        if ($start_tag)
            $this->_push_tag($tag_command);
        else
            $this->_pop_tag($tag_command);

        if ($start_tag) {
// Brett adding:
			$output = '<?php $this->currfileblock = "'.$this->_current_file.'"; ?>';
// Brett modified the following to be .= instead of just =
            $output .= '<?php ' . $this->_push_cacheable_state('block', $tag_command);
            $attrs = $this->_parse_attrs($tag_args);
            $_cache_attrs='';
            $arg_list = $this->_compile_arg_list('block', $tag_command, $attrs, $_cache_attrs);
            $output .= "$_cache_attrs\$this->_tag_stack[] = array('$tag_command', array(".implode(',', $arg_list).')); ';
            $output .= '$_block_repeat=true;' . $this->_compile_plugin_call('block', $tag_command).'($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);';
            $output .= 'while ($_block_repeat) { ob_start(); ?>';
        } else {
// Brett adding:
			$output = '<?php $this->currfileblock = "'.$this->_current_file.'"; ?>';

// Brett modified the following to be .= instead of just =
            $output .= '<?php $_block_content = ob_get_contents(); ob_end_clean(); ';
            $_out_tag_text = $this->_compile_plugin_call('block', $tag_command).'($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat)';
            if ($tag_modifier != '') {
                $this->_parse_modifiers($_out_tag_text, $tag_modifier);
            }
            $output .= '$_block_repeat=false;echo ' . $_out_tag_text . '; } ';
            $output .= " array_pop(\$this->_tag_stack); " . $this->_pop_cacheable_state('block', $tag_command) . '?>';

        }

        return true;
    }

    /**
     * compile custom function tag
     *
     * @param string 
     * @param string 
     * @param string
     * @param string
     *
     * @return string
     */
    function _compile_custom_tag($tag_command, $tag_args, $tag_modifier, &$output)
    {
        $found = false;
        $have_function = true;

        /*
         * First we check if the custom function has already been registered
         * or loaded from a plugin file.
         */
        if (isset($this->_plugins['function'][$tag_command])) {
            $found = true;
            $plugin_func = $this->_plugins['function'][$tag_command][0];
            if (!is_callable($plugin_func)) {
                $message = "custom function '$tag_command' is not implemented";
                $have_function = false;
            }
        }
        /*
         * Otherwise we need to load plugin file and look for the function
         * inside it.
         */
        else if ($plugin_file = $this->_get_plugin_filepath('function', $tag_command)) {
            $found = true;

            include_once $plugin_file;

            $plugin_func = 'smarty_function_' . $tag_command;
            if (!function_exists($plugin_func)) {
                $message = "plugin function $plugin_func() not found in $plugin_file\n";
                $have_function = false;
            } else {
                $this->_plugins['function'][$tag_command] = array($plugin_func, null, null, null, true);

            }
        }

        if (!$found) {
            return false;
        } else if (!$have_function) {
            $this->_syntax_error($message, E_USER_WARNING, __FILE__, __LINE__);
            return true;
        }

        /* declare plugin to be loaded on display of the template that
           we compile right now */
        $this->_add_plugin('function', $tag_command);

        $_cacheable_state = $this->_push_cacheable_state('function', $tag_command);

					// Brett added
					if(empty($this->doc_infoalias)){ $this->doc_infoalias = null; }
					if ($tag_command === $this->doc_infoalias || $tag_command === 'info' || $tag_command === 'doc_info' || $tag_command === 'tag') {
						$tag_args .= ' tplorig="'.$this->_current_file.'"';
					} // end if

        $attrs = $this->_parse_attrs($tag_args);
        $_cache_attrs = '';
        $arg_list = $this->_compile_arg_list('function', $tag_command, $attrs, $_cache_attrs);

        $output = $this->_compile_plugin_call('function', $tag_command).'(array('.implode(',', $arg_list)."), \$this)";
        if($tag_modifier != '') {
            $this->_parse_modifiers($output, $tag_modifier);
        }


        if($output != '') {
            $output =  '<?php ' . $_cacheable_state . $_cache_attrs . 'echo ' . $output . ';'
                . $this->_pop_cacheable_state('function', $tag_command) . "?>" . $this->_additional_newline;
        }

        return true;
    }
}

?>