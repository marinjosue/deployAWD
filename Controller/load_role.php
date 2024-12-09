<?php
require '../Connection/db.php';

$sql = "SELECT id_rol, roles FROM roles";
$result = $conn->query($sql);
$roles = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }
}

echo json_encode($roles);

$conn->close();
?>
