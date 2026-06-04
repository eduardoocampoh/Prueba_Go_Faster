<?php
session_start();
require_once 'db_connection.php'; // Usar la nueva conexión PDO
require_once 'cart_id.php';       // Para obtener $cart_id

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['id_Usuario'])) {
    header("Location: autenticar.php");
    exit();
}

$id_usuario = $_SESSION['id_Usuario'];

// Obtener detalles adicionales del usuario usando PDO
$consulta_usuario = "SELECT Email_usua, Telefono_usua, Direccion_usua, Ciudad_usua, Nombres_usua, Apellidos_usua, Foto_usua FROM usuario WHERE id_Usuario = ?";
$stmt_usuario = $pdo->prepare($consulta_usuario);
$stmt_usuario->execute([$id_usuario]);
$datos_usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);

if (!$datos_usuario) {
    // Manejar caso donde el usuario no se encuentra (ej. redirigir o mostrar error)
    header("Location: salir.php"); // O a una página de error
    exit();
}

$nombre_completo = $datos_usuario['Nombres_usua'] . " " . $datos_usuario['Apellidos_usua'];
$email = $datos_usuario['Email_usua'];
$telefono = $datos_usuario['Telefono_usua'];
$direccion = $datos_usuario['Direccion_usua'];
$ciudad = $datos_usuario['Ciudad_usua'];
$foto = !empty($datos_usuario['Foto_usua']) ? $datos_usuario['Foto_usua'] : 'usuario.jpg';

// Obtener los últimos 2 productos comprados (Favoritos) usando PDO
$consulta_favoritos = "
    SELECT p.Nombres_prod, p.id_Producto, p.Imagen_prod
    FROM factura f 
    JOIN producto p ON f.id_Producto = p.id_Producto 
    WHERE f.id_Usuario = ? 
    ORDER BY f.id_Factura DESC 
    LIMIT 2
";
$stmt_favoritos = $pdo->prepare($consulta_favoritos);
$stmt_favoritos->execute([$id_usuario]);
$favoritos = $stmt_favoritos->fetchAll(PDO::FETCH_ASSOC);

// Recuperar los ítems del carrito para el cart_id actual para calcular la cantidad
$stmt_cart = $pdo->prepare("SELECT quantity FROM cart_items WHERE cart_id = ?");
$stmt_cart->execute([$cart_id]);
$cart_items_quantities = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

$cantidad_carrito = 0;
foreach ($cart_items_quantities as $item) {
    $cantidad_carrito += $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Faster - Mi Cuenta</title>
    <meta name="author" content="Eduardo Ocampo Herrera"/>
    <meta name="description" content="página sobre la Aplición Web de servicio de domicilios Go Faster "/>
    <meta name="keywords" content="comidas, comidas rapidas, domicilios, pago en linea, restaurante, bebidas, tiendas" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="normalize.css">
    <script src="script.js"></script>
    <style>
        .detalles_perfil {
            margin-top: 10px;
            font-size: 0.9em;
            color: #555;
            text-align: left;
        }
        .detalles_perfil p {
            margin: 5px 0;
        }
        .favorito_item {
            display: inline-block;
            width: 45%;
            margin: 0 2%;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <menu>
        <div class="div_logo">    
            <a href="inicio.php"><IMG SRC="imagenes/go_faster.png" class="logo_gofaster" alt="Logo página" ></a>
            <a href="inicio.php" class="nombrelogo"><h1 ><br>Go Faster<br></h1></a>
        </div>
        <nav>
            <ul>
                <li><a href="salir.php">Salir</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="carrito.php">Carrito (<?php echo $cantidad_carrito; ?>)</a></li>
                <li><a href="#tiendas">Tiendas</a></li>
                <li><a href="#Buscar">Buscar</a></li>
            </ul>
        </nav>
    </menu>

    <div id="div_productos">
        <div id="opciones">
            <div class="busqueda">
                <H1 class="Busqueda"><b>Que producto buscas</b></H1>
                <form action="buscar.php" method="get" id="search-form">
                <fieldset>
                    <label for="search-input">Buscar:</label>
                    <input type="search" id="search-input" class="input_busqueda1" name="q" placeholder="Ingresa tu búsqueda...">
                    <input type="submit" value="Buscar" class="input_busqueda2">
                </fieldset>
                </form>
            </div>
            <div class="opciones">
                <H1 class="h1_opcion"><b>Mis Datos y Actividad</b></H1> 
            </div>
        </div>    
    </div>

    <div id="centro_cuenta">
        <div id="cuenta_user">
            <div class="cuenta_user">
                    <div class="img_cuenta_usuario">
                        <img src="imagenes/<?php echo htmlspecialchars($foto); ?>" class="imagen_cuenta" alt="Usuario"></img>
                    </div>
                    <div class="img_cuenta_usuario">
                        <H1 class="cuenta_usuario"><b><?php echo htmlspecialchars($nombre_completo); ?></b></H1>
                        <div class="detalles_perfil">
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono); ?></p>
                            <p><strong>Dirección:</strong> <?php echo htmlspecialchars($direccion); ?> (<?php echo htmlspecialchars($ciudad); ?>)</p>
                        </div>
                        <h2 class="pagar"><b>Realizar compra</b></h2>
                    </div>      
            </div>
            <div id="der_favoritos">
                <H2 style="text-align: center; margin-bottom: 20px;"><b>Mis Últimas Compras</b></H2>
                <div class="favoritos_user">
                    <?php if (count($favoritos) > 0): ?>
                        <?php foreach ($favoritos as $fav): ?>
                            <div class="favorito_item">
                                <?php 
                                    $img = !empty($fav['Imagen_prod']) ? $fav['Imagen_prod'] : 'go_faster.png';
                                ?>
                                <img src="imagenes/<?php echo htmlspecialchars($img); ?>" class="favorito" alt="<?php echo $fav['Nombres_prod']; ?>"></img>
                                <h2 class="h2_user"><b><?php echo htmlspecialchars($fav['Nombres_prod']); ?></b></h2> 
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: #777;">Aún no has realizado compras.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
