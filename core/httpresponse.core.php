<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$response.class.php 2580 2008-11-20 20:38:03Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Interface for the Response Object
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  HttpResponse
 */
interface Clansuite_Response_Interface
{
    # Output Methods
    public function setStatusCode($statusCode);
    public function addHeader($name, $value);
    public function setContent($content, $replace = false);
    public function flush();

    # Cookie Methods
    public function createCookie($name, $value='', $maxage = 0, $path='', $domain='', $secure = false, $HTTPOnly = false);
    public function deleteCookie($name, $path = '/', $domain = '', $secure = false, $httponly = null);
}

/**
 * Clansuite_HttpResponse $response
 *
 * Purpose:  Clansuite Core Class for Response Handling
 *
 * This class represents the web response object on a request processed by Clansuite.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  HttpResponse
 */
class Clansuite_HttpResponse implements Clansuite_Response_Interface
{
    /**
     * Status of the response as integer value.
     * $statusCode = '200' => 'OK'
     *
     * @var       integer
     */
    protected $statusCode = '200';

    /**
     * Array holding the response headers.
     *
     * @var       array
     */
    protected $headers = array();

    /**
     * Integer holding the GZIP compression level
     *
     * @var      integer
     */
    protected $output_compression_level = 7;

    /**
     * String holding the response body.
     *
     * @var       string
     */
    protected $body = null;

    # holds instance of Clansuite_Config
    protected $config;

    public function __construct(Clansuite_Config $config)
    {
        $this->config = $config; # set instance
    }

    /**
     * Sets the HTTP Status Code for this response.
     * This method is also used to set the return status code when there
     * is no error (for example for the status codes 200 (OK) or 301 (Moved permanently) ).
     *
     * @param  integer $statusCode The status code to set
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = (string) $statusCode;
    }

    /**
     * Get-Method for HTTP 1.1 status code and its meaning.
     *
     * used in (@link $this->flush )
     * @see $this->flush()
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
     */
    public function getStatusCodeDescription($statusCode)
    {
        /**
         * Array holding some often occuring status descriptions.
         * @var       array
         */
        $statusCodeDescription = array(
                                       # Successful
                                       '200'    => 'OK',
                                       '201'    => 'Created',
                                       '202'    => 'Accepted',
                                       # Redirection
                                       '301'    => 'Moved Permanently',
                                       '302'    => 'Found',
                                       '304'    => 'Not Modified',
                                       '307'    => 'Temporary Redirect',
                                       # Client Error
                                       '400'    => 'Bad Request',
                                       '401'    => 'Unauthorized',
                                       '403'    => 'Forbidden',
                                       '404'    => 'Not Found',
                                       # Server Error
                                       '500'    => 'Internal Server Error'
                                      );

        return $statusCodeDescription[$statusCode];
    }

     /**
      * add a header to the response array, which is send to the browser
      *
      * @param  string $name the name of the header
      * @param  string $value the value of the header
      */
    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * setContent:
     * appends content to the response body
     * when replace is true, the bodycontent is replaced
     *
     * @param string $content Content to store in the buffer
     * @param boolean $replace toggles append or replace content
     */
    public function setContent($content, $replace = false)
    {
        # check, if the content should be replaced
        if($replace == false)
        {
            # no, replace is false, we append the content
            $this->body .= $content;
        }
        else
        {
            # yes, replace the body with the content
            $this->body = $content;
        }
    }

    /**
     * get content retunrs the response body
     */
    public function getContent()
    {
        return $this->body;
    }

    /**
     * This flushes the headers and bodydata to the client.
     */
    public function flush()
    {
        // Guess what?
        session_write_close();

        // activateOutputCompression
        $this->activateOutputCompression();

        // Send the status line
        header('HTTP/1.1 '.$this->statusCode.' '.$this->getStatusCodeDescription($this->statusCode));

        // Set X-Powered-By Header to Clansuite Signature
        $this->addheader('X-Powered-By', '[ Clansuite - just an eSport CMS ][ Version : '. CLANSUITE_VERSION .' ][ www.clansuite.com ]');

        // Send our Content-Type with UTF-8 encoding
        #$this->addHeader('Content-Type', 'text/html; charset=UTF-8');

        // Send user specificed headers from $this->headers array
        foreach ($this->headers as $name => $value)
        {
             header("{$name}: {$value}", false);
        }

        // Finally PRINT the response body
        print $this->body;

        // Flush Compressed Buffer
        if(defined('OB_GZIP')){ new gzip_encode($this->output_compression_level); }

        // OK, Reset -> Package delivered! Return to Base!
        $this->clearHeaders();
    }

