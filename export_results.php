<?php
include 'db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=results_report.csv');

$output = fopen('php://output', 'w');

fputcsv($output, ['Student ID', 'Student Name', 'Subject', 'Marks', 'Grade']);

$query = mysqli_query($conn, "
    SELECT students.id, students.name, results.subject, results.marks
    FROM results
    INNER JOIN students ON students.id = results.student_id
    ORDER BY students.name ASC
");

while ($row = mysqli_fetch_assoc($query)) {
    $grade = "F";

    if ($row['marks'] >= 90) $grade = "A+";
    elseif ($row['marks'] >= 75) $grade = "A";
    elseif ($row['marks'] >= 60) $grade = "B";
    elseif ($row['marks'] >= 40) $grade = "C";

    fputcsv($output, [
        $row['id'],
        $row['name'],
        $row['subject'],
        $row['marks'],
        $grade
    ]);
}

fclose($output);
exit;
?>