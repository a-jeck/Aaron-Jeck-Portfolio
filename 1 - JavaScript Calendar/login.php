<?php
ini_set("session.cookie_httponly", 1);
session_start();

// Set content type
header("Content-Type: application/json");
require 'database.php';

/// Retrieve input and decode it
$json_in = file_get_contents('php://input');
$json_obj_in = json_decode($json_in, true);

/// Retrieve each line for signup
$email = (string) $json_obj_in['email'];
$pwd_attempt = (string) $json_obj_in['pwd'];


// Password and email entered; prepare query
$saved_info = $mysqli->prepare('select COUNT(*), user_id, hashed_pwd FROM users WHERE email=?;');
if (!$saved_info) {
    $error = ("Query Prep Failed: " + $mysqli->error());
    echo json_encode(
        array(
            'status' => false,
            'error' => $error
        )
    );
    exit;
} else {

    // Bind the inputted email
    $saved_info->bind_param('s', $email);
    $saved_info->execute();

    // Bind the query results
    $saved_info->bind_result($cnt, $user_id, $pwd_hash);
    $saved_info->fetch();

    // Compare the submitted password to the actual password hash
    if ($cnt == 1 && password_verify($pwd_attempt, $pwd_hash)) {
        // Login succeeded

        /// Log user in
        $_SESSION['user_id'] = $user_id;
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

        /// Tell JS what happened
        echo json_encode(
            array(
                'status' => true,
                'success' => true,
                'token' => $_SESSION['token']
            )
        );
    } else {
        // Login failed; redirect back to the login screen
        echo json_encode(
            array(
                'status' => true,
                'success' => false
            )
        );
    }
}