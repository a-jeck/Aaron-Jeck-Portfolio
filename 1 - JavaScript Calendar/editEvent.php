<?php
session_start();
header("Content-Type: application/json");


// Retrieve inputs
$json_in = file_get_contents('php://input');
$json_obj_in = json_decode($json_in, true);

$title = (string) $json_obj_in['title'];
$time = (string) $json_obj_in['time'];
$color = (int) $json_obj_in['color'];

$id = (int) $json_obj_in['id'];
$csrf_in = (string) $json_obj_in['token'];
$user = $_SESSION['user_id'];


require 'database.php';


// If valid token was passed
if ($csrf_in = $_SESSION['token']) {

    // Prepare edit query
    $editEvent = $mysqli->prepare('update events set title = ?, time = ?, color = ? where event_id = ?;');
    if (!$editEvent) {
        $error = ("Query Prep Failed: " + $mysqli->error());
        echo json_encode(
            array(
                'status' => false,
                'error' => $error
            )
        );
        exit;
    } else {
        // Execute query
        $editEvent->bind_param('ssii', $title, $time, $color, $id);
        $editEvent->execute();
        $editEvent->close();

        // Alert JS of success
        echo json_encode(
            array(
                'status' => true,
                'success' => true
            )
        );
        exit;
    }

    // If improper token was passed, alert JS of failure
} else {
    echo json_encode(
        array(
            'status' => true,

            'success' => false
        )
    );
}