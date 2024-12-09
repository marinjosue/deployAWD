<?php
// Incluir archivo de conexión a la base de datos
include('../Connection/db.php');

// Verificar si los datos fueron enviados por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtener los datos del formulario
    $cedula = $_POST['cedula'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $id_rol = $_POST['role']; // Asumimos que el rol se pasa con 'role' como el nombre del campo

    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Crear la consulta SQL para insertar el nuevo usuario
    $sql = "INSERT INTO users (cedula, first_name, last_name, address, phone, email, password, gender, id_rol)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la declaración SQL
    if ($stmt = $conn->prepare($sql)) {
        // Enlazar los parámetros
        $stmt->bind_param("ssssssssi", $cedula, $first_name, $last_name, $address, $phone, $email, $hashed_password, $gender, $id_rol);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si se ejecuta correctamente, devolver un JSON de éxito
            echo json_encode(['success' => true, 'message' => 'Usuario agregado correctamente.']);
        } else {
            // Si ocurre un error al ejecutar la consulta, devolver un error
            echo json_encode(['success' => false, 'message' => 'Error al agregar el usuario.']);
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
    }

    // Cerrar la conexión a la base de datos
    $conn->close();

} else {
    // Si no es una solicitud POST, devolver un error
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']);
}
?>
