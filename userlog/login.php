<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      #mensaje {
        height: 20px;
        font-size: 14px;
        margin-bottom: 15px;
        transition: color 0.3s ease;
      }
    </style>
    <script>
      function verificarContraseña() {
        var password = document.getElementById("pass").value;
        var confirmPassword = document.getElementById("confirm_pass").value;

        if (password === confirmPassword) {
          document.getElementById("mensaje").style.color = "green";
          document.getElementById("mensaje").innerHTML = "✔ Contraseña correcta";
        } else {
          document.getElementById("mensaje").style.color = "red";
          document.getElementById("mensaje").innerHTML = "✖ Contraseña incorrecta";
        }
      }
      window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');

        if (error === 'dni') {
            alert('Este DNI ya está registrado.');
        } else if (error === 'correo') {
            alert('Este correo ya está registrado.');
        }
    };
    </script>
  </head>
  <body>
    
    <div class="container">
      <div>
        <?php if (isset($_GET['session']) && $_GET['session'] === 'expired'): ?>
          <p class="alert alert-warning">Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.</p>
        <?php endif; ?>
      </div>
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
            <div class="title">Iniciar Sesión</div>
            <form action="check/checklogin.php" method="post">
              <div class="input-boxes">
                <div class="input-box">
                  <i class="fas fa-address-card"></i>
                  <input type="text" name="dni" placeholder="Ingresa tu DNI" required>
                </div>
                <div class="input-box">
                  <i class="fas fa-lock"></i>
                  <input type="password" name="pass" placeholder="Ingresa tu contraseña" required>
                </div>
                <div class="button input-box">
                  <input type="submit" value="Ingresar!">
                </div>
                <div class="text sign-up-text">¿No tienes cuenta? <label for="flip">Regístrate acá!</label></div>
              </div>
            </form>
          </div>

          <div class="signup-form">
            <div class="title">Registrarse</div>
            <form action="check/checkregister.php" method="post">
              <div class="input-boxes">
                <div class="input-box">
                  <i class="fas fa-address-book"></i>
                  <input type="text" name="name" placeholder="Ingresa tu nombre" required>
                </div>
                <div class="input-box">
                  <i class="fas fa-address-book"></i>
                  <input type="text" name="ape" placeholder="Ingresa tu apellido" required>
                </div>
                <div class="input-box">
                  <i class="fas fa-envelope"></i>
                  <input type="email" name="E-mail" placeholder="Ingresa tu E-mail" required>
                </div>
                <div class="input-box">
                  <i class="fas fa-address-card"></i>
                  <input type="text" name="dni" placeholder="Ingresa tu DNI" required>
                </div>
                <div class="input-box">
                  <i class="fas fa-lock"></i>
                  <input type="password" id="pass" name="pass" placeholder="Ingresa una contraseña" required>
                </div>
                <div class="input-box">
                  <i class="fas fa-lock"></i>
                  <input type="password" id="confirm_pass" placeholder="Confirma la contraseña" required oninput="verificarContraseña()">
                </div>
                <div id="mensaje" style="margin-bottom: 15px;"></div>

                <div class="button input-box">
                  <input type="submit" value="Registrarse">
                </div>
                <div class="text sign-up-text">¿Ya tienes una cuenta? <label for="flip">Inicia sesión</label></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
