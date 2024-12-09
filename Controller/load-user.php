<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'No se encontró al usuario logueado.'
    ]);
    exit;
}

// Conexión a la base de datos
require '../Connection/db.php';

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_id'];

$sql = "SELECT id, first_name, last_name, email, phone, id_rol FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'data' => $user
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Usuario no encontrado.'
    ]);
}

$stmt->close();
$conn->close();
