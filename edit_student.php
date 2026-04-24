<?php include 'config.php';
$id = $_GET['id'];
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM students WHERE id=$id"));
if(isset($_POST['name'])){
  $name=$_POST['name']; $email=$_POST['email']; $course=$_POST['course'];
  mysqli_query($conn, "UPDATE students SET name='$name', email='$email', course='$course' WHERE id=$id");
  header('Location: dashboard.php'); exit();
}
?>
<!DOCTYPE html><html><head><title>Edit Student</title><link rel="stylesheet" href="style.css"></head><body>
<div class="page-card" style="max-width:700px;margin:40px auto">
<h1>Edit Student</h1>
<form method="POST">
<input name="name" value="<?php echo $row['name']; ?>">
<input name="email" value="<?php echo $row['email']; ?>">
<select name="course"><option <?php if($row['course']=='MCA') echo 'selected'; ?>>MCA</option><option <?php if($row['course']=='BCA') echo 'selected'; ?>>BCA</option></select>
<button type="submit">Update Student</button>
</form></div></body></html>