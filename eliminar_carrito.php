<?php
session_start();

if (isset($_POST['id_producto']) && isset($_SESSION['carrito'])) {
    $id_producto = $_POST['id_producto'];
    
    // Si el producto existe en el carrito, lo eliminamos
    if (isset($_SESSION['carrito'][$id_producto])) {
        unset($_SESSION['carrito'][$id_producto]);
    }
}

// Redirigimos de vuelta al carrito
header("Location: carrito.php");
exit();
?>
<?php
require_once 'cart_id.php';

// La variable $cart_id está disponible y contiene el ID único del carrito.
// Puedes usar $cart_id para:
// 1. Consultar la base de datos para cargar los ítems del carrito asociados a este ID.
// 2. Guardar nuevos ítems o actualizaciones del carrito en la base de datos usando este ID.

?>