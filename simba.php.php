<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(120);

include 'config.php';

$response = "";

if(isset($_POST['message'])){
    $user_input = strtolower(trim($_POST['message']));

    // ✅ TOTAL STUDENTS
    if(strpos($user_input, "total") !== false){
        $count = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM students"))['c'];
        $response = "Total students: $count";
    }

    // ✅ MCA STUDENTS
    elseif(strpos($user_input, "mca") !== false){
        $count = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM students WHERE course='MCA'"))['c'];
        $response = "MCA students: $count";
    }

    // ✅ BCA STUDENTS
    elseif(strpos($user_input, "bca") !== false){
        $count = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) c FROM students WHERE course='BCA'"))['c'];
        $response = "BCA students: $count";
    }

    // ✅ TOPPER
    elseif(strpos($user_input, "topper") !== false){
        $top = mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT s.name, MAX(r.marks) as m 
            FROM results r 
            JOIN students s ON s.id=r.student_id
        "));
        $response = $top['name'] ? "Topper is: ".$top['name']." (".$top['m']." marks)" : "No result data available.";
    }

    // ✅ WEAK STUDENTS
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

    // ✅ LIST STUDENTS
    elseif(strpos($user_input, "list") !== false){
        $res = mysqli_query($conn,"SELECT name, course FROM students LIMIT 10");
        $response = "Students:\n";
        while($r = mysqli_fetch_assoc($res)){
            $response .= "- ".$r['name']." (".$r['course'].")\n";
        }
    }

    // 🤖 AI SUMMARY (NOW USING PROXY — SAME AS HOSTED)
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

        $prompt = "Give a short performance summary for these students: ".$dataset;

        $payload = [
            "model" => "gpt-4o-mini",
            "messages" => [
                ["role" => "system", "content" => "You are a student analytics assistant. Keep answers short and clear."],
                ["role" => "user", "content" => $prompt]
            ]
        ];

        // 🔥 SAME PROXY CALL (WORKS LOCALLY ALSO)
        $ch = curl_init("https://ai-proxy-bk4v.onrender.com/chat");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            "data" => json_encode($payload)
        ]));

        $result = curl_exec($ch);

        if(curl_errno($ch)){
            $response = "API Error: " . curl_error($ch);
        } else {
            $resData = json_decode($result, true);
            $response = $resData['choices'][0]['message']['content'] ?? "No response from AI.";
        }

        curl_close($ch);
    }
}
?>