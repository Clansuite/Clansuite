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

define('PHPTAL_VERSION', '1_1_8');

//{{{OS RELATED DEFINES
if (substr(PHP_OS,0,3) == 'WIN'){
    define('PHPTAL_OS_WIN', true);
    define('PHPTAL_PATH_SEP', '\\');
}
else {
    define('PHPTAL_OS_WIN', false);
    define('PHPTAL_PATH_SEP', '/');
}
//}}}
//{{{PHPTAL_PHP_CODE_DESTINATION
if (!defined('PHPTAL_PHP_CODE_DESTINATION')){
    if (PHPTAL_OS_WIN){
        if (file_exists('c:\\WINNT\\Temp\\')){
            define('PHPTAL_PHP_CODE_DESTINATION', 'c:\\WINNT\\Temp\\');
        }
        else {
            define('PHPTAL_PHP_CODE_DESTINATION', 'c:\\WINDOWS\\Temp\\');
        }
    }
    else {
        define('PHPTAL_PHP_CODE_DESTINATION', '/tmp/');
    }
}
//}}}
//{{{PHPTAL_DEFAULT_ENCODING
if (!defined('PHPTAL_DEFAULT_ENCODING')){
    define('PHPTAL_DEFAULT_ENCODING', 'UTF-8');
}
//}}}
//{{{PHPTAL_PHP_CODE_EXTENSION
if (!defined('PHPTAL_PHP_CODE_EXTENSION')){
    define('PHPTAL_PHP_CODE_EXTENSION', 'php');
}
//}}}

define('PHPTAL_XHTML', 1);
define('PHPTAL_XML',   2);

require_once 'PHPTAL/FileSource.php';
require_once 'PHPTAL/RepeatController.php';
require_once 'PHPTAL/Context.php';
require_once 'PHPTAL/Exception.php';
require_once 'PHPTAL/TalesRegistry.php';


/**
 * PHPTAL template entry point.
 * 
 * <code>
 * <?php
 * require_once 'PHPTAL.php';
 * try {
 *      $tpl = new PHPTAL('mytemplate.html');
 *      $tpl->title = 'Welcome here';
 *      $tpl->result = range(1, 100);
 *      ...
 *      echo $tpl->execute();
 * }
 * catch (Exception $e) {
 *      echo $e;
 * }
 * ?>
 * </code>
 * 
 * @author Laurent Bedubourg <lbedubourg@motion-twin.com>
 */
class PHPTAL 
{
    const XHTML = 1;
    const XML   = 2;
    
    /**
     * PHPTAL Constructor.
     *
     * @param string $path Template file path.
     */
    public function __construct($path=false)
    {
        $this->_path = $path;
        $this->_repositories = array();
        if (defined('PHPTAL_TEMPLATE_REPOSITORY')){
            $this->_repositories[] = PHPTAL_TEMPLATE_REPOSITORY;
        }
        $this->_resolvers = array();
        $this->_globalContext = new StdClass();
        $this->_context = new PHPTAL_Context();
        $this->_context->setGlobal($this->_globalContext);
    }

    /**
     * Clone template state and context.
     */
    public function __clone()
    {
        $context = $this->_context;
        $context = $this->_context;
        $this->_context = clone $this->_context;
        $this->_context->setParent($context);
        $this->_context->setGlobal($this->_globalContext);
    }

    /**
     * Set template file path.
     * @param $path string
     */
    public function setTemplate($path)
    {
        $this->_prepared = false;
        $this->_functionName = null;
        $this->_path = $path;
        $this->_source = null;
    }

    /**
     * Set template source.
     *
     * Should be used only with temporary template sources, prefer plain
     * files.
     *
     * @param $src string The phptal template source.
     * @param path string Fake and 'unique' template path.
     */
    public function setSource($src, $path=false)
    {
        if ($path == false)
            $path = '<string> '.md5($src);
        
        require_once 'PHPTAL/StringSource.php';
        $this->_source = new PHPTAL_StringSource($src, $path);
        $this->_path = $path;
    }
    
    /**
     * Specify where to look for templates.
     *
     * @param $rep mixed string or Array of repositories
     */
    public function setTemplateRepository($rep)
    {
        if (is_array($rep)){
            $this->_repositories = $rep;
        }
        else {
            $this->_repositories[] = $rep;
        }
    }

    /**
     * Ignore XML/XHTML comments on parsing.
     * @param $bool bool
     */
    public function stripComments($bool)
    {
        $this->_stripComments = $bool;
    }
    
