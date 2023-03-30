<?php

/// Given $_SESSION['id_lookup'], set variable $_SESSION['lookup_name_first'] and $_SESSION['lookup_name_last'] to corresponding name
require 'database.php';
$get_name = $mysqli->prepare("select first_name, last_name from users where user_id=?;");
if (!$get_name) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
} else {

    /// If select command is valid, execute it and bind its results 
    $get_name->bind_param('i', $_SESSION['id_lookup']);
    $get_name->execute();
    $get_name->bind_result($_SESSION['lookup_name_first'], $_SESSION['lookup_name_last']);
    $get_name->fetch();
    $get_name->close();
}
