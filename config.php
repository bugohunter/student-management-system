<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "sms_project";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("DB Error: " . mysqli_connect_error());
}

session_start();
?>