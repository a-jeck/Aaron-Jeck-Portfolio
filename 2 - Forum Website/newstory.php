<?php session_start(); ?>

    <?php
    $valid_url = true;
    if (isset($_SESSION['user_id'])) {
        echo '<div class="content"> <div class="enter">';


        echo '<form name="newstoryform" method="POST" >';
        echo 'Title:<input type="text" name="title" id="title_input" autofocus> <br>';
        echo 'Link (Optional):<input type="text" name="link" id="link_input"> <br>';
        echo 'Body:  <br><textarea id="body_input" class="text" cols="86" rows ="20" name="body"></textarea> <br>';
        echo  '<input type="submit" name="post" value="Post!"> <br><br>';
        echo '</form>';


        /// Once the user clicks post
        if (isset($_POST['post'])) {

            /// Has the user entered a title and body?
            if (isset($_POST['title']) && $_POST['body']) {

                /// Has the user tried to enter a link? 
                if (!empty($_POST['link'])) {
                    $check_link = (string) $_POST['link'];

                    /// Is it a valid URL? If not, leave the conditional
                    if (!filter_var($check_link, FILTER_VALIDATE_URL)) {
                        $valid_url = false;
                    }
                }

                if ($valid_url) {
                    require 'database.php';

                    /// Retrieve entered title and body
                    $title = (string) $_POST['title'];
                    $link = (string) $_POST['link'];
                    $body = (string) $_POST['body'];
                    $newauthor = $_SESSION['user_id'];

                    /// Prepare insertion
                    $adduser = $mysqli->prepare('insert into stories (author_id, title, link, body) values (?, ?, ?, ?)');
                    if (!$adduser) {
                        printf("Query Prep Failedd: %s\n", $mysqli->error);
                        exit;
                    }

                    /// Bind id, title, and body, execute, and close. 
                    $adduser->bind_param('ssss', $newauthor, $title, $link, $body);
                    $adduser->execute();
                    $adduser->close();

                    /// Get story ID
                    $getid = $mysqli->prepare("select last_insert_id()");
                    if (!$getid) {
                        printf("Query Prep Failed: %s\n", $mysqli->error);
                        exit;
                    } else {

                        /// If select command is valid, execute it and bind its results 
                        $getid->execute();
                        $getid->bind_result($newid);
                        $getid->fetch();
                    }

                    /// Take user to their new story
                    //header('Location: http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php?page_id=story&story_id=' . $newid);
		    header('Location: /portfolio/forum/story/' . $newid);
		} else {
                    /// The provided URL is invaldi! Alert the user. 
                    echo 'Please enter a valid URL. (http(s)://example.com/net/org/etc) <br>';
                    $valid_url = true;
                }
            } else {

                /// A required field has been left blank! Alert the user. 
                echo 'Please enter a title and body. <br>';
            }
        }
        echo '</div></div>';
    } else {
        //$url = 'http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php?page_id=login';
        echo '<h2> You must <a href="/portfolio/forum/login">login</a> before making a new post! </h2>';
    }

    ?>

