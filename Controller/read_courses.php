<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../connection/db.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo json_encode(['error' => 'ID del curso inválido.']);
    exit;
}

$sql_course = "SELECT id_course, course_name, course_description, course_youtube FROM courses WHERE id_course = ?";
$stmt_course = $conn->prepare($sql_course);
$stmt_course->bind_param("i", $id);
$stmt_course->execute();
$result_course = $stmt_course->get_result();
$course = $result_course->fetch_assoc();

if (!$course) {
    echo json_encode(['error' => 'Curso no encontrado.']);
    exit;
}

$sql_units = "SELECT unit_title, unit_content FROM course_units WHERE id_course = ?";
$stmt_units = $conn->prepare($sql_units);
$stmt_units->bind_param("i", $id);
$stmt_units->execute();
$result_units = $stmt_units->get_result();

$units = [];
while ($unit = $result_units->fetch_assoc()) {
    $units[] = $unit;
}

$course['units'] = $units;

header('Content-Type: application/json');
echo json_encode($course);

