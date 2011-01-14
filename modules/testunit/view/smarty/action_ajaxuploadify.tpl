{move_to target="pre_head_close"}
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.js"></script>

<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}css/uploadify/default.css" />
<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}css/uploadify/uploadify.css" />

<script type="text/javascript" src="{$www_root_themes_core}javascript/uploadify/swfobject.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.uploadify.v2.1.4.min.js"></script>

<script type="text/javascript">
// <![CDATA[
$(function(){

    $('#file_upload').uploadify({
            'uploader'        : '{$www_root_themes_core}javascript/uploadify/uploadify.swf',
            'script'          : '{$www_root}uploads/uploadify.php',
            'cancelImg'       : '{$www_root_themes_core}images/icons/cancel.png',
            'buttonText'      : 'Durchsuchen',
            'multi'           : false,
            'auto'            : true,
            'removeCompleted' : true,
            'height'          : 30,
            'width'           : 110,
            'folder'          : '{$www_root}uploads/temps',
            'wmode'           : 'opaque',
            'scriptAccess'    : 'always',
            onComplete: function(event, ID, fileObj, response, data) {
                // event:										The event object
                // ID											The unique ID of the file queue item.
                // fileObj: 
                //		[name]								The name of the uploaded file
                //		[filePath]							The path on the server to the uploaded file
                //		[size]									The size in bytes of the file
                //		[creationDate]					The date the file was created
                //		[modificationDate]			The last date the file was modified
                //		[type]									The file extension beginning with a ‘.’
                // response:								The text response sent back from the back-end upload script
                // data:
                //		[fileCount]							The number of files remaining in the queue
                //		[speed]								The average speed pf the file upload in KB/s
                var ele1 = document.getElementById('uploadimage');
                var ele2 = document.getElementById('previewlogo');
                ele1.value = fileObj.name;
                ele2.src = fileObj.filePath;
                if (response !== '1') alert(response + "\n" + fileObj + "\n" + data);
            },
            onError: function (a, b, c, d, e) {
                var msg;
                msg = 'Event: ' +a+ "\n";
                msg += 'File-ID: ' +b+ "\n";
                msg += 'FileObj: ' + "\n";
                msg += '        [name] ' + c.name +"\n";
                msg += '        [filePath] ' + c.filePath +"\n";
                msg += '        [size] ' + c.size +"\n";
                msg += '        [type] ' + c.type +"\n";
                msg += 'Response: ' + "\n";
                msg += '        error ' + d.status +"\n";
                msg += '        status ' + d.type +"\n";
                msg += '        info ' + d.info +"\n";
                msg += '        text ' + d.text;

                if (d.status == 404)
                    alert('Could not find upload script. Use a path relative to: '+'<?= getcwd() ?>' + "\n" + msg);
                else if (d.type === "HTTP")
                    alert(msg);
                else if (d.type ==="File Size")
                    alert(msg+"\n"+' Limit: '+Math.round(d.sizeLimit/1024)+'KB');
                else
                    alert(msg);
            }

    });

});

 // ]]>
</script>
{/move_to}

<img src="{$www_root_theme}images/blind.gif" border="0" height="10" width="1" alt="testunit" /><br />

<center>
<form method="post" name="testunits" action="index.php?mod=testunit&action=uAjax_uploadify">

<table class="tables" cellpadding="0" cellspacing="0" border="0" summary="testunit" align="center" style="width:800px;height:400px;border:1px solid #000;">
    <tr valign="top"><td colspan="2" valign="middle" align="center" class="arial12white" bgcolor="#FF0000"><b>Test: Ajax Upload mit Uploadify</b></td></tr>
    <tr valign="top"><td colspan="2" valign="top" bgcolor="#000000"><img src="{$www_root_theme}images/blind.gif" border="0" height="1" width="1" alt="testunit" /></td></tr>
    <tr valign="top"><td colspan="2" valign="top"><img src="{$www_root_theme}images/blind.gif" border="0" height="40" width="1" alt="testunit" /></td></tr>
    <tr valign="top">
        <td valign="top" width="50%" align="right" style="padding:10px;">
            <input id="file_upload" type="file" name="Filedata" />
        </td>
        <td class="text_black_12" valign="top" width="50%" align="left" style="padding:15px 10px;">
            Upload-Bild:&nbsp;<input type="text" id="uploadimage" name="uploadimage" value="" />
        </td>
    </tr>
    <tr><td colspan="2"><img src="{$www_root_theme}images/blind.gif" border="0" height="20" width="1" alt="testunit" /></td></tr>
    <tr valign="top">
        <td class="arial12black" width="200" valign="top" align="right" style="padding-right:10px;">Preview:</td>
        <td width="600">
            <img id="previewlogo" src="uploads/logo_none.jpg" width="180" height="135" />
        </td>
    </tr>
</table>
</form>
<b>>></b>&nbsp;<a href="index.php?mod=testunit" class="arial12black">back</a>&nbsp;<b><<</b><br/>
</center>
<img src="{$www_root_theme}images/blind.gif" border="0" height="10" width="1" alt="testunit" /><br />
