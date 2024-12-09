<?php
require '../Connection/db.php';

$response = ['success' => false, 'message' => ''];

$data = json_decode(file_get_contents("php://input"), true);
$id_usuario = $data['id'] ?? null;

if ($id_usuario) {
    // Preparar y ejecutar la consulta de eliminación
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_usuario);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Usuario eliminado correctamente";
    } else {
        $response['message'] = "Error al eliminar el usuario: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();

// Devolver la respuesta como JSON
echo json_encode($response);
?>
