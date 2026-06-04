<?php
session_start(); // Mantener session_start() por si se usa para otras cosas (ej. id_Usuario)
require_once 'db_connection.php'; // Conexión a la base de datos
require_once 'cart_id.php';       // Para obtener $cart_id

// Verifica si se recibió la información del producto
if (isset($_POST['id_producto'])) {
    
    $product_id = (int)$_POST['id_producto'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Permitir cantidad variable, por defecto 1

    // 1. Asegurar que el carrito exista en la tabla 'carts'
    $stmt = $pdo->prepare("SELECT id FROM carts WHERE id = ?");
    $stmt->execute([$cart_id]);
    $existing_cart = $stmt->fetch();

    if (!$existing_cart) {
        $stmt = $pdo->prepare("INSERT INTO carts (id, created_at, updated_at) VALUES (?, NOW(), NOW())");
        $stmt->execute([$cart_id]);
    }
    // Si el usuario está autenticado, actualizar user_id en la tabla carts
    if (isset($_SESSION['id_Usuario'])) {
        $stmt = $pdo->prepare("UPDATE carts SET user_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$_SESSION['id_Usuario'], $cart_id]);
    }

    // 2. Obtener el precio actual del producto de la tabla 'producto'
    $stmt = $pdo->prepare("SELECT Precio_prod FROM producto WHERE id_Producto = ?");
    $stmt->execute([$product_id]);
    $product_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product_data) {
        $price = $product_data['Precio_prod'];

        // 3. Verificar si el producto ya está en el carrito para este cart_id
        $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?");
        $stmt->execute([$cart_id, $product_id]);
        $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_item) {
            // Si el producto ya existe, actualizar la cantidad
            $new_quantity = $existing_item['quantity'] + $quantity;
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ?, added_at = NOW() WHERE id = ?");
            $stmt->execute([$new_quantity, $existing_item['id']]);
        } else {
            // Si el producto no existe, insertarlo como un nuevo ítem
            $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, price, added_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$cart_id, $product_id, $quantity, $price]);
        }

        // 4. Actualizar la fecha de actualización del carrito principal
        $stmt = $pdo->prepare("UPDATE carts SET updated_at = NOW() WHERE id = ?");
        $stmt->execute([$cart_id]);

        // Redirigir de vuelta a la página anterior
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        // Manejar error: Producto no encontrado en la base de datos
        // Podrías redirigir a una página de error o mostrar un mensaje
        header('Location: inicio.php?error=producto_no_encontrado');
        exit();
    }
} else {
    // Si no llegaron datos necesarios, redirigir al inicio
    header('Location: inicio.php');
    exit();
}?>