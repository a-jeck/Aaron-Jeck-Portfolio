<?php
session_start();
header("Content-Type: " . $_SESSION['currentmime']);
ob_clean();
flush();
readfile($_SESSION['currentdir']);
////    DISPLAYS INLINE HTML PHOTOS    ////
?>