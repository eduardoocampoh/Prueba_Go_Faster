<?php

// Configuración de la base de datos
define('DB_HOST', 'localhost'); // O la IP de tu servidor de base de datos
define('DB_NAME', 'prueba_go_faster'); // El nombre de tu base de datos
define('DB_USER', 'root');     // Tu usuario de base de datos
define('DB_PASS', '');         // Tu contraseña de base de datos (vacía por defecto en XAMPP)

// Opciones de PDO para una conexión segura y robusta
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en caso de errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve los resultados como arrays asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Deshabilita la emulación de sentencias preparadas para mayor seguridad
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"     // Asegura la codificación UTF-8
];

// Intentar establecer la conexión a la base de datos
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // En caso de error en la conexión, mostrar un mensaje y terminar la ejecución
    // En un entorno de producción, no deberías mostrar el error directamente al usuario
    // sino registrarlo y mostrar un mensaje genérico.
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// La variable $pdo ahora contiene el objeto de conexión a la base de datos.
// Puedes usar $pdo para ejecutar consultas preparadas.

?>