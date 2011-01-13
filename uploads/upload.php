<?php

# ------------------------------------------
# DEBUG
# ------------------------------------------
    $debug = false;
    $debugArray = array();

# ------------------------------------------
# DEFINING $upload_directory
# ------------------------------------------
    # Must point to a PHP writable directory
    # See http://www.onlamp.com/pub/a/php/2003/02/06/php_foundations.html for dealing with PHP permissions
    $upload_directory = '' ; // leave blank for default

# ------------------------------------------
# UPLOAD VAR Initialize
# ------------------------------------------
    $uploadFieldName = "Filedata";                                                // e.g. $_FILES[$PostFieldName]["tmp_name"]
    $extension_whitelist = array("jpg", "png");                               // Allowed file extensions
    $valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';            // Characters allowed in the file name (in a Regular Expression format)
    $MAX_FILENAME_LENGTH = 260;
    $file_name = "";
    $file_extension = "";
    $uploadfile = '';
    $msg = '';

    $debugArray["uploadFieldName"] = $uploadFieldName;
    $debugArray["MAX_FILENAME_LENGTH"] = $MAX_FILENAME_LENGTH;


# ***************************************************************************************
# NO MODIFICATIONS REQUIRED BELOW THIS LINE
# ***************************************************************************************
# ------------------------------------------------------------------------------

# ------------------------------------------
# SESSION
# ------------------------------------------
    // Get the session Id passed from SWFUpload. We have to do this to work-around the Flash Player Cookie Bug
    if (isset($_POST["sess_name"]) and isset($_POST["sess_id"])) {
        session_name($_POST["sess_name"]);
        session_id($_POST["sess_id"]);
    }

    session_start();

    if (!isset($_SESSION["file_info"])) {
        $_SESSION["file_info"] = array();
    }

# ------------------------------------------
# CREATE DEFAULT UPLOAD DIRECTY LOCATION
# ------------------------------------------
    If ( !$upload_directory ) {
         if (isset($_POST["upload_directory"]) and $_POST["upload_directory"] !=='' ) { $upload_directory = $_POST["upload_directory"]; }
         else { $upload_directory = 'uploads';  }

        $parent_dir = array_pop(explode(DIRECTORY_SEPARATOR, dirname(__FILE__)));
        $upload_directory = substr(dirname(__FILE__), 0, strlen(dirname(__FILE__)) - strlen($parent_dir) ) . $upload_directory ; 
         if (isset($_POST["upload_subdirectory"]) and $_POST["upload_subdirectory"] !=='' ) { $upload_directory .= DIRECTORY_SEPARATOR.$_POST["upload_subdirectory"]; }
    }

    $debugArray["upload_directory"] = $upload_directory;

# ------------------------------------------
# TEST UPLOAD DIRECTORY
# ------------------------------------------
    if ( is_dir($upload_directory) and is_writable($upload_directory) ) {
        $upload_directory_writable = true ;
    } else {
        $msg .= "The directory, \"$upload_directory\" is not writable by PHP. Permissions must be changed to upload files. (line:".__LINE__.")\n";
        $upload_directory_writable = false ;
    }

# ------------------------------------------
# PREPARE FILENAME
# ------------------------------------------
    $file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$uploadFieldName]['name']));
    if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
        $msg .= "ERROR:Invalid file name (line:".__LINE__.")\n";
        $debugArray["Error-Msg"] = $msg;
        //exit(0);
    }

# ------------------------------------------
# EXTENSION
# ------------------------------------------
    $path_info = pathinfo(basename($_FILES[$uploadFieldName]['name']));
    $file_extension = $path_info["extension"];
    $deldoble = '.'.$file_extension.'.'.$file_extension;
    $file_name = str_replace( $deldoble, '.'.$file_extension, $file_name);

    $uploadfile = $upload_directory. DIRECTORY_SEPARATOR . $file_name;

    $debugArray["file_name"] = $file_name;
    $debugArray["file_extension"] = $file_extension;
    $debugArray["uploadfile"] = $uploadfile;
    $debugArray["upload_directory_writable"] = ($upload_directory_writable?'yes':'no');


# ------------------------------------------
# CHECK IS FILE UPLOADED
# ------------------------------------------
    if ( !isset($_FILES[$uploadFieldName]) || !is_uploaded_file($_FILES[$uploadFieldName]["tmp_name"]) || $_FILES[$uploadFieldName]["error"] != 0) {
        switch ($_FILES[$upload_name]["error"]) {
            case 1: $error_msg = '#1 - File exceeded maximum server upload size of '.ini_get('upload_max_filesize').'.'; break;
            case 2: $error_msg = '#2 - File exceeded maximum file size.'; break;
            case 3: $error_msg = '#3 - File only partially uploaded.'; break;
            case 4: $error_msg = '#4 - No file uploaded.'; break; 
        }
        $msg .= $error_msg. " (line:".__LINE__.")\n";
        $debugArray["Error-Msg"] = $msg;

    } else {
# ------------------------------------------
# COPY UPLOAD SUCCESS
# ------------------------------------------
        if ( move_uploaded_file( $_FILES[$uploadFieldName]['tmp_name'] , $uploadfile ) ) {
            @chmod( $uploadfile, 0666 );
        } else {
            $msg .= "SWFUpload File Not Saved: ".$_FILES[$uploadFieldName]["name"]."\nSave Path: ".$uploadfile." (line:".__LINE__.")\n";
            $msg .= "Flash requires that we output something or it won't fire the uploadSuccess event\n";
        }
    }

    $file_id = md5(rand()*10000000);
    $debugArray["file_id"] = $file_id;
    $debugArray["Error-Msg"] = $msg;

    $_SESSION["file_info"][$file_id] = $file_name;

    echo "FILEID:" . $file_id;    // Return the file id to the script

    if($debug) { print_r($debugArray); }

?>