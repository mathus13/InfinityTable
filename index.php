<?php
/*
 * Check for session information. 
 * If present, display user info & notifications. 
 * If not, display login / registertration info
 * 
 */
session_start();
if(isset($_SESSION['login'])){
    $page = 'userStart.php';
}else{
    $page = 'login.php';
}
?>
