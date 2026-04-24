<?php
include 'db.php';

// selected date from form
$selected_date = isset($_POST['selected_date']) ? $_POST['selected_date'] : date("Y-m-d");

// Save attendance
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['attendance'])) {

    foreach ($_POST['attendance'] as $student_id => $status) {
        $student_id = intval($student_id);
        $status = mysqli_real_escape_string($conn, $status);

        $check = mysqli_query($conn,
            "SELECT * FROM attendance
             WHERE student_id='$student_id'
             AND att_date='$selected_date'"
        );

        if (mysqli_num_rows($check) > 0) {
            mysqli_query($conn,
                "UPDATE attendance
                 SET status='$status'
                 WHERE student_id='$student_id'
                 AND att_date='$selected_date'"
            );
        } else {
            mysqli_query($conn,
                "INSERT INTO attendance (student_id, att_date, status)
                 VALUES ('$student_id', '$selected_date', '$status')"
            );
        }
    }

    echo "<script>alert('Attendance saved successfully');</script>";
}

// Fetch students
$students = mysqli_query($conn, "SELECT * FROM students ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance</title>
    <style>
        body {
            background: #121212;
            color: white;
            font-family: Arial;
            padding: 20px;
            margin: 0;
        }

        .container {
            width: 85%;
            margin: auto;
            background: #1e1e1e;
            padding: 20px;
            border-radius: 12px;
        }

        .back-btn {
            background: #444;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
        }

        .date-box {
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="date"] {
            padding: 8px;
            border-radius: 6px;
            border: none;
            background: #444;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #2b2b2b;
        }

        th, td {
            padding: 12px;
            border: 1px solid #444;
            text-align: center;
        }

        select {
            padding: 6px;
            border-radius: 6px;
            background: #444;
            color: white;
            border: none;
        }

        .save-btn {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: green;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <button class="back-btn" onclick="window.location.href='dashboard.php'">⬅ Back</button>

    <h2>Student Attendance</h2>
    
<a href="export_attendance.php">
    <button type="button" class="save-btn" style="background:#007bff; margin-bottom:15px;">
        Export Attendance CSV
    </button>
</a>
    <form method="POST">
        <div class="date-box">
            <label>Select Date: </label>
            <input type="date" name="selected_date" value="<?php echo $selected_date; ?>">
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Status</th>
            </tr>

            <?php while($row = mysqli_fetch_assoc($students)) { 
                $student_id = $row['id'];

                $existing = mysqli_query($conn,
                    "SELECT status FROM attendance
                     WHERE student_id='$student_id'
                     AND att_date='$selected_date'"
                );

                $saved_status = "Present";

                if (mysqli_num_rows($existing) > 0) {
                    $att_row = mysqli_fetch_assoc($existing);
                    $saved_status = $att_row['status'];
                }
            ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <select name="attendance[<?php echo $student_id; ?>]">
                            <option value="Present" <?php if($saved_status=="Present") echo "selected"; ?>>Present</option>
                            <option value="Absent" <?php if($saved_status=="Absent") echo "selected"; ?>>Absent</option>
                        </select>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <button type="submit" class="save-btn">Save Attendance</button>
    </form>
</div>

</body>
</html>