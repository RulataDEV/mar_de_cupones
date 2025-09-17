-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-11-2024 a las 10:06:29
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cupones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigo_cupones`
--

CREATE TABLE `codigo_cupones` (
  `id` int(11) NOT NULL,
  `id_cupon` int(11) NOT NULL,
  `codigo` char(10) NOT NULL,
  `dni_usuario` varchar(20) NOT NULL,
  `vencimiento` date NOT NULL,
  `usado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `codigo_cupones`
--

INSERT INTO `codigo_cupones` (`id`, `id_cupon`, `codigo`, `dni_usuario`, `vencimiento`, `usado`) VALUES
(1, 8, '14a8434c1a', '47089719', '2024-12-28', 0),
(2, 6, '8d3ce360b0', '47089719', '2024-12-28', 0),
(3, 8, '107e8ea13e', '47089719', '2024-12-28', 0),
(4, 8, '2492a8997b', '47089719', '2024-12-28', 0),
(5, 8, '357df7bb38', '47089719', '2024-12-28', 0),
(6, 8, 'da61df90b0', '47089719', '2024-12-28', 0),
(7, 8, '28e510fc04', '47089719', '2024-12-28', 0),
(8, 8, 'a1c9935c9c', '47089719', '2024-12-28', 0),
(9, 8, 'f5b269f21d', '47089719', '2024-12-28', 0),
(10, 8, '84bf68ea42', '47089719', '2024-12-28', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comercios`
--

CREATE TABLE `comercios` (
  `id` int(7) NOT NULL,
  `id_usuario` int(7) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `categoria` varchar(30) NOT NULL,
  `localidad` varchar(255) NOT NULL,
  `calle` varchar(255) NOT NULL,
  `altura` int(5) NOT NULL,
  `cantidad_cupones` int(2) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `imagen_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `comercios`
--

INSERT INTO `comercios` (`id`, `id_usuario`, `nombre`, `categoria`, `localidad`, `calle`, `altura`, `cantidad_cupones`, `telefono`, `imagen_url`) VALUES
(15, 1, 'Lucas Pescaderia', 'Alimentos', 'Miramar', '40', 1263, 0, '11222333', '../uploads/6743c7d646c9f_comercio-feliz.jpg'),
(19, 5, 'Shrek peliculas', 'Entretenimiento', 'Mechongué', '40', 1111, 0, '12345678', '../uploads/674762cde753a_comercio_feliz.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cupones`
--

CREATE TABLE `cupones` (
  `id` int(10) NOT NULL,
  `id_comercio` int(10) NOT NULL,
  `categoria_comercio` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `stock` int(10) NOT NULL,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cupones`
--

INSERT INTO `cupones` (`id`, `id_comercio`, `categoria_comercio`, `titulo`, `fecha_inicio`, `fecha_fin`, `descripcion`, `stock`, `imagen_url`) VALUES
(6, 15, 'Alimentos', '2x1 En Burros', '2024-11-13', '2024-11-29', 'AAAAAAA', 200, '../uploads/67476461bae71_miramar.png'),
(7, 15, 'Alimentos', '50% de descuento en peces', '2024-11-27', '2024-12-27', 'Peces afiliados al descuento: atun, merluza, piraña, mojarra, pez gato, no se me ocurre otro nombre de pez', 1000, '../uploads/67476461bae71_miramar.png'),
(8, 15, 'Alimentos', '2x1 En Burros', '2024-11-30', '2024-12-07', 'Peces afiliados al descuento: atun, merluza, piraña, mojarra, pez gato, no se me ocurre otro nombre de pez', 3432, '../uploads/6743c7d646c9f_foto-credencial.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(7) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Apellido` varchar(255) NOT NULL,
  `Contrasena` varchar(255) NOT NULL,
  `Correo` varchar(255) NOT NULL,
  `DNI` int(9) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `comerciante` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `Nombre`, `Apellido`, `Contrasena`, `Correo`, `DNI`, `admin`, `comerciante`) VALUES
(1, 'Roman', 'Foschi', '$2y$10$hubSx.LXLd07xehGGqTtROcUqPC3/HWIJ4.ZTTpK3VocGSsy2b3mm', 'diegoromandark@gmail.com', 47089719, 1, 1),
(2, 'Roman ', 'Foschi', '$2y$10$RNF1NZGCXTkRdSyEIWhpHuFEHamAMhG8T5ie8oDIChTItDaNPDMfO', 'romanfoschi4@gmail.com', 47089720, 0, 1),
(3, 'Roman', 'Foschi', '$2y$10$S6I1F/CDDGB8aBPPDpl08OFI9eukTpWEoDskcjaHNHaa4GYyOseQ6', 'lucianoromanlopez@gmail.com', 47089721, 0, 0),
(4, 'Pedro', 'Ballarre', '$2y$10$HS2nQ1FQRwLrbYZdrrG6PuteuFnS2PFFi0djfn.J/4ZtO2YvIqara', 'pballare@gmail.com', 29728010, 0, 0),
(5, 'Shrek', 'Molino', '$2y$10$xdyBw9AqsmKrSqReA1lzVOafqGXawGj4.Abme.NqTAtPap8iAtbDa', 'shrek@yahoo.com', 46874392, 0, 0),
(6, 'Diego', 'Foschi', '$2y$10$cfiLrUz0XE1q69SyRyTkHufA/qcJW3HSppKK7AEaSWEgYJwYSmHeW', 'auredp@gmail.com', 46874206, 0, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `codigo_cupones`
--
ALTER TABLE `codigo_cupones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_cupon` (`id_cupon`);

--
-- Indices de la tabla `comercios`
--
ALTER TABLE `comercios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`),
  ADD KEY `categoria` (`categoria`);

--
-- Indices de la tabla `cupones`
--
ALTER TABLE `cupones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_comercio` (`id_comercio`),
  ADD KEY `categoria_comercio` (`categoria_comercio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `DNI` (`DNI`),
  ADD UNIQUE KEY `Correo` (`Correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `codigo_cupones`
--
ALTER TABLE `codigo_cupones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `comercios`
--
ALTER TABLE `comercios`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `cupones`
--
ALTER TABLE `cupones`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `codigo_cupones`
--
ALTER TABLE `codigo_cupones`
  ADD CONSTRAINT `codigo_cupones_ibfk_1` FOREIGN KEY (`id_cupon`) REFERENCES `cupones` (`id`);

--
-- Filtros para la tabla `comercios`
--
ALTER TABLE `comercios`
  ADD CONSTRAINT `comercios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cupones`
--
ALTER TABLE `cupones`
  ADD CONSTRAINT `cupones_ibfk_1` FOREIGN KEY (`id_comercio`) REFERENCES `comercios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cupones_ibfk_2` FOREIGN KEY (`categoria_comercio`) REFERENCES `comercios` (`categoria`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
