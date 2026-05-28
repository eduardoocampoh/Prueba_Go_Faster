<?php
include 'db.php';

if (isset($_POST['registrar'])) {
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $usuario = $_POST['nombre_usuario'];
    $password = $_POST['password'];
    $confirmar_password = $_POST['confirmacion_password'];

    if ($password !== $confirmar_password) {
        echo "<script>alert('Las contraseñas no coinciden'); window.history.back();</script>";
        exit();
    }

    // Encriptación de contraseña para mayor seguridad
    $password_encriptada = password_hash($password, PASSWORD_DEFAULT);
    
    // El campo Nombres_usua se usa como nombre de usuario para el login
    // Se incluye el campo Foto_usua con un valor por defecto
    $consulta = "INSERT INTO usuario (Nombres_usua, Apellidos_usua, Email_usua, Telefono_usua, Direccion_usua, Password_usua, Banco_usua, Ciudad_usua, Fecha_Nacimiento_usua, Foto_usua) VALUES (?, ?, ?, ?, ?, ?, 'N/A', 'N/A', '2000-01-01', 'usuario.jpg')";
    
    $sentencia = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($sentencia, "ssssss", $usuario, $apellidos, $correo, $telefono, $direccion, $password_encriptada);

    if (mysqli_stmt_execute($sentencia)) {
        echo "<script>alert('Usuario registrado exitosamente'); window.location='autenticar.php';</script>";
    } else {
        echo "<script>alert('Error al registrar: " . mysqli_error($conexion) . "'); window.history.back();</script>";
    }
    mysqli_stmt_close($sentencia);
}
?>
