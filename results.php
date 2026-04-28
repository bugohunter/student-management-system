<?php
include 'db.php';

// Save 5 subjects result
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    $student_id = intval($_POST['student_id']);

    for ($i = 1; $i <= 5; $i++) {
        $subject = mysqli_real_escape_string($conn, $_POST["subject$i"]);
        $marks = intval($_POST["marks$i"]);

        if (!empty($subject)) {
            $insert = mysqli_query($conn,
                "INSERT INTO results (student_id, subject, marks)
                 VALUES ('$student_id', '$subject', '$marks')"
            );

            if (!$insert) {
                die("Result SQL Error: " . mysqli_error($conn));
            }
        }
    }

    echo "<script>alert('5 Subjects Result Saved Successfully');</script>";
}

// fetch students
$students = mysqli_query($conn, "SELECT * FROM students ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Results</title>
    <style>
        body {
            background: #121212;
            color: white;
            font-family: Arial;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 70%;
            margin: auto;
            background: #1e1e1e;
            padding: 25px;
            border-radius: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: none;
            border-radius: 8px;
            background: #333;
            color: white;
        }

        button {
            width: 100%;
            padding: 12px;
            background: green;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
        }

        .export-btn {
            background: #007bff;
        }

        .back-btn {
            background: #444;
            margin-bottom: 20px;
        }

        .subject-box {
            margin-bottom: 15px;
            padding: 10px;
            background: #2b2b2b;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <button class="back-btn" onclick="window.location.href='dashboard.php'">⬅ Back</button>

    <h2>Result Module (5 Subjects)</h2>

    <form method="POST">
        <select name="student_id" required>
            <option value="">Select Student</option>
            <?php while($row = mysqli_fetch_assoc($students)) { ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo $row['name']; ?>
                </option>
            <?php } ?>
        </select>

        <?php for($i=1; $i<=5; $i++) { ?>
            <div class="subject-box">
                <input type="text" name="subject<?php echo $i; ?>" placeholder="Enter Subject <?php echo $i; ?>">
                <input type="number" name="marks<?php echo $i; ?>" placeholder="Enter Marks <?php echo $i; ?>">
            </div>
        <?php } ?>

        <button type="submit">Save 5 Subjects Result</button>
    </form>

    <br>

    <a href="export_results.php">
        <button class="export-btn">Export Results CSV</button>
    </a>
</div>

</body>
</html>