<?php
// Start session if not already started
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// Redirect to login if not authenticated
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true){
    header("Location: ../admin/index.php");
    exit;
}
?>