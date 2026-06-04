-- Active: 1763272708420@@127.0.0.1@3306@phpmyadmin
-- Active: 1763353644947@@127.0.0.1@3306@3306@mysql
-- Go Faster Master Database Schema
-- Consolidates: usuario, domiciliario, restaurante, producto, factura, domicilio

CREATE DATABASE IF NOT EXISTS `prueba_go_faster`;
USE `prueba_go_faster`;

-- 1. Table structure for table `usuario`
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id_Usuario` int(11) NOT NULL AUTO_INCREMENT,
  `Nombres_usua` varchar(100) NOT NULL,
  `Apellidos_usua` varchar(100) NOT NULL,
  `Banco_usua` varchar(30) NOT NULL,
  `Email_usua` varchar(100) NOT NULL,
  `Telefono_usua` bigint(20) DEFAULT NULL,
  `Direccion_usua` varchar(100) NOT NULL,
  `Ciudad_usua` varchar(50) NOT NULL,
  `Password_usua` varchar(255) NOT NULL, -- Increased length for hashing
  `Fecha_Nacimiento_usua` date NOT NULL,
  `Foto_usua` varchar(255) DEFAULT 'usuario.jpg',
  PRIMARY KEY (`id_Usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample data for `usuario`
INSERT INTO `usuario` VALUES 
(1,'Adrian','Paez','BBVA','abc@gmail.com',2147483647,'Avenida 15 #45-68','Yarumal','23568','2000-01-01','usuario.jpg'),
(2,'Jaime','Betancur','Bancolombia','monigo@gmail.com',2147483647,'Manzana 2 #34a-15','Yarumal','EDS.12','2000-01-01','usuario.jpg'),
(3,'Eduardo','Rubio','Banco Agrario','telo.azul@hotmail.com',2147483647,'calle 20 #12-8','Yarumal','cas000','2000-01-01','usuario.jpg'),
(4,'Maria','Ocampo','Nequi','maria@hotmail.com',3568745,'calle 1 # 10-10','Yarumal','azul..12','2000-01-01','usuario.jpg'),
(5,'Samuel','Velez','BBVA','casona@gmail.com',2147483647,'carrera 40 #25-10','Yarumal','cason123*','2000-01-01','usuario.jpg'),
(6,'Yenniffer','Posso','Bancolombia','yenni@gmail.com',2147483647,'calle 22 15 #15-8','Yarumal','yy1985','2000-01-01','usuario.jpg');

