<?php session_start();
require 'database.php';

/// Retrieve user ID from URL
$user_id = (int) $_GET['user_id'];

/// Retrieve user's name
$_SESSION['id_lookup'] = $user_id;
include('id_lookup.php');
$user_first_name = $_SESSION['lookup_name_first'];
$user_last_name = $_SESSION['lookup_name_last'];
require 'database.php';

echo '<div class="enter">';
/// If the user owns this page, see if they want a new bio
if ($_SESSION['user_id'] == $user_id) {
  
    echo '<form name="edit_bio" method="POST" id="edit_form">';
    echo '<button name="editor_window" value="bio" class="button2">New Bio</button>';
    echo '</form>';
   
}


// If they click new bio, create  box to allow the user to edit their bio
if (isset($_POST['editor_window'])) {
    echo 'Enter your new bio:';
    echo '<form name="bio_edit_window" method="POST"  class="centered">';
    echo '<textarea id="edited_bio" class="text" cols="30" rows ="5" name="edited_bio"></textarea>';
    echo '<input type="hidden" name="token" value="' .  $_SESSION['token'] . '">';
    echo '<input type="submit" name="apply_edit_bio" value="Apply New Bio">';
    echo  '</form>';
}
echo '</div>';

/// USER HAS FINISHED WRITING THEIR NEW BIO
if (isset($_POST['apply_edit_bio'])) {
    $edit_bio_sql = $mysqli->prepare('update users set bio=? where user_id=?;');
    if (!$edit_bio_sql) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    /// CSRF CHECK
    if (!hash_equals($_SESSION['token'], $_POST['token'])) {
        die("Unauthorized User Detected");
    }

    /// Bind, execute and close the statement.
    $newbio = (string) $_POST['edited_bio'];
    $edit_bio_sql->bind_param("si", $newbio, $user_id);
    $edit_bio_sql->execute();
    $edit_bio_sql->close();

    /// Take the user back to their page
    
    header('Location: /portfolio/forum/profile/' . $user_id);
}


/// Retrieve the User's bio
$get_bio = $mysqli->prepare("select bio from users where user_id=?;");
if (!$get_bio) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

    /// Execute and bind to $bio
    $get_bio->bind_param('i', $user_id);
    $get_bio->execute();
    $get_bio->bind_result($bio);
    $get_bio->fetch();
    $get_bio->close();



/// DISPLAY EVERYTHING ////

echo '<div class="content">';
echo '<h2> Bio: <br>' . $bio . '</h2>';

echo '<h2>' . htmlentities($user_first_name) . '\'s Stories </h2>';



/// Prepare SQL query for post information
$stories = $mysqli->prepare("select post_id, date, title, link from stories where author_id =? order by date desc;");
if (!$stories) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

/// Execute and retrieve data from SQL query
$stories->bind_param('i', $user_id);
$stories->execute();
$currentstory = $stories->get_result();

/// Count how many stories there are and set up table for results
$counter = 0;
echo '<table>';

/// Iterate through all stories
while ($row = $currentstory->fetch_assoc()) {

    /// New row for each story            
    echo '<tr>';

    /// Get at most first 100 characters from title - we don't want to display too many
    $full_title = $row['title'];
    $truncated_title = substr($full_title, 0, 100);


    /// Print the title of the story with a hyperlink to it
    printf(
        '%s %s %s %s ',
        '<td> <p class="title">',

         '<a href="/portfolio/forum/story/' . $row["post_id"] . '" class="title-link">',
htmlentities($truncated_title),
        '</a> </p>'
    );

    /// If the author included a link to a website, include it. 
    if (!empty($row['link'])) {

        /// Parse the URL to get domain.com & print
        $unedited_link = $row['link'];
        $edited_link = parse_url($unedited_link);
        $host =  $edited_link['host'];


        printf(
            '%s %s %s %s%s%s',
            '<p class="link">',
            "<a href=",
            htmlentities($unedited_link),
            'class="links">(',
            htmlentities($host),
            ')</a> </p>'
        );
    }

    /// Finish the presentation by printing the author's full name & the date.
    $date = date("l, F j, Y", strtotime($row['date']));
    printf(
        '%s %s %s %s%s',
        '<br> <p class="authordate"> posted by ',
        $user_first_name . " " . $user_last_name,
        'on',
        $date,
        '</p> </td>'
    );

    /// End the loop by adding one to the coutner and closing the tags
    $counter += 1;
    echo '</tr>';
}

/// Close the table and the SQL query
echo '</table>';
$stories->close();
?>
</div>
