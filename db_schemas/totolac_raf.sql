-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-01-2016 a las 01:46:17
-- Versión del servidor: 5.6.24
-- Versión de PHP: 5.5.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `totolac_raf`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apoyos`
--

CREATE TABLE IF NOT EXISTS `apoyos` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `apoyos`
--

INSERT INTO `apoyos` (`id`, `name`) VALUES
(1, 'SI'),
(2, 'Probablemente'),
(3, 'NO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colonias`
--

CREATE TABLE IF NOT EXISTS `colonias` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `colonias`
--

INSERT INTO `colonias` (`id`, `name`) VALUES
(1, 'San Juan Totolac'),
(2, 'Acxotla del Rio'),
(3, 'Los Reyes Quiahuixtlan'),
(4, 'Zaragoza'),
(5, 'La Trinidad Chimalpa'),
(6, 'Santiago Tepeticpac'),
(7, 'La Candelaria Teotlalpan'),
(8, 'San Francisco Ocotelulco'),
(9, 'San Miguel Tlamahuco'),
(10, 'Foraneo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `firmantes`
--

CREATE TABLE IF NOT EXISTS `firmantes` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ap` varchar(50) NOT NULL,
  `am` varchar(50) NOT NULL,
  `clave` varchar(20) NOT NULL,
  `colony_id` int(11) NOT NULL,
  `recolector_id` int(11) NOT NULL,
  `apoyo_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `firmantes`
--

INSERT INTO `firmantes` (`id`, `name`, `ap`, `am`, `clave`, `colony_id`, `recolector_id`, `apoyo_id`) VALUES
(1, 'Luigi', 'PÃ©rez', 'Calzada', 'FRPPMC60031029H501', 2, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recolectores`
--

CREATE TABLE IF NOT EXISTS `recolectores` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ap` varchar(50) NOT NULL,
  `am` varchar(50) NOT NULL,
  `colony` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `recolectores`
--

INSERT INTO `recolectores` (`id`, `name`, `ap`, `am`, `colony`) VALUES
(1, 'Luigi', 'PÃ©rez', 'Calzada', 2),
(2, 'Xicohtencatl', 'Delgado', 'Santiago', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apoyos`
--
ALTER TABLE `apoyos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `colonias`
--
ALTER TABLE `colonias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `firmantes`
--
ALTER TABLE `firmantes`
  ADD PRIMARY KEY (`id`), ADD KEY `colony_id` (`colony_id`), ADD KEY `recolector_id` (`recolector_id`), ADD KEY `apoyo_id` (`apoyo_id`);

--
-- Indices de la tabla `recolectores`
--
ALTER TABLE `recolectores`
  ADD PRIMARY KEY (`id`), ADD KEY `colony` (`colony`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `apoyos`
--
ALTER TABLE `apoyos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `colonias`
--
ALTER TABLE `colonias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `firmantes`
--
ALTER TABLE `firmantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `recolectores`
--
ALTER TABLE `recolectores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `firmantes`
--
ALTER TABLE `firmantes`
ADD CONSTRAINT `firmantes_ibfk_1` FOREIGN KEY (`colony_id`) REFERENCES `colonias` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `firmantes_ibfk_2` FOREIGN KEY (`recolector_id`) REFERENCES `recolectores` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `firmantes_ibfk_3` FOREIGN KEY (`apoyo_id`) REFERENCES `apoyos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `recolectores`
--
ALTER TABLE `recolectores`
ADD CONSTRAINT `recolectores_ibfk_1` FOREIGN KEY (`colony`) REFERENCES `colonias` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
