-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-07-2025 a las 05:28:17
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rosmed`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `usuario`, `password`) VALUES
(1, 'admin', '$2y$10$RToAh53AuxRtNb9D6YxfTu4RSVyf3apRionnYJkRqXb1HaOFlTZSG');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos_medicos`
--

CREATE TABLE `archivos_medicos` (
  `id` int(10) NOT NULL,
  `id_paciente` int(10) UNSIGNED DEFAULT NULL,
  `nombre_original` varchar(255) NOT NULL,
  `nombre_guardado` varchar(255) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_medico` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `archivos_medicos`
--

INSERT INTO `archivos_medicos` (`id`, `id_paciente`, `nombre_original`, `nombre_guardado`, `tipo`, `fecha_subida`, `id_medico`) VALUES
(8, 9, 'INFORMES.pdf', '688858c982c3a_INFORMES.pdf', 'application/pdf', '2025-07-29 05:14:49', NULL),
(10, 9, 'javier moljo.png', '68885a5fc80af_javier moljo.png', 'image/png', '2025-07-29 05:21:35', NULL),
(16, NULL, 'Coude_fp.png', '68896b5def7c8_Coude_fp.png', 'image/png', '2025-07-30 00:46:21', 8),
(18, 7, 'Captura de pantalla 2025-07-23 151034.png', '688aa5b4d10d2_Captura de pantalla 2025-07-23 151034.png', 'image/png', '2025-07-30 23:07:32', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matriculas`
--

CREATE TABLE `matriculas` (
  `id` int(10) UNSIGNED NOT NULL,
  `matricula` varchar(30) NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `matriculas`
--

INSERT INTO `matriculas` (`id`, `matricula`, `id_usuario`) VALUES
(1, '123456', 8),
(4, '884488', 14),
(5, '112233', 17);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE `medicos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `matricula` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`id`, `nombre`, `apellido`, `matricula`) VALUES
(4, 'Gordon', 'Gordon', '112233'),
(8, 'pedro', 'pascal', '999666');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medico_paciente`
--

CREATE TABLE `medico_paciente` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_medico` int(10) UNSIGNED DEFAULT NULL,
  `id_medico_sin_usuario` int(10) UNSIGNED DEFAULT NULL,
  `id_paciente_user` int(10) UNSIGNED DEFAULT NULL,
  `id_paciente_local` int(10) UNSIGNED DEFAULT NULL,
  `anotaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `medico_paciente`
--

INSERT INTO `medico_paciente` (`id`, `id_medico`, `id_medico_sin_usuario`, `id_paciente_user`, `id_paciente_local`, `anotaciones`) VALUES
(1, 8, NULL, 9, NULL, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur quos fugit quaerat est nulla exercitationem consequuntur maiores. Facilis, porro expedita et doloremque voluptates aliquam adipisci autem. Culpa veritatis corrupti dolorem.'),
(4, 8, NULL, 7, NULL, 'Lorem Lorem Lorem Lorem'),
(6, 8, NULL, NULL, 9, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur quos fugit quaerat est nulla exercitationem consequuntur maiores. Facilis, porro expedita et doloremque voluptates aliquam adipisci autem. Culpa veritatis corrupti dolorem.'),
(13, NULL, 4, 9, NULL, NULL),
(18, NULL, 8, 7, NULL, NULL),
(21, 17, NULL, 8, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id`, `nombre`, `apellido`, `dni`) VALUES
(9, 'Ana', 'Cortez', '11122222'),
(10, 'Florencia', 'Perez', '11222222');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` varchar(15) NOT NULL,
  `fechaNac` date NOT NULL,
  `tipoUser` enum('medico','paciente') NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(260) NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `dni`, `fechaNac`, `tipoUser`, `telefono`, `email`, `pass`, `activo`) VALUES
(7, 'Florencia', 'Farru', '111000111', '2000-10-10', 'paciente', '1000000001', 'flor.farru@gmail.com', '$2y$10$6gnZlvEIFJSZHVw1lVGroeqeHty3udCy1eSAFzKHhyeSOHNAvkexC', 1),
(8, 'Mora', 'Morales', '111222111', '2000-10-10', 'medico', '2000000002', 'medico@gmail.com', '$2y$10$vlixns7oASodeXuGG1PpJudFLSUH.6Buvwv05orCQnXzZ4e9jXOeO', 1),
(9, 'Test', 'Test', '111222333', '2000-10-10', 'paciente', '+123000000', 'test@test.ts', '$2y$10$CL5762KZajUADFdTO0R4sOQY/BxSsfdym2mA931AppWffYzJbokc.', 1),
(14, 'Estela', 'Estevez', '999000999', '1990-02-25', 'medico', '999000999', 'estevez@medico.com', '$2y$10$nlekRzLix5lyD8cMoFj.EOIEKaoXwbrOVy1duLiwbwftPy2sFmEKu', 1),
(15, 'Marcos', 'Morales', '777999777', '2000-10-10', 'medico', '+777888999', 'morales@gmail.com', 'morales1234', 1),
(17, 'Ricardo', 'Ramirez', '', '0000-00-00', 'medico', '', '', '', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `archivos_medicos`
--
ALTER TABLE `archivos_medicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_archivo_usuario_paciente` (`id_paciente`),
  ADD KEY `fk_archivo_usuario_medico` (`id_medico`);

--
-- Indices de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricula` (`matricula`);

--
-- Indices de la tabla `medico_paciente`
--
ALTER TABLE `medico_paciente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_medico` (`id_medico`),
  ADD KEY `fk_paciente_user` (`id_paciente_user`),
  ADD KEY `fk_paciente_local` (`id_paciente_local`),
  ADD KEY `fk_medico_sin_usuario` (`id_medico_sin_usuario`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `archivos_medicos`
--
ALTER TABLE `archivos_medicos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `matriculas`
--
ALTER TABLE `matriculas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `medico_paciente`
--
ALTER TABLE `medico_paciente`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `archivos_medicos`
--
ALTER TABLE `archivos_medicos`
  ADD CONSTRAINT `fk_archivo_usuario_medico` FOREIGN KEY (`id_medico`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_archivo_usuario_paciente` FOREIGN KEY (`id_paciente`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `matriculas`
--
ALTER TABLE `matriculas`
  ADD CONSTRAINT `matriculas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `medico_paciente`
--
ALTER TABLE `medico_paciente`
  ADD CONSTRAINT `fk_medico` FOREIGN KEY (`id_medico`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_medico_sin_usuario` FOREIGN KEY (`id_medico_sin_usuario`) REFERENCES `medicos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_paciente_local` FOREIGN KEY (`id_paciente_local`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_paciente_user` FOREIGN KEY (`id_paciente_user`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
