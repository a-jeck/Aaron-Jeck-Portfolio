<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/portfolio/filestorage/stylesheet.css">
    <title>Login</title>
</head>

<body>


    <!-- ////    VERIFIES VALID USER    //// -->
    <div id="login">
        <?php if (($_SESSION['wrongname'])) {
            echo "Invalid username. Please try again or sign up below. ";
            $_SESSION['wrongname'] = false;
        } else {
            echo "Welcome. ";
        }
        ?>

        <!-- ////    VERIFIES NO FAILED SIGNUP USER    //// -->
        <?php if (($_SESSION['failedsignup'])) {
            echo "Signup failed - invalid characters in username. Please try again or sign in below. ";
            $_SESSION['failedsignup'] = false;
        }
        ?>


        <!-- ////    VERIFIES NO DUPLICATE SIGNUP USER    //// -->
        <?php if (($_SESSION['duplicatename'])) {
            echo "Signup failed - duplicate username. Please try again or sign in below. ";
            $_SESSION['duplicatename'] = false;
        }
        ?>

        <!-- ////    FORM FOR LOGIN    //// -->
        Please login here:
        <form name="loginform" method="POST" action="index.php">
            <input type="hidden" name="page" value="home">
            <input type="text" name="username" id="username_input">
            <input type="submit" name="login" value="Login">
        </form>
    </div>

    
    or:
    <!-- ////    FORM FOR SIGNUP BUTTON    //// -->
    <form name="signupbutton" method="POST" action="index.php">
        <input type="hidden" name="page" value="signup">
        <input type="submit" name="signupstart" value="Signup">
    </form>


</body>

</html>
