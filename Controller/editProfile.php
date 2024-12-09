<?php
require '../Connection/db.php';
session_start();  // Iniciar la sesión para usar los mensajes

// Inicializar la variable de error
$error_message = '';
$success_message = '';

// Si el formulario se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id'] ?? null;
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $id_rol = $_POST['id_rol'] ?? '';


    // Validar que el ID del usuario existe
    if ($id_usuario) {
        // Preparar la consulta de actualización
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, address = ?, phone = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssi', $first_name, $last_name, $email, $address, $phone, $password, $id_usuario);

        if ($id_rol == 1) {
            $_SESSION['success_message'] = "Cambios realizados correctamente.";
            header("Location: ../ViewAdmin/Profile.html");
        } else {
            $_SESSION['success_message'] = "Cambios realizados correctamente.";
            header("Location: ../ViewUser/ProfileUser.html");
        }
        
        
        $stmt->close();
    } else {
        $error_message = "ID de usuario no válido.";
    }
}

// Obtener el ID del usuario para editar
$id_usuario = $_GET['id'] ?? null;

if ($id_usuario) {
    // Consultar los datos del usuario
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Obtener los datos del usuario
        $user = $result->fetch_assoc();
    } else {
        echo "Usuario no encontrado";
        exit;
    }

    $stmt->close();
} else {
    echo "ID de usuario no proporcionado.";
    exit;
}

$conn->close();
?>