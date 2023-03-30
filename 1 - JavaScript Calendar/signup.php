<?php

/// Set content type
header("Content-Type: application/json");
require 'database.php';

/// Retrieve input and decode it
$json_in = file_get_contents('php://input');
$json_obj_in = json_decode($json_in, true);

/// Retrieve each line for signup
$email = (string) $json_obj_in['email'];
$pwd = (string) $json_obj_in['pwd'];
$pwd_hash = password_hash((string) $pwd, PASSWORD_DEFAULT);
$first_name = (string) $json_obj_in['first_name'];
$last_name = (string) $json_obj_in['last_name'];


//Query the database to check if email is already in use
$check_email = $mysqli->prepare("select user_id from users where email=?;");
if (!$check_email) {
    $error = ("Query Prep Failed: " + $mysqli->error());
    echo json_encode(
        array(
            'status' => false,
            'error' => $error
        )
    );
    exit;
} else {
    $check_email->bind_param('s', $email);
    $check_email->execute();
    $check_email->bind_result($user_id);
    $check_email->fetch();



    if (!is_null($user_id)) {
        /// This email is  in use. Tell JS what happened
        echo json_encode(
            array(
                'status' => true,
                'exists' => true,
                'uploaded' => false
            )
        );
    } else {

        // This email is not in use. Insert the user into the users table.
        $add_user = $mysqli->prepare("insert into users (email, first_name, last_name, hashed_pwd) values (?, ?, ?, ?);");
        if (!$add_user) {
            $error = ("Query Prep Failed: " + $mysqli->error());
            echo json_encode(
                array(
                    'status' => false,
                    'error' => $error
                )
            );
            exit;
        }

        $add_user->bind_param('ssss', $email, $first_name, $last_name, $pwd_hash);
        $add_user->execute();
        $add_user->close();

        /// Tell JS what happened
        echo json_encode(
            array(
                'status' => true,
                'exists' => false,
                'uploaded' => true
            )
        );


    }
}