<?php 
session_start(); 
session_destroy(); 
setcookie('status', 'true', time() + (-3600),'/'); 
setcookie('role', $_COOKIE['role'], time() + (-3600),'/'); 
header('location:/sepatu/'); 
?>