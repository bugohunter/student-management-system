<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(120);

include 'config.php';

$response = "";

if(isset($_POST['message'])){
    $user_input = strtolower(trim($_POST['message']));

    // ✅ DIRECT FAST LOGIC (NO AI)

    // Total students
    if(strpos($user_input, "total") !== false){
        $count = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM students"))['c'];
        $response = "Total students: $count";
    }

    // MCA students
    elseif(strpos($user_input, "mca") !== false){
        $count = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM students WHERE course='MCA'"))['c'];
        $response = "MCA students: $count";
    }

    // BCA students
    elseif(strpos($user_input, "bca") !== false){
        $count = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM students WHERE course='BCA'"))['c'];
        $response = "BCA students: $count";
    }

    // Topper
    elseif(strpos($user_input, "topper") !== false){
        $top = mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT s.name, MAX(r.marks) as m 
            FROM results r 
            JOIN students s ON s.id=r.student_id
        "));
        $response = $top['name'] ? "Topper is: ".$top['name']." (".$top['m']." marks)" : "No result data available.";
    }

    // Weak students
    elseif(strpos($user_input, "weak") !== false){
        $res = mysqli_query($conn,"
            SELECT s.name, IFNULL(MAX(r.marks),0) as marks
            FROM students s
            LEFT JOIN results r ON s.id=r.student_id
            GROUP BY s.id
            HAVING marks < 40
        ");

        if(mysqli_num_rows($res) > 0){
            $response = "Weak students:\n";
            while($r = mysqli_fetch_assoc($res)){
                $response .= "- ".$r['name']." (".$r['marks']." marks)\n";
            }
        } else {
            $response = "No weak students found.";
        }
    }

    // List students
    elseif(strpos($user_input, "list") !== false){
        $res = mysqli_query($conn,"SELECT name, course FROM students LIMIT 10");
        $response = "Students:\n";
        while($r = mysqli_fetch_assoc($res)){
            $response .= "- ".$r['name']." (".$r['course'].")\n";
        }
    }

    // 🤖 OPTIONAL AI (ONLY FOR SUMMARY)
    else {
        $data = mysqli_query($conn,"
            SELECT s.name, IFNULL(MAX(r.marks),0) as marks
            FROM students s
            LEFT JOIN results r ON s.id = r.student_id
            GROUP BY s.id
            LIMIT 5
        ");

        $dataset = "";
        while($row = mysqli_fetch_assoc($data)){
            $dataset .= $row['name']."=".$row['marks'].", ";
        }

        $prompt = "Give a short performance summary: ".$dataset;

        $cmd = "ollama run llama3:instruct " . escapeshellarg($prompt);
        $output = shell_exec($cmd);

        $response = $output ? $output : "Summary unavailable.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AI Assistant</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app-shell">
<?php include 'sidebar.inc.php'; ?>

<main class="content">
<div class="page-card">
    <h2>🤖 Smart Student Assistant</h2>

    <form method="POST">
        <input type="text" name="message" placeholder="Ask about students..." required>
        <button type="submit">Ask</button>
    </form>

    <?php if($response){ ?>
        <div style="margin-top:20px;">
            <strong>Response:</strong>
            <div style="padding:15px;background:#1f2937;border-radius:10px;">
                <?php echo nl2br(htmlspecialchars($response)); ?>
            </div>
        </div>
    <?php } ?>

</div>
</main>
</div>

</body>
</html>