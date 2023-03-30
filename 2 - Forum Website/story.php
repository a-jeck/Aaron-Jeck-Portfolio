<?php session_start();
$storyid = (int) $_GET['story_id'];
require 'database.php';
$getstory = $mysqli->prepare("select author_id, date, title, body from stories where post_id = '" . htmlentities($storyid) . "'");
if (!$getstory) {
    printf("Query Prep Faileed: %s\n", $mysqli->error);
    exit;
} else {
    /// If select command is valid, execute it and bind its results 
    $getstory->execute();
    $getstory->bind_result($author, $timestamp, $title, $body);
    $getstory->fetch();
}

?>

    <div class="content">
        <?php
        $_SESSION['id_lookup'] = $author;
        include('id_lookup.php');


        echo '<h3>' . htmlentities($title) . '</h3> <br>';

        $date = date("l, F j, Y", strtotime($timestamp));

        printf(
            '%s %s %s %s %s %s %s %s%s',
            '<h4> Posted on: ',
            htmlentities($date),
            ' by ',
            //'<a href=" http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php?page_id=profile&user_id=' . $author . '">',
	    '<a href="/portfolio/forum/profile/' . $author . '">',
	    htmlentities($_SESSION['lookup_name_first']) . " " . htmlentities($_SESSION['lookup_name_last']),
            '</a>',
            '</h4><br>',
            $body,
            '<br>'
        );


        if ($_SESSION['user_id'] == $author) {
            include('story_delete_and_edit.php');
            echo '<div>';
            echo '<form name="delete" method="POST" >';
            echo '<input type="hidden" name="token" value="' .  $_SESSION['token'] . '">';
            echo '<input type="submit" name="delete_post" value="Delete Post">';
            echo '</form>';

            echo '<form name="edit" method="POST" >';
            echo '<input type="hidden" name="token" value="' .  $_SESSION['token'] . '">';
            echo '<input type="submit" name="edit_post" value="Edit Post" >';
            echo '</form>';
            echo '</div>';
        }
        echo '<br>============================ <br>';
        include('comments.php');

        ?>
    </div>
