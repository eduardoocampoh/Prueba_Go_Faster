<?php
session_start();
require_once 'db_connection.php'; // Usar la nueva conexión PDO
require_once 'cart_id.php';       // Para obtener $cart_id

// Obtener todos los productos con sus nombres de restaurante
$consulta = "
    SELECT p.id_Producto, p.Nombres_prod, p.Caracteristicas_prod, p.Precio_prod, p.Imagen_prod, r.Nombres_rest 
    FROM producto p 
    JOIN restaurante r ON p.id_restaurante = r.id_Restaurante 
    ORDER BY r.Nombres_rest, p.Nombres_prod
";
// Usar PDO para la consulta
$stmt = $pdo->query($consulta);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Go Faster - Productos</title>
    <meta name="author" content="Eduardo Ocampo Herrera"/>
    <meta name="description" content="Catálogo de productos Go Faster"/>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="normalize.css">
    <style>
        .productos_grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }
        .producto_card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
        }
        .producto_card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        .producto_img_container {
            width: 100%;
            height: 180px;
            overflow: hidden;
            background-color: #f9f9f9;
        }
        .producto_img_container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .producto_info {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .producto_rest {
            font-size: 0.8em;
            color: #FF978C;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .producto_nombre {
            font-size: 1.25em;
            margin: 0 0 10px 0;
            color: #333;
        }
        .producto_desc {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.4;
            flex-grow: 1;
        }
        .producto_precio_row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }
        .producto_precio {
            font-size: 1.3em;
            font-weight: bold;
            color: #2c3e50;
        }
        .btn_agregar {
            background-color: #FF978C;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn_agregar:hover {
            background-color: #f34734;
        }
        .header_productos {
            text-align: center;
            padding: 40px 20px 20px;
            margin-top: 100px;
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

    <header class="header_productos">
        <h1>Nuestros Productos</h1>
        <p>Explora lo mejor de nuestros restaurantes asociados</p>
        
        <div class="busqueda" style="margin-top: 30px; display: flex; justify-content: center;">
            <form action="buscar.php" method="get" id="search-form" style="background: white; padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <fieldset style="border: none; margin: 0; padding: 0; display: flex; align-items: center; gap: 15px;">
                    <label for="search-input" style="font-weight: bold; color: #333;">¿Qué producto buscas?</label>
                    <input type="search" id="search-input" name="q" placeholder="Ej. hamburguesa, pollo..." required style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 300px; outline: none;">
                    <input type="submit" value="Buscar" style="background-color: #FF978C; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; transition: background 0.3s;">
                </fieldset>
            </form>
        </div>
    </header>

    <main class="productos_grid">
        <?php foreach($productos as $fila): ?>
            <div class="producto_card">
                <div class="producto_img_container">
                    <?php 
                        $img = !empty($fila['Imagen_prod']) ? $fila['Imagen_prod'] : 'go_faster.png';
                    ?>
                    <img src="imagenes/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($fila['Nombres_prod']); ?>">
                </div>
                <div class="producto_info">
                    <span class="producto_rest"><?php echo htmlspecialchars($fila['Nombres_rest']); ?></span>
                    <h2 class="producto_nombre"><?php echo htmlspecialchars($fila['Nombres_prod']); ?></h2>
                    <p class="producto_desc"><?php echo htmlspecialchars($fila['Caracteristicas_prod']); ?></p>
                    <div class="producto_precio_row">
                        <span class="producto_precio">$<?php echo number_format($fila['Precio_prod'], 0, ',', '.'); ?></span>
                        
                        <form action="agregar_carrito.php" method="POST" style="margin: 0;">
                            <input type="hidden" name="id_producto" value="<?php echo $fila['id_Producto']; ?>">
                            <button type="submit" class="btn_agregar">Agregar</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <script src="script.js"></script>
</body>
</html>
