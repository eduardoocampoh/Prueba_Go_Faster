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
    <title>Go Faster - Registro</title>
    <meta name="author" content="Eduardo Ocampo Herrera"/>
    <meta name="description" content="Página de registro de usuario Go Faster"/>
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
    <div id="div_padre">
        <div id="div_section">
            <div id="id_h1">
                <div class="div_h1">
                    <h1>Creación de usuario.</h1>
                </div>
            </div>
            <section id="usuarioform">
                <form action="registro.php" method="post" class="formusuario">
                    <div class="div_izq">
                        <h2>Datos personales<i class="h2form"></i></h2>
                        <div class="divform">
                            <label for="documento" class="formlabel">Documento</label>
                            <input type="number" name="documento" required id="documento" class="forminput" placeholder="Número identificación">
                        </div>
                        <div class="divform">
                            <label for="nombres" class="formlabel">Nombres</label>
                            <input type="text" name="nombres" required id="nombres" class="forminput" placeholder="Nombres usuario">
                        </div>
                        <div class="divform">
                            <label for="apellidos" class="formlabel">Apellidos</label>
                            <input type="text" name="apellidos" required id="apellidos" class="forminput" placeholder="Apellidos usuario">
                        </div>
                        <div class="divform">
                            <label for="correo" class="formlabel">Correo electrónico</label>
                            <input type="email" name="correo" required id="correo" class="forminput" placeholder="Correo electrónico">
                        </div>
                        <div class="divform">
                            <label for="confirmacion" class="formlabel">Confirmación correo electrónico</label>
                            <input type="email" name="confirmacion" required id="confirmacion" class="forminput" placeholder="Confirmación correo electrónico">
                        </div>
                        <div class="divform">
                            <label for="direccion" class="formlabel">Dirección</label>
                            <input type="text" name="direccion" required id="direccion" class="forminput" placeholder="Dirección">
                        </div>
                        <div class="divform">
                            <label for="telefono" class="formlabel">Teléfono</label>
                            <input type="number" name="telefono" required id="telefono" class="forminput" placeholder="Número teléfonico">
                        </div>
                    </div>
                    <div class="div_der">
                        <h2>Datos de acceso<i class="h2form"></i></h2>
                            <div class="divform">
                                <label for="nombre_usuario" class="formlabel">Nombre usuario</label>
                                <input type="text" name="nombre_usuario" required id="nombre_usuario" class="forminput" placeholder="Usuario">
                            </div>
                            <div class="divform">
                                <label for="password" class="formlabel">Contraseña</label>
                                <input type="password" name="password" required id="password" class="forminput" placeholder="Ingresa tu contraseña">
                            </div>
                            <div class="divform">
                                <label for="confirmacion_password" class="formlabel">Confirmación contraseña</label>
                                <input type="password" name="confirmacion_password" required id="confirmacion_password" class="forminput" placeholder="Confirmación contraseña">
                            </div>
                            <div class="divform">
                                <button type="submit" name="registrar" class="crear_user form-button botonregistrar">Registrar</button>
                            </div>
                            <div class="divform">
                                <a href="autenticar.php" class="crear_user form-button botoningresar">Volver al Ingreso</a>
                            </div>
                    </div>      
                </form>
            </section>
        </div>
    </div>
</body>
</html>
