<?php session_start();
require 'database.php';

////// HANDLES EDIT WINDOW //////
if (isset($_POST['edit_post'])) {




    /// Prepare SQL query for body of the story-to-be-edited. 
    $get_story_sql = $mysqli->prepare("select body from stories where post_id =" . $storyid . "");
    if (!$get_story_sql) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }



    /// CSRF CHECK
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Unauthorized User Detected");
    }

    /// Execute, store results of, and close SQL query
    $get_story_sql->execute();
    $get_story_sql->bind_result($editbody);
    $get_story_sql->fetch();
    $get_story_sql->close();

    //// Create box to allow the user to edit their story. Displays the pre-edited text in the text box. 
    echo 'Edit your story: <br>';
    echo '<form name="edit_window" method="POST" >';
    echo '<textarea id="edited_story" class="text" cols="86" rows ="20" name="edited_story_upload">' . htmlentities($editbody) . '</textarea>';
    echo '<input type="hidden" name="token" value="' .  $_SESSION['token'] . '">';
    echo '<input type="submit" name="apply_edit" value="Apply Edit">';
    echo  '</form>';
}


////// APPLIES EDITS ONCE EDIT WINDOW IS COMPLETE //////
if (isset($_POST['apply_edit'])) {

    /// Retrieve the updated text of the edited story.
    $new_story = (string) ($_POST['edited_story_upload']);

    /// Prepare SQL statement to update the commment's text in the database.
    $edit_story_sql = $mysqli->prepare('update stories set body=? where post_id=?;');
    $edit_story_sql->bind_param("si", $new_story, $storyid);
    if (!$edit_story_sql) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    /// CSRF CHECK
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Unauthorized User Detected");
    }

    /// Execute and close the statement.
    $edit_story_sql->execute();
    $edit_story_sql->close();

    /// Redirect the user to the same page, reloading it and displaying the edited story.

	header('Location: /portfolio/forum/story/' . $storyid . '">');
}


////// HANDLES DELETIONS //////
if (isset($_POST['delete_post'])) {
    // Prepare the SQL statement to delete the story from the database. 
    $delete_story_sql = $mysqli->prepare("delete from stories where post_id=" . $storyid . ";");
    if (!$delete_story_sql) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    /// CSRF CHECK
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Unauthorized User Detected");
    }

    /// Execute and close the statement.
    $delete_story_sql->execute();
    $delete_story_sql->close();

    /// Redirect the user to their homepage, now without the now deleted post. 
	header('Location: /portfolio/forum');
}
