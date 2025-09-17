<?php

// Habilitar la visualización de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir el archivo de conexión a la base de datos
include('../../conexion.php');
include '../../session_timeout.php';


// Obtener el ID del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];

// Comprobar si el usuario es comerciante
$sql_com_check = "SELECT comerciante FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql_com_check);
if (!$stmt) {
    die("Error al preparar la consulta de comerciante: " . $conn->error);
}
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($is_comerciante);
$stmt->fetch();
$stmt->close();

// Verificar si el usuario es un comerciante
if ($is_comerciante != 1) {
    echo '<script>alert("Solo los comerciantes pueden registrar cupones"); window.location.href="../index.php";</script>';
    exit();
}

// Validar que los datos del formulario se hayan enviado
if (isset($_POST['titulo'], $_POST['fecha_inicio'], $_POST['fecha_fin'], $_POST['stock'], $_POST['descripcion'])) {
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $fecha_inicio = mysqli_real_escape_string($conn, $_POST['fecha_inicio']);
    $fecha_fin = mysqli_real_escape_string($conn, $_POST['fecha_fin']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);

    // Validación de imagen
    $maxFileSize = 2 * 1024 * 1024; // 2 MB
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        die("Error al subir la imagen.");
    }

    $fileTmpPath = $_FILES['imagen']['tmp_name'];
    $fileName = $_FILES['imagen']['name'];
    $fileSize = $_FILES['imagen']['size'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validaciones del archivo
    if ($fileSize > $maxFileSize) {
        die("El archivo es demasiado grande. Tamaño máximo: 2 MB.");
    }

    if (!in_array($fileExtension, $allowedExtensions)) {
        die("Tipo de archivo no permitido. Solo se permiten: " . implode(", ", $allowedExtensions));
    }

    $check = getimagesize($fileTmpPath);
    if ($check === false) {
        die("El archivo no es una imagen válida.");
    }

    // Rutas para la imagen
    $uploadDir = "../../uploads/";
    $uniqueFileName = uniqid() . "_" . basename($fileName);
    $physicalFilePath = $uploadDir . $uniqueFileName; // Ruta física para guardar la imagen
    $urlForDatabase = "../uploads/" . $uniqueFileName; // URL relativa para almacenar en la base de datos

    // Crear el directorio si no existe
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            die("Error al crear el directorio de subidas.");
        }
    }

    // Mover la imagen al directorio de subidas
    if (!move_uploaded_file($fileTmpPath, $physicalFilePath)) {
        die("Error al guardar la imagen en el servidor.");
    }

    // Recuperar el ID del comercio asociado al usuario
    $sql_comercio = "SELECT id, categoria FROM comercios WHERE id_usuario = ?";
    $stmt_comercio = $conn->prepare($sql_comercio);
    if (!$stmt_comercio) {
        die("Error al preparar la consulta de comercio: " . $conn->error);
    }
    $stmt_comercio->bind_param("i", $id_usuario);
    $stmt_comercio->execute();
    $stmt_comercio->bind_result($id_comercio, $categoria_com);
    $stmt_comercio->fetch();
    $stmt_comercio->close();

    if (!$id_comercio) {
        die("Error: El usuario no tiene un comercio asociado. ID de usuario: $id_usuario");
    }

    // Insertar el cupón en la base de datos
    $sql = "INSERT INTO cupones (id_comercio, categoria_comercio, titulo, fecha_inicio, fecha_fin, descripcion, stock, imagen_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error al preparar la consulta de inserción: " . $conn->error);
    }
    $stmt->bind_param("isssssis", $id_comercio, $categoria_com, $titulo, $fecha_inicio, $fecha_fin, $descripcion, $stock, $urlForDatabase);

    if ($stmt->execute()) {
        echo "Cupón registrado exitosamente.";
    } else {
        echo "Error al registrar el cupón: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Faltan datos del formulario.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
