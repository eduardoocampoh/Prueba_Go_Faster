<?php
session_start();
?>
<?php
require_once 'cart_id.php';

// La variable $cart_id está disponible y contiene el ID único del carrito.
// Puedes usar $cart_id para:
// 1. Consultar la base de datos para cargar los ítems del carrito asociados a este ID.
// 2. Guardar nuevos ítems o actualizaciones del carrito en la base de datos usando este ID.

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Faster - Ingresar</title>
    <meta name="author" content="Eduardo Ocampo Herrera"/>
    <meta name="description" content="Página de ingreso Go Faster"/>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="normalize.css">
    <script src="script.js"></script>
</head>
<body>
    <menu>
        <div class="div_logo">    
            <a href="inicio.php"><IMG SRC="imagenes/go_faster.png" class="logo_gofaster" alt="Logo página" ></a>
            <a href="inicio.php" class="nombrelogo"><h1><br>Go Faster<br></h1></a>
        </div>
        <nav>
            <ul>
                <?php if(isset($_SESSION['id_Usuario'])): ?>
                    <li><a href="cuenta_usuario.php">Mi Cuenta</a></li>
                    <li><a href="salir.php">Salir</a></li>
                <?php else: ?>
                    <li><a href="autenticar.php">Ingresar</a></li>
                    <li><a href="registro_usuario.php">Registrarse</a></li>
                <?php endif; ?>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="carrito.php">Carrito (<?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>)</a></li>
                <li><a href="#tiendas">Tiendas</a></li>
            </ul>
        </nav>
    </menu>
    <div id="usuario_existente">
        <section class="user_existente">
            <div class="imagen_user">
                <img src="imagenes/go_faster.png" class="img_user">
            </div>
                <form action="index.php" method="post" class="loguin">
                    <h1>Ingresa a tu usuario<i class="h1user"></i></h1>
                    <div class="ingreso_user">
                        <label for="usuario" class="form_user">Usuario</label>
                        <input type="text" name="usuario_user" id="usuario" class="form_input" placeholder="Ingresa tu usuario" required>
                    </div>
                    <div class="ingreso_user">
                        <label for="password" class="form_user">Contraseña</label>
                        <input type="password" name="Password_usua" id="password" class="form_input" placeholder="Ingresa tu contraseña" required>
                    </div>
                    <div class="ingreso_user">
                        <button type="submit" name="login" class="acceso_cuenta form-button boton_ingresar">Ingresar</button>
                    </div>
                    <div class="ingreso_user">
                        <a href="registro_usuario.php" class="acceso_cuenta form-button boton_registrar">Registrar</a>
                    </div>
                    <div class="ingreso_user">
                        <a href="#" class="form_olvido">Olvide la contraseña </a>
                    </div>
                </form>
        </section>
    </div>
</body>
</html>
