<?php
include '../../conexion.php';

// Sanitización de los datos recibidos del formulario
$nombre = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
$apellido = htmlspecialchars($_POST['ape'], ENT_QUOTES, 'UTF-8');
$correo = htmlspecialchars($_POST['E-mail'], ENT_QUOTES, 'UTF-8');
$dni = htmlspecialchars($_POST['dni'], ENT_QUOTES, 'UTF-8');
$password = password_hash($_POST['pass'], PASSWORD_DEFAULT);

// Verificar si el DNI o el correo ya están registrados
$verificar_usuario = "SELECT dni, correo FROM usuarios WHERE dni = ? OR correo = ?";
$stmt = $conn->prepare($verificar_usuario);
$stmt->bind_param("ss", $dni, $correo);
$stmt->execute();
$stmt->store_result();

// Incluir el script una sola vez
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

if ($stmt->num_rows > 0) {
    $stmt->bind_result($dni_existe, $correo_existe);
    $stmt->fetch();

    if ($dni_existe == $dni) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'DNI duplicado',
                        text: 'Este DNI ya está registrado.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = '../login.php';
                    });
                });
              </script>";
    } elseif ($correo_existe == $correo) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Correo duplicado',
                        text: 'Este correo ya está registrado.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = '../login.php';
                    });
                });
              </script>";
    }
} else {
    $consulta = "INSERT INTO usuarios (nombre, apellido, contrasena, correo, dni, admin, comerciante) 
                 VALUES (?, ?, ?, ?, ?, '0', '0')";
    $stmt = $conn->prepare($consulta);
    $stmt->bind_param("sssss", $nombre, $apellido, $password, $correo, $dni);

    if ($stmt->execute()) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registro exitoso',
                        text: 'Tu registro se completó correctamente.',
                        confirmButtonText: 'Iniciar sesión'
                    }).then(() => {
                        window.location.href = '../login.php';
                    });
                });
              </script>";
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al registrar el usuario.',
                        confirmButtonText: 'Intentar de nuevo'
                    }).then(() => {
                        window.location.href = '../login.php';
                    });
                });
              </script>";
    }
}

$stmt->close();
mysqli_close($conn);
?>
