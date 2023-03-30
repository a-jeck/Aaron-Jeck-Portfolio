<?php session_start();

if (isset($_SESSION['user_id'])) {
    echo '<h5>New Comment:</h5>';
    echo '<form name="newcommentform" method="POST"  class="new_comment">';
    echo '<textarea id="body_input" class="text" cols="30" rows="5" name="body"></textarea> <br>';
    echo ' <input type="submit" name="post" value="Post!">';
    echo '</form>';
}


require 'database.php';

/// Once the user clicks "post"
if (isset($_POST['post'])) {

    /// Retrieve the submitted text, the user's id, and the current post's id
    $body = (string) $_POST['body'];
    $author_id = $_SESSION['user_id'];
    $post_id = $storyid;

    // Retrieve the author's name
    $_SESSION['id_lookup'] = $author_id;
    include('id_lookup.php');
    $first_name_ins = $_SESSION['lookup_name_first'];
    $last_name_ins = $_SESSION['lookup_name_last'];

    /// Prepare and verify sql
    $addcommment = $mysqli->prepare('insert into comments (post_id, author_id, body, author_first_name, author_last_name) values (?, ?, ?, ?, ?)');
    if (!$addcommment) {
        printf("Query Prep Failedd: %s\n", $mysqli->error);
        exit;
    }

    /// Bind id, title, and body, execute, and close. 
    $addcommment->bind_param('sssss', $post_id, $author_id, $body, $first_name_ins, $last_name_ins);
    $addcommment->execute();
    $addcommment->close();
} ?>



<!-- PHP TO DISPLAY A USERS OWN COMMENTS IF THEY ARE LOGGED IN -->
<?php

/// If user is logged in
if (isset($_SESSION['user_id'])) {
    echo '<div class="comments">';
    /// Display their comments
    echo '<h5> My Comments: </h5>';

    /// This file includes all the php to process comment edits and deletes 
    include('comment_delete_and_edit.php');

    /// Prepare SQL to retrieve the user's comment
    $mycomments = $mysqli->prepare("select comment_id, date, body, author_first_name, author_last_name from comments where post_id=? and author_id =? order by date desc;");
    if (!$mycomments) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    /// Bind, execute SQL and store the results
    $mycomments->bind_param("ii", $storyid, $_SESSION['user_id']);
    $mycomments->execute();
    $mycurrentcomment = $mycomments->get_result();


    /// Keeps track of if user has any comments and, if so, how many
    $emptysetpersonal = true;
    $counter = 0;

    /// Start table
    echo "<table>";

    /// Iterate through user's comments
    while ($row = $mycurrentcomment->fetch_assoc()) {

        /// New row for each comment            
        echo '<tr>';

        /// The user has at least 1 comment
        $emptysetpersonal = false;

        /// Forms for editing or deleting each commment
        // echo 
        // echo 


        /// print author name and date
        $first_name_comment = $row['author_first_name'];
        $last_name_comment = $row['author_last_name'];
        $date_comment = date("l, F j, Y", strtotime($row['date']));

        printf(
            "<td> <h4> %s %s %s %s %s </h4> <br>",
            //'<a href=" http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php?page_id=profile&user_id=' . $_SESSION['user_id'] . '">',
	    '<a href="/portfolio/forum/profile/' . $_SESSION['user_id'] . '">',
	    htmlentities($first_name_comment) . " " . htmlentities($last_name_comment),
            '</a>',
            " on ",
            htmlentities($date_comment)
        );



        //print comment + buttons
        printf(
            "%s %s %s <br> %s %s %s %s %s %s",
            '<form name="comment_delete" method="POST"  id="comments_delete_form' . htmlentities($counter) . '"></form>',
            '<form name="comment_edit" method="POST"  id="comments_edit_form' . htmlentities($counter) . '"></form>',
            htmlentities($row["body"]),
            '<input type="hidden" name="commentid" value=' . $row["comment_id"] . ' form="comments_delete_form' . $counter . '">',
            '<input type="hidden" name="token" value="' .  $_SESSION['token'] . '" form="comments_delete_form' . $counter . '">',
            '<input type="submit" name="deletecomment" value="Delete Comment" form="comments_delete_form' . $counter . '">',
            '<input type="hidden" name="edit_comment_id" value=' . $row["comment_id"] . ' form="comments_edit_form' . $counter . '">',
            '<input type="hidden" name="token" value="' .  $_SESSION['token'] . '" form="comments_edit_form' . $counter . '">',
            '<input type="submit" name="edit_comment" value="Edit Comment" form="comments_edit_form' . $counter . '">'
        );

        /// Indiciate that one more comment is present
        $counter += 1;

        echo "</tr>";
    }

    echo "</table>";

    /// If no comments were returned, alert user.
    if ($emptysetpersonal) {
        echo 'You have no comments on this post!';
    }

    /// Close list,div, and SQL.
    // echo "</ul>\n";
    echo '</div>';
    $mycomments->close();

    /// Prepare SQL statement for all comments that do NOT belong to the current user (their comments have already been dispalyed above)
    $comments = $mysqli->prepare("select author_id, date, body, author_first_name, author_last_name from comments where post_id=? and author_id !=? order by date desc;");


    /// Verify SQL statement and bind
    if (!$comments) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $comments->bind_param('ii', $storyid, $_SESSION['user_id']);
} else {

    /// If the user is not logged in, there are no personal comments to show; prepare SQL statement for all comments
    $comments = $mysqli->prepare("select author_id, date, body, author_first_name, author_last_name from comments where post_id=? order by date desc;");


    /// Verify SQL statement and bind
    if (!$comments) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $comments->bind_param('i', $storyid);
}



/// Execute and store results
$comments->execute();
$currentcomment = $comments->get_result();

/// Keeps track of if there are any comments
$emptyset = true;

/// Displays that comments are about to be shown and starts list
echo '<h5> Comments: </h5>';
echo '<div class="comments">';
echo "<table>";

/// Iterates through all comments
while ($row = $currentcomment->fetch_assoc()) {
    echo "<tr>";

    /// At least one comment has been found
    $emptyset = false;

    /// print author name and date
    $first_name_comment = $row['author_first_name'];
    $last_name_comment = $row['author_last_name'];
    $date_comment = date("l, F j, Y", strtotime($row['date']));

    printf(
        "<td> <h4> %s %s %s %s %s </h4> <br>",
        //'<a href=" http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php?page_id=profile&user_id=' . $row['author_id'] . '">',
	'<a href="/portfolio/forum/profile' . $row['author_id'] . '">',
	htmlentities($first_name_comment) . " " . htmlentities($last_name_comment),
        '</a>',
        " on ",
        htmlentities($date_comment)
    );

    //print comment body
    printf(
        "%s <br>",
        htmlentities($row["body"])
    );
    echo "</tr>";
}

/// Close list and SQL 
echo "</table>";
echo '</div>';
$comments->close();

/// If no comments were found, alert user
if ($emptyset) {
    echo 'No comments on this post!';
}
?>
