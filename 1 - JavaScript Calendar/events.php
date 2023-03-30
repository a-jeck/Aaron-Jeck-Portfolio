<?php
session_start();

/// Set content type
header("Content-Type: application/json");
require 'database.php';

/// Retrieve input and decode it

$json_in = file_get_contents('php://input');
$json_obj_in = json_decode($json_in, true);

/// Retrieve each line for signup
$user = '';
if (isset($_SESSION['user_id'])) {
    $user = $_SESSION['user_id'];
}
// $user = 1;
$month = (int) $json_obj_in['month'];
$year = (int) $json_obj_in['year'];


//Query the database for list of events
$get_events = $mysqli->prepare("select event_id, title, time, day, color from events where (user_id=? and month=? and year=?) order by day asc, time asc;");
if (!$get_events) {
    $error = ("Query Prep Failed: " + $mysqli->error());
    echo json_encode(
        array(
            'status' => false,
            'error' => $error
        )
    );
    exit;
}
$get_events->bind_param('iii', $user, $month, $year);
$get_events->execute();
$events = $get_events->get_result();


// Initialize an array for all of the events in that month
$allEvents = array();

// Fill the array with the day, title, time, ID, and color, CSV-style
while ($event = $events->fetch_assoc()) {
    $csvString = $event['day'] . ',' . $event['title'] . ',' . $event['time'] . ',' . $event['event_id'] . ',' . $event['color'];

    array_push($allEvents, $csvString);
}

// If 0 events were found, tell JS
if (sizeof($allEvents) < 1) {
    echo json_encode(
	    array(
            'status' => true,
            'empty' => true,
            'month' => htmlspecialchars($month),
            'year' => htmlspecialchars($year)
        )
    );

    // If at least 1 event was found, send all events to JS.
} else {
    //$output = array();
    $output = ['status' => true];
    foreach ($allEvents as $key => $value) {
        $output += [htmlspecialchars($key) => htmlspecialchars($value)];
    }

    echo json_encode($output);
}

