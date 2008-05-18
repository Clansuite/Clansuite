<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * File:         httpresponse.class.php
    * Requires:     PHP 5.2
    *
    * Purpose:      Clansuite Core Class for Response Handling
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Jens-Andre Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Interface for the Response Object
 *
 * @package clansuite
 * @subpackage core
 * @category interfaces
 */
interface Clansuite_Response_Interface
{
    public function setStatusCode($statusCode);
    public function addHeader($name, $value);
    public function setContent($data);
    public function flush();
    public function newCookie($name, $value);
}

/**
 * HTTPResponse represents the web response object on a request processed by Clansuite.
 *
 * @todo: headers, cookies
 *
 * @package clansuite
 * @subpackage core
 * @category httpresponse
 */
class HTTPResponse implements Clansuite_Response_Interface
{
    /**
     * Status of the response as integer value.
     * $statusCode = '200' => 'OK'
     *
     * @var       integer
     * @access    protected
     */
    protected $statusCode = '200';

    /**
     * Array holding the response headers.
     *
     * @var       array
     * @access    protected
     */
    protected $headers = array();

    /**
     * String holding the response body.
     *
     * @var       string
     * @access    protected
     */
    protected $body = null;

    protected $config; # holds instance of Clansuite_Config

    public function __construct(Clansuite_Config $config)
    {
        $this->config = $config; # set instance
    }

    /**
     * Sets the HTTP Status Code for this response.
     * This method is also used to set the return status code when there
     * is no error (for example for the status codes 200 (OK) or 301 (Moved permanently) ).
     *
     * @access   public
     * @param    integer    $statusCode        The status code to set
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = (string) $statusCode;
    }

    /**
     * Get-Method for HTTP 1.1 status code and its meaning.
     *
     * used in (@link $this->flush )
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
      * @param  string    $name     the name of the header
      * @param  string    $value    the value of the header
      */
    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * append content to body
     *
     * @access public
     * @param  string  $content    Content to store in the buffer
     */
    public function setContent($content)
    {
        $this->body .= $content;
    }

    /**
     * This flushes the headers and bodydata to the client.
     *
     * @todo implement version into header: a. from $config or b. define
     */
    public function flush()
    {
        // Guess what?
        session_write_close();

        // Send the status line
        header('HTTP/1.1 '.$this->statusCode.' '.$this->getStatusCodeDescription($this->statusCode));

        // Send X-Powered-By Header to Clansuite Signature
        $this->addheader('X-Powered-By', '[ Clansuite - just an eSport CMS ][ Version : '. $this->config['clansuite_version'] .' ][ www.clansuite.com ]');

         // Send our Content-Type with UTF-8 encoding
        $this->addHeader('Content-Type', 'text/html; charset=UTF-8');

        // Send user specificed headers from $this->headers array
        foreach ($this->headers as $name => $value)
        {
            header("{$name}: {$value}", false);
        }

        // Finally PRINT the response body
        print $this->body;

        // Flush Compressed Buffer
        if(defined('OB_GZIP')){ new gzip_encode(7); }

        // OK, Reset -> Package delivered! Return to Base!
        $this->clearHeaders();
    }

    /**
     * Resets the Headers and the Data
     */
    public function clearHeaders()
    {
        $this->headers  = array();
        $this->data     = null;
    }

    /**
     * Sets a Cookie
     *
     * @todo until php6 namespaces, function name can not be setCookie
     *        because this would conflict with the php function
     *
     * @param string name
     * @param string value
     * @access public
     */
    public function newCookie($name, $value)
    {

    }
}
?>