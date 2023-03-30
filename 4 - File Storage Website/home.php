<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/portfolio/filestorage/stylesheet.css">
    <title>Home</title>
</head>

<body>


    <!--////    ALERT FOR UPLOADFAILURE    ////-->
    <?php
    if ($_SESSION['uploadfail']) {
        echo 'Alert: Upload Failure (' . htmlentities($_SESSION["uploadfailurecode"]) . ')';
        $_SESSION['uploadfail'] = false;
    }
    ?>


    <!--////    DISPLAY USERNAME    ////-->
    <h1>
        <?php echo $_SESSION['username'] ?>'s Files
    </h1>


    <!--////    FORMS & BUTTONS FOR UPLOAD AND LOGOUT    ////-->
    <form method="POST" id="uploadbutton" action="index.php">
        <input type="hidden" name="page" value="upload">
    </form>


    <?php
    /// ONLY DISPLAY LOGOUT BUTTON IF USER IS NOT AN ADMIN, IF ADMIN DISPLAY RETURN TO ADMIN BUTTON ///
    if ($_SESSION['isadmin'] == false) {
        echo '<form method="POST" id="logoutbutton" action="index.php">';
        echo ' <input type="hidden" name="page" value="logout">';
        echo ' <!-- <input type="submit" name="logout" value="Logout"> -->';
        echo '</form>';
    } else {
        echo '<form method="POST" id="returntoadmin" action="index.php">';
        echo ' <input type="hidden" name="page" value="admin">';
        echo ' <!-- <input type="submit" name="logout" value="Logout"> -->';
        echo '</form>';
    }
    ?>

     <!--////    UPLOAD BUTTON    ////-->
    <button name="upload" value="Upload" form="uploadbutton" class="button1">Upload</button>


    <?php
    /// ONLY DISPLAY LOGOUT BUTTON IF USER IS NOT AN ADMIN, IF ADMIN DISPLAY RETURN TO ADMIN BUTTON ///
    if ($_SESSION['isadmin'] == false) {
        echo '<button name="logout" value="Logout" form="logoutbutton" class="button2">Logout</button>';
    } else {
        echo '<button name="returnadmin" value="Return" form="returntoadmin" class="button2">Return</button>';
    }
    ?>


    <!-- ////    VARIABLES AND LOOP FOR DISPLAYING FILES    //// -->
    <?php
    /// Reads files in the user's folder
    $dir = "/srv/uploads/" . $_SESSION['username'];
    $a = scandir($dir);
    $_SESSION['files'] = $a;
    $counter = -2;

    /// Prints each file
    foreach ($a as $filename) {
        $counter += 1;
        if ($counter > 0) {
            echo '<form method="POST" action="index.php">';                                                 /// Creates form
            echo '<h2>';                                                                                    /// Formats each file box
            echo "File #" . htmlentities($counter) . ": " . htmlentities($filename) . '    ';               /// Prints filename
            echo '<input type="hidden" name="openfile" value="open' . htmlentities($counter) . '">';        /// Assigns value to each open button
            echo ' <input type="hidden" name="page" value="openpage">';                                     /// Assigns next page for index.php
            echo '<input type="submit" name="open_file' . htmlentities($counter) . '" value="Open">';       /// Button for open
            echo '</h2> </form>';
        }
    }


    ////    NOTIFICATION FOR NO FILES    ////
    if ($counter < 1) {
        echo '<h2> You have no files! <br> Click upload to get started! </h2>';
    }
    ?>

</body>

</html>
