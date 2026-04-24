<?php
include 'db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=attendance_report.csv');

$output = fopen('php://output', 'w');

// CSV headings
fputcsv($output, ['Student ID', 'Student Name', 'Date', 'Status']);

// Join students + attendance
$query = mysqli_query($conn, "
    SELECT students.id, students.name, attendance.att_date, attendance.status
    FROM attendance
    INNER JOIN students ON students.id = attendance.student_id
    ORDER BY attendance.att_date DESC, students.name ASC
");

while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, [
        $row['id'],
        $row['name'],
        $row['att_date'],
        $row['status']
    ]);
}

fclose($output);
exit;
?>