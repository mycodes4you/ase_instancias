-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 11-12-2020 a las 01:09:48
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ase_instancias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `b64_instancias`
--

CREATE TABLE `b64_instancias` (
  `id_instancia` int(11) NOT NULL,
  `nombre_instancia` text NOT NULL,
  `subdominio_instancia` text NOT NULL,
  `servidor_instancia` text NOT NULL,
  `img_instancia` text NOT NULL,
  `activa_instancia` int(11) NOT NULL,
  `fecha_ssl_instancia` date NOT NULL,
  `prefijo_instancias` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `b64_instancias`
--

INSERT INTO `b64_instancias` (`id_instancia`, `nombre_instancia`, `subdominio_instancia`, `servidor_instancia`, `img_instancia`, `activa_instancia`, `fecha_ssl_instancia`, `prefijo_instancias`) VALUES
(133, 'Academia', 'academia', 'Apagado', '', 0, '0000-00-00', ''),
(134, 'Alpha', 'alpha', 'OVH', '', 1, '0000-00-00', ''),
(135, 'Alpha Norte', 'alpha-norte', 'OVH', '', 1, '0000-00-00', ''),
(136, 'Sai Alpinismo', 'alpinismo', 'Codero', '', 1, '0000-00-00', ''),
(137, 'Alquimia Automotriz', 'alquimia', 'Jupiter', '', 1, '0000-00-00', ''),
(138, 'Antel Tlalpan', 'antel-tlalpan', '', '', 0, '0000-00-00', ''),
(139, 'AutoCare', 'autocare', 'Jupiter', '', 1, '0000-00-00', ''),
(140, 'AutoCenter Caderyta', 'autocenter-cadereyta', 'Jupiter', '', 1, '0000-00-00', ''),
(141, 'AutoClinic Centro', 'autoclinic-centro', '2', '', 1, '0000-00-00', ''),
(142, 'AutoClinic La Villa', 'autoclinic-lavilla', '', '', 0, '0000-00-00', ''),
(143, 'AutoClinic Mixcoac', 'autoclinic-mixcoac', '2', '', 1, '0000-00-00', ''),
(144, 'AutoClinic Sur', 'autoclinic-sur', 'Codero', '', 1, '0000-00-00', ''),
(145, 'AutoGo', 'autogo', '', '', 0, '0000-00-00', ''),
(146, 'Autolook', 'autolook', '2', '', 1, '0000-00-00', ''),
(147, 'AutoMundo Interlomas', 'automundo-interlomas', '', '', 0, '0000-00-00', ''),
(148, 'Autos e Imagen 1', 'autoseimagen1', '', '', 0, '0000-00-00', ''),
(149, 'CarGus', 'cargus', 'Codero', '', 1, '0000-00-00', ''),
(150, 'Carlook', 'carlook', 'Codero', '', 1, '0000-00-00', ''),
(151, 'Centro de Colisión', 'ccolision', '2', '', 1, '0000-00-00', ''),
(152, 'Chrysler Interlomas', 'chrysler-interlomas', '', '', 0, '0000-00-00', ''),
(153, 'Collision Center', 'collision-center', '2', '', 1, '0000-00-00', ''),
(154, 'Cúpula', 'cupula', 'Codero', '', 1, '0000-00-00', ''),
(155, 'CustomLab', 'customlab', '2', '', 1, '0000-00-00', ''),
(156, 'CyPO (Carrocerias y Pintura Optima)', 'cypo', 'Codero', '', 1, '0000-00-00', ''),
(157, 'DEMO', 'demo', '2', '', 1, '0000-00-00', ''),
(158, 'Sai El Carmen', 'el-carmen', 'Codero', '', 1, '0000-00-00', ''),
(159, 'Sai El Mirador', 'el-mirador', '2', '', 1, '0000-00-00', ''),
(160, 'Sai El Rosedal', 'el-rosedal', '2', '', 1, '0000-00-00', ''),
(161, 'Entrenamiento', 'entrenamiento', '2', '', 1, '0000-00-00', ''),
(162, 'Fazt', 'fazt', 'Codero', '', 1, '0000-00-00', ''),
(163, 'Forza (Strena Veracruz)', 'forza', '2', '', 1, '0000-00-00', ''),
(164, 'GAF', 'gaf', '', '', 0, '0000-00-00', ''),
(165, 'Galeana', 'galeana', '', '', 0, '0000-00-00', ''),
(166, 'GC Automotriz', 'gc-automotriz', '', '', 0, '0000-00-00', ''),
(167, 'Gisa', 'gisa-automotriz', '', '', 0, '0000-00-00', ''),
(168, 'Gran Prix', 'granprix', '2', '', 1, '0000-00-00', ''),
(169, 'Gran Prix 2', 'granprix2', '', '', 0, '0000-00-00', ''),
(170, 'Grupo Matrix', 'grupo-matrix', '2', '', 1, '0000-00-00', ''),
(171, 'GS Carroceria', 'gscarroceria', '', '', 0, '0000-00-00', ''),
(172, 'H Performance SW', 'hperformance', 'Jupiter', '', 1, '0000-00-00', ''),
(173, 'IndiCarTech', 'indicartech', '', '', 0, '0000-00-00', ''),
(175, 'Kater Automotriz', 'kater', '2', '', 1, '0000-00-00', ''),
(176, 'Keiken Automall', 'keikenautomall', '', '', 0, '0000-00-00', ''),
(177, 'La Fiera', 'la-fiera', 'Codero', '', 1, '0000-00-00', ''),
(178, 'La Pieza 2', 'lapiesa2', '', '', 0, '0000-00-00', ''),
(179, 'La Pieza 3', 'lapiesa3', '', '', 0, '0000-00-00', ''),
(180, 'La Pieza 4', 'lapiesa4', '', '', 0, '0000-00-00', ''),
(181, 'Sai Las Americas', 'las-americas', 'Codero', '', 1, '0000-00-00', ''),
(182, 'Sai Las Flores', 'las-flores', '2', '', 1, '0000-00-00', ''),
(183, 'Maestreta', 'mastreta', '', '', 0, '0000-00-00', ''),
(184, 'Maxima Automotriz', 'maxima-automotriz', '', '', 0, '0000-00-00', ''),
(185, 'MPM', 'mpm', '', '', 0, '0000-00-00', ''),
(186, 'MyP', 'myp', '', '', 0, '0000-00-00', ''),
(187, 'Natsa', 'natsa', '', '', 0, '0000-00-00', ''),
(188, 'Nissan Apizaco', 'nissanapizaco', '', '', 0, '0000-00-00', ''),
(189, 'Paint Explosion', 'paint-explosion', '2', '', 1, '0000-00-00', ''),
(190, 'Pasion F1 La Pieza', 'pasionf1lapiesa', '', '', 0, '0000-00-00', ''),
(191, 'Sai Peñuelas', 'penuelas', 'Codero', '', 1, '0000-00-00', ''),
(192, 'Profereauto', 'profereauto', '', '', 0, '0000-00-00', ''),
(193, 'Quality Service', 'qsa', '2', '', 1, '0000-00-00', ''),
(194, 'Qso', 'qso', '', '', 0, '0000-00-00', ''),
(195, 'Repisol', 'repisol', 'Codero', '', 1, '0000-00-00', ''),
(197, 'Sajiro Motors', 'sajiro', 'Codero', '', 1, '0000-00-00', ''),
(198, 'Sai San Antonio', 'sanantonio', '2', '', 1, '0000-00-00', ''),
(199, 'Sarsan Amores', 'sarsan-amores', '2', '', 1, '0000-00-00', ''),
(200, 'Sarsan Satelite', 'sarsan-satelite', '2', '', 1, '0000-00-00', ''),
(201, 'Scuderia S3', 'scuderiaese3', '', '', 0, '0000-00-00', ''),
(202, 'Scuderia S3', 'scuderia-s3', '2', '', 1, '0000-00-00', ''),
(203, 'SEPSA', 'sepsa', '', '', 0, '0000-00-00', ''),
(204, 'SMS', 'sms', '2', '', 1, '0000-00-00', ''),
(205, 'Strena', 'strena', '2', '', 1, '0000-00-00', ''),
(206, 'Strena Vallejo', 'strena-vallejo', '', '', 0, '0000-00-00', ''),
(207, 'Top Detallado Automotriz', 'top-da', 'Codero', '', 1, '0000-00-00', ''),
(208, 'Vecar', 'vecar', '2', '', 1, '0000-00-00', ''),
(209, 'Vipits', 'vipits', '2', '', 1, '0000-00-00', ''),
(210, 'Xtreme Collision', 'xtreme-collision', '', '', 0, '0000-00-00', ''),
(211, 'Xtreme Playa', 'xtreme-playa', '', '', 0, '0000-00-00', ''),
(212, 'Academia', 'academia', '', '', 0, '0000-00-00', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `b64_permisos_otorgados`
--

CREATE TABLE `b64_permisos_otorgados` (
  `id_p_o` int(11) NOT NULL,
  `id_n_p_o` text NOT NULL,
  `id_u_p_o` text NOT NULL,
  `estado_permiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `b64_permisos_otorgados`
--

INSERT INTO `b64_permisos_otorgados` (`id_p_o`, `id_n_p_o`, `id_u_p_o`, `estado_permiso`) VALUES
(1, '1', '1', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `usuario_usuario` text NOT NULL,
  `usuario_nombre1` text NOT NULL,
  `usuario_nombre2` text NOT NULL,
  `usuario_apellido1` text NOT NULL,
  `usuario_apellido2` text NOT NULL,
  `usuario_psswrd` text NOT NULL,
  `usuario_activo` int(11) NOT NULL,
  `usuario_foto` text NOT NULL,
  `config_navbar` text NOT NULL,
  `config_accent` text NOT NULL,
  `config_sidebar` text NOT NULL,
  `config_brand` text NOT NULL,
  `usuario_pass_ase` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `usuario_usuario`, `usuario_nombre1`, `usuario_nombre2`, `usuario_apellido1`, `usuario_apellido2`, `usuario_psswrd`, `usuario_activo`, `usuario_foto`, `config_navbar`, `config_accent`, `config_sidebar`, `config_brand`, `usuario_pass_ase`) VALUES
(1, '701', 'Carlos', 'Alejandro', 'Vazquez', 'Ramirez', '827ccb0eea8a706c4c34a16891f84e7b', 1, 'dist/img/usuario.png', 'navbar-dark navbar-navy', 'accent-navy', 'sidebar-dark-navy', 'navbar-navy', 'wGgS8dAsdZnWDeIJWGNWXA==');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `b64_instancias`
--
ALTER TABLE `b64_instancias`
  ADD PRIMARY KEY (`id_instancia`);

--
-- Indices de la tabla `b64_permisos_otorgados`
--
ALTER TABLE `b64_permisos_otorgados`
  ADD PRIMARY KEY (`id_p_o`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `b64_instancias`
--
ALTER TABLE `b64_instancias`
  MODIFY `id_instancia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

--
-- AUTO_INCREMENT de la tabla `b64_permisos_otorgados`
--
ALTER TABLE `b64_permisos_otorgados`
  MODIFY `id_p_o` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
