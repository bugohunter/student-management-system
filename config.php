<?php
$conn = mysqli_connect("localhost", "root", "", "sms_project");
if (!$conn) die("DB Error: " . mysqli_connect_error());
session_start();
?>