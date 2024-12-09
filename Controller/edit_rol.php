<?php
require '../Connection/db.php';

$response = ['success' => false, 'message' => ''];  // Respuesta inicial

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_rol = $_POST['id_rol'];
    $roles = $_POST['roles'];

    // Preparar y ejecutar la consulta
    $sql = "UPDATE roles SET roles = ? WHERE id_rol = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $roles, $id_rol);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Rol actualizado correctamente";
    } else {
        $response['message'] = "Error al actualizar el rol: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

// Devolver la respuesta como JSON
echo json_encode($response);
?>
