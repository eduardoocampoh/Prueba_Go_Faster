<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $nombre_usuario = $_POST['usuario_user'];
    $contrasena = $_POST['Password_usua'];

    // Consulta para obtener los datos del usuario
    $consulta = "SELECT id_Usuario, Password_usua, Nombres_usua, Apellidos_usua, Foto_usua FROM usuario WHERE Nombres_usua = ?";
    $sentencia = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($sentencia, "s", $nombre_usuario);
    mysqli_stmt_execute($sentencia);
    $resultado = mysqli_stmt_get_result($sentencia);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        // Soporte para ambos: hash (seguro) y texto plano (antiguo)
        if (password_verify($contrasena, $fila['Password_usua']) || $contrasena === $fila['Password_usua']) {
            $_SESSION['id_Usuario'] = $fila['id_Usuario'];
            $_SESSION['Nombres_usua'] = $fila['Nombres_usua'];
            $_SESSION['Apellidos_usua'] = $fila['Apellidos_usua'];
            $_SESSION['Foto_usua'] = $fila['Foto_usua'];
            
            header("Location: inicio.php");
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.history.back();</script>";
    }
    mysqli_stmt_close($sentencia);
}
?>