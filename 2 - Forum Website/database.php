<?php

    /// Signs into the newsadmin user.(Not filled in this public copy)
    $mysqli = new mysqli('<location>', '<username>', '<pwd>', '<database_name>');

    /// Handles any signin errors. 
    if ($mysqli->connect_errno) {
        printf("Connection Failed: %s\n", $mysqli->connect_error);
        exit;
    }
