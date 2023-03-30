<?php
session_start();

header("Content-Type: application/json");


// Retrieve inputs from JS
$json_in = file_get_contents('php://input');
$json_obj_in = json_decode($json_in, true);
$title = (string) $json_obj_in['title'];
$time = (string) $json_obj_in['time'];
$day = (int) $json_obj_in['day'] + 1;
$month = (int) $json_obj_in['month'];
$year = (int) $json_obj_in['year'];
$user = $_SESSION['user_id'];
$color = (int) $json_obj_in['color'];
$csrf_in = (string) $json_obj_in['token'];

// Make sure color was not left empty, it can be left empty based on the structure of JS 
if (empty($color)) {
    $color = 1;
}


require 'database.php';


// Make sure valid CSRF token was passed
if ($csrf_in = $_SESSION['token']) {

    // Prepare query
    $add_event = $mysqli->prepare('insert into events (title, time, day, month, year, user_id, color) VALUES (?, ?, ?, ?, ?, ?, ?);');
    if (!$add_event) {
        $error = ("Query Prep Failed: " + $mysqli->error());
        echo json_encode(
            array(
                'status' => false,
                'error' => $error
            )
        );
        exit;
    }

    // Bind and execute
    $add_event->bind_param('ssiiiii', $title, $time, $day, $month, $year, $user, $color);
    $add_event->execute();
    $add_event->close();

    // Alert JS of success
    echo json_encode(
        array(
            'status' => true
        )
    );
    exit;

    // ALert JS of failure
} else {
    $error = ("Invalid token.");
    echo json_encode(
        array(
            'status' => false,
            'error' => $error
        )
    );
    exit;
}