<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Editar Comercio</title>
    <link rel="stylesheet" href="styles.css">
    <link href="../indice/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <h2>Editar Comercio</h2>
        <form id="editarForm" method="post" action="check/editarcom.php" enctype="multipart/form-data">
            <div class="input-boxes">
                <div class="input-box">
                    <label for="nombre">Nombre del Comercio:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre del Comercio" required>
                </div>
                <div class="input-box">
                    <label for="categoria">Categoría:</label>
                    <select name="categoria" id="categoria" required>
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
                    <label for="localidad">Localidad:</label>
                    <select name="localidad" id="localidad" required>
                        <option value="">Seleccione una Localidad</option>
                        <option value="Miramar">Miramar</option>
                        <option value="Otamendi">Otamendi</option>
                        <option value="Mechongué">Mechongué</option>
                        <option value="Mar del sud">Mar del Sud</option>
                    </select>
                </div>
                <div class="input-box">
                    <label for="calle">Calle:</label>
                    <input type="text" id="calle" name="calle" placeholder="Calle" required>
                </div>
                <div class="input-box">
                    <label for="altura">Altura de Calle:</label>
                    <input type="text" id="altura" name="altura" placeholder="Altura de la Calle" required>
                </div>
                <div class="input-box">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="Teléfono" required>
                </div>
                <div class="input-box">
                    <label for="imagen">Actualizar Imagen:</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                </div>
                <div class="button input-box">
                    <input type="submit" value="Guardar Cambios">
                </div>
            </div>
        </form>
    </div>
</body>
</html>
