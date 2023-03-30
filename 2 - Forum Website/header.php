<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/portfolio/forum/stylesheet.css">
    <title>Document</title>
</head>

<body>
    <header>
        <h1> Forum Website </h1> <br>

        <nav>
            <?php

            /// RANDOM STORY SELECTOR
            echo '<form name="randomstory" method="POST">';
            echo '<button name="random_button" value="random" class="button2">Random</button>';
            echo '</form>';

            /// IF USER CLICKS RANDOM, query SQL for random post_id and redirect
            if (isset($_POST['random_button'])) {
                require 'database.php';
                $get_random = $mysqli->prepare("select post_id from stories order by rand() limit 1;");

                if (!$get_random) {
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                /// Execute and bind to $random_id
                $get_random->execute();
                $get_random->bind_result($random_id);
                $get_random->fetch();
                $get_random->close();
		echo 'here';
                /// redirect to random post
               // header('Location: http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php?page_id=story&story_id=' . $random_id);
	    header('Location: /portfolio/forum/story/' . $random_id);
	    }



            //// LOGOUT/LOGIN
            /// If user is logged in, present a logout button
            if (isset($_SESSION['user_id'])) {
                echo '<form name="logout" method="POST" >';
                echo '<button name="logout" value="logout" class="button2">Logout</button>';
                echo '</form>';

                /// When logout is clicked, destroy the session and redirect to the home page. 
                if (isset($_POST['logout'])) {
                    session_destroy();
		    //header('Location: http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php');
		      header('Location: /portfolio/forum');
		}
            } else {

                /// If the user is not logged in, present a login button
                echo '<form name="login" method="POST" >';
                echo '<button name="login" value="login" class="button2">Login</button>';
                echo '</form>';

                /// When login is clicked, redirect user to the login page 
                if (isset($_POST['login'])) {
                //    header('Location: http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php?page_id=login');
			header('Location: /portfolio/forum/login');
		}
            }

            ?>

            <!-- HOME  -->
            <form name="returnhome" method="POST">
                <button name="returnhome" value="Home" class="button2">Home</button>
            </form>

            <?php
            /// If user clicks home, redirect to homepage
            if (isset($_POST['returnhome'])) {
                //header('Location: http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php');
	    	header('Location: /portfolio/forum');
	    }
            ?>


            <!-- New Post  -->
            <form name="newstory" method="POST">
                <button name="newstory" value="New Story" class="button2">New Story</button>
            </form>
            <?php

            /// If user clicks newstory, redirect to newstory.php
            if (isset($_POST['newstory'])) {
                //header('Location: http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php?page_id=newstory');
	    	header('Location: /portfolio/forum/newstory');
	    }


            /// If user is logged in, present a profile button
            if (isset($_SESSION['user_id'])) {
                echo '<form name="profile" method="POST" >';
                echo '<button name="profileviewer" value="profile" class="button2">Profile</button>';
                echo '</form>';

                /// When profile is clicked, redirect to the profile page. 
                if (isset($_POST['profileviewer'])) {
                    //header('Location: http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php?page_id=profile&user_id=' . $_SESSION['user_id']);
			header('Location: /portfolio/forum/profile/' . $_SESSION['user_id']);
		}
            }
            ?>
        </nav>



        -----------------------------------------------------------------

    </header>
