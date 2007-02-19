<?php

/* $Id: $ */
/**
 * Project:     LAF: the Lazy Abstract Framework
 *
 * @package     LAF
 * @author      boots [jayboots ~ yahoo com]
 * @author      brettz9 [brettz9 ~ yahoo com]
 * @author      tdmme [no2spam ~ chello nl]
 * @version     0.4.2 2006-Okt-15 (@since 2005-May-16)
 * @copyright   brainpower, boots, 2002-2005
 * @license     LGPL 2.1
 */
 
 /* Changes
  * 2006-Okt-15:
  *       tdmme, I moved the if-statement that sets the page content-type of the header,
  *       from the constructor to a seperate function called 'setContentType()' and
  *       added a call to that function at the beginning of the 'smarty_outputfilter_SmartyDoc'
  *       function. This way the $this->doc_info array is NOT empty, like it was in when
  *       it was in the constructor. Now the automatic content-type recornizion works.
  */


class Render_SmartyDoc extends Smarty
{
    public $encoding = 'UTF-8'; // Used for generating headers
    public $HTTP_ACCEPT;

    protected $DOCTYPES = array(
      'HTML' => array(
          'Strict' => array(
            'signature' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">'
          )
        , 'Transitional' => array(
            'signature' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'
          )
        , 'Frameset' => array(
            'signature' => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">'
          )
        )
    , 'XHTML' => array(
          'Strict' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'
          )
        , 'Transitional' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
          )
        , 'Frameset' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">'
          )
        , '1.1' => array(
           'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'
          )
        , 'Basic' => array(
           'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">'
        )
    ));
