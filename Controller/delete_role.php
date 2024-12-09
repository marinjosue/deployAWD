<?php
require '../Connection/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer el cuerpo JSON enviado
    $input = json_decode(file_get_contents('php://input'), true);
    $id_rol = isset($input['id']) ? intval($input['id']) : 0;

    if ($id_rol > 0) {
        // Preparar la consulta SQL para eliminar el rol
        $sql = "DELETE FROM roles WHERE id_rol = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Vincular parámetros y ejecutar la consulta
            $stmt->bind_param("i", $id_rol);
            if ($stmt->execute()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Rol eliminado correctamente.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al eliminar el rol.'
                ]);
            }
            $stmt->close();
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error en la preparación de la consulta.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'ID de rol inválido.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
}

$conn->close();

?>
