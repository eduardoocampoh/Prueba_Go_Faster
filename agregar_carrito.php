<?php
session_start();

// Verifica si se recibió la información del producto
if (isset($_POST['id_producto']) && isset($_POST['nombre']) && isset($_POST['precio'])) {
    
    $id_producto = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $imagen = isset($_POST['imagen']) ? $_POST['imagen'] : 'go_faster.png';
    $cantidad = 1; // Por defecto agrega 1

    // Si el carrito no existe en la sesión, lo creamos como un arreglo vacío
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }

    // Si el producto ya está en el carrito, le sumamos 1 a la cantidad
    if (isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto]['cantidad'] += $cantidad;
    } else {
        // Si no está, lo agregamos como un nuevo elemento
        $_SESSION['carrito'][$id_producto] = array(
            'nombre' => $nombre,
            'precio' => $precio,
            'imagen' => $imagen,
            'cantidad' => $cantidad
        );
    }

    // Redirigir de vuelta a la página anterior (productos.php o buscar.php)
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    // Si no llegaron datos, enviarlo al inicio
    header('Location: inicio.php');
    exit();
}
?>