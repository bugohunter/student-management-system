<?php
include 'db.php';

$student_data = null;
$attendance_percentage = 0;
$present_classes = 0;
$total_classes = 0;
$results = null;

// Search by student ID
if (isset($_POST['student_id'])) {
    $student_id = intval($_POST['student_id']);

    // Student details
    $student = mysqli_query($conn, "SELECT * FROM students WHERE id='$student_id'");
    $student_data = mysqli_fetch_assoc($student);

    if ($student_data) {
        // Attendance summary
        $total_att = mysqli_query($conn, "SELECT COUNT(*) as total FROM attendance WHERE student_id='$student_id'");
        $total_att_data = mysqli_fetch_assoc($total_att);

        $present_att = mysqli_query($conn, "SELECT COUNT(*) as present FROM attendance WHERE student_id='$student_id' AND status='Present'");
        $present_att_data = mysqli_fetch_assoc($present_att);

        $total_classes = $total_att_data['total'];
        $present_classes = $present_att_data['present'];
        $attendance_percentage = $total_classes > 0 ? round(($present_classes / $total_classes) * 100, 2) : 0;

        // Results
        $results = mysqli_query($conn, "SELECT * FROM results WHERE student_id='$student_id'");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Portal</title>
    <style>
        body {
            background: #121212;
            color: white;
            font-family: Arial;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 80%;
            margin: auto;
            background: #1e1e1e;
            padding: 25px;
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
            width: auto;
        }

        h2, h3 {
            text-align: center;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
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
        }

        .card {
            background: #2b2b2b;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #2a2a2a;
        }

        th, td {
            border: 1px solid #444;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #333;
        }

        .summary {
            display: flex;
            gap: 20px;
            justify-content: space-between;
            margin-top: 20px;
        }

        .summary-box {
            flex: 1;
            background: #2b2b2b;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <button class="back-btn" onclick="window.location.href='dashboard.php'">⬅ Back</button>

    <h2>🎓 Student Self-Service Portal</h2>

    <form method="POST">
        <input type="number" name="student_id" placeholder="Enter Student ID" required>
        <button type="submit">Get Student Data</button>
    </form>

    <?php if ($student_data) { ?>
        <div class="card">
            <h3>Student Profile</h3>
            <p><strong>ID:</strong> <?php echo $student_data['id']; ?></p>
            <p><strong>Name:</strong> <?php echo $student_data['name']; ?></p>
            <p><strong>Email:</strong> <?php echo $student_data['email']; ?></p>
            <p><strong>Course:</strong> <?php echo $student_data['course']; ?></p>
        </div>

        <div class="summary">
            <div class="summary-box">
                <h3><?php echo $attendance_percentage; ?>%</h3>
                <p>Attendance</p>
            </div>
            <div class="summary-box">
                <h3><?php echo $present_classes; ?>/<?php echo $total_classes; ?></h3>
                <p>Present Classes</p>
            </div>
        </div>

        <div class="card">
            <h3>Results Summary</h3>
            <table>
                <tr>
                    <th>Subject</th>
                    <th>Marks</th>
                </tr>

                <?php
                $total_marks = 0;
                $subject_count = 0;

                while($row = mysqli_fetch_assoc($results)) {
                    $total_marks += $row['marks'];
                    $subject_count++;
                ?>
                    <tr>
                        <td><?php echo $row['subject']; ?></td>
                        <td><?php echo $row['marks']; ?></td>
                    </tr>
                <?php } ?>
            </table>

            <br>

            <p>
                <strong>Percentage:</strong>
                <?php
                $percentage = $subject_count > 0 ? round($total_marks / $subject_count, 2) : 0;
                echo $percentage . "%";
                ?>
            </p>
        </div>
    <?php } ?>
</div>

</body>
</html>