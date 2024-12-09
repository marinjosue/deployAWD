<?php
require '../Connection/db.php';
session_start(); 
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id'] ?? null;
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $id_rol = $_POST['id_rol'] ?? '';

    if ($id_usuario) {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, address = ?, phone = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssi', $first_name, $last_name, $email, $address, $phone, $password, $id_usuario);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Cambios realizados correctamente.";  
            header("Location: ../ViewAdmin/Users.html");  
            exit;
        } else {
            $error_message = "Error al actualizar el usuario: " . $conn->error;
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
        }
        
        .container {
            margin-top: 50px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #4e73df;
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .form-control {
            border-radius: 10px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
        }

        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
            border-radius: 5px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }

        .alert {
            margin-top: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h4>Editar Usuario</h4>
            </div>
            <div class="card-body">
                
                <?php if ($error_message): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="edit_user.php">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">

                    <div class="mb-3">
                        <label for="first_name" class="form-label">Nombre:</label>
                        <input type="text" name="first_name" class="form-control" value="<?= $user['first_name'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Apellido:</label>
                        <input type="text" name="last_name" class="form-control" value="<?= $user['last_name'] ?>" required>
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
                        <input type="password" name="password" class="form-control" value="<?= $user['password'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Rol:</label>
                        <input type="text" name="id_rol" class="form-control" value="<?= $user['id_rol'] ?>" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
