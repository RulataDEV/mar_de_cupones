<?php
require '../../conexion.php';
include '../../session_timeout.php';
header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['error' => 'Error, el usuario no está autenticado.']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Validar campos requeridos
$requiredFields = ['nombre', 'categoria', 'localidad', 'calle', 'altura', 'telefono'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['error' => "El campo $field es obligatorio."]);
        exit;
    }
}

// Capturar datos del formulario
$nombre = $_POST['nombre'];
$categoria = strtolower($_POST['categoria']);
$localidad = $_POST['localidad'];
$calle = $_POST['calle'];
$altura = $_POST['altura'];
$telefono = $_POST['telefono'];

// Validación de imagen
$uploadDir = "../../uploads/";
$uniqueFileName = null;

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['imagen']['tmp_name'];
    $fileName = $_FILES['imagen']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExtension, $allowedExtensions)) {
        $uniqueFileName = uniqid() . "_" . basename($fileName);
        $physicalFilePath = $uploadDir . $uniqueFileName;

        // Crear directorio si no existe
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!move_uploaded_file($fileTmpPath, $physicalFilePath)) {
            echo json_encode(['error' => 'Error al subir la imagen.']);
            exit;
        }
    }
}

// Guardar la URL en la base de datos si se subió una imagen nueva
$urlForDatabase = $uniqueFileName ? str_replace("../../", "../", $uploadDir) . $uniqueFileName : null;

// Actualizar el comercio en la base de datos
$update_query = "UPDATE comercios SET nombre = ?, categoria = ?, localidad = ?, calle = ?, altura = ?, telefono = ?, imagen_url = IFNULL(?, imagen_url) WHERE id_usuario = ?";
$update_stmt = $conn->prepare($update_query);

if (!$update_stmt) {
    echo json_encode(['error' => 'Error en la consulta SQL: ' . $conn->error]);
    exit;
}

$update_stmt->bind_param("ssssissi", $nombre, $categoria, $localidad, $calle, $altura, $telefono, $urlForDatabase, $id_usuario);

if ($update_stmt->execute()) {
    echo json_encode(['success' => 'Comercio actualizado correctamente.']);
} else {
    echo json_encode(['error' => 'Error al actualizar el comercio.']);
}

// Cerrar conexión
$update_stmt->close();
$conn->close();
?>
