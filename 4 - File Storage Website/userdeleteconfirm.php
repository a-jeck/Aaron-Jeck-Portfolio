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
    <h1>
        Are you sure that you want to delete this user and all of their files? This action is irreversible.
    </h1>
    <!--////    FORM FOR DELETE CONFIRMATION BUTTON    ////-->
    <form method="POST" action="index.php" id="confirmdeletion">
        <input type="hidden" name="page" value="userdelete">
    </form>

    <!--////    YES/NO BUTTONS FOR DELETE CONFIRMATION    ////-->
    <button name="yesdelete" value="Yes" form="confirmdeletion" class="button2">Yes</button>
    <button name="no" value="No" form="confirmdeletion" class="button1">No</button>
</body>

</html>
