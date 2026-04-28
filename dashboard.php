<?php include 'config.php'; if(!isset($_SESSION['admin'])) header('Location: login.php');
$count=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM students"))['total'];
$mca=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM students WHERE course='MCA'"))['c'];
$bca=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM students WHERE course='BCA'"))['c'];
$topper=mysqli_fetch_assoc(mysqli_query($conn,"SELECT s.name, MAX(r.marks) max_marks FROM results r JOIN students s ON s.id=r.student_id"));
?>
<!DOCTYPE html><html><head><title>Dashboard</title><link rel="stylesheet" href="style.css"><script src="https://cdn.jsdelivr.net/npm/chart.js"></script></head><body>
<div class="app-shell"><?php include 'sidebar.inc.php'; ?><main class="content">
<div class="stats-grid">
<div class="stat-card"><h3>Total</h3><div class="big-number"><?php echo $count; ?></div></div>
<div class="stat-card"><h3>MCA</h3><div class="big-number"><?php echo $mca; ?></div></div>
<div class="stat-card"><h3>BCA</h3><div class="big-number"><?php echo $bca; ?></div></div>
<div class="stat-card"><h3>Topper</h3><div><?php echo $topper['name'] ?? 'N/A'; ?></div></div>
</div>
<div class="page-card" style="margin-top:20px">
<input id="searchBox" placeholder="Search..." onkeyup="filterTable()">
<table id="studentTable"><tr><th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Action</th></tr>
<?php $res=mysqli_query($conn,"SELECT * FROM students ORDER BY id DESC"); while($r=mysqli_fetch_assoc($res)){ ?>
<tr><td><?php echo $r['id']; ?></td><td><?php echo $r['name']; ?></td><td><?php echo $r['email']; ?></td><td><span class="badge"><?php echo $r['course']; ?></span></td><td><a href="edit_student.php?id=<?php echo $r['id']; ?>">Edit</a> | <a class="delete-btn" href="delete_student.php?id=<?php echo $r['id']; ?>">Delete</a></td></tr>
<?php } ?></table><br>
<a href="export_report.php">📥 Download Report</a>
 |  <a href="ai_chat.php">🤖 AI Insights</a>
<canvas id="studentChart"></canvas>
</div></main></div>
<script>
new Chart(document.getElementById('studentChart'),{type:'bar',data:{labels:['MCA','BCA'],datasets:[{data:[<?php echo $mca; ?>,<?php echo $bca; ?>]}]}});
function filterTable(){let q=searchBox.value.toLowerCase();document.querySelectorAll('#studentTable tr').forEach((r,i)=>{if(i===0)return;r.style.display=r.innerText.toLowerCase().includes(q)?'':'none';});}
</script></body></html>