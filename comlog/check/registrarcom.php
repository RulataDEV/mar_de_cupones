<?php
require '../../conexion.php'; 
include '../../session_timeout.php';
header('Content-Type: application/json');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['error' => 'Error, el usuario no está autenticado.']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Verificar si el usuario tiene permisos de comerciante
$query = "SELECT comerciante FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['error' => 'Error en la consulta SQL: ' . $conn->error]);
    exit;
}
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Usuario no encontrado en la base de datos.']);
    exit;
}

// Validar campos requeridos
$requiredFields = ['nombre', 'categoria', 'localidad', 'calle', 'altura', 'telefono'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['error' => "El campo $field es obligatorio."]);
        exit;
    }
}

$nombre = $_POST['nombre'];
$categoria = strtolower($_POST['categoria']); // Convertir categoría a minúsculas
$localidad = $_POST['localidad'];
$calle = $_POST['calle'];
$altura = $_POST['altura'];
$telefono = $_POST['telefono'];

// Validación de imagen
$uploadDir = "../../uploads/";
$defaultImagesDir = "../default-images/"; // Carpeta donde están las imágenes por defecto
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

        // Mover archivo subido
        if (!move_uploaded_file($fileTmpPath, $physicalFilePath)) {
            $uniqueFileName = null; // Falló la subida, usar imagen predeterminada
        }
    }
}

// Usar imagen predeterminada si no se subió ninguna imagen válida
if ($uniqueFileName === null) {
    $defaultImagePath = $defaultImagesDir . $categoria . ".png";
    $uniqueFileName = file_exists($defaultImagePath) ? $defaultImagePath : $defaultImagesDir . "default.png";
}

// Guardar la URL en la base de datos
$urlForDatabase = str_replace("../../", "../", $uploadDir) . $uniqueFileName;

// Insertar el comercio en la base de datos
$insert_query = "INSERT INTO comercios (id_usuario, nombre, categoria, localidad, calle, altura, telefono, imagen_url) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_query);
if (!$insert_stmt) {
    echo json_encode(['error' => 'Error en la consulta SQL: ' . $conn->error]);
    exit;
}

$insert_stmt->bind_param("issssiss", $id_usuario, $nombre, $categoria, $localidad, $calle, $altura, $telefono, $urlForDatabase);

if ($insert_stmt->execute()) {
    echo json_encode(['success' => 'Comercio registrado correctamente.']);
} else {
    echo json_encode(['error' => 'Error al registrar el comercio en la base de datos.']);
}

// Cerrar conexiones
$insert_stmt->close();
$stmt->close();
$conn->close();
?>