    /**
     * Set output mode 
     * @param $mode int (PHPTAL::XML or PHPTAL::XHTML).
     */
    public function setOutputMode($mode=PHPTAL_XHTML)
    {
        if ($mode != PHPTAL::XHTML && $mode != PHPTAL::XML){
            throw new PHPTAL_Exception('Unsupported output mode '.$mode);
        }
        $this->_outputMode = $mode;
    }

    /**
     * Set ouput encoding.
     * @param $enc string example: 'UTF-8'
     */
    public function setEncoding($enc)
    {
        $this->_encoding = $enc; 
    }

    /**
     * Set I18N translator.
     */
    public function setTranslator(PHPTAL_TranslationService $t)
    {
        $this->_translator = $t;
    }

    /**
     * Set template pre filter.
     */
    public function setPreFilter(PHPTAL_Filter $filter)
    {
        $this->_prefilter = $filter;
    }

    /**
     * Set template post filter.
     */
    public function setPostFilter(PHPTAL_Filter $filter)
    {
        $this->_postfilter = $filter;
    }

    /**
     * Register a trigger for specified phptal:id.
     * @param $id string phptal:id to look for
     */
    public function addTrigger($id, PHPTAL_Trigger $trigger)
    {
        $this->_triggers[$id] = $trigger;
    }

    /**
     * Returns trigger for specified phptal:id.
     * @param $id string phptal:id
     */
    public function getTrigger($id)
    {
        if (array_key_exists($id, $this->_triggers)){
            return $this->_triggers[$id];
        }
        return null;
    }

    /**
     * Set a context variable.
     * @param $varname string
     * @param $value mixed
     */
    public function __set($varname, $value)
    {
        $this->_context->__set($varname, $value);
    }

    /**
     * Set a context variable.
     * @param $varname string
     * @param $value mixed
     */
    public function set($varname, $value)
    {
        $this->_context->__set($varname, $value);
    }
    
    /**
     * Execute the template code.
     *
     * @return string
     */
    public function execute() 
    {
        if (!$this->_prepared) {
            $this->prepare();
        }
       
        // includes generated template PHP code
        $this->_context->__file = $this->__file;
        require_once $this->_codeFile;
        $templateFunction = $this->_functionName;
        try {
            $res = $templateFunction($this, $this->_context);
        }
        catch (Exception $e){
            ob_end_clean();
            throw $e;
        }

        // unshift doctype
        $docType = $this->_context->__docType;
        if ($docType){
            $res = $docType . "\n" . $res;
        }
        // unshift xml declaration
        $xmlDec = $this->_context->__xmlDeclaration;
        if ($xmlDec){
            $res = $xmlDec . "\n" . $res;
        }
        
        if ($this->_postfilter != null){
            return $this->_postfilter->filter($res);
        }
        return $res;
    }

    /**
     * Execute a template macro.
     * @param $path string Template macro path
     */
    public function executeMacro($path)
    {
        // extract macro source file from macro name, if not source file
        // found in $path, then the macro is assumed to be local
        if (preg_match('/^(.*?)\/([a-z0-9_]*?)$/i', $path, $m)){
            list(,$file,$macroName) = $m;

            // TODO: stores a list of already prepared macro to avoid this 
            // preparation on each call
            $tpl = new PHPTAL($file);
            $tpl->_encoding = $this->_encoding;
            $tpl->setTemplateRepository($this->_repositories);
            array_unshift($tpl->_repositories, dirname($this->_source->getRealPath()));
            $tpl->_resolvers = $this->_resolvers;
            $tpl->_prefilter = $this->_prefilter;
            $tpl->_postfilter = $this->_postfilter;
            $tpl->prepare();

            // save current file
            $currentFile = $this->_context->__file;            
            $this->_context->__file = $tpl->__file;
            
            // require PHP generated code and execute macro function
            require_once $tpl->getCodePath();
            $fun = $tpl->getFunctionName() . '_' . $macroName;
            $fun($this, $this->_context);
            
            // restore current file
            $this->_context->__file = $currentFile;
        }
        else {
            // call local macro
            $fun = $this->getFunctionName() . '_' . trim($path);
            $fun( $this, $this->_context );            
        }
    }