-- 2. Table structure for table `domiciliario`
DROP TABLE IF EXISTS `domiciliario`;
CREATE TABLE `domiciliario` (
  `id_Domiciliario` int(11) NOT NULL AUTO_INCREMENT,
  `Nombres_domic` varchar(100) NOT NULL,
  `Apellidos_domic` varchar(100) NOT NULL,
  `Banco_domic` varchar(30) NOT NULL,
  `Email_domic` varchar(100) NOT NULL,
  `Telefono_domic` bigint(20) DEFAULT NULL,
  `Direccion_domic` varchar(100) NOT NULL,
  `Ciudad_domic` varchar(50) NOT NULL,
  `Password_domic` varchar(255) NOT NULL,
  `Fecha_Nacimiento_domic` date NOT NULL,
  PRIMARY KEY (`id_Domiciliario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample data for `domiciliario`
INSERT INTO `domiciliario` VALUES 
(1,'Alberto','Ramirez','Bancolombia','albrami@gmail.com',2147483647,'carrera 17 #52-1','Yarumal','alber**','2000-01-01'),
(2,'Carlos','Noriega','BBVA','noriega@gmail.com',2147483647,'calle 22 #15-17','Yarumal','envio*','2000-01-01');

-- 3. Table structure for table `restaurante`
DROP TABLE IF EXISTS `restaurante`;
CREATE TABLE `restaurante` (
  `id_Restaurante` int(11) NOT NULL AUTO_INCREMENT,
  `Nombres_rest` varchar(100) NOT NULL,
  `Apellidos_rest` varchar(100) NOT NULL,
  `Banco_rest` varchar(30) NOT NULL,
  `Email_rest` varchar(100) NOT NULL,
  `Telefono_rest` bigint(20) DEFAULT NULL,
  `Direccion_rest` varchar(100) NOT NULL,
  `Ciudad_rest` varchar(50) NOT NULL,
  `Password_rest` varchar(255) NOT NULL,
  `Fecha_Nacimiento_rest` date NOT NULL,
  PRIMARY KEY (`id_Restaurante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample data for `restaurante`
INSERT INTO `restaurante` VALUES 
(1,'Frisbye','Venta de Pollo','BBVA','frisbye@gmail.com',2147483647,'calle 13 #22-18','Yarumal','fry789.','2000-01-01'),
(2,'Burguer King','Comidas Rapidas','Bancolombia','Burguer@gmail.com',2147483647,'carrera 2 #22-17','Yarumal','burg123.','2000-01-01');

-- 4. Table structure for table `producto`
DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto` (
  `id_Producto` int(11) NOT NULL AUTO_INCREMENT,
  `Nombres_prod` varchar(100) NOT NULL,
  `Caracteristicas_prod` varchar(400) NOT NULL,
  `Precio_prod` int(20) NOT NULL,
  `id_restaurante` int(11) NOT NULL,
  `Imagen_prod` varchar(255) DEFAULT 'go_faster.png',
  PRIMARY KEY (`id_Producto`),
  KEY `id_restaurante` (`id_restaurante`),
  CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurante` (`id_Restaurante`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample data for `producto`
INSERT INTO `producto` VALUES 
(1,'Pollo asado entero','Pollo entero asado Colombiano 1.250 gr',45000,1,'pollo.jpg'),
(2,'Hamburguesa Doble carne','Hamburguesa especial de la casa, carne de 400 gr, salsa de la casa',31000,2,'hamburguesa.jpg'),
(3,'Pero Caliente Americano','Pero caliente con salchicha americana',24000,2,'go_faster.png'),
(4,'Consome de pollo','Rico caldo de consome de pollo Colombiano, acompanado de arepa y aguacate',14000,1,'pollo.jpg');

-- 5. Table structure for table `factura`
DROP TABLE IF EXISTS `factura`;
CREATE TABLE `factura` (
  `id_Factura` int(11) NOT NULL AUTO_INCREMENT,
  `id_Producto` int(11) DEFAULT NULL,
  `id_Usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_Factura`),
  KEY `id_Usuario` (`id_Usuario`),
  KEY `id_Producto` (`id_Producto`),
  CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`id_Usuario`) REFERENCES `usuario` (`id_Usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`id_Producto`) REFERENCES `producto` (`id_Producto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample data for `factura`
INSERT INTO `factura` VALUES (100,1,3),(101,2,5),(102,4,1),(104,2,6),(105,3,2),(106,4,4);

-- 6. Table structure for table `domicilio`
DROP TABLE IF EXISTS `domicilio`;
CREATE TABLE `domicilio` (
  `id_Domicilio` int(11) NOT NULL AUTO_INCREMENT,
  `id_factura` int(11) DEFAULT NULL,
  `id_Usuario` int(11) DEFAULT NULL,
  `id_Producto` int(11) DEFAULT NULL,
  `id_Domiciliario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_Domicilio`),
  KEY `id_factura` (`id_factura`),
  KEY `id_Usuario` (`id_Usuario`),
  KEY `id_Producto` (`id_Producto`),
  KEY `id_Domiciliario` (`id_Domiciliario`),
  CONSTRAINT `domicilio_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `factura` (`id_Factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `domicilio_ibfk_2` FOREIGN KEY (`id_Usuario`) REFERENCES `usuario` (`id_Usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `domicilio_ibfk_3` FOREIGN KEY (`id_Producto`) REFERENCES `producto` (`id_Producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `domicilio_ibfk_4` FOREIGN KEY (`id_Domiciliario`) REFERENCES `domiciliario` (`id_Domiciliario`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Sample data for `domicilio`
INSERT INTO `domicilio` VALUES (1,100,3,1,1),(2,101,5,2,2),(3,102,1,4,1),(4,104,6,2,2),(5,105,2,3,1),(6,106,4,4,2);

-- 7. Table structure for table `carts`
DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
    id VARCHAR(32) PRIMARY KEY,
    user_id INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 8. Table structure for table `cart_items`
DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE `cart_items` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id VARCHAR(32) NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10, 2) NOT NULL,
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES producto(id_Producto) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;