<?php
session_start();
include('server.php');
header("Content-Type: text/html; charset=utf-8");
mysqli_query($db, "SET NAMES 'utf8'"); 

?>