    /**
     * Prepare template without executing it.
     */
    public function prepare()
    {
        // find the template source file
        $this->findTemplate();
        $this->__file = $this->_source->getRealPath();
        // where php generated code should resides
        $this->_codeFile = PHPTAL_PHP_CODE_DESTINATION . $this->getFunctionName() . '.' . PHPTAL_PHP_CODE_EXTENSION;
        // parse template if php generated code does not exists or template
        // source file modified since last generation of PHPTAL_FORCE_REPARSE
        // is defined.
        if (defined('PHPTAL_FORCE_REPARSE') 
            || !file_exists($this->_codeFile) 
            || filemtime($this->_codeFile) < $this->_source->getLastModifiedTime()){
            $this->parse();
        }
        $this->_prepared = true;
    }

    /**
     * Returns the path of the intermediate PHP code file.
     *
     * The returned file may be used to cleanup (unlink) temporary files
     * generated by temporary templates or more simply for debug.
     * 
     * @return string
     */
    public function getCodePath()
    {
        return $this->_codeFile;
    }

    /**
     * Returns the generated template function name.
     * @return string
     */
    public function getFunctionName()
    {
        if (!$this->_functionName) {
            $this->_functionName = 'tpl_'.PHPTAL_VERSION.md5($this->_source->getRealPath());
        }
        return $this->_functionName;
    }

    /**
     * Returns template translator.
     * @return PHPTAL_TranslationService
     */
    public function getTranslator()
    {
        return $this->_translator;
    }
    
    /**
     * Returns array of exceptions catched by tal:on-error attribute.
     * @return array<Exception>
     */
    public function getErrors()
    {
        return $this->_errors;
    }
    
    /**
     * Public for phptal templates, private for user.
     * @access private
     */
    public function addError(Exception $error)
    {
        array_push($this->_errors, $error); 
    }

    /**
     * Returns current context object.
     * @return PHPTAL_Context
     */
    public function getContext()
    {
        return $this->_context;
    }

    public function getGlobalContext()
    {
        return $this->_globalContext;
    }

    public function pushContext()
    {
        $this->_context = $this->_context->pushContext();
        return $this->_context;
    }

    public function popContext()
    {
        $this->_context = $this->_context->popContext();
        return $this->_context;
    }
    
    protected function parse()
    {
        require_once 'PHPTAL/Dom/Parser.php';
        
        // instantiate the PHPTAL source parser 
        $parser = new PHPTAL_Dom_Parser();
        $parser->stripComments($this->_stripComments);

        $data = $this->_source->getData();
        if ($this->_prefilter)
            $data = $this->_prefilter->filter($data);
        $tree = $parser->parseString($data);

        require_once 'PHPTAL/Php/CodeGenerator.php';
        $generator = new PHPTAL_Php_CodeGenerator($this->_source->getRealPath());
        $generator->setEncoding($this->_encoding);
        $generator->setOutputMode($this->_outputMode);
        $generator->generate($tree);

        if (!@file_put_contents($this->_codeFile, $generator->getResult())) {
            throw new Exception('Unable to open '.$this->_codeFile.' for writing');
        }
    }

    /** 
     * Search template source location. 
     */
    protected function findTemplate()
    {
        if ($this->_path == false){
            throw new Exception('No template file specified');
        }

        // template source already defined
        if ($this->_source != null){
            return;
        }
       
        array_push($this->_resolvers, new PHPTAL_FileSourceResolver($this->_repositories));
        foreach ($this->_resolvers as $resolver){
            $source = $resolver->resolve($this->_path);
            if ($source != null){
                $this->_source = $source;
                break;
            }
        }
        array_pop($this->_resolvers);

        if ($this->_source == null){
            throw new Exception('Unable to locate template file '.$this->_path);
        }
    }

    protected $_prefilter = null;
    protected $_postfilter = null;

    // list of template source repositories
    protected $_repositories = array();
    // template path
    protected $_path = null;
    // template source resolvers
    protected $_resolvers = array();
    // template source (only set when not working with file)
    protected $_source = null;
    // destination of PHP intermediate file
    protected $_codeFile = null;
    // php function generated for the template
    protected $_functionName = null;
    // set to true when template is ready for execution
    protected $_prepared = false;
    
    // associative array of phptal:id => PHPTAL_Trigger
    protected $_triggers = array();
    // i18n translator
    protected $_translator = null;

    // global execution context
    protected $_globalContext = null;
    // current execution context
    protected $_context = null;
    // current template file (changes within macros)
    public  $__file = false;
    // list of on-error caught exceptions
    protected $_errors = array();

    protected $_encoding = PHPTAL_DEFAULT_ENCODING; 
    protected $_outputMode = PHPTAL::XHTML;
    protected $_stripComments = false;
}

?>
