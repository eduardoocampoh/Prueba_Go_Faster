<?php
session_start(); // Mantener session_start() para otras posibles variables de sesión (ej. id_Usuario)
require_once 'db_connection.php'; // Conexión a la base de datos
require_once 'cart_id.php';       // Para obtener $cart_id

// Inicializar/Crear el Carrito en la Base de Datos si no existe
$stmt = $pdo->prepare("SELECT id FROM carts WHERE id = ?");
$stmt->execute([$cart_id]);
$existing_cart = $stmt->fetch();

if (!$existing_cart) {
    $stmt = $pdo->prepare("INSERT INTO carts (id, created_at, updated_at) VALUES (?, NOW(), NOW())");
    $stmt->execute([$cart_id]);
}
// Si el usuario está autenticado, también podrías actualizar user_id aquí
if (isset($_SESSION['id_Usuario'])) {
    $stmt = $pdo->prepare("UPDATE carts SET user_id = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$_SESSION['id_Usuario'], $cart_id]);
}


// Recuperar los ítems del carrito para el cart_id actual
$stmt = $pdo->prepare("
    SELECT
        ci.product_id,
        ci.quantity,
        ci.price AS item_price,
        p.Nombres_prod AS product_name,
        p.Imagen_prod AS product_image
    FROM
        cart_items ci
    JOIN
        producto p ON ci.product_id = p.id_Producto
    WHERE
        ci.cart_id = ?
");
$stmt->execute([$cart_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular la cantidad total de items en el carrito para mostrar en el menú
$cantidad_carrito = 0;
foreach ($cart_items as $item) {
    $cantidad_carrito += $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Faster - Carrito de Compras</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="normalize.css">
    <style>
        .carrito_container {
            max-width: 800px;
            margin: 150px auto 50px; /* Margen superior para evitar el menú fijo */
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .carrito_item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .carrito_item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .carrito_info {
            flex-grow: 1;
            padding: 0 20px;
        }
        .carrito_info h3 {
            margin: 0 0 5px 0;
        }
        .carrito_info p {
            margin: 5px 0;
            color: #555;
        }
        .carrito_acciones {
            text-align: right;
            min-width: 120px;
        }
        .precio_subtotal {
            font-size: 1.2em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .btn_eliminar {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
        }
        .btn_eliminar:hover {
            background-color: #e60000;
        }
        .carrito_total_section {
            text-align: right;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
        }
        .total_texto {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
        }
        .btn_pagar {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 1.1em;
            font-weight: bold;
            margin-top: 15px;
            transition: background-color 0.3s;
        }
        .btn_pagar:hover {
            background-color: #45a049;
        }
        .carrito_vacio {
            text-align: center;
            padding: 40px;
            font-size: 1.2em;
            color: #777;
        }
        .carrito_vacio a {
            color: #FF978C;
            text-decoration: none;
            font-weight: bold;
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
            </ul>
        </nav>
    </menu>

    <div class="carrito_container">
        <h1 style="text-align: center; color: #333;">Tu Carrito de Compras</h1>
        
        <?php if(!empty($cart_items)): ?>
            
            <?php 
            $total_general = 0;
            foreach($cart_items as $item): 
                $subtotal = $item['item_price'] * $item['quantity'];
                $total_general += $subtotal;
            ?>
                <div class="carrito_item">
                    <img src="imagenes/<?php echo htmlspecialchars($item['product_image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                    
                    <div class="carrito_info">
                        <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                        <p>Precio unitario: $<?php echo number_format($item['item_price'], 0, ',', '.'); ?></p>
                        <p>Cantidad: <strong><?php echo $item['quantity']; ?></strong></p>
                    </div>
                    
                    <div class="carrito_acciones">
                        <div class="precio_subtotal">$<?php echo number_format($subtotal, 0, ',', '.'); ?></div>
                        
                        <!-- Formulario para eliminar el producto -->
                        <form action="eliminar_carrito.php" method="POST">
                            <input type="hidden" name="id_producto" value="<?php echo $item['product_id']; ?>">
                            <button type="submit" class="btn_eliminar">Eliminar</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="carrito_total_section">
                <div class="total_texto">Total a Pagar: $<?php echo number_format($total_general, 0, ',', '.'); ?></div>
                <!-- Aquí iría el enlace a tu pasarela de pago o confirmación de factura -->
                <a href="#" class="btn_pagar" onclick="alert('Función de pago en desarrollo. Total: $<?php echo number_format($total_general, 0, ',', '.'); ?>')">Proceder al Pago</a>
            </div>

        <?php else: ?>
            <div class="carrito_vacio">
                <p>Tu carrito está vacío en este momento.</p>
                <p><a href="productos.php">¡Ve a explorar nuestros productos!</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>