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