    /**
     *  ================================================
     *     Compress output if the browser supports it
     *  ================================================
     *
     * Method 1: zlib
     * Method 2: Fallback to ob_start('gz_handler') = output buffering with gzip handling
     *
     * @todo note by vain: problems reported with cached smarty templates... we'll see how that works out
     */
    public function activateOutputCompression()
    {
        # Check for Debugging
        if( (bool)XDEBUG === false or (bool)DEBUG === false)
        {
            # Method 1: zlib
            if (extension_loaded('zlib'))
            {
                ini_set('zlib.output_compression'       , true);
                ini_set('zlib.output_compression_level' , $this->output_compression_level);

                # Method 2: Fallback to ob_start('gz_handler') = output buffering with gzip handling
                if((bool)ini_get('zlib.output_compression') === false)
                {
                  ob_start('ob_gzhandler');

                  require ROOT_LIBRARIES . 'gzip_encode/class.gzip_encode.php';

                  define('OB_GZIP', true);
                }
            }
        }
        else
        {
            #No Output-Compression when Debugging!
        }
    }

    /**
     *  ================================================
     *     Unicode & Charset Settings
     *  ================================================
     * @link    http://www.php.net/manual/en/ref.unicode.php
     */
    public function applyOutputCharset()
    {
        #declare(encoding=$config['language']['outputcharset']);
        /**
        #ini_set('unicode.output_encoding', 'utf-8');
        #ini_set('unicode.stream_encoding', 'utf-8');
        #ini_set('unicode.runtime_encoding', 'utf-8');
        unicode.fallback_encoding       =
        unicode.from_error_mode         = U_INVALID_SUBSTITUTE;         # replace invalid characters
        unicode.from_error_subst_char   =
        unicode.http_input_encoding     = $config['language']['outputcharset'];
        unicode.output_encoding         = $config['language']['outputcharset'];
        unicode.runtime_encoding        =
        unicode.script_encoding         = $config['language']['outputcharset'];
        # this is PHP_INI_PERDIR and can only set via php.ini or .htaccess "php_flag unicode.semantics 1"
        #unicode.semantics               = 1
        */

        // Set Charset and Character Encoding
        if(function_exists('mb_http_output'))
        {
            mb_http_output($this->config['language']['outputcharset']);
            mb_internal_encoding($this->config['language']['outputcharset']);
            mb_regex_encoding('UTF-8');
            mb_language('uni');
            # replace mail(), str*(), ereg*() by mbstring functions
            ini_set('mbstring.func_overload','7');
        }

        /*
        // iconv
        if (function_exists('iconv')
        {
            iconv_set_encoding('input_encoding',   $config['language']['outputcharset']);
            iconv_set_encoding('internal_encoding',$config['language']['outputcharset']);
            iconv_set_encoding('output_encoding',  $config['language']['outputcharset']);
        }*/
    }

    /**
     * Resets the Headers and the Data
     */
    public function clearHeaders()
    {
        $this->headers  = array();
        $this->body     = null;
    }
    /**
     * A better alternative (RFC 2109 compatible) to the php setcookie() function
     *
     * @author isooik at gmail-antispam dot com
     * @link http://de.php.net/manual/de/function.setcookie.php#81398
     * @param string Name of the cookie
     * @param string Value of the cookie
     * @param int Lifetime of the cookie
     * @param string Path where the cookie can be used
     * @param string Domain which can read the cookie
     * @param bool Secure mode?
     * @param bool Only allow HTTP usage? (PHP 5.2)
     * @return bool True or false whether the method has successfully run
     *
     * @todo until php6 namespaces, the methodname can not be setCookie()
     *       because this would conflict with the php function
     */
    public function createCookie($name, $value='', $maxage = 0, $path='', $domain='', $secure = false, $HTTPOnly = false)
    {
        $ob = ini_get('output_buffering');

        # Abort the method if headers have already been sent, except when output buffering has been enabled
        if ( headers_sent() and (bool) $ob === false or strtolower($ob) == 'off' )
        {
            return false;
        }

        if ( !empty($domain) )
        {
            # Fix the domain to accept domains with and without 'www.'.
            if ( strtolower( substr($domain, 0, 4) ) == 'www.' )
            {
                $domain = substr($domain, 4);
            }

            # Add the dot prefix to ensure compatibility with subdomains
            if ( substr($domain, 0, 1) != '.' )
            {
                $domain = '.'.$domain;
            }

            # Remove port information.
            $port = strpos($domain, ':');

            if ( $port !== false )
            {
                $domain = substr($domain, 0, $port);
            }
        }

        # Prevent "headers already sent" error with utf8 support (BOM)
        //if ( utf8_support ) header('Content-Type: text/html; charset=utf-8');
        # @todo appending to string with dots is slow, "sprintf"y this
        header('Set-Cookie: '.rawurlencode($name).'='.rawurlencode($value)
                                    .(empty($domain) ? '' : '; Domain='.$domain)
                                    .(empty($maxage) ? '' : '; Max-Age='.$maxage)
                                    .(empty($path) ? '' : '; Path='.$path)
                                    .(!$secure ? '' : '; Secure')
                                    .(!$HTTPOnly ? '' : '; HttpOnly'), false);
        return true;
    }

