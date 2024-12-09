<?php
    // Conexión a la base de datos
    $servername = "autorack.proxy.rlwy.net";
    $username = "root"; 
    $password = "GdmUDNqCjqbuDPBCYwdipQHNdOgLMRim"; 
    $dbname = "railway"; 
    $port = "59374";
    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
?>