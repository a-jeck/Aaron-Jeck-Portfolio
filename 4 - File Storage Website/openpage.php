<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/portfolio/filestorage/stylesheet.css">
    <title>View File</title>
</head>

<body>
    <?php
    //// Retrieves & Sets Filename/Directory ////
    $filenum = substr($_SESSION['file_number'], 4);
    $filename = $_SESSION['files'][$filenum + 1];
    $_SESSION['currentfilename'] = $filename;
    $dir = '/srv/uploads/' . $_SESSION['username'] . '/' . $filename;
    $_SESSION['currentdir'] = $dir;


    //// Gets File Type for Display ////
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($dir);
    $_SESSION['currentmime'] = $mime;


    //// Cuts filetype to a single phrase for proper HTML display ////
    $slashpos = strpos($mime, '/') + 1;
    $filecat  = substr($mime, 0, $slashpos - 1);
    $filetype = substr($mime, $slashpos);
    ?>


    <!-- ////    PRINTS FILE NAME    //// -->
    <h1><?php echo htmlentities($filename); ?></h1>


    <!-- ////    FORMS FOR EXIT, DELETE, AND DOWNLOAD    //// -->
    <form action="index.php" method="POST" id="exitbuttonform">
        <input type="hidden" name="page" value="home">
    </form>

    <form action="index.php" method="POST" id="deletebuttonform">
        <input type="hidden" name="page" value="delete">
    </form>

    <form action="index.php" method="POST" id="downloadbuttonform">
        <input type="hidden" name="page" value="download">
    </form>

    <form action="index.php" method="POST" id="viewbuttonform">
        <input type="hidden" name="page" value="view">
    </form>


    <!-- ////    BUTTONS FOR EXIT, AND DELETE    //// -->
    <button name="exit" value="Exit" form="exitbuttonform" class="button3">Exit</button>
    <button name="delete" value="Delete" form="deletebuttonform" class="button2">Delete</button>
  
    
    <!-- ////    DISPLAY EXTRA VIEW BUTTON IF FILE IS A PDF    //// -->
    <?php
        if ($filetype == 'pdf') {
        echo '<button name="view" value="View" form="viewbuttonform" class="button1">View</button>';
        $page = 'view';
        $_SESSION['openme'] = $filename;
        $_SESSION['openmedir'] = $dir;
        }
    ?>

    
    <!-- ////    BUTTON FOR DOWNLOAD    //// -->
    <button name="downloadbutton" value="download" form="downloadbuttonform" class="button3">Download</button>
    <br>


    <!-- ////    DISPLAYS THE FILE    //// -->
    <?php
    if ($filecat == 'image') {                                          /// INLINE IF IMAGE USING VIEWER.PHP
        echo '<img src="viewer.php" alt=' . htmlentities($filename) . '>';
    } elseif ($filecat == 'text') {                                     /// INLINE IF TEXT USING PHP/HTML
        echo '<h4>';
        echo file_get_contents($dir);
        echo '</h4>';
    } elseif ($filetype != 'pdf') {                                     /// IN NEW PAGE USING HTTP HEADER IF PDF
        echo '<h2>';
        echo "Viewing filetype is unsupported. Please download the file to view.";
        echo '</h2>';
    }
    ?>
</body>

</html>
