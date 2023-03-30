<?php
session_start();
header("Content-Type: application/json");


// Retrieve entries from JavaScript
$json_in = file_get_contents('php://input');
$json_obj_in = json_decode($json_in, true);
$id = $json_obj_in['id'];
$user = $_SESSION['user_id'];
$csrf_in = $json_obj_in['token'];



require 'database.php';


// If the appropriate CSRF token was passed
if ($csrf_in = $_SESSION['token']) {

    // Prepare delete query
    $deleteEvent = $mysqli->prepare('delete from events where event_id = ?;');
    if (!$deleteEvent) {
        $error = ("Query Prep Failed: " + $mysqli->error());
        echo json_encode(
            array(
                'status' => false,
                'error' => $error
            )
        );
        exit;
    }

    // Execute delete
    $deleteEvent->bind_param('i', $id);
    $deleteEvent->execute();
    $deleteEvent->close();

    // Tell JS of successs
    echo json_encode(
        array(
            'status' => true,
            'success' => true
        )
    );
    exit;

    // Tell JS of failure if tokens do not match
} else {
    echo json_encode(
        array(
            'status' => true,
            'success' => false
        )
    );
}