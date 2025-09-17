<?php
include '../conexion.php';
include '../session_timeout.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registra tu cupon!</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="formbold-main-wrapper">
  <div class="formbold-form-wrapper">
    <form action="check/registrarcupon.php" method="POST" enctype="multipart/form-data">
      <div class="formbold-form-title">
        <h2>Subir Cupon</h2>
        <p>
          Rellena estos formularios con la informacion correspondiente
        </p>
      </div>

      <div class="formbold-mb-3">
        <div>
          <label for="titulo" class="formbold-form-label">Titulo</label>
          <input type="text" name="titulo" id="titulo" class="formbold-form-input"/>
        </div>
        <div>
          <label for="descripcion" class="formbold-form-label">Descripción</label>
          <input type="text" name="descripcion" id="descripcion" class="formbold-form-input"/>
        </div>
      </div>

      <div class="formbold-input-flex">
        <div>
          <label for="fecha_inicio" class="formbold-form-label">Fecha de inicio</label>
          <input type="date" name="fecha_inicio" id="fecha_inicio" class="formbold-form-input"/>
        </div>
        <div>
            <label for="fecha_fin" class="formbold-form-label">Fecha de fin</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="formbold-form-input"/>
          </div>
      </div>

      <div class="formbold-mb-3">
        <label for="stock" class="formbold-form-label">stock</label>
        <input type="number" name="stock" id="stock" class="formbold-form-input"/>
      </div>

      <!-- Campo de Imagen -->
      <div class="formbold-mb-3">
        <label for="imagen" class="formbold-form-label">Imagen del Cupón</label>
        <input type="file" name="imagen" id="imagen" class="formbold-form-input" required />
      </div>

      <button class="formbold-btn">Subir Cupon</button>
    </form>
  </div>
</div>

</body>
</html>
