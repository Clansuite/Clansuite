{move_to target="pre_head_close"}
<script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.js"></script>

<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}javascript/swfupload/swfupload.css" />

<script type="text/javascript" src="{$www_root_themes_core}javascript/swfupload/swfupload.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}javascript/swfupload/handlers.js"></script>

<script type="text/javascript">
// <![CDATA[
    var swfupload;

window.onload = function() {
    var settings = {
        // Backend Settings
        upload_url: "{$www_root}uploads/upload.php",

        // File Upload Settings
        file_size_limit : "2 MB",
        file_types : "*.jpg",
        file_types_description : "JPG Images",
        file_upload_limit : 0,

        // Event Handler Settings - these functions as defined in Handlers.js
        swfupload_preload_handler : preLoad,
        swfupload_load_failed_handler : loadFailed,
        file_queue_error_handler : fileQueueError,
        file_dialog_complete_handler : fileDialogComplete,
        upload_progress_handler : uploadProgress,
        upload_error_handler : uploadError,
        upload_success_handler : uploadSuccess,
        upload_complete_handler : uploadComplete,

        // Button Settings
        button_image_url : "{$www_root_themes_core}javascript/swfupload/XPButtonNoText_150x22.png",
        button_placeholder_id : "spanButtonPlaceholder",
        button_width: 150,
        button_height: 22,
        button_text : '<span class="button">Upload</span>',
        button_text_style : '.button { text-align:center; font-family: Helvetica, Arial, sans-serif; font-size: 12pt; } .buttonSmall { font-size: 10pt; }',
        button_text_top_padding: 2,
        //button_text_left_padding: 10,
        button_action : SWFUpload.BUTTON_ACTION.SELECT_FILE,
        button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
        button_cursor: SWFUpload.CURSOR.HAND,

        // Flash Settings
        flash_url : "{$www_root_themes_core}javascript/swfupload/swfupload.swf",
        flash9_url : "{$www_root_themes_core}javascript/swfupload/swfupload_FP9.swf",

        // Post Settings
        post_params: {
             "sess_name": "{$sess_name}",
             "sess_id": "{$sess_id}",
             "upload_directory": "uploads",
             "upload_subdirectory": "temps"
        },

        custom_settings : {
            upload_target : "divFileProgressContainer",
            uploadpath : "{$www_root}uploads",
            picture_width: 180,
            picture_height: 135,
            picture_quality: 100
        },

        // Debug Settings
        debug: true
    }

    swfupload = new SWFUpload(settings);

}

 // ]]>
</script>
{/move_to}

<center>

<table class="tables" cellpadding="0" cellspacing="0" border="0" summary="testunit" align="center" style="width:800px;height:400px;border:1px solid #000;">
    <tr valign="top"><td colspan="2" valign="middle" align="center" class="arial12white" bgcolor="#FF0000"><b>Test: Ajax Upload mit swfUpload</b></td></tr>
    <tr valign="top"><td colspan="2" valign="top" bgcolor="#000000"><img src="{$www_root_theme}images/blind.gif" border="0" height="1" width="1" alt="testunit" /></td></tr>
    <tr valign="top"><td colspan="2" valign="top"><img src="{$www_root_theme}images/blind.gif" border="0" height="40" width="1" alt="testunit" /></td></tr>
    <tr valign="top">
        <td valign="top" width="50%" align="right" style="padding:10px;">
            <span id="spanButtonPlaceholder"></span>
        </td>
        <td valign="top" width="50%" align="left" style="padding:15px 10px;">
            Upload-Bild:&nbsp;
            <input type="text" id="image_small" name="image_small" value="" />
            <div id="divFileProgressContainer" style="height: 35px; float:right;"></div>
            <div id="thumbnails"></div>
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

Navigation: {breadcrumbs}

</center>