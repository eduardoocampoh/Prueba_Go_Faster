<?php
session_start(); // Mantener session_start() por si se usa para otras cosas
require_once 'db_connection.php'; // Conexión a la base de datos
require_once 'cart_id.php';       // Para obtener $cart_id

if (isset($_POST['id_producto'])) {
    $product_id = (int)$_POST['id_producto'];
    
    // Eliminar el producto de la tabla cart_items
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?");
    $stmt->execute([$cart_id, $product_id]);

    // Opcional: Actualizar la fecha de actualización del carrito principal
    $stmt = $pdo->prepare("UPDATE carts SET updated_at = NOW() WHERE id = ?");
    $stmt->execute([$cart_id]);
}

// Redirigimos de vuelta al carrito
header("Location: carrito.php");
exit();
?>