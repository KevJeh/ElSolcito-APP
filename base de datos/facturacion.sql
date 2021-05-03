-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-03-2021 a las 19:57:38
-- Versión del servidor: 10.4.17-MariaDB
-- Versión de PHP: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `facturacion`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_detalle_temp` (IN `codigo` INT, IN `cantidad` INT, IN `token_user` VARCHAR(50))  BEGIN
    
    	DECLARE precio_actual decimal(10,2);
        SELECT precio INTO precio_actual FROM producto WHERE idproducto = codigo;
        
        INSERT INTO detalle_temp(token_user, idproducto, cantidad, precio_venta) VALUE(token_user, codigo, cantidad, precio_actual);
        
        SELECT tmp.correlativo, tmp.idproducto, p.descripcion, tmp.cantidad, tmp.precio_venta FROM detalle_temp tmp 
        INNER JOIN producto p
        ON tmp.idproducto = p.idproducto
        WHERE tmp.token_user = token_user
        ORDER BY tmp.correlativo DESC;
        
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `anular_factura` (IN `no_factura` INT)  BEGIN
    	DECLARE existe_factura int;
        DECLARE registros int;
        DECLARE a int;

        DECLARE cod_producto int;
        DECLARE cant_producto int;
        DECLARE existencia_actual int;
        DECLARE nueva_existencia int;
        
        SET existe_factura = (SELECT COUNT(*) FROM factura WHERE nofactura = no_factura and estado = 1);
        
        IF existe_factura > 0 THEN
        	CREATE TEMPORARY TABLE tbl_tmp(
                id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                cod_prod BIGINT,
                cant_prod int);
                
                SET a = 1;
                
                SET registros = (SELECT COUNT(*) FROM detallefactura WHERE nofactura = no_factura);
                
                IF registros > 0 THEN
                	INSERT INTO tbl_tmp(cod_prod, cant_prod) SELECT idproducto, cantidad FROM detallefactura WHERE nofactura = no_factura;
                    
                    WHILE a <= registros DO
                    	SELECT cod_prod, cant_prod INTO cod_producto, cant_producto FROM tbl_tmp WHERE id = a;
                        SELECT existencia INTO existencia_actual FROM producto WHERE idproducto = cod_producto;
                        SET nueva_existencia = existencia_actual + cant_producto;
                        UPDATE producto SET existencia = nueva_existencia WHERE idproducto = cod_producto;
                        SET a = a + 1;
             		END WHILE;
                	
                    UPDATE factura SET estado = 2 WHERE nofactura = no_factura;
                    DROP TABLE tmp_tbl;
                    SELECT * FROM factura WHERE nofactura = no_factura;
                
                END IF;
        
        ELSE
        	SELECT 0 factura;
        END IF;
        
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_detalle_temp` (`id_detalle` INT, `token_user` VARCHAR(50))  BEGIN
        DELETE FROM detalle_temp WHERE correlativo = id_detalle;
        
        SELECT tmp.correlativo, tmp.idproducto, p.descripcion, tmp.cantidad, tmp.precio_venta FROM detalle_temp tmp 
        INNER JOIN producto p
        ON tmp.idproducto = p.idproducto
        WHERE tmp.token_user = token_user
        ORDER BY tmp.correlativo DESC;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_venta` (IN `cod_usuario` INT, IN `cod_cliente` INT, IN `token` VARCHAR(50))  BEGIN
    DECLARE factura int;
    
    DECLARE registros int;
    DECLARE total DECIMAL(10,2);
    DECLARE nueva_existencia int;
    DECLARE existencia_actual int;
    DECLARE tmp_id_producto int;
    DECLARE tmp_cant_producto int;
    DECLARE a INT;
    SET a = 1;
   	
    CREATE TEMPORARY TABLE tbl_tmp_tokenuser (
        	id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        	id_prod BIGINT,
        	cant_prod INT);
     SET registros = (SELECT COUNT(*) FROM detalle_temp WHERE token_user = token);
     
     IF registros > 0 THEN
     	INSERT INTO tbl_tmp_tokenuser(id_prod,cant_prod) SELECT idproducto,cantidad FROM detalle_temp WHERE token_user = token;
        
        INSERT INTO factura(usuario,codcliente) VALUES(cod_usuario,cod_cliente);
        SET factura = LAST_INSERT_ID();
        
        INSERT INTO detallefactura(nofactura,idproducto,cantidad,precio_venta) SELECT (factura) as nofactura,idproducto,cantidad,precio_venta FROM detalle_temp WHERE token_user=token;
        
        WHILE a <= registros DO
        	SELECT id_prod, cant_prod INTO tmp_id_producto, tmp_cant_producto FROM tbl_tmp_tokenuser WHERE id = a;
        	SELECT existencia INTO existencia_actual FROM producto WHERE idproducto = tmp_id_producto;
            SET nueva_existencia = existencia_actual - tmp_cant_producto;
            UPDATE producto SET existencia = nueva_existencia WHERE idproducto = tmp_id_producto;
            SET a=a+1;
        END WHILE;
        
        SET total = (SELECT SUM(cantidad * precio_venta) FROM detalle_temp WHERE token_user = token);
        UPDATE factura SET totalfactura = total WHERE nofactura = factura;
        DELETE FROM detalle_temp WHERE token_user = token;
        TRUNCATE TABLE tbl_tmp_tokenuser;
        SELECT * FROM factura WHERE nofactura = factura;
           
     ELSE
     SELECT 0;
     
     END IF;
     
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(11) NOT NULL,
  `cuit` varchar(11) DEFAULT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `telefono` varchar(12) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `dateadd` datetime DEFAULT current_timestamp(),
  `usuario_id` int(45) DEFAULT NULL,
  `estado` int(45) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idcliente`, `cuit`, `nombre`, `telefono`, `direccion`, `dateadd`, `usuario_id`, `estado`) VALUES
