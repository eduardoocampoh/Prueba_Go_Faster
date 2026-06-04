<?php

// Define el nombre de la cookie para el ID del carrito
define('CART_COOKIE_NAME', 'cart_id');

// Define el tiempo de expiración de la cookie (ej. 30 días)
// 86400 segundos = 1 día
define('CART_COOKIE_EXPIRATION', time() + (86400 * 30));

/**
 * Genera un ID único para el carrito.
 * Utiliza random_bytes para mayor seguridad si está disponible,
 * de lo contrario, recurre a uniqid y md5.
 *
 * @return string Un ID único para el carrito.
 */
function generateUniqueCartId() {
    if (function_exists('random_bytes')) {
        // Genera 16 bytes aleatorios y los convierte a una cadena hexadecimal (32 caracteres)
        return bin2hex(random_bytes(16));
    } else {
        // Fallback para versiones antiguas de PHP o entornos sin random_bytes
        // Menos seguro pero proporciona un ID razonablemente único.
        return md5(uniqid(mt_rand(), true));
    }
}

// Verifica si la cookie cart_id ya existe en el navegador del usuario
if (!isset($_COOKIE[CART_COOKIE_NAME])) {
    // Si la cookie no existe, genera un nuevo ID único para el carrito
    $cart_id = generateUniqueCartId();

    // Establece el cart_id como una cookie en el navegador del usuario
    // 'expires' => CART_COOKIE_EXPIRATION: La cookie expirará después de 30 días.
    // 'path' => '/': La cookie estará disponible en todo el dominio.
    // 'domain' => '': La cookie será válida solo para el host actual.
    // 'secure' => isset($_SERVER['HTTPS']): La cookie solo se enviará a través de HTTPS si la conexión actual es segura.
    // 'httponly' => true: Evita que JavaScript acceda a la cookie, mejorando la seguridad contra ataques XSS.
    // 'samesite' => 'Lax': Ayuda a proteger contra ataques CSRF.
    setcookie(
        CART_COOKIE_NAME,
        $cart_id,
        [
            'expires' => CART_COOKIE_EXPIRATION,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]
    );
} else {
    // Si la cookie ya existe, utiliza su valor
    $cart_id = $_COOKIE[CART_COOKIE_NAME];
}

// La variable $cart_id ahora está disponible para ser utilizada en otras partes de tu aplicación PHP.
// Por ejemplo, usarías este $cart_id para consultar o almacenar datos del carrito en tu base de datos.

?>