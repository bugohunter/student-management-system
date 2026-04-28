<?php include 'config.php';
if(isset($_POST['username'])){
 if($_POST['username']==='admin' && $_POST['password']==='admin123'){
   $_SESSION['admin']='admin'; header('Location: dashboard.php'); exit();
 }
 $error='Invalid credentials';
}
?>
<!DOCTYPE html><html><head><title>Login</title><link rel="stylesheet" href="style.css"></head><body>
<div class="login-wrap"><div class="page-card"><h1>🌙 SMS Pro Login</h1>
<?php if(isset($error)) echo "<p style='color:#f87171'>$error</p>"; ?>
<form method="POST"><input name="username" placeholder="Username"><input type="password" name="password" placeholder="Password"><button>Login</button></form>
</div></div></body></html>