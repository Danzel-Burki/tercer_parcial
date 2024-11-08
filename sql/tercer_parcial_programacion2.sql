-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2024 a las 23:06:58
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tercer_parcial_programacion2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `archivo_adjunto` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_completada` timestamp NULL DEFAULT NULL,
  `eliminado` tinyint(1) DEFAULT 0,
  `completada` tinyint(1) DEFAULT 0,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id`, `nombre`, `archivo_adjunto`, `fecha_creacion`, `fecha_completada`, `eliminado`, `completada`, `descripcion`) VALUES
(0, 'Danzel', 'uploads/tarea.pdf', '2024-11-08 22:06:18', '2024-11-08 22:06:38', 1, 1, 'asd'),
(0, 'asd', 'uploads/tarea.pdf', '2024-11-08 22:06:26', '2024-11-08 22:06:38', 0, 1, 'asd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas_eliminadas`
--

CREATE TABLE `tareas_eliminadas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `archivo_adjunto` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_completada` timestamp NULL DEFAULT NULL,
  `fecha_eliminacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas_eliminadas`
--

INSERT INTO `tareas_eliminadas` (`id`, `nombre`, `archivo_adjunto`, `fecha_creacion`, `fecha_completada`, `fecha_eliminacion`, `descripcion`) VALUES
(0, 'Danzel', 'uploads/tarea.pdf', '2024-11-08 22:06:18', '0000-00-00 00:00:00', '2024-11-08 22:06:20', 'asd');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
