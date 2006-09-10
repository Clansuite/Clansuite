<?php

/* $Id: $ */
/**
 * Project:     LAF: the Lazy Abstract Framework
 *
 * @package     LAF
 * @author      boots [jayboots ~ yahoo com]
 * @version     0.4.1 2005-Aug-10 (@since 2005-May-16)
 * @copyright   brainpower, boots, 2002-2005
 * @license     LGPL 2.1
 */


class Render_SmartyDoc extends Smarty
{
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
        , 'Strict11' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'
          )
        )
    , 'XHTML1.1' => array(
          'Normal' => array(
            'signature' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'
          )
        )
    );
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
    , 'css' => array(
          'renameto' => 'href'
        , 'optional' => array('rel', 'id', 'class', 'dir', 'lang', 'style', 'xml__lang', 'type', 'target', 'rev', 'media', 'hreflang', 'charset', 'external')
        , 'defaults' => array('rel'=>'stylesheet', 'type'=>'text/css', 'media'=>'screen', 'external'=>false)
        )
    , 'script' => array(
          'renameto' => 'src'
        , 'optional' => array('type', 'defer', 'xml__space', 'charset', 'language')
        , 'defaults' => array('type'=>'text/javascript')
        )
    , 'base' => array(
          'renameto' => 'href'
        , 'optional' => array('target')
        , 'defaults' => array()
        )
    , 'onload' => array(
          'renameto' => 'onload'
        , 'optional' => array()
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


    /**
     * CONSTRUCTOR
     */
    public function __construct()
    {
        $this->Smarty();
        $this->register_function('doc_info', array($this, 'smarty_function_doc_info'), false);
        $this->register_block('doc_raw', array($this, 'smarty_block_doc_raw'), false);
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
             $this->trigger_error("loadDocModule: bad module name.");
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
            $this->indent = $indent;
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
    public function addRawHeader($content='', $key=null)
    {
        $this->addRaw($content, $key, 'head');
    }

    /**
     * Add raw data to be inserted verbatim into the document body
     */
    public function addRawContent($content='', $key=null)
    {
        $this->addRaw($content, $key, 'body');
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
                if ($allowed == 'title' || $allowed == 'base' || $allowed == 'DOCTYPE') {
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
    public function getRawHeader($key=null)
    {
        return $this->getRaw($key, 'head');
    }

    /**
     * Get a previously stored raw body data item
     *
     * @return mixed raw body item based on key, current body item if key=null otherwise false
     */
    public function getRawContent($key=null)
    {
        return $this->getRaw($key, 'body');
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
        if (!$display) return $output;
        echo $output;
    }


    /**
     * PROTECTED API
     */

    protected function addRaw($content, $key='', $target='head')
    {
        if ($target !== 'head') {
            if ($target !== 'body') {
                $target = 'head';
            }
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
        if ($target !== 'head') {
            if ($target !== 'body') {
                $target = 'head';
            }
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
        $params['key'] = !isset($params['key']) ? '' : $params['key'];
        $params['target'] = !isset($params['target']) ? '' : $params['target'];
        
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

    /**
     * SmartyDoc outputfilter plugin
     *
     * Create qualified document using previously collected user information
     *
     * @return string document
     */
    public function smarty_outputfilter_SmartyDoc($source, &$smarty)
    {
        if (!empty($smarty->doc_info)) {
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

            // generate the doctype signature
            //$doc_source = $smarty->getDoctypeSignature() . "\n<html>\n<head>\n";
            $doc_source = $smarty->getDoctypeSignature() . "\n<head>\n";

            // insert module header-pre content
            $doc_source .= $module_content['head_pre'];

            // process each of the known doc info types

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

            // process 'meta' doc info
            if (isset($_doc_info['meta'])) {
                foreach ($_doc_info['meta'] as $meta) {
                    $doc_source .= "{$indent}<meta";
                    foreach ($meta as $a=>$v) {
                        if (!empty($v)) {
                            $doc_source .= " {$a}=\"{$v}\"";
                        }
                    }
                $doc_source .= " />\n";
                }
            }

            // process 'link' doc info
            if (isset($_doc_info['link'])) {
                foreach ($_doc_info['link'] as $link) {
                    $doc_source .= "{$indent}<link";
                    foreach ($link as $a=>$v) {
                        if (!empty($v)) {
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

            // process 'script' doc info
            if (isset($_doc_info['script'])) {
                foreach ($_doc_info['script'] as $link) {
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

            // insert module header-pre content
            $doc_source .= $module_content['head_post'];

            // process special attributes for the body tag
            $doc_source .= "</head>\n<body";
            if (isset($_doc_info['onload'])) {
                $doc_source .= ' onload="';
                foreach ($_doc_info['onload'] as $body) {
                    $doc_source .= "{$body['onload']};";
                }
                $doc_source .= '"';
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
            //$doc_source .= "</body>\n</html>";
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

/* vim: set expandtab: */

?>