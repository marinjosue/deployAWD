<?php
require '../Connection/db.php';

// Obtener datos del formulario
$cedula = $_POST['cedula'] ?? '';
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$address = $_POST['address'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$id_rol = 3;

// Validar datos
if (empty($cedula) || empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Por favor llena todos los campos obligatorios.']);
    exit;
}

// Consulta para insertar
$sql = "INSERT INTO users (cedula, first_name, last_name, address, phone, email, password, id_rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
    exit;
}

$stmt->bind_param('sssssssi', $cedula, $first_name, $last_name, $address, $phone, $email, $password, $id_rol);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar usuario: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>