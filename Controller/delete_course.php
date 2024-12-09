<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "courses";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica si la conexión es correcta
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibe el ID del curso desde la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? intval($data['id']) : 0;

// Valida que el ID sea mayor a 0
if ($id <= 0) {
    echo json_encode(['error' => 'ID del curso inválido.']);
    exit;
}

// Eliminar las unidades asociadas al curso
$sql_units = "DELETE FROM course_units WHERE id_course = ?";
$stmt_units = $conn->prepare($sql_units);
$stmt_units->bind_param("i", $id);
$stmt_units->execute();

// Eliminar el curso
$sql_course = "DELETE FROM courses WHERE id_course = ?";
$stmt_course = $conn->prepare($sql_course);
$stmt_course->bind_param("i", $id);
$stmt_course->execute();

// Verificar si el curso fue eliminado
if ($stmt_course->affected_rows > 0) {
    echo json_encode(['success' => 'Curso eliminado correctamente.']);
} else {
    echo json_encode(['error' => 'No se pudo eliminar el curso.']);
}

// Cerrar conexiones
$stmt_units->close();
$stmt_course->close();
$conn->close();
?>