// Note that all of the below (besides pre_xform) should be legitimate XHTML attributes
    protected $doc_info_types = array(
      'title' => array(
          'renameto' => null
        , 'optional' => array('id', 'class', 'dir', 'lang', 'style', 'xml__lang')
        , 'defaults' => array()
        )
    , 'meta' => array(
          'renameto' => 'name'
        , 'optional' => array('content', 'http_equiv', 'scheme', 'dir', 'lang', 'xml__lang')
        , 'defaults' => array('content'=>'')
        )
    , 'link' => array(
          'renameto' => 'href'
        , 'optional' => array('rel', 'id', 'class', 'dir', 'lang', 'style', 'xml__lang', 'type', 'target', 'rev', 'media', 'hreflang', 'charset', 'title')
        , 'defaults' => array()
        )
    , 'xml' => array(
          'renameto' => 'version'
        , 'optional' => array('encoding', 'standalone')
        , 'defaults' => array()
        )
    , 'html' => array(
          'renameto' => 'xml:lang'
        , 'optional' => array('xmlns', 'dir', 'xml__lang', 'lang', 'version')
        , 'defaults' => array('xmlns'=>'http://www.w3.org/1999/xhtml')
        )
    , 'head' => array(
          'renameto' => 'xml:lang'
        , 'optional' => array('profile', 'dir', 'xml__lang', 'lang')
        , 'defaults' => array()
        )
    , 'stylesheet' => array(
          'renameto' => 'href'
        , 'optional' => array('type', 'title', 'media', 'charset', 'alternate', 'pre_xform')
        , 'defaults' => array('type'=>'text/xml')
        )
    , 'style' => array(
          'renameto' => null
        , 'optional' => array('type', 'media', 'title', 'lang', 'xml__lang', 'dir')
        , 'defaults' => array('type'=>'text/css')
        )
    , 'script' => array(
          'renameto' => null
        , 'optional' => array('type', 'charset', 'defer')
        , 'defaults' => array('type'=>'text/javascript')
        )
    , 'css' => array(
          'renameto' => 'href'
        , 'optional' => array('rel', 'id', 'class', 'dir', 'lang', 'style', 'xml__lang', 'type', 'target', 'rev', 'media', 'hreflang', 'charset', 'external')
        , 'defaults' => array('rel'=>'stylesheet', 'type'=>'text/css', 'media'=>'screen', 'external'=>false)
        )
    , 'code' => array(
          'renameto' => 'src'
        , 'optional' => array('type', 'defer', 'xml__space', 'charset', 'language')
        , 'defaults' => array('type'=>'text/javascript')
        )
    , 'base' => array(
          'renameto' => 'href'
        , 'optional' => array('target')
        , 'defaults' => array()
        )
    , 'body'=> array(
          'renameto' => 'onload'
        , 'optional' => array('id', 'class', 'lang', 'dir', 'xml__lang', 'title', 'style', 'onunload', 'onclick', 'ondblclick', 'onmousedown', 'onmouseup', 'onmouseover', 'onmousemove', 'onmouseout', 'onkeypress', 'onkeydown', 'onkeyup', 'bgcolor', 'background', 'text', 'link', 'vlink', 'alink')
        , 'defaults' => array()
        )
    , 'DOCTYPE' => array(
          'renameto' => 'FAMILY'
        , 'optional' => array('LEVEL')
        , 'defaults' => array('LEVEL'=>'Transitional')
        )
    );
    protected $doc_indent = '    ';
    protected $doc_css_url = '';
    protected $doc_script_url = '';

    private $doc_info = array();
    private $doc_raw  = array();
    private $doc_modules = array();
    static $target_choices = array('head', 'body', 'style', 'script');

    /**
     * CONSTRUCTOR
     */
    public function __construct()
    {
        $this->Smarty();
        $this->register_function('doc_info', array($this, 'smarty_function_doc_info'), false);
        $this->register_block('doc_raw', array($this, 'smarty_block_doc_raw'), false);
        // the following line is only needed when you need doc_raw, it executes some
        // heavy regular expressions. So only enable it if you really need it
        // (you can comment it out if you don't need it)
        $this->register_prefilter(array($this, 'smarty_prefilter_SmartyDoc'));
    }


    /**
     * PUBLIC API
     */

    public function &getDocModule($smarty_doc_module='')
    {
        $module = $this->loadDocModule($smarty_doc_module);
        return $this->doc_modules[$module];
    }

    public function loadDocModule($smarty_doc_module='')
    {
        if (!isset($smarty_doc_module) && is_string($smarty_doc_module) && strlen($smarty_doc_module)>0) {
             $this->trigger_error('loadDocModule: bad module name.');
        }
        $module = "smarty_docmodule_$smarty_doc_module";
        if (!array_key_exists($module, $this->doc_modules)) {
            require_once $this->_get_plugin_filepath('docmodule', $smarty_doc_module);
            $this->registerDocModule(new $module($this));
        }
        return $module;
    }

    public function registerDocModule(ISmartyDocModule $smarty_doc_module)
    {
        $class = get_class($smarty_doc_module);
        if (!array_key_exists($class, $this->doc_modules)) {
            $this->doc_modules[$class] = $smarty_doc_module;
        }
    }

    /**
     * Clear internally collected document information
     */
    public function resetDoc()
    {
        $this->doc_info = array();
        $this->doc_raw = array();
    }

    /**
     * Set the doctype family and level
     */
    public function setDoctype($family='XHTML', $level='Transitional')
    {
        if (isset($family) && !empty($family) && is_string($family)) {
            $this->doc_info['DOCTYPE']['FAMILY'] = $family;
        }
        if (isset($level) && !empty($level) && is_string($level)) {
            $this->doc_info['DOCTYPE']['LEVEL'] = $level;
        }
    }

    /**
     * Get the signature of the currently set doctype
     *
     * @return mixed doctype signature if available or otherwise null
     */
    public function getDoctypeSignature()
    {
        if (!isset($this->doc_info['DOCTYPE'])) {
            $this->doc_info['DOCTYPE']['FAMILY'] = '';
            $this->doc_info['DOCTYPE']['LEVEL'] = '';
        }

        $family = $this->doc_info['DOCTYPE']['FAMILY'];
        $level  = $this->doc_info['DOCTYPE']['LEVEL'];

        if (array_key_exists($family, $this->DOCTYPES)) {
            if (array_key_exists($level, $this->DOCTYPES[$family])) {
                return $this->DOCTYPES[$family][$level]['signature'];
            }
        }
    }

    /**
     * Set the default amount of indenting to apply to generated tags
     */
    public function setIndent($indent='    ')
    {
        if (isset($indent) && !empty($indent) && is_string($indent)) {
            $this->doc_indent = $indent;
        }
    }
    /**
     * Get the default amount of indenting to apply to generated tags
     */
    public function getIndent()
    {
        return $this->doc_indent;
    }

    /**
     * Set the path to append to CSS style urls
     */
    public function setCSSUrl($url='')
    {
        if (isset($url) && !empty($url) && is_string($url)) {
            $this->doc_css_url = $url;
        }
    }

    /**
     * Set the path to append to script style urls
     */
    public function setScriptUrl($url='')
    {
        if (isset($url) && !empty($url) && is_string($url)) {
            $this->doc_script_url = $url;
        }
    }

    /**
     * Add raw data to be inserted verbatim into the document head
     */
    public function addRawHeader($content='', $key=null, $target='head')
    {
        $this->addRaw($content, $key, $target);
    }

    /**
     * Add raw data to be inserted verbatim into the document body
     */
    public function addRawContent($content='', $key=null, $target='body')
    {
        $this->addRaw($content, $key, $target);
    }

    /**
     * Add an item to the doc_info collected information
     */
    public function addInfo($params=array())
    {
        $element = array();
        foreach ($this->doc_info_types as $allowed=>$rules) {
            if (array_key_exists($allowed, $params) && isset($params[$allowed])) {
                foreach ($rules['optional'] as $attribute) {
                    $_attribute = str_replace('_', '-', str_replace('__', ':', $attribute));
                    if (array_key_exists($attribute, $params) && isset($params[$attribute])) {
                        $element[$_attribute] = $params[$attribute];
                    } else if (array_key_exists($attribute, $params)) {
                        $element[$_attribute] = null;
                    } else if (array_key_exists($attribute, $rules['defaults'])) {
                        $element[$_attribute] = $rules['defaults'][$attribute];
                    }
                    if ($attribute == 'href' or $attribute == 'src') {
                        $element[$_attribute] = urlencode($element[$_attribute]);
                    }
                }
                $renameto = (is_null($rules['renameto'])) ? '_content' : $rules['renameto'];
                $element[$renameto] = $params[$allowed];
                if (in_array($allowed, array('title', 'base', 'xml', 'html', 'head', 'body', 'DOCTYPE'))) {
                    $this->doc_info[$allowed] = $element;
                } else {
                    $this->doc_info[$allowed][$element[$renameto]] = $element;
                }
                break;
            }
        }
    }

    /**
     * Get a previously stored raw header data item
     *
     * @return mixed raw header item based on key, current header item if key=null otherwise false
     */
    public function &getDocInfo()
    {
        return $this->doc_info;
    }

    /**
     * Get a previously stored raw header data item
     *
     * @return mixed raw header item based on key, current header item if key=null otherwise false
     */
    public function getRawHeader($key=null, $target='head')
    {
        return $this->getRaw($key, $target);
    }

    /**
     * Get a previously stored raw body data item
     *
     * @return mixed raw body item based on key, current body item if key=null otherwise false
     */
    public function getRawContent($key=null, $target='body')
    {
        return $this->getRaw($key, $target);
    }

    /**
     * Override Smarty::fetch() to ensure that the SmartyDoc outputfilter is not active
     */
    public function fetch($resource_name, $cache_id=null, $compile_id=null, $display=false)
    {
        $outputfilter_loaded = array_key_exists('smarty_outputfilter_SmartyDoc', $this->_plugins['outputfilter']);
        if ($outputfilter_loaded) {
            $this->unregister_outputfilter('smarty_outputfilter_SmartyDoc');
        }
        $output = parent::fetch($resource_name, $cache_id, $compile_id);
        if ($outputfilter_loaded) {
            $this->register_outputfilter(array($this, 'smarty_outputfilter_SmartyDoc'));
        }
        if (!$display) return $output;
        echo $output;
    }

    /**
     * Similar to Smarty::display() except that it defers to SmartyDoc::fetchDoc().
     */
    public function displayDoc($resource_name, $cache_id=null, $compile_id=null)
    {
        $this->_smarty_vars['template'] = $resource_name; // Added so that the template name would be accessible within the outputfilter
        $this->fetchDoc($resource_name, $cache_id, $compile_id, true);
    }


    /**
     * Produce a complete document as determined by the collected document
     * information and using the SmartyDoc outputfilter. Clears collected
     * document information after executing.
     */
    public function fetchDoc($resource_name, $cache_id=null, $compile_id=null, $display=false)
    {
        $this->register_outputfilter(array($this, 'smarty_outputfilter_SmartyDoc'));
        $output = parent::fetch($resource_name, $cache_id, $compile_id);
        $this->unregister_outputfilter('smarty_outputfilter_SmartyDoc');
        $this->resetDoc();
        
		if (!$display) {
			return $output;
		}
        echo $output;
    }

    /**
     * Convert the curly braces temporarily into double brackets, so that
     * Smarty can parse them (for CSS or Javascript)
     */
    public function smarty_prefilter_SmartyDoc($source, &$smarty)
    {
        // Prepare delimiters for insertion into regexps below
        $ldelim = preg_quote($this->left_delimiter, '@');
        $rdelim = preg_quote($this->right_delimiter, '@');

        $source = preg_replace('@'.$ldelim.'\$([^}]*?)'.$rdelim.'@', '-~#-~$1-#~-', $source); // Adding a temporary replacement to hide the genuine smarty variables from the other curly brace items
        $source = preg_replace('@'.$ldelim.'#([^}]*?)'.$rdelim.'@', '--#-~$1--~-', $source); // Adding a temporary replacement to hide the genuine config variables from the other curly brace items

        $callback = create_function('$matches', "return \$matches[1].str_replace(array('{', '}'), array('[[', ']]'), \$matches[3]).\$matches[4];"); // This approach is better than using preg_replace with the e modifier, since preg_replace auto-performs addslashes() which we do not want here

        $source = preg_replace_callback("@(".$ldelim."doc_info style=(['|\"]))([^\\2]*?)(\\2.*?".$rdelim.")@s", $callback, $source);
        $source = preg_replace_callback("@(".$ldelim."doc_info script=(['|\"]))([^\\2]*?)(\\2.*?".$rdelim.")@s", $callback, $source);
        $source = preg_replace_callback("@(".$ldelim."doc_raw target=(['|\"]{0,1})style\\2[^}]*?".$rdelim.")(.*?)(".$ldelim."/doc_raw".$rdelim.")@s", $callback, $source);
        $source = preg_replace_callback("@(".$ldelim."doc_raw target=(['|\"]{0,1})script\\2[^}]*?".$rdelim.")(.*?)(".$ldelim."/doc_raw".$rdelim.")@s", $callback, $source);

        $source = preg_replace('@-~#-~(.*?)-#\~-@s', $this->left_delimiter.'$$1'.$this->right_delimiter, $source); // Reverting back temporary replacement from above to allow smarty variables to work
        $source = preg_replace('@--#-~(.*?)--\~-@s', $this->left_delimiter.'#$1'.$this->right_delimiter, $source); // Reverting back temporary replacement from above to allow config variables to work
        return $source;
    }


    /**
     * PROTECTED API
     */

    protected function addRaw($content, $key='', $target='head')
    {
        if (!in_array($target, self::$target_choices)) {
            $target = 'head';
        }

        if (isset($content) && !empty($content) && is_string($content)) {
            if (!empty($key)) {
                $this->doc_raw[$target][$key] = $content;
            } else {
                $this->doc_raw[$target][] = $content;
            }
        }
    }

    protected function getRaw($key=null, $target='head')
    {
        if (!in_array($target, self::$target_choices)) {
            $target = 'head';
        }
        if (isset($key) && is_string($key) && strlen($key)>0) {
            if (!isset($this->doc_raw[$target][$key])) {
                return false;
            }
            return $this->doc_raw[$target][$key];
        } else {
            return current($this->doc_raw[$target]);
        }
    }


    /**
     * SMARTY PLUGIN CALLBACKS
     */

    /**
     * Smarty {doc_module} function plugin
     *
     * Insert some raw text into the html header from anywhere at anytime
     *
     * @return nothing
     */
    public function smarty_function_doc_module($params, &$smarty)
    {
        if (!isset($params['name']) && is_string($params['name']) && strlen($params['name'])>0) {
             $smarty->trigger_error("doc_module: required 'name' parameter missing.");
        } else {
            $smarty->loadDocModule($params['name']);
        }
    }


    /**
     * Smarty {doc_raw} block plugin
     *
     * Insert some raw text into the html header from anywhere at anytime
     *
     * @return nothing
     */
    public function smarty_block_doc_raw($params, $content, &$smarty)
    {
        $key = (isset($params['key']) && is_string($params['key']) && strlen($params['key'])>0)
            ? $params['key']
            : null;
        if (isset($params['target']) && strtolower($params['target']) === 'body') {
            $smarty->addRawContent($content, $key, $params['target']);
        } else {
            $smarty->addRawHeader($content, $key, $params['target']);
        }
    }

    /**
     * Smarty {doc_info} function plugin
     *
     * Insert html header items from anywhere at anytime
     *
     * @return nothing
     */
    public function smarty_function_doc_info($params, &$smarty)
    {
        $smarty->addInfo($params);
    }
	
	
	protected function setContentType()
	{
    	// first check if type is XHTML or not and if so, whether the browser accepts application/xhtml+xml content type
        if ($this->doc_info['DOCTYPE']['FAMILY'] === 'XHTML') {
            if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml')) {
                $this->HTTP_ACCEPT = 'application/xhtml+xml';
            }
            elseif (stristr($_SERVER['HTTP_ACCEPT'], 'application/xml')) {
                $this->HTTP_ACCEPT = 'application/xml';
            }
            elseif (stristr($_SERVER['HTTP_ACCEPT'], 'text/xml')) {
                $this->HTTP_ACCEPT = 'text/xml';
            }
      		//Send Opera 7.0 application/xhtml+xml
            elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Opera 7') || stristr($_SERVER['HTTP_USER_AGENT'], 'Opera/7')) {
                $this->HTTP_ACCEPT = 'application/xhtml+xml';
            }
      		//Send everyone else text/html
            else {
                $this->HTTP_ACCEPT = 'text/html';
            }
        }
        else {
            $this->HTTP_ACCEPT = 'text/html';
        }
	}

    /**
     * SmartyDoc outputfilter plugin
     *
     * Create qualified document using previously collected user information
     *
     * @return string document
     */
    public function smarty_outputfilter_SmartyDoc($source, &$smarty)
    {
		$this->setContentType();
	
        if (!empty($smarty->doc_info) || !empty($smarty->doc_raw)) {
            $indent     = $smarty->getIndent();
            $_doc_info  =& $smarty->doc_info;

            // process modules
            $module_content = array('head_pre'=>'', 'head_post'=>'', 'body_pre'=>'', 'body_post'=>'');
            foreach ($this->doc_modules as $module) {
                $module->onDocStart();

                $mod_content = $module->onHeadStart();
                if (!empty($mod_content)) {
                    $module_content['head_pre'] .= "\n" . $mod_content . "\n";
                }
                $mod_content = $module->onHeadEnd();
                if (!empty($mod_content)) {
                    $module_content['head_post'] .= "\n" . $mod_content . "\n";
                }
                $mod_content = $module->onBodyStart();
                if (!empty($mod_content)) {
                    $module_content['body_pre'] .= "\n" . $mod_content . "\n";
                }
                $mod_content = $module->onBodyEnd();
                if (!empty($mod_content)) {
                    $module_content['body_post'] .= "\n" . $mod_content . "\n";
                }
                $mod_content = $module->onDocEnd();
            }


            header('Content-Type: '.$this->HTTP_ACCEPT.'; charset='.$this->encoding);

            // Send javascript header
            if (isset($_doc_info['code']) || isset($_doc_info['script']) || isset($this->doc_raw['script'])) {
                header('Content-Script-Type: text/javascript');
            }

            // process 'xml' (XML Declaration) doc info
            if (isset($_doc_info['xml'])) {
                $doc_source .= '<'.'?xml';
                foreach ($_doc_info['xml'] as $a=>$v) {
                    if ($a == 'version') {
                        $docadd = " {$a}=\"{$v}\"";
                    }
                    elseif (!empty($v)) {
                        $docadd2 .= " {$a}=\"{$v}\"";
                    }
                }
                $doc_source .= $docadd.$docadd2;
                $doc_source .= '?'.">\n";
            }

            // process 'stylesheet' (xml-stylesheet) doc info
            if (isset($_doc_info['stylesheet'])) {
                foreach ($_doc_info['stylesheet'] as $stylesheet) {
                    $doc_source .= "{$indent}<?xml-stylesheet";
                    foreach ($stylesheet as $a=>$v) {
                        if (!empty($v) && $a != 'pre-xform') {
                            $doc_source .= " {$a}=\"{$v}\"";
                        }
                    }
											 $xsl[] = $stylesheet['href']; // for XSL processing below
											 $pre_xform[] = $stylesheet['pre-xform']; // for XSL processing below
		                $doc_source .= "?>\n";
                }
            }


            // generate the doctype signature
            $doc_source .= $smarty->getDoctypeSignature() . "\n";


            // process 'html' doc info
            if (isset($_doc_info['html'])) {
                $doc_source .= '<html';
                foreach ($_doc_info['html'] as $a=>$v) {
                    if (!empty($v) && ($this->doc_info['DOCTYPE']['FAMILY'] === 'XHTML' || $a != 'xmlns')) {
                        $doc_source .= " {$a}=\"{$v}\"";
                    }
                }
                $doc_source .= ">\n";
            }
            elseif ($this->doc_info['DOCTYPE']['FAMILY'] === 'XHTML') {
							    $doc_source .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
            }
            else {
							    $doc_source .= "<html>\n";
            }


            // process 'head' doc info
            if (isset($_doc_info['head'])) {
                $doc_source .= '<head';
                foreach ($_doc_info['head'] as $a=>$v) {
                    if (!empty($v)) {
                        $doc_source .= " {$a}=\"{$v}\"";
                    }
                }
                $doc_source .= ">\n";
            }
            else {
							    $doc_source .= "<head>\n";
            }


            // insert module header-pre content
            $doc_source .= $module_content['head_pre'];


            // process each of the known doc info types

            // process 'meta' doc info
            if (isset($_doc_info['meta'])) {
                foreach ($_doc_info['meta'] as $meta) {
                    $doc_source .= "{$indent}<meta";
                    foreach ($meta as $a=>$v) {
                        if ($v === 'http_accept') { // Allow for http type (and charset) to be auto-calculated (such as from the Navbarcrumbs class)
                            $doc_source .= " {$a}=\"".$this->HTTP_ACCEPT.'; charset='.$this->encoding.'"';
                        }
                        elseif (!empty($v)) {
                            $doc_source .= " {$a}=\"{$v}\"";
                        }
                    }
                $doc_source .= " />\n";
                }
            }

            // process 'title' doc info
            if (isset($_doc_info['title'])) {
                $doc_source .= "{$indent}<title";
                foreach ($_doc_info['title'] as $a=>$v) {
                    if ($a != '_content') {
                        if (!empty($v)) {
                            $doc_source .= " {$a}=\"{$v}\"";
                        }
                    }
                }
                $doc_source .= ">{$_doc_info['title']['_content']}</title>\n";
            }


            // process 'base' doc info
            if (isset($_doc_info['base'])) {
                $doc_source .= "{$indent}<base";
                foreach ($_doc_info['base'] as $a=>$v) {
                    if (!empty($v)) {
                        $doc_source .= " {$a}=\"{$v}\"";
                    }
                }
                $doc_source .= " />\n";
            }


            // process 'style' doc info
            if (isset($_doc_info['style'])) {
                $doc_source .= "{$indent}<style";

                foreach ($_doc_info['style'] as $styleattribs) {
                    foreach ($styleattribs as $a=>$v) {
                        if ($a != '_content') {
                            if (!empty($v)) {
                                $doc_source .= " {$a}=\"{$v}\"";
                            }
                        }
                        else {
                            $stylecontent = $v;
                        }
                    }
                }
				         $stylecontent = str_replace(array('[[', ']]'), array('{', '}'), $stylecontent);
                $doc_source .= ">/*<![CDATA[*/\n{$stylecontent}\n/*]]>*/</style>\n";
            }

            // process 'script' doc info
            if (isset($_doc_info['script'])) {
                $doc_source .= "{$indent}<script";
                foreach ($_doc_info['script'] as $scriptattribs) {
                    foreach ($scriptattribs as $a=>$v) {
                        if ($a != '_content') {
                            if (!empty($v)) {
                                $doc_source .= " {$a}=\"{$v}\"";
                            }
                        }
                        else {
                            $scriptcontent = $v;
                        }
                    }
                }
				         $scriptcontent = str_replace(array('[[', ']]'), array('{', '}'), $scriptcontent);
                $doc_source .= ">/*<![CDATA[*/\n{$scriptcontent}\n/*]]>*/</script>\n";
            }

            // process 'link' doc info
            if (isset($_doc_info['link'])) {
                foreach ($_doc_info['link'] as $link) {
                    $doc_source .= "{$indent}<link";
                    foreach ($link as $a=>$v) {
                        if ($v === 'http_accept') { // Allow for http type to be auto-calculated (such as from the Navbarcrumbs class)
                            $doc_source .= " {$a}=\"{$this->HTTP_ACCEPT}\"";
                        }
                        elseif (!empty($v)) {
                            $doc_source .= " {$a}=\"{$v}\"";
                        }
                    }
                    $doc_source .= " />\n";
                }
            }

            // process 'css' doc info
            if (isset($_doc_info['css'])) {
                foreach ($_doc_info['css'] as $link) {
                    $href = $link['href'];
                    if (!(substr($href, 0, 1) == '/' || substr($href, 0, 7) == 'http://')) {
                        $href = $smarty->doc_css_url . $href;
                    }
                    unset($link['href']);
                    if (isset($link['external']) && $link['external'] === true) {
                        unset($link['external']);
                        $doc_source .= "{$indent}<link";
                        foreach ($link as $a=>$v) {
                            if (!empty($v)) {
                                $doc_source .= " {$a}=\"{$v}\"";
                            }
                        }
                        $doc_source .= " href=\"{$href}\" />\n";
                    } else {
                        unset($link['external']);
                        unset($link['title']);
                        unset($link['rel']);
                        $doc_source .= "{$indent}<style";
                        foreach ($link as $a=>$v) {
                            if (!empty($v)) {
                                $doc_source .= " {$a}=\"{$v}\"";
                            }
                        }
                        $doc_source .= ">\n";
                        $doc_source .= "/* <![CDATA[ */\n";
                        $doc_source .= "@import \"{$href}\";\n";
                        $doc_source .= "/* ]]> */\n";
                        $doc_source .= "{$indent}</style>\n";
                    }
                
                }
            }

            // process 'code' doc info
            if (isset($_doc_info['code'])) {
                foreach ($_doc_info['code'] as $link) {
                    $src = $link['src'];
                    if (!(substr($src, 0, 1) == '/' || substr($src, 0, 7) == 'http://')) {
                        $src = $smarty->doc_script_url . $src;
                    }
                    $doc_source .= "{$indent}<script";
                    unset($link['src']);
                    foreach ($link as $a=>$v) {
                        if (!empty($v)) {
                            $doc_source .= " {$a}=\"{$v}\"";
                        }
                    }
                    $doc_source .= " src=\"{$src}\"></script>\n";
                }
            }

            // process doc_raw targetted for the head
            if (isset($this->doc_raw['head'])) {
                foreach ($this->doc_raw['head'] as $raw) {
                    $doc_source .= "{$raw}\n";
                }
            }

            // process doc_raw targetted for the style tag
            if (isset($this->doc_raw['style'])) {

// print $this->_smarty_vars['template'];
// file_put_contents

									$doc_source .= "{$indent}<style type=\"text/css\">\n\t/*<![CDATA[*/\n";
                foreach ($this->doc_raw['style'] as $raw) {
				             $raw = str_replace(array('[[', ']]'), array('{', '}'), $raw);
                    $doc_source .= $raw."\n";

                }
                $doc_source .= "\t/*]]>*/</style>\n";
            }

            // process doc_raw targetted for the script tag
//$doc_source .= $smarty['template'];
            if (isset($this->doc_raw['script'])) {
                $doc_source .= "{$indent}<script type=\"text/javascript\">\n\t/*<![CDATA[*/\n";
                foreach ($this->doc_raw['script'] as $raw) {
						         $raw = str_replace(array('[[', ']]'), array('{', '}'), $raw);
                   $doc_source .= $raw."\n";
                }
                $doc_source .= "\t/*]]>*/</script>\n";
            }


            // insert module header-pre content
            $doc_source .= $module_content['head_post'];


            // process 'body' doc info
            $doc_source .= "</head>\n<body";
            if (isset($_doc_info['body'])) {
                foreach ($_doc_info['body'] as $a=>$v) {
                    if (!empty($v)) {
                        $doc_source .= " {$a}=\"{$v}\"";
                    }
                }
            }
            $doc_source .= ">\n";


            // insert module header-pre content
            $doc_source .= $module_content['body_pre'];

            // process doc_raw targetted for the body
            if (isset($this->doc_raw['body'])) {
                foreach ($this->doc_raw['body'] as $raw) {
                    $doc_source .= "{$indent}{$raw}\n";
                }
            }

            // add-in the original source
            $doc_source .= "{$source}\n";

            // insert module header-pre content
            $doc_source .= $module_content['body_post'];

            // y'all come back now, y'hear?
            $doc_source .= "</body>\n</html>";


							if (extension_loaded('xsl')) {
								$xslproc = new XSLTProcessor;
								$xslcount = count($xsl);
								for ($i = 0; $i < $xslcount; $i++) {
									if ($pre_xform[$i]) { // make sure the template designer in fact wants the stylesheet transformed server-side
										$xslproc->importStyleSheet($xsl[$i]); // attach the xsl rule
										$doc_source = $xslproc->transformToXML($doc_source);
									}
								}
							}

							return $doc_source;

        }
        return $source;
    }

}

interface ISmartyDocModule
{
    public function onDocStart();
    public function onDocEnd();
    public function onHeadStart();
    public function onHeadEnd();
    public function onBodyStart();
    public function onBodyEnd();
}

abstract class ASmartyDocModule implements ISmartyDocModule
{
    protected $smarty;
    public function __construct(Render_SmartyDoc $smarty)
    {
        $this->smarty = $smarty;
    }
    public function onDocStart() {}
    public function onDocEnd() {}
    public function onHeadStart() {}
    public function onHeadEnd() {}
    public function onBodyStart() {}
    public function onBodyEnd() {}
}

?>