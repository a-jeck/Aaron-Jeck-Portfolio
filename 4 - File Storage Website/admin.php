<?php session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/portfolio/filestorage/stylesheet.css">
    <title>Admin Panel</title>
</head>

<body>
    <?php


    /////////////////  ADMIN PANEL  /////////////////
    echo '<h1> Admin Panel </h1>';
    $counter = 0;
    
    /////////////////  DISPLAYS EACH USER WITH DELETE AND OPEN BUTTONS  /////////////////
    $lines = file("/srv/uploads/users.txt", FILE_IGNORE_NEW_LINES);
    foreach ($lines as $name) {
        echo '<div class="admindelete">';
        
        /// Open User
        echo '<form method="POST" action="index.php">';
        echo "User: " . htmlentities($name);          
        echo ' <input type="hidden" name="page" value="changeuser">';
        echo ' <input type="hidden" name="launch_user" value="' . htmlentities($lines[($counter)]) . '">';
        echo '<input type="submit" name="openuser" value="Open">';
        echo '</form>';
        
        /// Delete User
        echo '<form method="POST" action="index.php" id="deleteuser' . htmlentities($counter) . '">';
        echo ' <input type="hidden" name="page" value="userdeleteconfirm">';
        echo ' <input type="hidden" name="deleteuserID" value="' . htmlentities($counter) . '">';
        echo '</form>';
        echo '<button name="deleteme" value="delete" form="deleteuser' . htmlentities($counter) . '" class="button2">Delete User</button>';
        echo '</div>';

        $counter += 1;
    }

    
    /////////////////  LOGOUT OF THE ADMIN PANEL  /////////////////
    echo '<form method="POST" id="logoutbutton" action="index.php">';
    echo ' <input type="hidden" name="page" value="logout">';
    echo ' <!-- <input type="submit" name="logout" value="Logout"> -->';
    echo '</form>';
    echo '<button name="logout" value="Logout" form="logoutbutton" class="button2">Logout</button>';
    ?>
</body>

</html>
