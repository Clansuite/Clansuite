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
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: layout.core.php 2870 2009-03-25 23:21:42Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

if (!class_exists('Clansuite_Formelement_Input')) { require 'input.form.php'; }

/**
 *  Clansuite_Formelement
 *  |
 *  \- Clansuite_Formelement_Input
 *      |
 *      \- Clansuite_Formelement_File
 */
class Clansuite_Formelement_File extends Clansuite_Formelement_Input implements Clansuite_Formelement_Interface
{   /**
     * Flag variable for the usage of apc, uploadify, normal, simple jquery
     *
     * @string
     */
    protected $uploadType;

    /**
     * constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->type = 'file';

        # watch out, that the form needs the enctype="multipart/form-data"
        # else you'll get the filename only and not the content of the file.
    }

    public function setUploadType($uploadType)
    {
        $this->uploadType = $uploadType;

        return $this;
    }

    public function render()
    {
        /**
         * Simple Ajax
         */
        if($this->uploadType == 'ajaxupload')
        {
           $javascript = '<script src="'.WWW_ROOT_THEMES_CORE . '/javascript/jquery/ajaxupload.3.5.js'. '" type="text/javascript"></script>';
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

            # css style
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

            # output ajax file upload form using jQuery
            $html .= '<!-- Ajax Upload Button --><div id="upload">Upload File</div>
                     <!-- Ajax Upload Status --><span id="upload_status"></span>
                     <!-- List Files --><ul id="files"></ul>';

            return $javascript.$html;
        }
        elseif($this->uploadType == 'apc')
        {
            # APC RFC1867 File Upload Progress Hook check
            if(ini_get('apc.rfc1867') == false)
            {
                echo 'No Upload with APC possible.';
            }

            /**
             * APC PROGRESS BAR will only work if
             * a) APC active
             * b) apc.rfc1867 = 1
             * Watch out: This value can not be set by .htaccess, it's a PHP_INI_SYSTEM value.
             * You have to set this in php.ini.
             * @see http://php.net/manual/de/apc.configuration.php
             */

            $javascript = "<script type=\"text/javascript\"> //<![CDATA[

                            $(document).ready(function()
                            {
                                $(\"#progressbar\").progressbar({ value: 0 });
                            });

                            function getUploadProgress(uniqueID) {
                                var req;
                                try {
                                    req = window.XMLHttpRequest?new XMLHttpRequest():
                                        new ActiveXObject(\"Microsoft.XMLHTTP\");
                                } catch (e) {
                                    // No AJAX Support
                                }

                                req.onreadystatechange = function() {
                                    if ((req.readyState == 4) && (req.status == 200)) {
                                        // evaluate the incomming json array
                                        var status = eval('(' + req.responseText + ')');
                                        // call updateDisplay and assign array
                                        updateDisplay(status);
                                    }
                                }
                                req.open('GET', 'get-progress.php?uniqueID='+uniqueID);
                                req.send(null);
                            }

                            function updateDisplay(status)
                            {
                                var rate = parseInt(status['rate']/1024);
                                if(status['cancel_upload'])
                                {
                                    txt='Upload was cancelled after '+resp['current']+' bytes!';
                                }
                                else
                                {
                                    txt=status['total']+' bytes uploaded!';
                                }
                                txt += '<br>Upload rate was '+rate+' kbps.';

                                document.getElementById('upload_status').style.display = '';

                                var percent = parseInt(100*(status['current']/status['total']));
                                document.getElementById('uploadFile').innerHTML = status['filename'];
                                document.getElementById('uploadSize').innerHTML = (parseInt(status['current'])/1024) + 'KB of ' + (parseInt(status['total'])/1024) + 'KB';
                                document.getElementById('progressBar').style.width = ''+percent+'%';

                                // jquery progress bar
                                $('#progressbar').progressbar('option', 'value', percent);

                                // todo: cancel button, status['done'], status['total'], status['canceled']

                                //document.getElementById('upload_status').innerHTML =  txt;

                            }
                            //]]></script>";

            # add an iframe, so that the upload happens in there and is not blocking the website
            $html = '<!-- Hidden iframe for performing the Upload -->'.CR.'
                     <iframe style="display:none" name="hidden_upload" src="'.WWW_ROOT.'/upload-file.php"></iframe>';

            # add ajax status (upload_status, uploadFile, uploadSize, progressBar)
            $html .= '<!-- Ajax Upload Status -->
                      <div id="progressbar"></div>
                      <div id="upload_status" style="display:none;">
                        Currently uploading <strong id="uploadFile"></strong><br>
                        <span id="uploadSize"></span><br>
                        <div style="width:600px; background:#CCCCCC;">
                            <div id="progressBar" style="background-color:#00CC66; width:0%;">&nbsp;</div>
                        </div>
                      </div>
                    ';

            /**
             * APC needs a hidden element
             * a) with a certain name
             * b) with a unique tracking id for the file
             * c) placed before the input file element.
             */
            if (!class_exists('Clansuite_Formelement_Hidden')) { require 'hidden.form.php'; }
            $uniqueID = md5(uniqid(mt_rand(), true));
            $hidden = new Clansuite_Formelement_Hidden();
            $hidden->setName('APC_UPLOAD_PROGRESS')->setID('upload_status')->setValue($uniqueID);
            $html .= $hidden;

            # add the input element
            $html .= '<input name="uploadfile" size="30" type="file">';

            # add a submit button
            if (!class_exists('Clansuite_Formelement_Submitbutton')) { require 'submitbutton.form.php'; }
            $submit = new Clansuite_Formelement_Submitbutton();
            $submit->setValue(_('Upload File'));
            $submit->setAdditionals("onclick=\"this.disabled=true; setInterval('getUploadProgress(\''+this.form.APC_UPLOAD_PROGRESS.value+'\')', 750); \" ");
            $html .= $submit;

            return $javascript.$html;
        }
        elseif($this->uploadType == 'uploadify')
        {
            /**
             * jQuery Uploadify
             * needs jq 1.3.2
             */
            $javascript =  '<link href="'. WWW_ROOT_THEMES_CORE .'/cssc/uploadifye/default.css" rel="stylesheet" type="text/css" />
                            <link href="'. WWW_ROOT_THEMES_CORE .'/css/uploadify/uploadify.css" rel="stylesheet" type="text/css" />
                            <script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE . '/javascript/uploadify/swfobject.js"></script>
                            <script type="text/javascript" src="'.WWW_ROOT_THEMES_CORE . '/javascript/jquery/jquery.uploadify.v2.1.0.min.js"></script>';


            $javascript .= "<script type=\"text/javascript\">// <![CDATA[
                            $(document).ready(function() {
                                $('#uploadify').uploadify({
                                'uploader'  : '".WWW_ROOT_THEMES_CORE."/javascript/uploadify/uploadify.swf',
                                'script'    : '".WWW_ROOT_THEMES_CORE."/javascript/uploadify/uploadify.php',
                                'cancelImg' : '".WWW_ROOT_THEMES_CORE."/images/icons/cancel.png',
                                'auto'      : true,
                                'folder'    : '/uploads'
                                });
                            });
                            // ]]></script>";

            $html = "<div id=\"fileQueue\"></div>
                     <input type=\"file\" name=\"uploadify\" id=\"uploadify\" />
                     <p><a href=\"javascript:jQuery('#uploadify').uploadifyClearQueue()\">Cancel All Uploads</a></p>";

            return $javascript.$html;
        }
        else # fallback to normal <input type="file"> upload
        {
            # by using the render method of the parent class
            #return parent::render();

            return '<input name="uploadfile" type="file">';
        }
    }

    public function __toString()
    {
        return $this->render();
    }
}
?>
