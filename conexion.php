<?php
    // Conexión a la base de datos
    $servidor = "localhost";
    $usuario = "root";
    $contraseña = "";
    $bd = "cupones";

    // Establecer la conexión
    $conn = mysqli_connect($servidor, $usuario, $contraseña, $bd);

    // Verificar la conexión
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }
?>