<?php

session_start();

header("Content-Type: application/json");

echo json_encode(array('status' => true));

session_destroy();
