<?php
require '../Connection/db.php';  // Asegúrate de incluir la conexión a la base de datos

// Definir la respuesta inicial
$response = ['success' => false, 'data' => []];

try {
    // Preparar la consulta para obtener los usuarios
    $sql = "SELECT id, cedula, first_name, last_name, address, phone, email, password, gender, id_rol FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Almacenar los resultados en un array
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = [
                'id' => $row['id'],
                'cedula' => $row['cedula'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'address' => $row['address'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'password' => $row['password'],
                'gender' => $row['gender'],
                'id_rol' => $row['id_rol']
            ];
        }

        // Devolver la respuesta con los datos
        $response['success'] = true;
        $response['data'] = $usuarios;
    } else {
        $response['message'] = 'No se encontraron usuarios.';
    }
} catch (Exception $e) {
    $response['message'] = 'Error al obtener usuarios: ' . $e->getMessage();
}

// Devolver la respuesta como JSON
echo json_encode($response);

$conn->close();
?>
