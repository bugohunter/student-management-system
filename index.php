<?php include 'config.php'; if(!isset($_SESSION['admin'])) header('Location: login.php'); ?>
<!DOCTYPE html><html><head><title>Add Student</title><link rel="stylesheet" href="style.css"></head><body>
<div class="app-shell"><?php include 'sidebar.inc.php'; ?><main class="content"><div class="page-card">
<h1>Add Student</h1><form action="save_student.php" method="POST">
<input name="name" placeholder="Student Name" required>
<input type="email" name="email" placeholder="Email" required>
<select name="course"><option>MCA</option><option>BCA</option></select>
<button>Save Student</button></form></div></main></div></body></html>