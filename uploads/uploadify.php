<?php
if (empty($_FILES) == false)
{
    $uploadFieldName = "Filedata";                                                // e.g. $_FILES[$PostFieldName]["tmp_name"]

    $valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';            // Characters allowed in the file name (in a Regular Expression format)
    $MAX_FILENAME_LENGTH = 260;
    $file_name = "";
    $file_extension = "";
    $uploadfile = '';

    $tempFile = $_FILES[$uploadFieldName]['tmp_name'];

    $upload_directory = 'uploads';
    $upload_subdirectory = 'temps';

    $parent_dir = array_pop(explode(DIRECTORY_SEPARATOR, dirname(__FILE__)));
    $upload_directory = substr(dirname(__FILE__), 0, strlen(dirname(__FILE__)) - strlen($parent_dir) ) . $upload_directory ; 

    if ($upload_subdirectory !=='' ) 
        $upload_directory .= DIRECTORY_SEPARATOR.$upload_subdirectory;

    if ( !is_writable($upload_directory) ) 
    {
        $upload_directory_writable = false ;
    }
    else
    {
        $upload_directory_writable = true ;
    }

    $file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$uploadFieldName]['name']));
    if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
        $msg .= "ERROR:Invalid file name (line:".__LINE__.")\n";
        //echo $msg;
    }

    $path_info = pathinfo(basename($_FILES[$uploadFieldName]['name']));
    $file_extension = $path_info["extension"];
    $deldoble = '.'.$file_extension.'.'.$file_extension;
    $file_name = str_replace( $deldoble, '.'.$file_extension, $file_name);

    $uploadfile = $upload_directory. DIRECTORY_SEPARATOR . $file_name;

    # COPY UPLOAD SUCCESS
    if( $upload_directory_writable )
    {
        move_uploaded_file($tempFile,$uploadfile);
        chmod( $uploadfile, 0666 );
        echo "1";
    }
    else {
        echo "0";
    }
}
?>