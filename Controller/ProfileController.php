<?php
// Incluir el archivo de conexión
require_once('../connection/db.php');
session_start();


// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigir al login
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_id'];

// Consulta para obtener los datos del usuario
$sql = "SELECT users.id,users.cedula, users.first_name, users.last_name, users.email, users.phone, roles.roles 
        FROM users 
        INNER JOIN roles ON users.id_rol = roles.id_rol 
        WHERE users.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id); // Vincula el ID del usuario
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontraron datos del usuario
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(['user' => $user]);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}

$conn->close();
?>
