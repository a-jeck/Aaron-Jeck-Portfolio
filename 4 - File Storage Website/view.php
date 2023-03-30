<?php
session_start();
header("Content-Type: application/pdf");
ob_clean();
flush();
readfile($_SESSION['openmedir']);
////    DISPLAYS PDFS IN HTTP HEADER    ////
?>