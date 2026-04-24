<?php
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="student_report.csv"');
include 'config.php';
echo "ID,Name,Email,Course
";
$res = mysqli_query($conn, "SELECT * FROM students");
while($r=mysqli_fetch_assoc($res)){
 echo "{$r['id']},{$r['name']},{$r['email']},{$r['course']}
";
}
?>