    /**
     * Deletes a cookie
     *
     * @param string $name Name of the cookie
     * @param string $path Path where the cookie is used
     * @param string $domain Domain of the cookie
     * @param bool Secure mode?
     * @param bool Only allow HTTP usage? (PHP 5.2)
     */
    public function deleteCookie($name, $path = '/', $domain = '', $secure = false, $httponly = null)
    {
        # expire = 324993600 = 1980-04-19
        setcookie($name, '', 324993600, $path, $domain, $secure, $httponly);
    }

    /**
     * Sets NoCache Header Values
     */
    public function setNoCacheHeader()
    {
        # reset pragma header
        $this->addHeader('Pragma',        'no-cache');
        # reset cache-control
        $this->addHeader('Cache-Control', 'no-store, no-cache, must-revalidate');
        # append cache-control
        $this->addHeader('Cache-Control', 'post-check=0, pre-check=0');
        # force immediate expiration
        $this->addHeader('Expires',       '1');
    }

    /**
     * Redirect
     *
     * Redirects to another action after disabling the caching.
     * This avoids the typicals reposting after an POST is send,
     * by disabling the cache.
     * This enables the POST-Redirect-GET Workflow.
     *
     * @param string Redirect to this URL
     * @param int    seconds before redirecting (for the html tag "meta refresh")
     * @param int    http status code, default: '302' => 'Not Found'
     * @param text   text of redirect message
     */
    public function redirectNoCache($url, $time = 0, $statusCode = 302, $text='')
    {
        $this->setNoCacheHeader();
        $this->redirect($url, $time = 0, $statusCode = 302, $text='');
    }

    /**
     * Redirect
     *
     * Redirects to the URL.
     * This redirects automatically, when headers are not already sent,
     * else it provides a link to the target URL for manual redirection.
     *
     * Time defines how long the redirect screen will be displayed.
     * Statuscode defines a http status code. The default value is 302.
     * Text is a messagestring for the htmlbody of the redirect screen.
     *
     * @param string Redirect to this URL
     * @param int    seconds before redirecting (for the html tag "meta refresh")
     * @param int    http status code, default: '302' => 'Not Found'
     * @param text   text of redirect message
     */
    public function redirect($url, $time = 0, $statusCode = 302, $text='')
    {
        # redirect only, if headers are NOT already send
        if (headers_sent($filename, $linenum) == false)
        {
            # clear all output buffers
            while(@ob_end_clean());

            # redirect to ...
            $this->setStatusCode($statusCode);

            # redirect html content
            $redirect_html  = '';
            $redirect_html  = '<html><head>';
            $redirect_html .= '<meta http-equiv="refresh" content="' . $time . '; URL=' . $url . '" />';
            $redirect_html .= '</head><body>' . $text . '</body></html>';

            # $this->addHeader('Location', $url);
            $this->setContent($redirect_html, $time, htmlspecialchars($url, ENT_QUOTES, 'UTF-8'));

            # Flush the content on the normal way!
            $this->flush();

            # @todo event log
        }
        else # headers already send!
        {
            #echo "<script>document.location.href='$url';</script>";
            print "Header bereits gesendet in $filename in Zeile $linenum\n" .
                  "Redirect nicht moeglich, klicken Sie daher statt dessen <a " .
                  "href=\"$url\">diesen Link</a> an\n";

            # Exit after redirect
            exit;
        }
    }
}
?>