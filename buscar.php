<?php
session_start();
include 'db.php';

$termino_busqueda = isset($_GET['q']) ? $_GET['q'] : '';
$resultados = [];

if (!empty($termino_busqueda)) {
    $parametro = "%" . $termino_busqueda . "%";
    
    // Consulta mejorada uniendo con restaurante para mostrar el origen
    $consulta = "
        SELECT p.*, r.Nombres_rest 
        FROM producto p 
        JOIN restaurante r ON p.id_restaurante = r.id_Restaurante 
        WHERE p.Nombres_prod LIKE ? OR p.Caracteristicas_prod LIKE ?
    ";
    
    $sentencia = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($sentencia, "ss", $parametro, $parametro);
    mysqli_stmt_execute($sentencia);
    $datos_busqueda = mysqli_stmt_get_result($sentencia);
    
    while ($resultado = mysqli_fetch_assoc($datos_busqueda)) {
        $resultados[] = $resultado;
    }
    mysqli_stmt_close($sentencia);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Faster - Buscar</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="normalize.css">
    <style>
        .grid_resultados {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }
        .tarjeta_producto {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
        }
        .contenedor_img {
            width: 100%;
            height: 180px;
            overflow: hidden;
        }
        .contenedor_img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .info_producto {
            padding: 15px;
            flex-grow: 1;
        }
        .precio {
            font-size: 1.3em;
            font-weight: bold;
            color: #2c3e50;
        }
        .header_busqueda {
            text-align: center;
            padding: 120px 20px 20px;
        }
    </style>
</head>
<body>
    <menu>
        <div class="div_logo">    
            <a href="inicio.php"><IMG SRC="imagenes/go_faster.png" class="logo_gofaster" alt="Logo"></a>
            <a href="inicio.php" class="nombrelogo"><h1><br>Go Faster<br></h1></a>
        </div>
        <nav>
            <ul>
                <?php if(isset($_SESSION['id_Usuario'])): ?>
                    <li><a href="cuenta_usuario.php">Mi Cuenta</a></li>
                    <li><a href="salir.php">Salir</a></li>
                <?php else: ?>
                    <li><a href="autenticar.php">Ingresar</a></li>
                <?php endif; ?>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="carrito.php">Carrito (<?php echo isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0; ?>)</a></li>
            </ul>
        </nav>
    </menu>

    <div class="header_busqueda">
        <h1>Resultados para: "<?php echo htmlspecialchars($termino_busqueda); ?>"</h1>
        <form action="buscar.php" method="get" style="margin-top: 20px;">
            <input type="search" name="q" value="<?php echo htmlspecialchars($termino_busqueda); ?>" class="input_busqueda1" required>
            <input type="submit" value="Buscar" class="input_busqueda2">
        </form>
    </div>

    <main class="grid_resultados">
        <?php if (count($resultados) > 0): ?>
            <?php foreach ($resultados as $prod): ?>
                <div class="tarjeta_producto">
                    <div class="contenedor_img">
                        <?php 
                            $img = !empty($prod['Imagen_prod']) ? $prod['Imagen_prod'] : 'go_faster.png';
                        ?>
                        <img src="imagenes/<?php echo htmlspecialchars($img); ?>" alt="Imagen de producto">
                    </div>
                    <div class="info_producto">
                        <span style="color: #FF978C; font-weight: bold; font-size: 0.8em;"><?php echo htmlspecialchars($prod['Nombres_rest']); ?></span>
                        <h2 style="margin: 5px 0;"><?php echo htmlspecialchars($prod['Nombres_prod']); ?></h2>
                        <p style="font-size: 0.9em; color: #666;"><?php echo htmlspecialchars($prod['Caracteristicas_prod']); ?></p>
                        <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center;">
                            <span class="precio">$<?php echo number_format($prod['Precio_prod'], 0, ',', '.'); ?></span>
                            
                            <form action="agregar_carrito.php" method="POST" style="margin: 0;">
                                <input type="hidden" name="id_producto" value="<?php echo $prod['id_Producto']; ?>">
                                <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($prod['Nombres_prod']); ?>">
                                <input type="hidden" name="precio" value="<?php echo $prod['Precio_prod']; ?>">
                                <input type="hidden" name="imagen" value="<?php echo htmlspecialchars($img); ?>">
                                <button type="submit" class="btn_agregar" style="background-color: #FF978C; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">Añadir</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; grid-column: 1 / -1;">No se encontraron productos que coincidan con tu búsqueda.</p>
        <?php endif; ?>
    </main>

</body>
</html>
