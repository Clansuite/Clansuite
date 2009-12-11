<?php
/**
* Smarty Internal Plugin Compile Capture
*
* Compiles the {continue} tag
*
* @package Smarty
* @subpackage Compiler
* @author Jens-André Koch
*/
/**
* Smarty Internal Plugin Compile continue Class
*/
class Smarty_Internal_Compile_continue extends Smarty_Internal_CompileBase {
    /**
    * Compiles code for the {continue} tag
    *
    * @param array $args array with attributes from parser
    * @param object $compiler compiler object
    * @return string compiled code
    */
    public function compile($args, $compiler)
    {
        $this->compiler = $compiler;
        // check and get attributes
        $_attr = $this->_get_attributes($args);

        $_output = "<?php continue; ?>";

        return $_output;
    }
}
?>