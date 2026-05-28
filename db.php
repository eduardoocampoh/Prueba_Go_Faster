<?php
$servidor = "localhost";
$usuario_db = "root";
$clave_db = "";
$nombre_base_de_datos = "prueba_go_faster";

$conexion = mysqli_connect($servidor, $usuario_db, $clave_db, $nombre_base_de_datos);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
