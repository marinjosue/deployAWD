<?php
require '../Connection/db.php';

$sql = "SELECT id_course, course_name, start_date, end_date, price FROM courses";
$result = $conn->query($sql);

$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($courses);

$conn->close();
?>
