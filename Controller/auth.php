<?php
// Incluir el archivo de conexión
require_once('../connection/db.php');

// Obtener datos del formulario
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validar datos ingresados
if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Por favor ingresa todos los campos']);
    exit;
}

// Consulta para verificar el usuario
$sql = "SELECT users.id, users.first_name, users.last_name, users.password, roles.roles 
        FROM users 
        INNER JOIN roles ON users.id_rol = roles.id_rol 
        WHERE users.cedula = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username); // Asegúrate de que envíes 'cedula'
$stmt->execute();
$result = $stmt->get_result();

// Validar si el usuario existe
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verificar contraseña
    if ($password === $user['password']) {
        session_start(); // Iniciar la sesión
        $_SESSION['user_id'] = $user['id']; // Guardar el ID del usuario en la sesión
        $_SESSION['role'] = $user['roles']; // También puedes guardar el rol si lo necesitas
        
        echo json_encode([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'role' => $user['roles'],
            'id' => $user['id'], // También puedes enviarlo al cliente si lo necesitas
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
}

$conn->close();
?>