(1, '11111111111', 'Cliente 1', '0800111111', 'Domicilio 1', '2021-03-05 04:56:38', 1, 1),
(2, '22222222222', 'Cliente 2', '0800222222', 'Domicilio 2', '2021-03-05 04:57:03', 1, 1),
(3, '33333333333', 'Cliente 3', '0800333333', 'Domicilio 3', '2021-03-05 04:57:50', 1, 1),
(4, '44444444444', 'Cliente 4 ', '0800444444', 'Domicilio 4', '2021-03-16 04:16:47', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(20) NOT NULL,
  `cuit` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `razon_social` varchar(50) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `iva` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `cuit`, `nombre`, `razon_social`, `telefono`, `email`, `direccion`, `iva`) VALUES
(1, '30101001003', 'El Solcito Music!', '', '3764202020', 'elsolcitomusic@gmail.com', 'Av. Ficticia 0000', 21);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallefactura`
--

CREATE TABLE `detallefactura` (
  `correlativo` bigint(11) NOT NULL,
  `nofactura` bigint(11) DEFAULT NULL,
  `idproducto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `detallefactura`
--

INSERT INTO `detallefactura` (`correlativo`, `nofactura`, `idproducto`, `cantidad`, `precio_venta`) VALUES
(41, 29, 1, 1, '25000.00'),
(42, 29, 3, 2, '1500.00'),
(43, 30, 4, 3, '3000.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `correlativo` int(11) NOT NULL,
  `token_user` varchar(50) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `correlativo` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `observacion` varchar(200) NOT NULL DEFAULT 'SIN OBSERVACIÓN'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `entradas`
--

INSERT INTO `entradas` (`correlativo`, `idproducto`, `fecha`, `cantidad`, `precio`, `proveedor_id`, `usuario_id`, `observacion`) VALUES
(1, 3, '2021-03-16 13:13:12', 10, '1500.00', 1, 1, '00001'),
(2, 4, '2021-03-16 13:17:04', 15, '3000.00', 1, 1, '00002'),
(3, 9, '2021-03-16 13:17:49', 5, '4000.00', 2, 1, '00003'),
(4, 8, '2021-03-16 13:18:40', 12, '2500.00', 1, 1, '00003');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `nofactura` bigint(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `usuario` int(11) DEFAULT NULL,
  `codcliente` int(11) DEFAULT NULL,
  `totalfactura` decimal(10,2) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`nofactura`, `fecha`, `usuario`, `codcliente`, `totalfactura`, `estado`) VALUES
(29, '2021-03-16 13:29:50', 1, 2, '28000.00', 1),
(30, '2021-03-16 15:20:17', 1, 4, '9000.00', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `idmensaje` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `mail` varchar(20) NOT NULL,
  `asunto` varchar(30) NOT NULL,
  `texto` text NOT NULL,
  `data` date NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`idmensaje`, `nombre`, `mail`, `asunto`, `texto`, `data`, `estado`) VALUES
(1, 'Casco Sergio', 'sergiocasco05@gmail.', 'Prueba', ' Ingrese su mensaje', '2021-03-16', 1),
(2, 'Katrin Batista', 'katrin@gmail.com', 'Prueba 2', ' Esta es una prueba de mensaje...', '2021-03-16', 1),
(3, 'Casco Sergio', 'sergiocasco05@gmail.', 'Prueba 3', ' Prueba Nº3', '2021-03-16', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idproducto` int(11) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `codigo` varchar(100) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `existencia` int(11) DEFAULT 0,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1,
  `foto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idproducto`, `descripcion`, `codigo`, `precio`, `existencia`, `date_add`, `usuario_id`, `estado`, `foto`) VALUES
(1, 'Guitarra Eléctrica', '00001', '25000.00', 14, '2021-03-05 05:00:52', 1, 1, 'img_5ad3a930f86c7983b55e980f0a20fb0e.jpg'),
(2, 'Bajo Eléctrico', '00002', '35000.00', 23, '2021-03-05 05:01:30', 1, 1, 'img_a0d507b0c2253d248c8e125644f64bc5.jpg'),
(3, 'Harmonica', '00003', '1500.00', 8, '2021-03-05 05:02:04', 1, 1, 'img_fefa4daee74f456f120c3ade65e07a3e.jpg'),
(4, 'Flauta Dulce', '00004', '3000.00', 15, '2021-03-05 05:03:34', 1, 1, 'img_3b6c8842885f83a28c9fce2cd17e3b0d.jpg'),
(8, 'Teclado Eléctrico', '00005', '2500.00', 12, '2021-03-05 16:33:00', 1, 1, 'img_2cfd6e879146f9f4f11bc72c80d4a520.jpg'),
(9, 'Pandereta Professional', '00006', '4000.00', 5, '2021-03-14 07:31:58', 1, 1, 'img_ac6a31120fc227d514113585aab6c5ba.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `idproveedor` int(11) NOT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `cuit` varchar(100) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` bigint(11) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `date_add` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`idproveedor`, `proveedor`, `cuit`, `contacto`, `telefono`, `direccion`, `date_add`, `usuario_id`, `estado`) VALUES
(1, 'Proveedor 1', '11111111111', 'Contacto Proveedor 1', 1234123456, 'Domicilio 1', '2021-03-05 04:58:44', 1, 1),
(2, 'Proveedor 2', '22222222222', 'Contacto Proveedor 2', 1234123456, 'Domicilio 2', '2021-03-05 04:59:18', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `idrol` int(11) NOT NULL,
  `rol` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idrol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Supervisor'),
(3, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `usuario` varchar(15) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `rol` int(11) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `rol`, `estado`) VALUES
(1, 'Administrador                    ', 'admin@admin.com', 'admin', '81dc9bdb52d04dc20036dbd8313ed055', 1, 1),
(2, 'Supervisor', 'supervisor@supervisor.com', 'super', '81dc9bdb52d04dc20036dbd8313ed055', 2, 1),
(3, 'Vendedor', 'vendedor@vendedor.com', 'vende', '81dc9bdb52d04dc20036dbd8313ed055', 3, 1),
(4, 'Casco Sergio  ', 'sergiocasco05@gmail.com', 'scasco', '827ccb0eea8a706c4c34a16891f84e7b', 3, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `usuario_id_2` (`usuario_id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `codproducto` (`idproducto`),
  ADD KEY `nofactura` (`nofactura`);

--
-- Indices de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `nofactura` (`token_user`),
  ADD KEY `codproducto` (`idproducto`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`correlativo`),
  ADD KEY `codproducto` (`idproducto`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`nofactura`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `codcliente` (`codcliente`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`idmensaje`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idproducto`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`idproveedor`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  MODIFY `correlativo` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `nofactura` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `idmensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idproducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `idproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detallefactura`
--
ALTER TABLE `detallefactura`
  ADD CONSTRAINT `detallefactura_ibfk_1` FOREIGN KEY (`nofactura`) REFERENCES `factura` (`nofactura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detallefactura_ibfk_2` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD CONSTRAINT `detalle_temp_ibfk_2` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `entradas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `entradas_ibfk_3` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedor` (`idproveedor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `factura_ibfk_2` FOREIGN KEY (`codcliente`) REFERENCES `cliente` (`idcliente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`idrol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
