<?php session_start();


////// HANDLES EDIT WINDOW //////
if (isset($_POST['edit_comment'])) {
    
    /// Retrieve comment id
    $edit_comment_id = (int) $_POST['edit_comment_id'];

    /// Prepare SQL query for body of the comment-to-be-edited. 
    $get_comment_sql = $mysqli->prepare("select body from comments where comment_id =?");
    if (!$get_comment_sql) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    /// CSRF CHECK
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Unauthorized User Detected");
    }

    /// Bind values, execute, store results of, and close SQL query
    $get_comment_sql->bind_param("s", $edit_comment_id);
    $get_comment_sql->execute();
    $get_comment_sql->bind_result($editbody);
    $get_comment_sql->fetch();
    $get_comment_sql->close();

    //// Create box to allow the user to edit their comment. Displays the pre-edited text in the text box. 
    echo 'Edit your comment:';
    echo '<form name="edit_window" method="POST" >';
    // echo '<input type="text" name="edited_comment" value="' . htmlentities($editbody) . '" >';
    echo '<textarea id="edited_comment" class="text" cols="30" rows ="5" name="edited_comment">' . htmlentities($editbody) . '</textarea>';
    echo '<input type="hidden" name="edited_comment_id" value="' . htmlentities($edit_comment_id) . '" >';
    echo '<input type="hidden" name="token" value="' .  $_SESSION['token'] . '">';
    echo '<input type="submit" name="apply_edit_comment" value="Apply Edit">';
    echo  '</form>';
}


////// APPLIES EDITS ONCE EDIT WINDOW IS COMPLETE //////
if (isset($_POST['apply_edit_comment'])) {
    
    /// Retrieve the updated text and id of the edited comment.
    $new_comment = (string) $_POST['edited_comment'];
    $edit_comment_id_apply = (string) $_POST['edited_comment_id'];

    /// Prepare SQL statement to update the commment's text in the database.
    $edit_comment_sql = $mysqli->prepare('update comments set body=? where comment_id=?;');
    if (!$edit_comment_sql) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    /// CSRF CHECK
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Unauthorized User Detected");
    }

    /// Bind, execute and close the statement.
    $edit_comment_sql->bind_param("si", $new_comment, $edit_comment_id_apply);
    $edit_comment_sql->execute();
    $edit_comment_sql->close();

    /// Redirect the user to the same page, reloading it and displaying the edited comment.
    //header('Location: http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php?page_id=story&story_id=' . $storyid);
    header('Location: /portfolio/forum/story/' . $storyid);
}


////// HANDLES DELETIONS //////
if (isset($_POST['deletecomment'])) {

    /// Retrieve the id of the comment-to-be-deleted.
    $delete_comment_id = (int) $_POST['commentid'];

    /// Prepare the SQL statement to delete the comment from the database. 
    $delete_comment_sql = $mysqli->prepare("delete from comments where comment_id=?;");
    if (!$delete_comment_sql) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    /// CSRF CHECK
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Unauthorized User Detected");
    }

    /// Bind, execute and close the statement.
    $delete_comment_sql->bind_param("s", $delete_comment_id);
    $delete_comment_sql->execute();
    $delete_comment_sql->close();

    /// Redirect the user to the same page, reloading it and no longer disdplaying the comment.
    //header('Location: http://ec2-18-219-245-55.us-east-2.compute.amazonaws.com/~aaron/module3/index.php?page_id=story&story_id=' . $storyid);
    header('Location: /portfolio/forum/story/' . $storyid);
}
