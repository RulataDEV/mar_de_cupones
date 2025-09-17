<?php
    session_start();
    include_once 'config.php'; // Incluir configuración global

    // Asegurar que LOGIN_PATH esté definido
    if (!defined('LOGIN_PATH')) {
        die("Error: Ruta de login no definida en la configuración.");
    }

    // Define el tiempo máximo de inactividad (en segundos)
    define('MAX_INACTIVITY', 300);

    if (isset($_SESSION['last_activity'])) {
        $inactiveTime = time() - $_SESSION['last_activity'];

        if ($inactiveTime > MAX_INACTIVITY) {
            // Limpieza segura de la sesión
            session_unset();
            session_destroy();

            // Regenerar el ID de sesión para mayor seguridad
            session_start();
            session_regenerate_id(true);

            // Redirigir al login con mensaje de sesión expirada
            header("Location: " . LOGIN_PATH . "?session=expired");
            exit();
        }
    }

    // Actualizar la última actividad
    $_SESSION['last_activity'] = time();
?>