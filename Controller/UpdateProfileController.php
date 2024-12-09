<?php
require '../Connection/db.php';

session_start();

// Inicializar las variables de error y éxito
$error_message = '';
$success_message = '';

// Obtener el ID del usuario desde el parámetro GET o POST
$id_usuario = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id'] ?? null;
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $cedula = $_POST['cedula'] ?? ''; // Nueva variable para cédula

    // Verifica si se ha subido una imagen
    if (!empty($_FILES['profile_image']['name'])) {
        $upload_dir = '../imgProfile/';

        // Usa la cédula del usuario para nombrar la imagen
        $target_file = $upload_dir . $cedula . '.png';

        // Intenta mover el archivo cargado al directorio especificado
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            // Actualiza la columna profile_image en la base de datos
            $profile_image = $cedula . '.png';
            $sql_image = "UPDATE users SET profile_image = ? WHERE id = ?";
            $stmt_image = $conn->prepare($sql_image);
            $stmt_image->bind_param('si', $profile_image, $id_usuario);
            if ($stmt_image->execute()) {
                $success_message = "Imagen de perfil actualizada correctamente.";
            } else {
                $error_message = "Error al actualizar la imagen en la base de datos: " . $conn->error;
            }
            $stmt_image->close();
        } else {
            $error_message = "Error al cargar la imagen. Por favor, verifica los permisos de escritura en la carpeta.";
        }
    }

    // Actualizar los datos del usuario
    $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, address = ?, phone = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssi', $first_name, $last_name, $email, $address, $phone, $password, $id_usuario);

    if ($stmt->execute()) {
        // Obtener el rol del usuario actualizado
        $sql_rol = "SELECT id_rol FROM users WHERE id = ?";
        $stmt_rol = $conn->prepare($sql_rol);
        $stmt_rol->bind_param('i', $id_usuario);
        $stmt_rol->execute();
        $result_rol = $stmt_rol->get_result();

        if ($result_rol->num_rows > 0) {
            $user = $result_rol->fetch_assoc();
            $id_rol = $user['id_rol'];

            // Redirigir según el rol del usuario
            if ($id_rol == 1) {
                $_SESSION['success_message'] = "Cambios realizados correctamente.";
                header("Location: ../ViewAdmin/Profile.html");
            } else {
                $_SESSION['success_message'] = "Cambios realizados correctamente.";
                header("Location: ../ViewUser/ProfileUser.html");
            }
            exit;
        } else {
            $error_message = "Error al obtener el rol del usuario.";
        }

        $stmt_rol->close();
    } else {
        $error_message = "Error al actualizar el usuario: " . $conn->error;
    }

    $stmt->close();
}

// Obtener datos del usuario
if ($id_usuario) {
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Usuario no encontrado.";
        exit;
    }

    $stmt->close();
} else {
    echo "ID de usuario no proporcionado.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h4>Editar Usuario</h4>
            </div>
            <div class="card-body">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <?php if ($success_message): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <input type="hidden" name="cedula" value="<?= $user['cedula'] ?>">

                    <div class="mb-3">
                        <label for="first_name" class="form-label">Nombre:</label>
                        <input type="text" name="first_name" class="form-control" value="<?= $user['first_name'] ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Apellido:</label>
                        <input type="text" name="last_name" class="form-control" value="<?= $user['last_name'] ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo:</label>
                        <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección:</label>
                        <input type="text" name="address" class="form-control" value="<?= $user['address'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono:</label>
                        <input type="text" name="phone" class="form-control" value="<?= $user['phone'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="text" name="password" class="form-control" value="<?= $user['password'] ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Actualizar Foto de Perfil:</label>
                        <input type="file" name="profile_image" class="form-control" accept=".png">
                    </div>
                    <script>
                        document.querySelector('input[name="profile_image"]').addEventListener('change', function(event) {
                            const fileInput = event.target;
                            const file = fileInput.files[0];
                            const cedula = document.querySelector('input[name="cedula"]').value;

                            if (file) {
                                // Create a preview of the selected image
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const previewContainer = document.getElementById('image-preview');
                                    previewContainer.innerHTML = `<img src="${e.target.result}" alt="Imagen de Perfil" class="img-thumbnail" style="max-width: 200px;">`;
                                };
                                reader.readAsDataURL(file);

                                // Rename the file input with the user's cedula
                                const dataTransfer = new DataTransfer();
                                const renamedFile = new File([file], `${cedula}.png`, { type: file.type });
                                dataTransfer.items.add(renamedFile);
                                fileInput.files = dataTransfer.files;
                            }
                        });
                    </script>

                    <div class="mb-3">
                        <label for="image-preview" class="form-label">Vista Previa de la Imagen:</label>
                        <div id="image-preview"></div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div><br>
                    <div class="text-center">
                        <a href="../ViewAdmin/Profile.html" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>