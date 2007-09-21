<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf � 2005-2007
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
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$)
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
 */
interface response_interface
{
    public function setStatus($status);
    public function addHeader($name, $value);
    public function setContent($data);
    public function flush();
}

/**
 * response represents the response object
 * on a request processed by clansuite.
 */
class httpresponse implements response_interface
{
    /**
     * Status of the response as integer value.
     * $status = '200' => 'OK'
     * $status = '404' => 'Not Found'
     *
     * @var       integer
     * @access    private
     */
    private $status = '200';

    /**
     * Array holding the response headers.
     *
     * @var       array
     * @access    private
     * todo note by vain: better protected?
     */
    private $headers = array();

    /**
     * String holding the response body.
     *
     * @var        string
     * @access    private
     */
    private $body = null;

    /**
     * Sets the HTTP Status Code for this response.
     * This method is also used to set the return status code when there
     * is no error (for example for the status codes 200 (OK) or 301 (Moved permanently) ).
     *
     * @access   public
     * @param    integer    $status        The status code to set
     */
    public function setStatus($status)
    {
        $this->status = (string) $status;
    }

    /**
     *
     * todo: check functionality
     * used in (@link $this->flush )
     */
    public function statusDescr($status)
    {
        /**
         * Array holding some often occuring status descriptions.
         * @var       array
         */
        $statusDescr = array('200'    => 'OK'
                            ,'202'    => 'Accepted'
                            ,'301'    => 'Moved Permanently'
                            ,'302'    => 'Moved Temporarily'
                            ,'304'    => 'Not Modified'
                            ,'400'    => 'Bad Request'
                            ,'401'    => 'Unauthorized'
                            ,'403'    => 'Forbidden'
                            ,'404'    => 'Not Found'
                            ,'500'    => 'Internal Server Error'
                            );
        return $statusDescr($status);
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
     * @todo: implement version into header: a. from $config or b. define
     */
    public function flush()
    {
        $config['version'] = '0.1 alpha - dev';

        // Send the status line
        header('HTTP/1.1 '.$this->status.' '.$this->statusDescr($this->status), true);

        // Send X-Powered-By Header to Clansuite Signature
        header('X-Powered-By: [ Clansuite - just an eSport CMS ] [ Version : '. $config['version'] .' ] [ www.clansuite.com ]' , false);

        // Send our Content-Type with UTF-8 encoding
        header('Content-Type: text/html; charset=UTF-8');

        // Send user specificed headers from $this->headers array
        foreach ($this->headers as $name => $value)
        {
            header("{$name}: {$value}");
        }

        // Finally PRINT the response body
        print $this->body;

        // reset headers and data
        $this->headers = array();
        $this->data = null;
    }

    /**
     * Redirect to URL
     *
     * @param string $url
     * @param integer $status
     * @access public
     */
    public function redirect($url, $status = null)
    {
        // safe session data
        session_write_close();
        // set status and header location to redirect url
        $this->setStatus($status);
        $this->addHeader('Location', $url)->flush();
    }
}
?>