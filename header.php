<?php
include '../conexion.php'; // Asegúrate de incluir tu archivo de conexión
$dni = $_SESSION['dni'];

$sql_check_admin = "SELECT admin FROM usuarios WHERE dni = ?";
$stmt_admin = $conn->prepare($sql_check_admin);
$stmt_admin->bind_param("s", $dni);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();
$row_admin = $result_admin->fetch_assoc();
$is_admin = $row_admin['admin'] ?? 0; // Asignar 0 si no es admin
$stmt_admin->close();

// Contar cuántos cupones ha reclamado el usuario
$sql_count_cupones = "SELECT COUNT(*) as total FROM codigo_cupones WHERE dni_usuario = ?";
$stmt_count = $conn->prepare($sql_count_cupones);
$stmt_count->bind_param("s", $dni);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$total_cupones = $row_count['total'];
$stmt_count->close();

// Lógica para mostrar el número de cupones
if ($total_cupones > 9) {
    $display_count = '+9';
} else {
    $display_count = $total_cupones > 0 ? $total_cupones : ''; // Mostrar vacío si no hay cupones
}

// Verificar si el usuario es comerciante
$sql_check_comerciante = "SELECT comerciante FROM usuarios WHERE dni = ?";
$stmt_check = $conn->prepare($sql_check_comerciante);
$stmt_check->bind_param("s", $dni);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row_check = $result_check->fetch_assoc();
$is_comerciante = $row_check['comerciante'] ?? 0; // Asignar 0 si no se encuentra el usuario
$stmt_check->close();

// Obtener el id del usuario
$sql_get_user_id = "SELECT id FROM usuarios WHERE dni = ?";
$stmt_get_user_id = $conn->prepare($sql_get_user_id);
$stmt_get_user_id->bind_param("s", $dni);
$stmt_get_user_id->execute();
$result_get_user_id = $stmt_get_user_id->get_result();
$row_get_user_id = $result_get_user_id->fetch_assoc();
$id_usuario = $row_get_user_id['id'] ?? null; // Asignar null si no se encuentra el usuario
$stmt_get_user_id->close();

// Verificar cuántos comercios tiene el usuario
$sql_check_comercios = "SELECT COUNT(*) as total_comercios FROM comercios WHERE id_usuario = ?";
$stmt_check_comercios = $conn->prepare($sql_check_comercios);
$stmt_check_comercios->bind_param("i", $id_usuario); // Cambiar a "i" si id_usuario es un entero
$stmt_check_comercios->execute();
$result_check_comercios = $stmt_check_comercios->get_result();
$row_check_comercios = $result_check_comercios->fetch_assoc();
$total_comercios = $row_check_comercios['total_comercios'];
$stmt_check_comercios->close();

// Obtener los comercios del usuario
$sql_get_comercios = "SELECT * FROM comercios WHERE id_usuario = ?";
$stmt_get_comercios = $conn->prepare($sql_get_comercios);
$stmt_get_comercios->bind_param("i", $id_usuario);
$stmt_get_comercios->execute();
$result_get_comercios = $stmt_get_comercios->get_result();
$comercios = $result_get_comercios->fetch_all(MYSQLI_ASSOC);
$stmt_get_comercios->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Mar de Cupones</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <link rel="icon" type="image/x-icon" href="img/mga.png" />

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <!-- Navbar start -->
    <div class="container-fluid fixed-top">
        <div class="container topbar bg-primary d-none d-lg-block">
            <div class="d-flex justify-content-between">
                <div class="top-info ps-2">
                    <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">Municipalidad de General Alvarado</a></small>
                    <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">comunidad@mga.gov.ar</a></small>
                </div>
                <div class="top-link pe-2">
                    <a href="https://mga.gov.ar/" class="text-white"><small class="text-white mx-2">Pagina Oficial</small></a>
                </div>
            </div>
        </div>
        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <a href="index.php" class="navbar-brand"><h1 class="text-primary display-6">Mar de Cupones</h1></a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                    </div>
                    <div class="d-flex m-3 me-0">
                        <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
                        <a href="cart.php" class="position-relative me-4 my-auto">
                        <i class="fa fa-shopping-bag fa-2x"></i>
                        <?php if ($display_count): ?>
                            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">
                                <?= $display_count ?>
                            </span>
                        <?php endif; ?>
                        </a>
                        <div class="dropstart">
                            <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-user fa-2x"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <?php
                                    if($is_admin == true){
                                        echo '<li><a class="dropdown-item" href="../admin/listado_usuarios.php">Administrar Usuarios</a></li>';
                                        echo '<li><a class="dropdown-item" href="../admin/listado_comercios.php">Administrar Comercios</a></li>';
                                        echo '<li><a class="dropdown-item" href="../admin/listado_cupones.php">Administrar Cupones</a></li>';    
                                    }
                                    if($is_comerciante == true){
                                        echo '<li><a class="dropdown-item" href="../cuplogin/cuponlog.php">Registrar Cupones</a></li>';
                                        echo '<li><a class="dropdown-item" href="mis_comercios.php">Mis Comercios</a></li>';
                                        echo '<li><a class="dropdown-item" href="usar_cupon.php">Reclamar Cupon</a></li>'; // Opción añadida
                                    }
                                    echo '<li><a class="dropdown-item" href="#" id="registerCommerce">Registrar Comercio</a></li>';

                                ?>
                                <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>   
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('registerCommerce').addEventListener('click', function(event) {
                event.preventDefault(); // Evitar la redirección inmediata
                if (<?= $is_comerciante ?> == 0) {
                    Swal.fire({
                        title: 'Cómo registrarse como comerciante',
                        text: 'Para registrarte debes de enviar un correo a turismo@mga.gov.ar, en el correo debes de colocar tu nombre, apellido, DNI y una breve descripción sobre ti. En el asunto debes colocar "solicitud de comerciante".',
                        imageUrl: 'img/Ejemplo-correo.png',
                        imageWidth:  650,
                        imageHeight: 400,
                        imageAlt: 'Ejemplo de correo',
                        width: '800px',
                        padding: '3em',
                        background: '#fff',
                        backdrop: `
                            rgba(0,0,123,0.4)
                        `
                    });
                } else {
                    // Redirigir a la página de registro de comercio
                    window.location.href = '../comlog/comlog.php'; // Redirigir si ya es comerciante
                }
            });
        });
    </script>