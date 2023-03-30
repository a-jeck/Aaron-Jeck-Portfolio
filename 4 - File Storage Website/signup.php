<?php session_start(); ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="/portfolio/filestorage/stylesheet.css">
    <title>Signup</title>
</head>

<body>
    Enter your new username below:


    <!-- ////    FORM & BUTTONS FOR SIGNING UP AND EXITING    //// -->
    <form name="signupform" method="POST" action="index.php" id="signupform">
        <input type="hidden" name="page" value="signupconfirm">
        <input type="text" name="newusername" id="newusername">
    </form>
    <button type="submit" name="signup" value="Signup" form="signupform" class="button1">Signup</button>
    <button type="submit" name="signupexit" value="Exit" form="signupform" class="button2">Exit</button>
</body>

</html>
