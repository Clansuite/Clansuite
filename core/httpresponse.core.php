<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-AndrÃ© Koch Â© 2005 - onwards
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

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
    public static function setStatusCode($statusCode);
    public static function addHeader($name, $value);
    public static function setContent($content, $replace = false);
    public static function sendResponse();

    # Cookie Methods
    public static function createCookie($name, $value='', $maxage = 0, $path='', $domain='', $secure = false, $HTTPOnly = false);
    public static function deleteCookie($name, $path = '/', $domain = '', $secure = false, $httponly = null);
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
    protected static $statusCode = '200';

    /**
     * @var array Array holding the response headers.
     */
    protected static $headers = array();

    /**
     * @var string String holding the response body.
     */
    protected static $body = null;

    /**
     * Sets the HTTP Status Code for this response.
     * This method is also used to set the return status code when there
     * is no error (for example for the status codes 200 (OK) or 301 (Moved permanently) ).
     *
     * @param  integer $statusCode The status code to set
     */
    public static function setStatusCode($statusCode)
    {
        self::$statusCode = (string) $statusCode;
    }

    /**
     * Get HTTP 1.1 status code description by status code.
     *
     * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
     */
    public static function getStatusCodeDescription($statusCode)
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
                                       '500'    => 'Internal Server Error',
                                       '503'    => 'Service Temporarily Unavailable'
                                      );

        return $statusCodeDescription[$statusCode];
    }

     /**
      * add a header to the response array, which is send to the browser
      *
      * @param  string $name the name of the header
      * @param  string $value the value of the header
      */
    public static function addHeader($name, $value)
    {
        self::$headers[$name] = $value;
    }

    /**
     * setContent:
     * appends content to the response body
     * when replace is true, the bodycontent is replaced
     *
     * @param string $content Content to store in the buffer
     * @param boolean $replace toggles append or replace content
     */
    public static function setContent($content, $replace = false)
    {
        # check, if the content should be replaced
        if($replace == false)
        {
            # no, replace is false, we append the content
            self::$body .= $content;
        }
        else
        {
            # yes, replace the body with the content
            self::$body = $content;
        }
    }

    /**
     * get content retunrs the response body
     */
    public static function getContent()
    {
        return self::$body;
    }

    /**
     * This flushes the headers and bodydata to the client.
     */
    public static function sendResponse()
    {
        # Guess what?
        session_write_close();

        # activateOutputCompression when not in debugging mode
        if( XDEBUG === false and DEBUG === false)
        {
            Clansuite_ResponseEncode::start_outputbuffering('7');
        }

        # Send the status line
        self::addHeader('HTTP/1.1', self::$statusCode.' '.self::getStatusCodeDescription(self::$statusCode));

        # Set X-Powered-By Header to Clansuite Signature
        self::addHeader('X-Powered-By', '[ Clansuite - just an eSport CMS ][ Version : '. CLANSUITE_VERSION .' ][ www.clansuite.com ]');

        # Suppress Framesets
        self::addHeader('X-Frame-Options', 'deny'); # not SAMEORIGIN

        # Send our Content-Type with UTF-8 encoding
        self::addHeader('Content-Type', 'text/html; charset=UTF-8');

        # Send user specificed headers from self::$headers array
        if(false === headers_sent())
        {
            foreach(self::$headers as $name => $value)
            {
                header($name . ': ' . $value, false);
            }
        }

        // unheroic approach to silence all html validators by fixing the ampersand problem ( turns & to &amp;)
        // exclude javascript && logical operator
        // @todo find preg_match for & replacing that does not grab && AND remove double str_replace
        #self::$body = str_replace('&&','CS+AND+CS', self::$body);
        #self::$body = preg_replace('/&(?![#]?[a-z0-9]{1,7};)/i', '&amp;$1', self::$body);
        #self::$body = str_replace('CS+AND+CS','&&', self::$body);

        # make it possible to attach HTML content to the body directly before flushing the response
        Clansuite_CMS::triggerEvent('onBeforeResponse', array('body' => self::$body));

        # Finally echo the response body
        echo self::getContent();

        // Flush Compressed Buffer
        if( XDEBUG === false and DEBUG === false)
        {
            Clansuite_ResponseEncode::end_outputbuffering();
        }

        # OK, Reset -> Package delivered! Return to Base!
        self::clearHeaders();
    }

    /**
     * Resets the Headers and the Data
     */
    public static function clearHeaders()
    {
        self::$headers  = array();
        self::$body     = null;
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
     * Note: until php6 namespaces, the methodname can not be setCookie()
     *       because this would conflict with the php function name.
     */
    public static function createCookie($name, $value='', $maxage = 0, $path='', $domain='', $secure = false, $HTTPOnly = false)
    {
        $ob = ini_get('output_buffering');

        # Abort the method if headers have already been sent, except when output buffering has been enabled
        if ( headers_sent() and (bool) $ob === false or mb_strtolower($ob) == 'off' )
        {
            return false;
        }

        if ( !empty($domain) )
        {
            # Fix the domain to accept domains with and without 'www.'.
            if ( mb_strtolower( mb_substr($domain, 0, 4) ) == 'www.' )
            {
                $domain = mb_substr($domain, 4);
            }

            # Add the dot prefix to ensure compatibility with subdomains
            if ( mb_substr($domain, 0, 1) != '.' )
            {
                $domain = '.'.$domain;
            }

            # Remove port information.
            $port = mb_strpos($domain, ':');

            if ( $port !== false )
            {
                $domain = mb_substr($domain, 0, $port);
            }
        }

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
    public static function deleteCookie($name, $path = '/', $domain = '', $secure = false, $httponly = null)
    {
        # expire = 324993600 = 1980-04-19
        setcookie($name, '', 324993600, $path, $domain, $secure, $httponly);
    }

    /**
     * Sets NoCache Header Values
     */
    public static function setNoCacheHeader()
    {
        # set nocache via session
        #session_cache_limiter('nocache');

        # reset pragma header
        self::addHeader('Pragma',        'no-cache');
        # reset cache-control
        self::addHeader('Cache-Control', 'no-store, no-cache, must-revalidate');
        # append cache-control
        self::addHeader('Cache-Control', 'post-check=0, pre-check=0');
        # force immediate expiration
        self::addHeader('Expires',       '1');
    }

    /**
     * Redirect
     *
     * Redirects to another action after disabling the caching.
     * This avoids the typical reposting after an POST is send by disabling the cache.
     * This enables the POST-Redirect-GET Workflow.
     *
     * @param string Redirect to this URL
     * @param int    seconds before redirecting (for the html tag "meta refresh")
     * @param int    http status code, default: '302' => 'Not Found'
     * @param text   text of redirect message
     * @param string redirect mode LOCATION, REFRESH, JS, HTML
     */
    public static function redirectNoCache($url, $time = 0, $statusCode = 302, $text = '', $mode = null)
    {
        self::setNoCacheHeader();
        self::redirect($url, $time, $statusCode, $text);
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
     * @param string redirect mode LOCATION, REFRESH, JS, HTML
     */
    public static function redirect($url, $time = 0, $statusCode = 302, $message = null, $mode = null )
    {
        $linenum = '';
        $redirect_html = '';

        # redirect only, if headers are NOT already send
        if (headers_sent($filename, $linenum) == false)
        {
            # clear all output buffers
            #while(@ob_end_clean());

            # redirect to ...
            self::setStatusCode($statusCode);

            /**
             * Set flashmessage on redirect
             */
            if(isset($message) and strpos($message, '#'))
            {
                /**
                 * detect flashmessage tunneling ($message is "flashmessagetype#message text")
                 * array[0] = type ; array[1] = message
                 */
                $array = explode('#', $message);

                # ensure type is a valid flashmessagetype
                if(in_array($array[0], Clansuite_Flashmessages::getFlashMessageTypes()))
                {
                    Clansuite_Flashmessages::setMessage($array[0], $array[1]);
                }

                unset($message);
            }

            switch($mode)
            {
                default:
                case 'LOCATION':
                    self::addHeader('LOCATION', $url);
                    break;
                case 'REFRESH':
                    header('Refresh: 0; URL="' . $url . '"');
                    break;
                case 'JS':
                    $redirect_html = '<script type="text/javascript">window.location.href=' . $url . ';</script>';
                    break;
                case 'HTML':
                    # redirect html content
                    $redirect_html = '<html><head>';
                    $redirect_html .= '<meta http-equiv="refresh" content="' . $time . '; URL=' . $url . '" />';
                    $redirect_html .= '</head><body>' . $message . '</body></html>';
                    break;
            }

            if(empty($redirect_html) === false)
            {
                #self::addHeader('Location', $url);
                self::setContent($redirect_html, $time, htmlspecialchars($url, ENT_QUOTES, 'UTF-8'));
            }

            # Flush the content on the normal way!
            self::sendResponse();
        }
        else # headers already send!
        {
            $msg  = _('Header already send in file %s in line %s. Redirecting impossible.');
            $msg .= _('You might click this link instead to redirect yourself to the <a href="%s">target url</a> an');
            printf($msg, $filename, $linenum, $url);
            exit;
        }
    }
}
?>