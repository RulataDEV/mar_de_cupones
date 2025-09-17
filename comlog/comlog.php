<?php
include '../conexion.php';
include '../session_timeout.php';

// Comprobar si el usuario está logueado
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../userlog/login.php');
    exit;
}

// Obtener el ID del usuario actual
$id_usuario = $_SESSION['id_usuario'];

// Comprobar si el usuario es comerciante
$sql_com_check = "SELECT comerciante FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql_com_check);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($is_comerciante);
$stmt->fetch();
$stmt->close();

// Si el usuario no es comerciante, redirigir a la página de confirmación
if ($is_comerciante != 1) {
    header('Location: ../userlog/registrar_comerciante.php'); // Cambia esta URL a la página que deseas mostrar
    exit;
}
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Registrar Comercio</title>
    <link rel="stylesheet" href="styles.css">
    <link href="../indice/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <input type="checkbox" id="flip">
        <div class="cover">
            <div class="front">
                <img src="miramar.png" alt="">
                <div class="text">
                    <span class="text-1">Cada cupón<br> es una nueva oportunidad</span>
                    <span class="text-2">de cambiar tu vida!</span>
                </div>
            </div>
            <div class="back">
                <img class="backImg" src="mga.png" alt="">
                <div class="text">
                    <span class="text-1">¿Qué esperas para unirte?</span>
                    <span class="text-2">¡Vamos a registrarnos!</span>
                </div>
            </div>
        </div>
        <div class="forms">
            <div class="form-content">
                <div class="login-form">
                    <div class="title">Registrar un Comercio</div>
                    <form id="registroForm" method="post" action="check/registrarcom.php" enctype="multipart/form-data">
                        <div class="input-boxes">
                            <div class="input-box">
                                <i class="fas fa-store"></i>
                                <input type="text" name="nombre" placeholder="Nombre del Comercio" required>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-list"></i>
                                <select name="categoria" id="categoria" class="form-dropdown" required>
                                    <option value="">Seleccione una categoría</option>
                                    <option value="Gastronomía">Gastronomía</option>
                                    <option value="Alimentos">Alimentos</option>
                                    <option value="Heladería">Heladería</option>
                                    <option value="Mascotas">Mascotas</option>
                                    <option value="Alojamiento">Alojamiento</option>
                                    <option value="Estética y belleza">Estética y belleza</option>
                                    <option value="Balneario">Balneario</option>
                                    <option value="Entretenimiento">Entretenimiento</option>
                                    <option value="Tecnología">Tecnología</option>
                                    <option value="Servicios para vehículo">Servicios para vehículo</option>
                                    <option value="Delivery y take-away">Delivery y take-away</option>
                                    <option value="Otros">Otros</option>
                                </select>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-map-marker-alt"></i>
                                <select name=" localidad" id="localidad" class="form-dropdown" required>
                                    <option value="">Seleccione una Localidad</option>
                                    <option value="Miramar">Miramar</option>
                                    <option value="Otamendi">Otamendi</option>
                                    <option value="Mechongué">Mechongué</option>
                                    <option value="Mar del sur">Mar del Sud</option>
                                </select>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-map-signs"></i>
                                <input type="text" name="calle" placeholder="Calle" required>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-map-signs"></i>
                                <input type="text" name="altura" placeholder="Altura de Calle" required>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-phone"></i>
                                <input type="tel" name="telefono" placeholder="Teléfono" required>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-image"></i>
                                <input type="file" name="imagen" accept="image/*" required>
                            </div>
                            <div class="button input-box">
                                <input type="submit" value="Registrar Comercio">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</body>
</html>