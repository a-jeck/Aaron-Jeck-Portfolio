<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/portfolio/filestorage/stylesheet.css">
    <title>Upload</title>
</head>

<body>
    

    <!-- ////    ALERT IN CASE OF UPLOAD FAILURE    //// -->
    <?php if (isset($_SESSION['uploadfailure'])) {
        echo "Please select a file to upload with a valid username!";
    }
    unset($_SESSION['uploadfailure']); ?>


    <!-- ////    FORM AND BUTTONS FOR UPLOADING FILE    //// -->
    <form enctype="multipart/form-data" method="POST" action="index.php" id="uploadform">
        <h3>
            <input type="hidden" name="page" value="upload_handler">
            <label for="uploadfile_input"> Choose a file to upload:</label> <input name="uploadedfile" type="file" id="uploadfile_input">
        </h3>
    </form>
    <button name="uploadsubmit" value="Upload File" form="uploadform" class="button1">Upload</button>
    <button name="uploadreturn" value="Return" form="uploadform" class="button2">Cancel</button>




</body>

</html>
