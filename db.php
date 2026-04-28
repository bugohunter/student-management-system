<?php
$host = "sql100.infinityfree.com";
$user = "if0_41739406";
$password = "sms700763";
$database = "if0_41739406_sms_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>