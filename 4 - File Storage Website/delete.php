<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/portfolio/filestorage/stylesheet.css">
    <title>Delete Confirmation</title>
</head>

<body>

Are you sure that you want to delete this file?
        <!--////    FORM FOR DELETE CONFIRMATION BUTTON    ////-->
        <form  method="POST" action="index.php">
            <input type="hidden" name="page" value="delete_handler">
            <input type="submit" name="yes" value="Yes">
            <input type="submit" name="no" value="No">
        </form>
</body>

</html>
