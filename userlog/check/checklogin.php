<?php
if (!session_id()) {
    session_start();
}

include '../../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST['dni'];
    $password = $_POST['pass'];

    // Ajusta la consulta para obtener toda la información necesaria
    $query = $conn->prepare("SELECT id, contrasena, comerciante FROM usuarios WHERE dni = ?");
    $query->bind_param("s", $dni);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
        $query->bind_result($id_usuario, $hashed_password, $es_comerciante);
        $query->fetch();

        if (password_verify($password, $hashed_password)) {
            // Guardar información del usuario en la sesión
            $_SESSION['loggedin'] = true;
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['dni'] = $dni;
            $_SESSION['comerciante'] = $es_comerciante; // Establece si el usuario es comerciante (0 o 1)

            // Redirigir al index después de iniciar sesión
            header('location: ../../indice/index.php');
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "El usuario no existe.";
    }

    $query->close();
}

$conn->close();
?>
