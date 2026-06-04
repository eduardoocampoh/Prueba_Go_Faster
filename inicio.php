<?php
session_start();
require_once 'db_connection.php'; // Usar la nueva conexión PDO
require_once 'cart_id.php';       // Para obtener $cart_id

// Obtener 3 productos aleatorios para mostrar como destacados
$consulta_destacados = "SELECT p.*, r.Nombres_rest FROM producto p JOIN restaurante r ON p.id_restaurante = r.id_Restaurante ORDER BY RAND() LIMIT 4";
// Usar PDO para la consulta
$stmt_destacados = $pdo->query($consulta_destacados);
$productos_destacados = $stmt_destacados->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Go Faster - Inicio</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="normalize.css">
    <style>
        .bienvenida_usuario {
            padding: 20px;
            background: #fef1f0;
            text-align: center;
            border-bottom: 1px solid #FF978C;
        }
        .grid_destacados {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 40px 20px;
            max-width: 1200px;
            margin: auto;
        }
        .tarjeta_inicio {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 15px;
            text-align: center;
        }
        .imagen_inicio {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <menu>
        <div class="div_logo">    
            <a href="inicio.php"><IMG SRC="imagenes/go_faster.png" class="logo_gofaster" alt="Logo"></a>
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
                <li><a href="carrito.php">Carrito (<?php echo $cantidad_carrito; ?>)</a></li>
                <li><a href="#tiendas">Tiendas</a></li>
            </ul>
        </nav>
    </menu>

    <?php if(isset($_SESSION['Nombres_usua'])): ?>
        <div class="bienvenida_usuario">
            <h2>¡Hola, <?php echo htmlspecialchars($_SESSION['Nombres_usua']); ?>! ¿Qué se te antoja hoy?</h2>
        </div>
    <?php endif; ?>

    <header class="inicio_header">
        <div class="div_inicio">
            <h1>Bienvenido a la mejor experiencia de domicilios</h1>
            <p>Pide en tus restaurantes favoritos de Yarumal de forma rápida y segura.</p>
            
            <form action="buscar.php" method="get" class="form_busqueda_inicio">
                <input type="search" name="q" placeholder="¿Qué quieres comer?" class="input_busqueda1" required>
                <input type="submit" value="Buscar" class="input_busqueda2">
            </form>
        </div>
    </header>

    <section>
        <h2 style="text-align: center; margin-top: 50px;">Productos Destacados</h2>
        <div class="grid_destacados">
            <?php foreach($productos_destacados as $prod): ?>
                <div class="tarjet-inicio">
                    <img src="imagenes/<?php echo !empty($prod['Imagen_prod']) ? $prod['Imagen_prod'] : 'go_faster.png'; ?>" class="imagen_inicio">
                    <h3 style="margin: 10px 0;"><?php echo htmlspecialchars($prod['Nombres_prod']); ?></h3>
                    <p style="color: #FF978C; font-weight: bold;"><?php echo htmlspecialchars($prod['Nombres_rest']); ?></p>
                    <p style="font-weight: bold; font-size: 1.1em;">$<?php echo number_format($prod['Precio_prod'], 0, ',', '.'); ?></p>
                    <a href="productos.php" class="btn_agregar" style="display: inline-block; text-decoration: none; margin-top: 10px; padding: 5px 15px;">Pedir ahora</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer style="background: #333; color: white; padding: 40px 20px; text-align: center; margin-top: 50px;">
        <p>&copy; 2026 Go Faster - Todos los derechos reservados</p>
    </footer>

</body>
</html>
