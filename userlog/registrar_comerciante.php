<?php
session_start();
include '../conexion.php';

// Asegúrate de que el usuario esté logueado
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../userlog/login.php');
    exit;
}

// Obtener el ID del usuario logueado
$id_usuario = $_SESSION['id_usuario'];

// Obtener los datos del usuario logueado (nombre, apellido y correo)
$query = $conn->prepare("SELECT nombre, apellido, correo FROM usuarios WHERE id = ?");
$query->bind_param("i", $id_usuario);
$query->execute();
$query->bind_result($nombre, $apellido, $correo_usuario);
$query->fetch();
$query->close();
?>

<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Registro como Comerciante</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Estilo para hacer más grande el campo de descripción */
        textarea {
            width: 100%;
            height: 100%; /* Ajusta la altura según sea necesario */
            resize: none; /* Evita que el usuario cambie el tamaño manualmente */
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Incluye jQuery -->
</head>
<body>
    <div class="container">
        <div>
            <a href="../indice/index.php"><h3>⤺</h3></a>
        </div>
        <div class="forms">
            <div class="form-content">
                <div class="login-form">
                    <div class="title">Solicitud de Registro como Comerciante</div>
                    <form id="requestForm">
                        <div class="input-boxes">
                            <div class="input-box">
                                <i class="fas fa-user"></i>
                                <input type="text" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" readonly>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-user"></i>
                                <input type="text" name="apellido" value="<?php echo htmlspecialchars($apellido); ?>" readonly>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-address-card"></i>
                                <input type="text" name="dni" value="<?php echo htmlspecialchars($_SESSION['dni']); ?>" readonly>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="correo" placeholder="Correo electrónico" required>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="contrasena" placeholder="Contraseña de tu correo electrónico" required>
                            </div>
                            <div class="input-box">
                                <i></i>
                                <textarea name="descripcion" placeholder="Describe el comercio o comercios que tienes..." required></textarea>
                            </div>
                            <div class="button input-box">
                                <input type="submit" value="Enviar Solicitud">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#requestForm').on('submit', function(e) {
                e.preventDefault(); // Evitar el envío normal del formulario

                $.ajax({
                    type: 'POST',
                    url: 'send_request.php', // Cambia esto a la ruta correcta de tu archivo PHP
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Éxito',
                                text: 'Su solicitud fue enviada exitosamente',
                                icon: 'success'
                            }).then(() => {
                                window.location.href = '../indice/index.php '; // Redirigir a la página de inicio
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.error,
                                icon : 'error'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al enviar la solicitud. Inténtalo de nuevo más tarde.',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>