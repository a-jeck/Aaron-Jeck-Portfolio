<?php session_start();


// This file handles what main (body) content to show between the header and footer.



/// First, we check if this is a new session. A new session will have an empty 'page' sesion variable. 
/// If we are in a new session (the user has just logged out or arrived to the website), we take them home. 
if (isset($_GET['page_id'])) {
    $page = (string) $_GET['page_id'];
} else {
    $page = 'home';
}



/// The switchboard for the body, locating what needs to be displayed based on $page.
switch ($page) {

        /// 'home' case 
    case 'home':
        include('home.php');
        break;

        /// 'story' case
    case 'story':
        include('story.php');
        break;

    case 'login':
        include('login.php');
        break;

    case 'newstory':
        include('newstory.php');
        break;

    case 'profile':
        include('profile.php');
        break;
}
