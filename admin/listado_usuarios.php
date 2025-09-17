<?php
session_start();

include '../conexion.php';

// Verificar si el usuario ha iniciado sesión y obtener el ID
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../userlog/login.php");
    exit();
}

// Obtener el ID del usuario actual
$id_usuario = $_SESSION['id_usuario'];

// Comprobar si el usuario es administrador
$sql_admin_check = "SELECT admin FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql_admin_check);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($is_admin);
$stmt->fetch();
$stmt->close();

// Si el usuario no es administrador, redirigirlo a la página principal
if ($is_admin != 1) {
    header("Location: ../indice/index.php");
    exit();
}

// Actualizar el estado de comerciante
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['usuario_id'])) {
    $usuario_id = intval($_POST['usuario_id']);
    $comerciante = isset($_POST['comerciante']) ? 1 : 0;

    $update_sql = "UPDATE usuarios SET comerciante = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $comerciante, $usuario_id);

    if ($update_stmt->execute()) {
        // Opcional: Mensaje de éxito (puedes agregar un alert en el frontend)
    }
    $update_stmt->close();
}

// Variables para la búsqueda y el filtro
$search = '';
$comerciante_filter = null;

// Verificar si se ha enviado la búsqueda o el filtro
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['search'])) {
        $search = $_POST['search'];
    }

    if (isset($_POST['comerciante_filter'])) {
        $comerciante_filter = $_POST['comerciante_filter'] === '1' ? 1 : 0;
    }
}

// Consultar usuarios con los filtros y búsqueda
$sql = "SELECT id, nombre, apellido, correo, comerciante FROM usuarios WHERE 
        (id LIKE ? OR nombre LIKE ? OR apellido LIKE ? OR correo LIKE ?)";

if ($comerciante_filter !== null) {
    $sql .= " AND comerciante = ?";
}

$stmt = $conn->prepare($sql);
$search_param = "%$search%";
if ($comerciante_filter !== null) {
    $stmt->bind_param("ssssi", $search_param, $search_param, $search_param, $search_param, $comerciante_filter);
} else {
    $stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container mt-5">
        <div class="table-container">
            <h1>Listado de Usuarios</h1>
            <form method="POST" action="listado_usuarios.php" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Buscar por ID, Nombre, Apellido o Correo" value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" name="comerciante_filter" value="1" class="form-check-input" <?php echo ($comerciante_filter === 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label">Mostrar solo comerciantes</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    </div>
                </div>
            </form>

            <table class="table table-hover table-bordered text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Comerciante</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row["id"]); ?></td>
                                <td><?php echo htmlspecialchars($row["nombre"]); ?></td>
                                <td><?php echo htmlspecialchars($row["apellido"]); ?></td>
                                <td><?php echo htmlspecialchars($row["correo"]); ?></td>
                                <td>
                                    <form action="listado_usuarios.php" method="post" class="d-inline">
                                        <input type="hidden" name="usuario_id" value="<?php echo $row["id"]; ?>">
                                        <input type="checkbox" name="comerciante" value="1" <?php echo ($row["comerciante"] == 1) ? "checked" : ""; ?> class="form-check-input" onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td>
                                    <a href="editar_usuario.php?id=<?php echo $row["id"]; ?>" class="btn btn-edit">Editar</a>
                                    <a href="borrar_usuario.php?id=<?php echo $row["id"]; ?>" class="btn btn-delete" onclick="return confirm('¿Seguro que quieres borrar este usuario?');">Borrar</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='6'>No se encontraron usuarios.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>
