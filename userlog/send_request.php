<?php
session_start();
use Google\Client;
use Google\Service\Gmail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include '../conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['loggedin'])) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado.']);
    exit;
}

// Obtener datos del usuario logueado
$id_usuario = $_SESSION['id_usuario'];
$query = $conn->prepare("SELECT nombre, apellido, dni FROM usuarios WHERE id = ?");
$query->bind_param("i", $id_usuario);
$query->execute();
$query->bind_result($nombre_usuario, $apellido_usuario, $dni_usuario);
$query->fetch();
$query->close();

// Obtener datos del formulario
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$descripcion = $_POST['descripcion'];

// Crear el cliente de Google
$client = new Client();
$client->setClientId('YOUR_CLIENT_ID'); // Reemplaza con tu Client ID
$client->setClientSecret('YOUR_CLIENT_SECRET'); // Reemplaza con tu Client Secret
$client->setRedirectUri('http://localhost/oauth2callback'); // Cambia esto según sea necesario
$client->addScope(Gmail::GMAIL_SEND);

// Verificar si se ha iniciado sesión
if (!isset($_SESSION['access_token'])) {
    // Redirigir al usuario a la pantalla de autorización
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit;
}

// Obtener el token de acceso
$accessToken = $_SESSION['access_token'];

// Almacenar el token de acceso y el refresh token en la sesión
$_SESSION['access_token'] = $accessToken;
$_SESSION['refresh_token'] = $client->getRefreshToken();

// Crear una instancia de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->AuthType = 'XOAUTH2';
    $mail->OAuth = new OAuth([
        'provider' => new Google([
            'clientId' => '870970109393-ddd54vok4qn1h5qr0rnb0s70jch0adge.apps.googleusercontent.com',
            'clientSecret' => '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',
        ]),
        'accessToken' => $accessToken,
        'refreshToken' => $_SESSION['refresh_token'],
    ]);

    // Configurar el remitente y el destinatario
    $mail->setFrom($correo, 'Solicitud de Comerciante');
    $mail->addAddress('romanfoschi4@gmail.com', 'Municipalidad'); // Cambia esto al correo del destinatario

    // Contenido del correo
    $mail->Subject = 'Nueva Solicitud de Registro como Comerciante';
    $mail->Body    = "Nombre: $nombre_usuario\nApellido: $apellido_usuario\nDNI: $dni_usuario\nDescripción del comercio: $descripcion";

    // Enviar el correo
    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
}