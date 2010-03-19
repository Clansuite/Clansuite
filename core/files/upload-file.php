<?php
#var_dump($_FILES);
if(!empty($_FILES) and isset($_FILES['uploadfile']['tmp_name']) )
{
    # define a upload directory as restriction
    $uploaddir = './uploads/';
    # build complete filepath, by cutting of the dirnames with basename()
    $file = $uploaddir . basename($_FILES['uploadfile']['name']);

    if( move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file) )
    {
        echo "File uploaded.";
    }
    else
    {
        echo "File Upload Error.";
    }
}
?>
