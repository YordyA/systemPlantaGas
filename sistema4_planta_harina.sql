-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-04-2025 a las 19:58:49
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
-- Base de datos: `sistema4_planta_harina`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos`
--

CREATE TABLE `articulos` (
  `IDArticulo` int(11) NOT NULL,
  `FechaRegArticulo` datetime NOT NULL DEFAULT current_timestamp(),
  `CodigoArticulo` tinytext NOT NULL,
  `DescripcionArticulo` tinytext NOT NULL,
  `IDAlicuota` int(11) NOT NULL,
  `EstadoArticulo` int(11) NOT NULL,
  `UltimaActualicionArticulo` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `articulos`
--

INSERT INTO `articulos` (`IDArticulo`, `FechaRegArticulo`, `CodigoArticulo`, `DescripcionArticulo`, `IDAlicuota`, `EstadoArticulo`, `UltimaActualicionArticulo`) VALUES
(1, '2025-04-14 10:22:07', '01', 'HARINA LA APUREÑA', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulos_alicuotas`
--

CREATE TABLE `articulos_alicuotas` (
  `IDAlicuota` int(11) NOT NULL,
  `FechaRegAlicuota` datetime NOT NULL DEFAULT current_timestamp(),
  `DescripcionAlicuota` tinytext NOT NULL,
  `ValorAlicuota` decimal(11,2) NOT NULL,
  `EstadoAlicuota` int(11) NOT NULL,
  `UltimaActualiacionAlicuota` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `articulos_alicuotas`
--

INSERT INTO `articulos_alicuotas` (`IDAlicuota`, `FechaRegAlicuota`, `DescripcionAlicuota`, `ValorAlicuota`, `EstadoAlicuota`, `UltimaActualiacionAlicuota`) VALUES
(1, '2025-02-24 11:27:39', 'EXENTO 0%', 0.00, 1, NULL),
(2, '2025-02-24 11:27:39', 'ALICUOTA GENERAL 16%', 0.16, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `IDCliente` int(11) NOT NULL,
  `RifCedula` tinytext NOT NULL,
  `RazonSocial` tinytext NOT NULL,
  `DomicilioFiscal` tinytext NOT NULL,
  `TipoCliente` int(11) NOT NULL,
  `EstadoCliente` int(11) NOT NULL,
  `UltimaActualizacionCliente` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despachos_detalles`
--

CREATE TABLE `despachos_detalles` (
  `IDDespachoDetalle` int(11) NOT NULL,
  `IDDespachoResumen` int(11) NOT NULL,
  `IDInvPlanta` int(11) DEFAULT NULL,
  `IDInvProduccion` int(11) DEFAULT NULL,
  `CantDesp` decimal(11,2) NOT NULL,
  `PrecioVentaDespUSD` decimal(11,2) NOT NULL,
  `PrecioVentaDespBS` decimal(11,2) NOT NULL,
  `ValorAlicuotaDesp` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despachos_resumen`
--

CREATE TABLE `despachos_resumen` (
  `IDDespachoResumen` int(11) NOT NULL,
  `FechaRegDesp` datetime NOT NULL DEFAULT current_timestamp(),
  `FechaDesp` date NOT NULL,
  `IDCliente` int(11) NOT NULL,
  `IDTipoDesp` int(11) NOT NULL,
  `NroNota` int(11) NOT NULL,
  `FacturaSerie` tinytext DEFAULT NULL,
  `FacturaNro` tinytext DEFAULT NULL,
  `FacturaNroControl` tinytext DEFAULT NULL,
  `Chofer` tinytext NOT NULL,
  `ChoferCedula` tinytext NOT NULL,
  `ObservacionDesp` tinytext NOT NULL,
  `ResponsableDesp` tinytext NOT NULL,
  `EstadoDesp` int(11) NOT NULL,
  `UltimaActualiacionDesp` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despachos_tipos`
--

CREATE TABLE `despachos_tipos` (
  `IDTipoDespacho` int(11) NOT NULL,
  `FechaRegTipoDesp` datetime NOT NULL DEFAULT current_timestamp(),
  `DescripcionTipoDesp` tinytext NOT NULL,
  `EstadoTipoDesp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `despachos_tipos`
--

INSERT INTO `despachos_tipos` (`IDTipoDespacho`, `FechaRegTipoDesp`, `DescripcionTipoDesp`, `EstadoTipoDesp`) VALUES
(1, '2025-03-01 11:22:41', 'COMERCIALIZACION', 1),
(2, '2025-03-01 11:22:41', 'CONTRIBUCION SOLCIAL', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulas`
--

CREATE TABLE `formulas` (
  `IDFormulaProduccion` int(11) NOT NULL,
  `FechaRegFormula` datetime NOT NULL DEFAULT current_timestamp(),
  `IDArticulo` int(11) NOT NULL,
  `IDInvProduccion` int(11) NOT NULL,
  `CantUtilizar` decimal(20,4) NOT NULL,
  `EstadoFormula` int(11) NOT NULL,
  `UltimaActualizacionFormula` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `formulas`
--

INSERT INTO `formulas` (`IDFormulaProduccion`, `FechaRegFormula`, `IDArticulo`, `IDInvProduccion`, `CantUtilizar`, `EstadoFormula`, `UltimaActualizacionFormula`) VALUES
(1, '2025-04-14 13:32:14', 1, 9, 0.1000, 0, '2025-04-16 11:46:21 AM - DEP SISTEMAS'),
(2, '2025-04-16 11:46:14', 1, 9, 1.0000, 0, '2025-04-16 11:46:21 AM - DEP SISTEMAS'),
(3, '2025-04-16 11:47:31', 1, 9, 1.0000, 1, NULL),
(4, '2025-04-16 11:47:31', 1, 10, 1.0000, 1, NULL),
(5, '2025-04-16 11:47:31', 1, 12, 1.0000, 1, NULL),
(6, '2025-04-16 11:47:31', 1, 11, 1.0000, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_planta`
--

CREATE TABLE `inventario_planta` (
  `IDInvPlanta` int(11) NOT NULL,
  `FechaRegInvPlanta` datetime NOT NULL DEFAULT current_timestamp(),
  `IDProduccionResumen` int(11) NOT NULL,
  `FechaExpe` tinytext NOT NULL,
  `Existencia` decimal(11,2) NOT NULL,
  `PrecioCosto` decimal(11,2) NOT NULL,
  `PrecioVenta` decimal(11,2) NOT NULL,
  `EstadoInvPlanta` int(11) NOT NULL,
  `UltimiaActualiacionInvPlanta` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `inventario_planta`
--

INSERT INTO `inventario_planta` (`IDInvPlanta`, `FechaRegInvPlanta`, `IDProduccionResumen`, `FechaExpe`, `Existencia`, `PrecioCosto`, `PrecioVenta`, `EstadoInvPlanta`, `UltimiaActualiacionInvPlanta`) VALUES
(1, '2025-04-15 15:09:40', 1, '15-04-2025', 0.00, 0.00, 0.00, 1, NULL),
(2, '2025-04-15 18:50:33', 2, '15-04-2025', 1450.00, 1.30, 0.00, 1, 'DEP SISTEMAS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_planta_existencia`
--

CREATE TABLE `inventario_planta_existencia` (
  `IDInventarioPlantaValor` int(11) NOT NULL,
  `FecahRegExistenciaPlanta` date NOT NULL,
  `IDInvPlanta` int(11) NOT NULL,
  `ExistenciaSistema` int(11) NOT NULL,
  `CostoU` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_planta_movimientos`
--

CREATE TABLE `inventario_planta_movimientos` (
  `IDInvPlantaMov` int(11) NOT NULL,
  `FechaRegMov` datetime NOT NULL DEFAULT current_timestamp(),
  `FechaMov` date NOT NULL,
  `TipoMov` int(11) NOT NULL,
  `IDInvPlanta` int(11) NOT NULL,
  `ExistenciaAnterior` decimal(11,2) NOT NULL,
  `Movimiento` decimal(11,2) NOT NULL,
  `ExistenciaActual` decimal(11,2) NOT NULL,
  `ObservacionMov` tinytext NOT NULL,
  `ResponsableMov` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `inventario_planta_movimientos`
--

INSERT INTO `inventario_planta_movimientos` (`IDInvPlantaMov`, `FechaRegMov`, `FechaMov`, `TipoMov`, `IDInvPlanta`, `ExistenciaAnterior`, `Movimiento`, `ExistenciaActual`, `ObservacionMov`, `ResponsableMov`) VALUES
(1, '2025-04-15 15:09:40', '2025-04-15', 1, 1, 0.00, 1000.00, 1000.00, 'LOTE NRO 1', 'DEP SISTEMAS'),
(2, '2025-04-15 18:50:33', '2025-04-15', 1, 2, 0.00, 1500.00, 1500.00, 'LOTE NRO 2', 'DEP SISTEMAS'),
(3, '2025-04-16 08:45:32', '2025-04-16', 1, 2, 0.00, 50.00, 2100.00, 'LOTE NRO 2', 'DEP SISTEMAS'),
(4, '2025-04-16 08:46:01', '2025-04-16', 1, 2, 0.00, 1.00, 2101.00, 'LOTE NRO 2', 'DEP SISTEMAS'),
(5, '2025-04-16 09:59:21', '2025-04-16', 2, 2, 2101.00, 651.00, 1450.00, 'ANULACION DE PRODUCCION DE  2025-04-16', 'DEP SISTEMAS'),
(6, '2025-04-16 11:14:07', '2025-04-16', 2, 1, 1000.00, 1000.00, 0.00, 'ANULACION DE PRODUCCION DE  2025-04-15', 'DEP SISTEMAS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_produccion`
--

CREATE TABLE `inventario_produccion` (
  `IDInvProduccion` int(11) NOT NULL,
  `FechaRegInvProduccion` datetime NOT NULL DEFAULT current_timestamp(),
  `IDTipoProducto` int(11) NOT NULL,
  `IDUnidadMedida` int(11) NOT NULL,
  `IDAlicuota` int(11) DEFAULT NULL,
  `CodigoProducto` tinytext NOT NULL,
  `DescripcionProducto` tinytext NOT NULL,
  `CapacidadEmpaque` decimal(11,2) DEFAULT NULL,
  `CostoUnitario` decimal(20,8) NOT NULL,
  `PrecioUnitario` decimal(11,2) NOT NULL,
  `ExistenciaMinima` decimal(20,6) NOT NULL,
  `Existencia` decimal(20,6) NOT NULL,
  `EstadoInvProduccion` int(11) NOT NULL,
  `UltimiaActualizacionInvProduccion` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `inventario_produccion`
--

INSERT INTO `inventario_produccion` (`IDInvProduccion`, `FechaRegInvProduccion`, `IDTipoProducto`, `IDUnidadMedida`, `IDAlicuota`, `CodigoProducto`, `DescripcionProducto`, `CapacidadEmpaque`, `CostoUnitario`, `PrecioUnitario`, `ExistenciaMinima`, `Existencia`, `EstadoInvProduccion`, `UltimiaActualizacionInvProduccion`) VALUES
(4, '2025-03-31 18:42:36', 2, 1, 1, '001', 'EMPAQUE DE 1 KG', 1.00, 0.01000000, 0.00, 0.000000, 750.000000, 1, NULL),
(7, '2025-04-14 10:30:52', 1, 1, 1, 'MB-01', 'MAIZ BLANCO', NULL, 0.35000000, 0.50, 100000.000000, 75000.000000, 1, '2025-04-14 10:33:46 AM - DEP SISTEMAS'),
(8, '2025-04-14 10:31:16', 1, 1, 1, 'MA-01', 'MAIZ AMARILLO', NULL, 0.35000000, 0.50, 50000.000000, 80000.000000, 1, NULL),
(9, '2025-04-14 10:54:27', 3, 0, NULL, '', 'GASOIL', NULL, 0.02436000, 0.00, 0.000000, 0.000000, 1, NULL),
(10, '2025-04-14 10:55:03', 3, 0, NULL, '', 'AGUA', NULL, 0.00001000, 0.00, 0.000000, 0.000000, 1, NULL),
(11, '2025-04-14 10:56:38', 3, 0, NULL, '', 'ELECTRICIDAD', NULL, 0.00001000, 0.00, 0.000000, 0.000000, 1, NULL),
(12, '2025-04-14 10:57:25', 3, 0, NULL, '', 'GAS', NULL, 0.00004000, 0.00, 0.000000, 0.000000, 1, NULL),
(13, '2025-04-15 10:58:14', 5, 1, 1, 'P1', 'PICO', NULL, 0.00000000, 0.10, 0.000000, 490.000000, 1, NULL),
(14, '2025-04-15 10:58:51', 5, 1, 1, 'B1', 'BARRIDO', NULL, 0.00000000, 0.20, 0.000000, 480.000000, 1, NULL),
(15, '2025-04-15 10:58:51', 5, 1, 1, 'A1', 'AFRECHO', NULL, 0.00000000, 0.30, 0.000000, 470.000000, 1, NULL),
(16, '2025-04-15 10:58:51', 5, 1, 1, 'F1', 'FECULA DE MAIZ', NULL, 0.00000000, 0.40, 0.000000, 460.000000, 1, NULL),
(17, '2025-04-15 10:58:51', 5, 1, 1, 'H1', 'HARINA DE DESCARTE', NULL, 0.00000000, 0.50, 0.000000, 450.000000, 1, NULL),
(18, '2025-04-15 10:58:51', 5, 1, 1, 'I1', 'IMPUREZAS', NULL, 0.00000000, 0.60, 0.000000, 245.000000, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_produccion_conteo`
--

CREATE TABLE `inventario_produccion_conteo` (
  `IDInvProduccionConteo` int(11) NOT NULL,
  `FechaRegConteo` datetime NOT NULL DEFAULT current_timestamp(),
  `FechaCierreConteo` date NOT NULL,
  `NroConteo` int(11) NOT NULL,
  `IDInvProduccion` int(11) NOT NULL,
  `CantSistema` decimal(20,6) NOT NULL,
  `CantFisica` decimal(20,6) NOT NULL,
  `Diferencia` decimal(20,6) NOT NULL,
  `EstadoConteo` int(11) NOT NULL,
  `ResponsableConteo` tinytext NOT NULL,
  `UltimaActualiacionConteo` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `inventario_produccion_conteo`
--

INSERT INTO `inventario_produccion_conteo` (`IDInvProduccionConteo`, `FechaRegConteo`, `FechaCierreConteo`, `NroConteo`, `IDInvProduccion`, `CantSistema`, `CantFisica`, `Diferencia`, `EstadoConteo`, `ResponsableConteo`, `UltimaActualiacionConteo`) VALUES
(1, '2025-04-14 10:18:26', '2025-04-01', 1, 1, 0.000000, 0.000000, 0.000000, 0, '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_produccion_existencia`
--

CREATE TABLE `inventario_produccion_existencia` (
  `IDInvProduccionExistencia` int(11) NOT NULL,
  `FechaRegExistencia` datetime NOT NULL DEFAULT current_timestamp(),
  `FechaExistencia` date NOT NULL,
  `IDInvMateriaPrima` int(11) NOT NULL,
  `ExistenciaFinal` decimal(20,6) NOT NULL,
  `CostoUFinal` decimal(20,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_produccion_medidas`
--

CREATE TABLE `inventario_produccion_medidas` (
  `IDUnidadMedida` int(11) NOT NULL,
  `DescripcionUnidadMedida` tinytext NOT NULL,
  `EstadoUnidadMedida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `inventario_produccion_medidas`
--

INSERT INTO `inventario_produccion_medidas` (`IDUnidadMedida`, `DescripcionUnidadMedida`, `EstadoUnidadMedida`) VALUES
(1, 'KG', 1),
(2, 'LTS', 1),
(3, 'UND', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_produccion_movimientos`
--

CREATE TABLE `inventario_produccion_movimientos` (
  `IDInvProduccionMov` int(11) NOT NULL,
  `FechaRegMov` datetime NOT NULL DEFAULT current_timestamp(),
  `FechaMov` date NOT NULL,
  `TipoMov` int(11) NOT NULL,
  `IDInvProduccion` int(11) NOT NULL,
  `ExistenciaAnterior` decimal(20,6) NOT NULL,
  `Movimiento` decimal(20,6) NOT NULL,
  `ExistenciaActual` decimal(20,6) NOT NULL,
  `ObservacionMov` tinytext NOT NULL,
  `ResponsableMov` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `inventario_produccion_movimientos`
--

INSERT INTO `inventario_produccion_movimientos` (`IDInvProduccionMov`, `FechaRegMov`, `FechaMov`, `TipoMov`, `IDInvProduccion`, `ExistenciaAnterior`, `Movimiento`, `ExistenciaActual`, `ObservacionMov`, `ResponsableMov`) VALUES
(1, '2025-04-15 15:09:39', '2025-04-15', 2, 13, 0.000000, 10.000000, 0.000000, 'LOTE NRO 1', 'DEP SISTEMAS'),
(2, '2025-04-15 15:09:40', '2025-04-15', 2, 14, 0.000000, 20.000000, 0.000000, 'LOTE NRO 1', 'DEP SISTEMAS'),
(3, '2025-04-15 15:09:40', '2025-04-15', 2, 15, 0.000000, 30.000000, 0.000000, 'LOTE NRO 1', 'DEP SISTEMAS'),
(4, '2025-04-15 15:09:40', '2025-04-15', 2, 16, 0.000000, 40.000000, 0.000000, 'LOTE NRO 1', 'DEP SISTEMAS'),
(5, '2025-04-15 15:09:40', '2025-04-15', 2, 17, 0.000000, 50.000000, 0.000000, 'LOTE NRO 1', 'DEP SISTEMAS'),
(6, '2025-04-15 15:09:40', '2025-04-15', 2, 18, 0.000000, 5.000000, 0.000000, 'LOTE NRO 1', 'DEP SISTEMAS'),
(7, '2025-04-15 18:08:58', '2025-04-15', 2, 7, 79000.000000, 5000.000000, 74000.000000, 'PRODUCCION LOTE NRO 00002', 'DEP SISTEMAS'),
(8, '2025-04-15 18:09:21', '2025-04-15', 2, 13, 0.000000, 100.000000, 0.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(9, '2025-04-15 18:09:21', '2025-04-15', 2, 14, 0.000000, 100.000000, 0.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(10, '2025-04-15 18:09:21', '2025-04-15', 2, 15, 0.000000, 100.000000, 0.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(11, '2025-04-15 18:09:21', '2025-04-15', 2, 16, 0.000000, 100.000000, 0.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(12, '2025-04-15 18:09:21', '2025-04-15', 2, 17, 0.000000, 100.000000, 0.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(13, '2025-04-15 18:09:21', '2025-04-15', 2, 18, 0.000000, 50.000000, 0.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(14, '2025-04-15 18:09:26', '2025-04-15', 2, 13, 100.000000, 100.000000, 100.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(15, '2025-04-15 18:09:26', '2025-04-15', 2, 14, 100.000000, 100.000000, 100.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(16, '2025-04-15 18:09:26', '2025-04-15', 2, 15, 100.000000, 100.000000, 100.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(17, '2025-04-15 18:09:26', '2025-04-15', 2, 16, 100.000000, 100.000000, 100.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(18, '2025-04-15 18:09:26', '2025-04-15', 2, 17, 100.000000, 100.000000, 100.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(19, '2025-04-15 18:09:26', '2025-04-15', 2, 18, 50.000000, 50.000000, 50.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(20, '2025-04-15 18:11:27', '2025-04-15', 2, 13, 200.000000, 100.000000, 200.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(21, '2025-04-15 18:11:27', '2025-04-15', 2, 14, 200.000000, 100.000000, 200.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(22, '2025-04-15 18:11:27', '2025-04-15', 2, 15, 200.000000, 100.000000, 200.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(23, '2025-04-15 18:11:27', '2025-04-15', 2, 16, 200.000000, 100.000000, 200.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(24, '2025-04-15 18:11:27', '2025-04-15', 2, 17, 200.000000, 100.000000, 200.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(25, '2025-04-15 18:11:27', '2025-04-15', 2, 18, 100.000000, 50.000000, 100.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(26, '2025-04-15 18:49:56', '2025-04-15', 2, 13, 300.000000, 100.000000, 300.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(27, '2025-04-15 18:49:56', '2025-04-15', 2, 14, 300.000000, 100.000000, 300.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(28, '2025-04-15 18:49:56', '2025-04-15', 2, 15, 300.000000, 100.000000, 300.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(29, '2025-04-15 18:49:56', '2025-04-15', 2, 16, 300.000000, 100.000000, 300.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(30, '2025-04-15 18:49:56', '2025-04-15', 2, 17, 300.000000, 100.000000, 300.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(31, '2025-04-15 18:49:56', '2025-04-15', 2, 18, 150.000000, 50.000000, 150.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(32, '2025-04-15 18:50:33', '2025-04-15', 2, 13, 400.000000, 100.000000, 400.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(33, '2025-04-15 18:50:33', '2025-04-15', 2, 14, 400.000000, 100.000000, 400.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(34, '2025-04-15 18:50:33', '2025-04-15', 2, 15, 400.000000, 100.000000, 400.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(35, '2025-04-15 18:50:33', '2025-04-15', 2, 16, 400.000000, 100.000000, 400.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(36, '2025-04-15 18:50:33', '2025-04-15', 2, 17, 400.000000, 100.000000, 400.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(37, '2025-04-15 18:50:33', '2025-04-15', 2, 18, 200.000000, 50.000000, 200.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(38, '2025-04-16 08:33:34', '2025-04-16', 2, 13, 500.000000, 1.000000, 500.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(39, '2025-04-16 08:33:34', '2025-04-16', 2, 14, 500.000000, 1.000000, 500.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(40, '2025-04-16 08:33:34', '2025-04-16', 2, 15, 500.000000, 1.000000, 500.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(41, '2025-04-16 08:33:34', '2025-04-16', 2, 16, 500.000000, 1.000000, 500.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(42, '2025-04-16 08:33:34', '2025-04-16', 2, 17, 500.000000, 1.000000, 500.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(43, '2025-04-16 08:33:34', '2025-04-16', 2, 18, 250.000000, 1.000000, 250.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(44, '2025-04-16 08:33:46', '2025-04-16', 2, 13, 501.000000, 1.000000, 501.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(45, '2025-04-16 08:33:46', '2025-04-16', 2, 14, 501.000000, 1.000000, 501.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(46, '2025-04-16 08:33:46', '2025-04-16', 2, 15, 501.000000, 1.000000, 501.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(47, '2025-04-16 08:33:46', '2025-04-16', 2, 16, 501.000000, 1.000000, 501.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(48, '2025-04-16 08:33:46', '2025-04-16', 2, 17, 501.000000, 1.000000, 501.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(49, '2025-04-16 08:33:46', '2025-04-16', 2, 18, 251.000000, 1.000000, 251.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(50, '2025-04-16 08:44:30', '2025-04-16', 2, 13, 502.000000, 1.000000, 502.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(51, '2025-04-16 08:44:31', '2025-04-16', 2, 14, 502.000000, 1.000000, 502.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(52, '2025-04-16 08:44:31', '2025-04-16', 2, 15, 502.000000, 1.000000, 502.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(53, '2025-04-16 08:44:31', '2025-04-16', 2, 16, 502.000000, 1.000000, 502.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(54, '2025-04-16 08:44:31', '2025-04-16', 2, 17, 502.000000, 1.000000, 502.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(55, '2025-04-16 08:44:31', '2025-04-16', 2, 18, 252.000000, 1.000000, 252.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(56, '2025-04-16 08:45:32', '2025-04-16', 2, 13, 503.000000, 1.000000, 503.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(57, '2025-04-16 08:45:32', '2025-04-16', 2, 14, 503.000000, 1.000000, 503.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(58, '2025-04-16 08:45:32', '2025-04-16', 2, 15, 503.000000, 1.000000, 503.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(59, '2025-04-16 08:45:32', '2025-04-16', 2, 16, 503.000000, 1.000000, 503.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(60, '2025-04-16 08:45:32', '2025-04-16', 2, 17, 503.000000, 1.000000, 503.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(61, '2025-04-16 08:45:32', '2025-04-16', 2, 18, 253.000000, 1.000000, 253.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(62, '2025-04-16 08:46:00', '2025-04-16', 2, 13, 504.000000, 1.000000, 504.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(63, '2025-04-16 08:46:00', '2025-04-16', 2, 14, 504.000000, 1.000000, 504.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(64, '2025-04-16 08:46:00', '2025-04-16', 2, 15, 504.000000, 1.000000, 504.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(65, '2025-04-16 08:46:00', '2025-04-16', 2, 16, 504.000000, 1.000000, 504.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(66, '2025-04-16 08:46:00', '2025-04-16', 2, 17, 504.000000, 1.000000, 504.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(67, '2025-04-16 08:46:00', '2025-04-16', 2, 18, 254.000000, 1.000000, 254.000000, 'LOTE NRO 2', 'DEP SISTEMAS'),
(68, '2025-04-16 09:59:20', '2025-04-16', 2, 13, 505.000000, 5.000000, 505.000000, 'ANULACION DE PRODUCCION DE  2025-04-16', 'DEP SISTEMAS'),
(69, '2025-04-16 09:59:20', '2025-04-16', 2, 14, 505.000000, 5.000000, 505.000000, 'ANULACION DE PRODUCCION DE  2025-04-16', 'DEP SISTEMAS'),
(70, '2025-04-16 09:59:20', '2025-04-16', 2, 15, 505.000000, 5.000000, 505.000000, 'ANULACION DE PRODUCCION DE  2025-04-16', 'DEP SISTEMAS'),
(71, '2025-04-16 09:59:20', '2025-04-16', 2, 16, 505.000000, 5.000000, 505.000000, 'ANULACION DE PRODUCCION DE  2025-04-16', 'DEP SISTEMAS'),
(72, '2025-04-16 09:59:21', '2025-04-16', 2, 17, 505.000000, 5.000000, 505.000000, 'ANULACION DE PRODUCCION DE  2025-04-16', 'DEP SISTEMAS'),
(73, '2025-04-16 09:59:21', '2025-04-16', 2, 18, 255.000000, 5.000000, 255.000000, 'ANULACION DE PRODUCCION DE  2025-04-16', 'DEP SISTEMAS'),
(74, '2025-04-16 11:14:07', '2025-04-16', 2, 13, 500.000000, 10.000000, 500.000000, 'ANULACION DE PRODUCCION DE  2025-04-15', 'DEP SISTEMAS'),
(75, '2025-04-16 11:14:07', '2025-04-16', 2, 14, 500.000000, 20.000000, 500.000000, 'ANULACION DE PRODUCCION DE  2025-04-15', 'DEP SISTEMAS'),
(76, '2025-04-16 11:14:07', '2025-04-16', 2, 15, 500.000000, 30.000000, 500.000000, 'ANULACION DE PRODUCCION DE  2025-04-15', 'DEP SISTEMAS'),
(77, '2025-04-16 11:14:07', '2025-04-16', 2, 16, 500.000000, 40.000000, 500.000000, 'ANULACION DE PRODUCCION DE  2025-04-15', 'DEP SISTEMAS'),
(78, '2025-04-16 11:14:07', '2025-04-16', 2, 17, 500.000000, 50.000000, 500.000000, 'ANULACION DE PRODUCCION DE  2025-04-15', 'DEP SISTEMAS'),
(79, '2025-04-16 11:14:07', '2025-04-16', 2, 18, 250.000000, 5.000000, 250.000000, 'ANULACION DE PRODUCCION DE  2025-04-15', 'DEP SISTEMAS'),
(80, '2025-04-16 11:22:38', '2025-04-16', 1, 7, 74000.000000, 1000.000000, 75000.000000, 'ANULACION DE PRODUCCION NRO DE LOTE 00001', 'DEP SISTEMAS'),
(81, '2025-04-21 11:10:00', '2025-04-21', 2, 7, 75000.000000, 1000.000000, 74000.000000, 'PRODUCCION LOTE NRO 00003', 'DEP SISTEMAS'),
(82, '2025-04-21 11:43:59', '2025-04-21', 1, 7, 74000.000000, 1000.000000, 75000.000000, 'ANULACION DE PRODUCCION NRO DE LOTE 00003', 'DEP SISTEMAS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_produccion_tipos_productos`
--

CREATE TABLE `inventario_produccion_tipos_productos` (
  `IDTipoProducto` int(11) NOT NULL,
  `FechaRegTipoProducto` datetime NOT NULL DEFAULT current_timestamp(),
  `EditarProductoXTipo` int(11) NOT NULL,
  `DescripcionTipoProducto` tinytext NOT NULL,
  `EstadoTipoProducto` int(11) NOT NULL,
  `UltimaActualiacionTipoProducto` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `inventario_produccion_tipos_productos`
--

INSERT INTO `inventario_produccion_tipos_productos` (`IDTipoProducto`, `FechaRegTipoProducto`, `EditarProductoXTipo`, `DescripcionTipoProducto`, `EstadoTipoProducto`, `UltimaActualiacionTipoProducto`) VALUES
(1, '2025-02-24 15:06:20', 1, 'MATERIA PRIMA', 1, NULL),
(2, '2025-02-24 15:06:20', 1, 'EMPAQUES', 1, NULL),
(3, '2025-02-24 15:06:20', 1, 'COSTOS', 1, NULL),
(5, '2025-02-24 15:06:20', 1, 'SUBPRODUCTOS', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_detalles`
--

CREATE TABLE `produccion_detalles` (
  `IDProduccionDetalle` int(11) NOT NULL,
  `IDProduccionResumen` int(11) NOT NULL,
  `IDInvProduccion` int(11) NOT NULL,
  `CostoUtilizado` decimal(20,4) NOT NULL,
  `CantidadUtilizada` decimal(20,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `produccion_detalles`
--

INSERT INTO `produccion_detalles` (`IDProduccionDetalle`, `IDProduccionResumen`, `IDInvProduccion`, `CostoUtilizado`, `CantidadUtilizada`) VALUES
(1, 1, 7, 0.3500, 1000.000000),
(2, 2, 7, 0.3500, 5000.000000),
(3, 2, 9, 20.0000, 1.000000),
(4, 2, 10, 15.0000, 2.000000),
(5, 2, 12, 13.3333, 3.000000),
(6, 2, 11, 12.5000, 4.000000),
(7, 3, 7, 0.3500, 1000.000000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_resumen`
--

CREATE TABLE `produccion_resumen` (
  `IDProduccionResumen` int(11) NOT NULL,
  `FechaRegProduccion` datetime NOT NULL DEFAULT current_timestamp(),
  `FechaProduccion` date NOT NULL,
  `FechaCaducidad` date NOT NULL,
  `IDArticulo` int(11) NOT NULL,
  `IDEmpaque` int(11) NOT NULL,
  `NroLote` int(11) NOT NULL,
  `CantidadProducida` int(11) NOT NULL,
  `ResponsableProduccion` tinytext NOT NULL,
  `EstadoProduccion` int(11) NOT NULL,
  `UltimaActualizacionProduccion` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `produccion_resumen`
--

INSERT INTO `produccion_resumen` (`IDProduccionResumen`, `FechaRegProduccion`, `FechaProduccion`, `FechaCaducidad`, `IDArticulo`, `IDEmpaque`, `NroLote`, `CantidadProducida`, `ResponsableProduccion`, `EstadoProduccion`, `UltimaActualizacionProduccion`) VALUES
(1, '2025-04-14 13:32:24', '2025-04-14', '2025-10-11', 1, 4, 1, 0, 'DEP SISTEMAS', 0, '2025-04-16 11:22:38 AM - DEP SISTEMAS'),
(2, '2025-04-15 18:08:58', '2025-04-15', '2025-10-12', 1, 4, 2, 1450, 'DEP SISTEMAS', 2, NULL),
(3, '2025-04-21 11:10:00', '2025-04-21', '2025-10-18', 1, 4, 3, 0, 'DEP SISTEMAS', 0, '2025-04-21 11:43:59 AM - DEP SISTEMAS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion_subproductos`
--

CREATE TABLE `produccion_subproductos` (
  `IDProduccionSubProductos` int(11) NOT NULL,
  `Fecha` date NOT NULL DEFAULT current_timestamp(),
  `IDProduccionResumen` int(11) NOT NULL,
  `IDInvProduccion` int(11) NOT NULL,
  `Cantidad` decimal(20,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `produccion_subproductos`
--

INSERT INTO `produccion_subproductos` (`IDProduccionSubProductos`, `Fecha`, `IDProduccionResumen`, `IDInvProduccion`, `Cantidad`) VALUES
(30, '2025-04-15', 2, 1, 1500.000000),
(31, '2025-04-15', 2, 13, 100.000000),
(32, '2025-04-15', 2, 14, 100.000000),
(33, '2025-04-15', 2, 15, 100.000000),
(34, '2025-04-15', 2, 16, 100.000000),
(35, '2025-04-15', 2, 17, 100.000000),
(36, '2025-04-15', 2, 18, 50.000000),
(37, '2025-04-15', 2, 1, 1500.000000),
(38, '2025-04-15', 2, 13, 100.000000),
(39, '2025-04-15', 2, 14, 100.000000),
(40, '2025-04-15', 2, 15, 100.000000),
(41, '2025-04-15', 2, 16, 100.000000),
(42, '2025-04-15', 2, 17, 100.000000),
(43, '2025-04-15', 2, 18, 50.000000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `IDUsuario` int(11) NOT NULL,
  `FechaRegUsuario` datetime NOT NULL DEFAULT current_timestamp(),
  `NombreUsuario` tinytext NOT NULL,
  `Usuario` tinytext NOT NULL,
  `Clave` tinytext NOT NULL,
  `IDPrivilegio` int(11) NOT NULL,
  `EstadoUsuario` int(11) NOT NULL,
  `UltimaActualizacionUsuario` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`IDUsuario`, `FechaRegUsuario`, `NombreUsuario`, `Usuario`, `Clave`, `IDPrivilegio`, `EstadoUsuario`, `UltimaActualizacionUsuario`) VALUES
(1, '2025-02-24 11:21:27', 'DEP SISTEMAS', 'sistemas', '$2y$10$xSGYSZ0zzZ.Y/pqxUh4dv.kZjfCaHG3xtLq9268vGbWjnEJZgOqru', 1, 1, NULL),
(20, '2025-03-11 12:44:44', 'Zurima Rivas', 'Zurima', '$2y$10$vVfSJt6oUCLQsCjqvoTMW.iUiVkVOm4bMG0heVHQZ7FM24MzztIo2', 1, 1, NULL),
(21, '2025-03-12 08:49:05', 'Roberto Lopez', 'Roberto', '$2y$10$spqie/4ZVTsK/cI/NO7r5.VbATGlaFsPeaXLB2bPT3YVAc.98upcK', 1, 1, NULL),
(22, '2025-03-12 11:06:11', 'Nidia Ortega', 'Nidia', '$2y$10$jGe2JQQiWX11nHHKZwKrUOSJWniR9Bpd6e5P.jqSn1Y5OON/w01yC', 1, 1, NULL),
(23, '2025-03-12 11:06:28', 'Ana Solozarno', 'Ana', '$2y$10$aXwmaqpZWl4AWJRCexYytOdttrtp5qjHGXsFmrNA1ipc4.HMGYcWK', 1, 1, NULL),
(24, '2025-03-02 12:49:52', 'Wilmer', 'wilmer', '$2y$10$MDcoG7YtUzoXTWc7NCWZxOlA7gdHjyq4zDR26C6FbTVm4pVyt0soa', 1, 1, NULL),
(27, '2025-03-24 15:12:08', 'Adrian', 'Adrian', '$2y$10$IJveyh1F3Q/tYyZwOq1jLuCNkJ/.v3rWjOziamHadki.LUfj9W7dq', 1, 1, NULL),
(28, '2025-04-07 16:03:21', 'ROSA', 'ROSA', '$2y$10$/ln2Sq36xjYeIt8YXh3hR.UK1MFSUFS4iPO56pPxXcYchn73JMphC', 1, 1, NULL),
(29, '2025-04-07 16:03:39', 'MARIELY', 'MARIELY', '$2y$10$K01gG4wqoEYudzeybyYPbeWAkpwJnlUgMv31.J5JNqMbZQ1ZOfp9O', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_privilegios`
--

CREATE TABLE `usuarios_privilegios` (
  `IDPrivilegio` int(11) NOT NULL,
  `FechaRegPrivilegio` datetime NOT NULL DEFAULT current_timestamp(),
  `DescripcionPrivilegio` tinytext NOT NULL,
  `EstadoPrivilegio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios_privilegios`
--

INSERT INTO `usuarios_privilegios` (`IDPrivilegio`, `FechaRegPrivilegio`, `DescripcionPrivilegio`, `EstadoPrivilegio`) VALUES
(1, '2025-02-24 11:21:50', 'ADMINISTRADOR', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`IDArticulo`);

--
-- Indices de la tabla `articulos_alicuotas`
--
ALTER TABLE `articulos_alicuotas`
  ADD PRIMARY KEY (`IDAlicuota`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`IDCliente`);

--
-- Indices de la tabla `despachos_detalles`
--
ALTER TABLE `despachos_detalles`
  ADD PRIMARY KEY (`IDDespachoDetalle`);

--
-- Indices de la tabla `despachos_resumen`
--
ALTER TABLE `despachos_resumen`
  ADD PRIMARY KEY (`IDDespachoResumen`);

--
-- Indices de la tabla `despachos_tipos`
--
ALTER TABLE `despachos_tipos`
  ADD PRIMARY KEY (`IDTipoDespacho`);

--
-- Indices de la tabla `formulas`
--
ALTER TABLE `formulas`
  ADD PRIMARY KEY (`IDFormulaProduccion`);

--
-- Indices de la tabla `inventario_planta`
--
ALTER TABLE `inventario_planta`
  ADD PRIMARY KEY (`IDInvPlanta`);

--
-- Indices de la tabla `inventario_planta_existencia`
--
ALTER TABLE `inventario_planta_existencia`
  ADD PRIMARY KEY (`IDInventarioPlantaValor`);

--
-- Indices de la tabla `inventario_planta_movimientos`
--
ALTER TABLE `inventario_planta_movimientos`
  ADD PRIMARY KEY (`IDInvPlantaMov`);

--
-- Indices de la tabla `inventario_produccion`
--
ALTER TABLE `inventario_produccion`
  ADD PRIMARY KEY (`IDInvProduccion`);

--
-- Indices de la tabla `inventario_produccion_conteo`
--
ALTER TABLE `inventario_produccion_conteo`
  ADD PRIMARY KEY (`IDInvProduccionConteo`);

--
-- Indices de la tabla `inventario_produccion_existencia`
--
ALTER TABLE `inventario_produccion_existencia`
  ADD PRIMARY KEY (`IDInvProduccionExistencia`);

--
-- Indices de la tabla `inventario_produccion_medidas`
--
ALTER TABLE `inventario_produccion_medidas`
  ADD PRIMARY KEY (`IDUnidadMedida`);

--
-- Indices de la tabla `inventario_produccion_movimientos`
--
ALTER TABLE `inventario_produccion_movimientos`
  ADD PRIMARY KEY (`IDInvProduccionMov`);

--
-- Indices de la tabla `inventario_produccion_tipos_productos`
--
ALTER TABLE `inventario_produccion_tipos_productos`
  ADD PRIMARY KEY (`IDTipoProducto`);

--
-- Indices de la tabla `produccion_detalles`
--
ALTER TABLE `produccion_detalles`
  ADD PRIMARY KEY (`IDProduccionDetalle`);

--
-- Indices de la tabla `produccion_resumen`
--
ALTER TABLE `produccion_resumen`
  ADD PRIMARY KEY (`IDProduccionResumen`);

--
-- Indices de la tabla `produccion_subproductos`
--
ALTER TABLE `produccion_subproductos`
  ADD PRIMARY KEY (`IDProduccionSubProductos`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IDUsuario`);

--
-- Indices de la tabla `usuarios_privilegios`
--
ALTER TABLE `usuarios_privilegios`
  ADD PRIMARY KEY (`IDPrivilegio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulos`
--
ALTER TABLE `articulos`
  MODIFY `IDArticulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `articulos_alicuotas`
--
ALTER TABLE `articulos_alicuotas`
  MODIFY `IDAlicuota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `IDCliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `despachos_detalles`
--
ALTER TABLE `despachos_detalles`
  MODIFY `IDDespachoDetalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `despachos_resumen`
--
ALTER TABLE `despachos_resumen`
  MODIFY `IDDespachoResumen` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `despachos_tipos`
--
ALTER TABLE `despachos_tipos`
  MODIFY `IDTipoDespacho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `formulas`
--
ALTER TABLE `formulas`
  MODIFY `IDFormulaProduccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `inventario_planta`
--
ALTER TABLE `inventario_planta`
  MODIFY `IDInvPlanta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `inventario_planta_existencia`
--
ALTER TABLE `inventario_planta_existencia`
  MODIFY `IDInventarioPlantaValor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario_planta_movimientos`
--
ALTER TABLE `inventario_planta_movimientos`
  MODIFY `IDInvPlantaMov` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `inventario_produccion`
--
ALTER TABLE `inventario_produccion`
  MODIFY `IDInvProduccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `inventario_produccion_conteo`
--
ALTER TABLE `inventario_produccion_conteo`
  MODIFY `IDInvProduccionConteo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `inventario_produccion_existencia`
--
ALTER TABLE `inventario_produccion_existencia`
  MODIFY `IDInvProduccionExistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario_produccion_medidas`
--
ALTER TABLE `inventario_produccion_medidas`
  MODIFY `IDUnidadMedida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `inventario_produccion_movimientos`
--
ALTER TABLE `inventario_produccion_movimientos`
  MODIFY `IDInvProduccionMov` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT de la tabla `inventario_produccion_tipos_productos`
--
ALTER TABLE `inventario_produccion_tipos_productos`
  MODIFY `IDTipoProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `produccion_detalles`
--
ALTER TABLE `produccion_detalles`
  MODIFY `IDProduccionDetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `produccion_resumen`
--
ALTER TABLE `produccion_resumen`
  MODIFY `IDProduccionResumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `produccion_subproductos`
--
ALTER TABLE `produccion_subproductos`
  MODIFY `IDProduccionSubProductos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IDUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `usuarios_privilegios`
--
ALTER TABLE `usuarios_privilegios`
  MODIFY `IDPrivilegio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
