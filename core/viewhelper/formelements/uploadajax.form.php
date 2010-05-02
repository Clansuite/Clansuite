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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

# conditional include of the parent class
if (false == class_exists('Clansuite_Formelement_File',false))
{ 
    include dirname(__FILE__) . '/file.form.php';
}

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_File
 *      |
 *      \- Clansuite_Formelement_Uploadajax
 */
class Clansuite_Formelement_Uploadajax extends Clansuite_Formelement_File implements Clansuite_Formelement_Interface
{
    /**
     * This renders a ajax file upload form using jQuery ajaxupload.
     */
    public function render()
    {
        # a) loads the ajaxupload javascript file
        $javascript = '<script src="'.WWW_ROOT_THEMES_CORE . '/javascript/jquery/ajaxupload.js'. '" type="text/javascript"></script>';

        # b) handler for the ajaxupload
        $javascript .= "
        <script type=\"text/javascript\">// <![CDATA[
        $(function(){
                var btnUpload=$(\"#upload\");
                var status=$(\"#upload_status\");
                new AjaxUpload(btnUpload, {
                    action: 'upload-file.php',
                    // name of the file input box
                    name: 'uploadfile',
                    onSubmit: function(file, ext){
                        if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                            // check for valid file extension
                            status.text('Only JPG, PNG or GIF files are allowed');
                            return false;
                        }
                        status.text('Uploading...');
                    },
                    onComplete: function(file, response){

                        // on completion clear the status
                        status.text('');

                        // add uploaded file to list
                        if(response===\"success\"){
                            $('<li></li>').appendTo('#files').html('<img src=\"./uploads/'+file+'\" alt=\"\" /><br />'+file).addClass('success');
                        } else{
                            $('<li></li>').appendTo('#files').text(file).addClass('error');
                        }
                    }
                });
            });
            // ]]></script>";

        # c) css style
        $html = '<STYLE type="text/css">'.CR.'
                 #upload{
                     margin:30px 200px; padding:15px;
                     font-weight:bold; font-size:1.3em;
                     font-family:Arial, Helvetica, sans-serif;
                     text-align:center;
                     background:#f2f2f2;
                     color:#3366cc;
                     border:1px solid #ccc;
                     width:150px;
                     cursor:pointer !important;
                     -moz-border-radius:5px; -webkit-border-radius:5px;
                     }'.CR.
                '</STYLE>';

        # d) output div elements (Button, Status, Files)
        $html .= '<!-- Ajax Upload Button --><div id="upload">Upload File</div>
                  <!-- Ajax Upload Status --><span id="upload_status"></span>
                  <!-- List Files --><ul id="files"></ul>';

        return $javascript.$html;
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>