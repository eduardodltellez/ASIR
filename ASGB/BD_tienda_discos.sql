-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-03-2019 a las 12:40:34
-- Versión del servidor: 10.1.35-MariaDB
-- Versión de PHP: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda_discos`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ampliar_contratos` ()  BEGIN

DECLARE fin boolean default false;
DECLARE id_contrato int;

DECLARE micursor CURSOR FOR select id from contratos where year(fecha_fin)=year(now());
DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin=true;
 
OPEN micursor;

WHILE not fin DO

FETCH micursor into id_contrato;

if not fin then

  update contratos set fecha_fin = date_add(fecha_fin, interval 1 year) where id=id_contrato;
  
end if;

END WHILE;

CLOSE micursor;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `direccion` (IN `num_pedido` INT)  begin

if num_pedido <=0 then
select "Número de pedido incorrecto";

else     
select nombre, apellidos, provincia, ciudad, codigo_postal, calle, numero, piso from direccion_clientes d join clientes c on d.dni_cliente=c.dni join pedidos p on c.dni=p.dni_cliente where p.id=num_pedido;

end if;
    
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `estado_pedidos` (`primero` INT, `ultimo` INT)  begin

if primero >0 and ultimo>=primero then
repeat
select id as "Pedido", dni_cliente, estado from pedidos where id=primero;
set primero=primero+1;
until primero>ultimo
end repeat;

else
select "Los parámetros no son correctos";
end if;
    
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `facturacion_anio` (`anio` INT)  begin

DECLARE fin boolean default false;
DECLARE id_ped int;
DECLARE resultado double default 0;
DECLARE prov_resultado double;

DECLARE micursor CURSOR FOR select id_pedido from facturas_clientes where year(fecha_emision)=anio;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin=true;
 
OPEN micursor;

WHILE not fin DO

FETCH micursor into id_ped;

if not fin then

select round(sum(precio_unidad*1.10),2) from discos join discos_pedidos on num_referencia=num_ref_disco where id_pedido=id_ped into prov_resultado;

set resultado=resultado+prov_resultado;

end if;

end while;

close micursor;

select resultado as "Facturación";
   
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `fin_contrato` (`dni_empleado` VARCHAR(9))  begin
declare anios, meses, dias, resto int;
select datediff(fecha_fin, now()) from contratos join trabajadores on num_ss_trabajador=numero_ss where dni=dni_empleado into dias;
set anios=dias div 365; 
set resto=dias % 365; 
set meses=resto div 30; 
set dias=resto % 30;
select concat(anios, " años ", meses, " meses y ", dias, " días de contrato") as "Le quedan";
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ganado_total` (`t_dni` VARCHAR(9))  begin
declare n_ss decimal(12,0);
declare t_id, meses_trabajados int;
declare total_ganado double;
select numero_ss from trabajadores where dni=t_dni into n_ss;
select id, TIMESTAMPDIFF(MONTH, fecha_inicio, now()) from contratos where num_ss_trabajador=n_ss into t_id, meses_trabajados;
select round(salario * meses_trabajados,2) from nominas where id=t_id into total_ganado;
select total_ganado as "Total Euros ganados";    
end$$

--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `pedidos_mes` (`mes` INT(2), `anio` INT(4)) RETURNS INT(11) begin
declare resultado int; 
select count(*) from pedidos where year(fecha_realizacion)=anio and month(fecha_realizacion)=mes into resultado;
return resultado; 
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `salario_anual` (`dni_empleado` VARCHAR(9)) RETURNS DECIMAL(9,2) begin
declare resultado decimal(9,2);
select salario * 14 from nominas n join contratos c on n.id_contrato=c.id join trabajadores t on c.num_ss_trabajador=t.numero_ss where dni=dni_empleado into resultado;
return resultado;
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `stock_bajo` (`ref_disco` INT(10)) RETURNS TINYINT(1) begin
declare stock_disco int;
declare resultado boolean default false; 
select stock from discos where num_referencia=ref_disco into stock_disco;
if stock_disco <= 10 then
set resultado=true;
end if;
return resultado; 
end$$

CREATE DEFINER=`root`@`localhost` FUNCTION `stock_total` () RETURNS INT(11) begin
declare resultado int; 
 
select sum(stock) from discos into resultado;
 
return resultado;
 
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agencias_transporte`
--

CREATE TABLE `agencias_transporte` (
  `cif` varchar(9) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `telefono` decimal(9,0) DEFAULT NULL,
  `e_mail` varchar(40) DEFAULT NULL,
  `metodo_transporte` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `agencias_transporte`
--

INSERT INTO `agencias_transporte` (`cif`, `nombre`, `telefono`, `e_mail`, `metodo_transporte`) VALUES
('102204583', 'Inmotrans', '633011589', 'inmotrans@gmail.com', 'Peninsula y Baleares'),
('158966400', 'Transportalia', '665521307', 'transportalia@hotmail.com', '24 horas'),
('325488706', 'Rapidtrans', '771125680', 'rapidtrans@hotmail.com', 'Urgente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cds`
--

CREATE TABLE `cds` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo` enum('CD','DVD','BLUE-RAY') DEFAULT NULL,
  `num_ref_disco` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cds`
--

INSERT INTO `cds` (`id`, `tipo`, `num_ref_disco`) VALUES
(1, 'CD', 56789),
(2, 'CD', 45609),
(3, 'DVD', 57412),
(4, 'BLUE-RAY', 37890),
(5, 'BLUE-RAY', 89651),
(6, 'CD', 89564),
(7, 'DVD', 22869),
(8, 'DVD', 47258),
(9, 'CD', 74982),
(10, 'BLUE-RAY', 83256),
(11, 'CD', 45896),
(12, 'CD', 14589);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `apellidos` varchar(40) DEFAULT NULL,
  `e_mail` varchar(40) DEFAULT NULL,
  `telefono` decimal(9,0) DEFAULT NULL,
  `num_tarjeta_credito` decimal(16,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`dni`, `nombre`, `apellidos`, `e_mail`, `telefono`, `num_tarjeta_credito`) VALUES
('25898741Z', 'Fernando', 'Fajardo Cepa', 'fernandocepas@gmail.com', '655887412', '8788963321258475'),
('48552589K', 'Noelia', 'Fernandez Ochoa', 'noefbk@hotmail.com', '759824563', '6987452001145698'),
('58793214F', 'Rodrigo', 'Garcia Carrion', 'rodricarrion35@hotmail.com', '693214588', '2310056987453014'),
('65894125P', 'Amanda', 'Trujillo Incertis', 'amanda56@hotmail.com', '755213694', '5698541230259787'),
('71698331S', 'Carmen', 'Gracia Laguna', 'carmengracia30@gmail.com', '654159735', '8954732001459036'),
('73550486B', 'Diana', 'Romero Clemente', 'diana57@hotmail.com', '687522456', '8874566320025897'),
('78563256W', 'Eladio', 'Cortes Perez', 'eladiocortes@gmail.com', '926585413', '8965321458974563'),
('78990012H', 'Francisco', 'Osuna Galvez', 'pacoosuna75@gmail.com', '663201484', '4552001326985471');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratos`
--

CREATE TABLE `contratos` (
  `id` int(10) UNSIGNED NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `categoria` enum('Administrativo','Logistica','Programador','Jefe') DEFAULT NULL,
  `num_ss_trabajador` decimal(12,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `contratos`
--

INSERT INTO `contratos` (`id`, `fecha_inicio`, `fecha_fin`, `categoria`, `num_ss_trabajador`) VALUES
(1, '2015-05-01', '2022-05-01', 'Programador', '865325698541'),
(2, '2017-04-05', '2020-04-05', 'Administrativo', '754625986532'),
(3, '2014-09-08', '2024-09-08', 'Programador', '898745145632'),
(4, '2015-01-15', '2020-01-15', 'Administrativo', '748489563254'),
(5, '2016-08-24', '2024-08-24', 'Programador', '125458976326'),
(6, '2018-01-01', '2020-01-01', 'Administrativo', '896573652145'),
(7, '2015-12-05', '2023-12-05', 'Logistica', '877963325411'),
(8, '2017-05-21', '2020-05-21', 'Logistica', '956832564785'),
(9, '2016-02-05', '2023-02-05', 'Logistica', '596832148956'),
(10, '2014-04-01', '2028-04-01', 'Jefe', '757575757575');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curriculums`
--

CREATE TABLE `curriculums` (
  `nombre` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `apellidos` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `dni` varchar(9) NOT NULL,
  `puesto` varchar(15) DEFAULT NULL,
  `sexo` varchar(6) DEFAULT NULL,
  `estudios` varchar(15) DEFAULT NULL,
  `experiencia` varchar(5) DEFAULT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `documento` varchar(50) DEFAULT NULL,
  `foto` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `curriculums`
--

INSERT INTO `curriculums` (`nombre`, `apellidos`, `dni`, `puesto`, `sexo`, `estudios`, `experiencia`, `descripcion`, `documento`, `foto`) VALUES
('Esperanza', 'Gomez Torralba', '50258951H', 'Programador Web', 'Mujer', 'Licenciatura', '5-10', 'Eficaz', 'curriculums/Esperanza_Gomez_Torralba.pdf', 'fotos/Esperanza_Gomez_Torralba.jpg'),
('Antonio', 'Verdejo Navarro', '60258471N', 'Peon', 'Hombre', 'Primaria', '20+', 'Rapido', 'curriculums/Antonio_Verdejo_Navarro.pdf', 'fotos/Antonio_Verdejo_Navarro.jpg'),
('Laura', 'Gimenez', '65741258J', 'Logistica', 'Mujer', 'Grado Medio', '1-5', 'Cooperacion', 'curriculums/Laura_Gimenez_Castillo.pdf', 'fotos/Laura_Gimenez_Castillo.jpg'),
('Tamara', 'Rodriguez Murillo', '70245895P', 'Programador Web', 'Mujer', 'Grado Superior', '1-5', 'Puntual', 'curriculums/Tamara_Rodriguez_Murillo.pdf', 'fotos/Tamara_rodriguez_murillo.jpeg'),
('Alberto', 'Fernandez de las Cuevas', '71584365Q', 'Peon', 'Hombre', 'Grado Medio', '10-20', 'Trabajador', 'curriculums/Alberto_Fernandez_Cuevas.pdf', 'fotos/Alberto_Fernandez_Cuevas.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion_clientes`
--

CREATE TABLE `direccion_clientes` (
  `id` int(10) UNSIGNED NOT NULL,
  `provincia` varchar(30) DEFAULT NULL,
  `ciudad` varchar(30) DEFAULT NULL,
  `codigo_postal` decimal(5,0) DEFAULT NULL,
  `calle` varchar(30) DEFAULT NULL,
  `numero` varchar(5) DEFAULT NULL,
  `piso` varchar(3) DEFAULT NULL,
  `dni_cliente` varchar(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `direccion_clientes`
--

INSERT INTO `direccion_clientes` (`id`, `provincia`, `ciudad`, `codigo_postal`, `calle`, `numero`, `piso`, `dni_cliente`) VALUES
(1, 'Albacete', 'Villarobledo', '14520', 'Capitan Casado', '54', '0', '78563256W'),
(2, 'Madrid', 'Madrid', '50000', 'Alcala', '452', '1§C', '65894125P'),
(3, 'Barcelona', 'Cornella', '85690', 'Buendia', '104', '4§E', '71698331S'),
(4, 'Toledo', 'Torrijos', '40150', 'San Sebastian', '58', '0', '58793214F'),
(5, 'Burgos', 'Aranda de Duero', '80560', 'Marques de Verona', '145', '3§F', '78990012H'),
(6, 'Valencia', 'Sedav¡', '70155', 'Ramirez Lasala', '71', '2§E', '48552589K'),
(7, 'Granada', 'Durcal', '14580', 'San Marcos', '32', '0', '25898741Z'),
(8, 'Badajoz', 'Badajoz', '10257', 'Carneros', '168', '3§C', '73550486B');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discos`
--

CREATE TABLE `discos` (
  `num_referencia` int(10) UNSIGNED NOT NULL,
  `fecha_lanzamiento` date DEFAULT NULL,
  `artista` varchar(30) DEFAULT NULL,
  `titulo` varchar(50) DEFAULT NULL,
  `precio_unidad` decimal(5,2) DEFAULT NULL,
  `stock` int(10) UNSIGNED DEFAULT NULL,
  `cif_sello_discografico` varchar(9) DEFAULT NULL,
  `imagen` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `discos`
--

INSERT INTO `discos` (`num_referencia`, `fecha_lanzamiento`, `artista`, `titulo`, `precio_unidad`, `stock`, `cif_sello_discografico`, `imagen`) VALUES
(14589, '2016-10-29', 'Nick Curly', 'Dont Takers', '8.12', 70, '554012397', 'img_discos/disco3.png'),
(22869, '2018-02-14', 'Jay Lumen', 'Inside', '8.36', 70, '233147852', 'img_discos/disco.png'),
(37890, '2018-02-01', 'Oscar Mulero', 'Frio y Calor', '6.37', 54, '772201596', 'img_discos/disco2.png'),
(45609, '2018-01-30', 'Nicole Moudaber', 'Spring', '7.22', 50, '654630114', 'img_discos/disco.png'),
(45896, '2015-03-30', 'Eli Brown', 'Sumatra', '6.08', 66, '847710025', ''),
(47258, '2018-01-23', 'Carl Cox', 'See You', '6.41', 7, '233147852', 'img_discos/disco2.png'),
(55479, '2019-03-04', 'Charlotte the Witte', 'Stars', '7.83', 75, '233147852', NULL),
(56789, '2017-09-05', 'Paco Osuna', 'Mindblowing', '8.54', 100, '654630114', 'img_discos/disco3.png'),
(57412, '2017-05-16', 'Sam Michaels', 'Venore', '7.59', 124, '554012397', 'img_discos/disco.png'),
(74982, '2017-05-22', 'Mario Ochoa', 'Aldebaran', '5.08', 50, '654630114', ''),
(83256, '2016-08-19', 'Weska', 'Voyage', '5.61', 9, '233147852', 'img_discos/disco3.png'),
(89564, '2017-11-05', 'Stephan Bozin', 'Strand', '7.03', 80, '847710025', 'img_discos/disco2.png'),
(89651, '2019-01-15', 'Amelie Lens', 'Awakenings', '8.65', 142, '772201596', '');

--
-- Disparadores `discos`
--
DELIMITER $$
CREATE TRIGGER `descuento_drumcode` BEFORE INSERT ON `discos` FOR EACH ROW begin
declare cif_sello varchar(9);
select cif from sellos_discograficos where nombre="Drumcode" into cif_sello;
if new.cif_sello_discografico=cif_sello and new.precio_unidad > 8 then
set new.precio_unidad=new.precio_unidad-(new.precio_unidad*0.10);
end if;
end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `no_stock` AFTER UPDATE ON `discos` FOR EACH ROW begin
if new.stock=0 then
insert into sin_stock values (new.num_referencia, new.artista, new.titulo);
end if;
end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `si_stock` BEFORE UPDATE ON `discos` FOR EACH ROW begin
if old.stock=0 and new.stock > 0 then
delete from sin_stock where num_referencia=old.num_referencia;
end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discos_pedidos`
--

CREATE TABLE `discos_pedidos` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_pedido` int(10) UNSIGNED DEFAULT NULL,
  `num_ref_disco` int(10) UNSIGNED DEFAULT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `discos_pedidos`
--

INSERT INTO `discos_pedidos` (`id`, `id_pedido`, `num_ref_disco`, `cantidad`) VALUES
(1, 1, 56789, 1),
(2, 1, 57412, 1),
(3, 1, 89564, 1),
(4, 1, 22869, 1),
(5, 1, 83256, 1),
(6, 1, 45896, 1),
(7, 1, 14589, 1),
(8, 2, 56789, 1),
(9, 2, 45609, 1),
(10, 2, 57412, 1),
(11, 2, 37890, 1),
(12, 2, 89651, 1),
(13, 2, 89564, 1),
(14, 2, 22869, 1),
(15, 3, 22869, 1),
(16, 3, 47258, 1),
(17, 3, 74982, 1),
(18, 3, 83256, 1),
(19, 3, 45896, 1),
(20, 3, 47258, 1),
(21, 3, 14589, 1),
(22, 4, 56789, 1),
(23, 4, 45609, 1),
(24, 4, 57412, 1),
(25, 4, 37890, 1),
(26, 4, 89651, 1),
(27, 4, 89564, 1),
(28, 4, 22869, 1),
(29, 5, 22869, 1),
(30, 5, 47258, 1),
(31, 5, 74982, 1),
(32, 5, 83256, 1),
(33, 5, 45896, 1),
(34, 5, 47258, 1),
(35, 5, 14589, 1),
(36, 6, 56789, 1),
(37, 6, 57412, 1),
(38, 6, 89651, 1),
(39, 6, 22869, 1),
(40, 6, 74982, 1),
(41, 6, 45896, 1),
(42, 6, 45609, 1),
(43, 7, 45609, 2),
(44, 7, 37890, 2),
(45, 7, 89564, 2),
(46, 7, 47258, 2),
(47, 7, 83256, 2),
(48, 7, 14589, 2),
(49, 7, 74982, 2),
(50, 8, 56789, 3),
(51, 8, 57412, 3),
(52, 8, 89651, 3),
(53, 8, 22869, 3),
(54, 8, 74982, 3),
(55, 8, 45896, 3),
(56, 8, 45609, 3),
(57, 9, 45609, 2),
(58, 9, 37890, 2),
(59, 9, 89564, 2),
(60, 9, 47258, 2),
(61, 9, 83256, 2),
(62, 9, 14589, 2),
(63, 9, 74982, 2),
(64, 10, 56789, 1),
(65, 10, 57412, 1),
(66, 10, 89651, 1),
(67, 10, 22869, 1),
(68, 10, 74982, 1),
(69, 10, 45896, 1),
(70, 10, 45609, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas_clientes`
--

CREATE TABLE `facturas_clientes` (
  `num_referencia` int(10) UNSIGNED NOT NULL,
  `fecha_emision` date NOT NULL,
  `id_pedido` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `facturas_clientes`
--

INSERT INTO `facturas_clientes` (`num_referencia`, `fecha_emision`, `id_pedido`) VALUES
(1, '2015-06-23', 1),
(2, '2015-09-18', 2),
(3, '2017-12-16', 3),
(4, '2018-01-16', 4),
(5, '2018-02-04', 5),
(6, '2015-08-05', 6),
(7, '2015-12-10', 7),
(8, '2016-01-30', 8),
(9, '2018-03-22', 9),
(10, '2018-03-23', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mp3`
--

CREATE TABLE `mp3` (
  `id` int(10) UNSIGNED NOT NULL,
  `megas` decimal(3,1) DEFAULT NULL,
  `num_ref_disco` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `mp3`
--

INSERT INTO `mp3` (`id`, `megas`, `num_ref_disco`) VALUES
(1, '12.5', 56789),
(2, '13.3', 45609),
(3, '8.5', 57412),
(4, '9.2', 37890),
(5, '14.4', 89651),
(6, '8.9', 89564),
(7, '10.2', 22869),
(8, '12.1', 47258),
(9, '11.5', 74982),
(10, '8.9', 83256),
(11, '9.8', 45896),
(12, '10.3', 14589);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nominas`
--

CREATE TABLE `nominas` (
  `id` int(10) UNSIGNED NOT NULL,
  `fecha_emision` date NOT NULL,
  `salario` decimal(7,2) DEFAULT NULL,
  `id_contrato` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `nominas`
--

INSERT INTO `nominas` (`id`, `fecha_emision`, `salario`, `id_contrato`) VALUES
(1, '2015-05-01', '950.80', 1),
(2, '2016-04-01', '970.50', 2),
(3, '2015-06-01', '965.25', 3),
(4, '2017-09-01', '974.25', 4),
(5, '2018-01-01', '956.70', 5),
(6, '2016-07-01', '985.65', 6),
(7, '2017-08-01', '974.50', 7),
(8, '2017-11-01', '967.45', 8),
(9, '2018-02-01', '992.45', 9),
(10, '2016-03-01', '948.80', 10),
(12, '2019-03-03', '985.56', 1);

--
-- Disparadores `nominas`
--
DELIMITER $$
CREATE TRIGGER `bonificacion_salario` BEFORE INSERT ON `nominas` FOR EACH ROW begin

declare antiguedad int;

select TIMESTAMPDIFF(YEAR, fecha_inicio, now()) from contratos where id=new.id_contrato into antiguedad;

if antiguedad >= 3 then
set new.salario=new.salario+(new.salario*0.01);
end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(10) UNSIGNED NOT NULL,
  `fecha_realizacion` date NOT NULL,
  `metodo_pago` enum('Tarjeta','Reembolso','PayPal') DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `dni_cliente` varchar(9) DEFAULT NULL,
  `cif_agencia_transporte` varchar(9) DEFAULT NULL,
  `num_ss_trabajador_prepara` decimal(12,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `fecha_realizacion`, `metodo_pago`, `estado`, `dni_cliente`, `cif_agencia_transporte`, `num_ss_trabajador_prepara`) VALUES
(1, '2015-06-22', 'Tarjeta', 'Entregado', '78563256W', '158966400', '877963325411'),
(2, '2015-09-17', 'Reembolso', 'Entregado', '65894125P', '325488706', '956832564785'),
(3, '2017-12-15', 'Tarjeta', 'Enviado', '71698331S', '102204583', '596832148956'),
(4, '2018-01-15', 'Reembolso', 'En transito', '58793214F', '325488706', '877963325411'),
(5, '2018-02-03', 'Tarjeta', 'En preparacion', '78990012H', '102204583', '596832148956'),
(6, '2015-08-04', 'PayPal', 'Entregado', '78563256W', '158966400', '877963325411'),
(7, '2015-12-09', 'Reembolso', 'Entregado', '65894125P', '325488706', '956832564785'),
(8, '2016-01-29', 'Tarjeta', 'Enviado', '71698331S', '102204583', '596832148956'),
(9, '2018-03-21', 'Reembolso', 'En transito', '58793214F', '325488706', '877963325411'),
(10, '2018-03-22', 'PayPal', 'En preparacion', '78990012H', '102204583', '596832148956');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `pedidos_iva`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `pedidos_iva` (
`Num Pedido` int(10) unsigned
,`Importe` decimal(39,2)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `preparadores`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `preparadores` (
`Num Pedido` int(10) unsigned
,`Nombre Preparador` varchar(20)
,`apellidos` varchar(40)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sellos_discograficos`
--

CREATE TABLE `sellos_discograficos` (
  `cif` varchar(9) NOT NULL,
  `nombre` varchar(30) DEFAULT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `ciudad` varchar(30) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `e_mail` varchar(40) DEFAULT NULL,
  `telefono` decimal(9,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sellos_discograficos`
--

INSERT INTO `sellos_discograficos` (`cif`, `nombre`, `pais`, `ciudad`, `direccion`, `e_mail`, `telefono`) VALUES
('233147852', 'Drumcode', 'Belgica', 'Amberes', 'C/ Heeinek N§ 75', 'drumcode@drumcode.com', '699014425'),
('554012397', 'Footwork', 'Reino Unido', 'Londres', 'C/ Stringer N§ 28', 'footwork@hotmail.com', '454201269'),
('654630114', 'Mindshake', 'Espa¤a', 'Madrid', 'C/ Preciados 350 7§ A', 'mindshake@records.es', '652004778'),
('772201596', 'Suara', 'Espa¤a', 'Valencia', 'C/ Cid Campeador 356 3§ H', 'suara@discografica.es', '695224701'),
('847710025', 'Kompact', 'Alemania', 'Berlin', 'C/ Werebeen N§ 234', 'kompact@gmail.com', '862014589');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sin_stock`
--

CREATE TABLE `sin_stock` (
  `num_referencia` int(10) DEFAULT NULL,
  `artista` varchar(30) DEFAULT NULL,
  `titulo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajadores`
--

CREATE TABLE `trabajadores` (
  `numero_ss` decimal(12,0) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `apellidos` varchar(40) DEFAULT NULL,
  `localidad` varchar(30) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `telefono` decimal(9,0) DEFAULT NULL,
  `num_cuenta` varchar(20) DEFAULT NULL,
  `num_ss_jefe` decimal(12,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `trabajadores`
--

INSERT INTO `trabajadores` (`numero_ss`, `dni`, `nombre`, `apellidos`, `localidad`, `direccion`, `telefono`, `num_cuenta`, `num_ss_jefe`) VALUES
('125458976326', '58479321K', 'Romualdo', 'Conejo Pellejo', 'Torrenueva', 'C/ Postas N§ 104', '789456123', '12365258975632449582', '757575757575'),
('596832148956', '75369894V', 'Fernando', 'Barrajon Huertas', 'Moral', 'C/ Carneros N§ 23', '685225897', '78998745552145632594', '757575757575'),
('748489563254', '68955412Z', 'Jaime', 'Se¤oret Se¤oret', 'Villamanrique', 'C/ Bataneros N§ 40', '658989712', '75698456325654123699', '757575757575'),
('754625986532', '78411458J', 'Guillermo', 'Gimenez Sanchez', 'Membrilla', 'C/ Real N§ 76', '654789874', '56589874123232145689', '757575757575'),
('757575757575', '75889364F', 'Eduardo', 'de Lamo Tellez', 'Santa Cruz', 'C/ Cervantes N§ 75 2§ C', '753357753', '96589658965893214785', '757575757575'),
('865325698541', '78542651C', 'Antonio', 'Lopez Menchen', 'Valdepe¤as', 'C/ Caba¤as N§ 16', '656324587', '58965412325478965412', '757575757575'),
('877963325411', '41477896L', 'Jesus', 'Malpica Fernandez', 'Santa Cruz', 'C/ Castillo N§ 90', '696696696', '87963114523256478965', '757575757575'),
('896573652145', '76589425T', 'Anabel', 'Tellez Gormaz', 'Santa Cruz', 'C/ Esperanza N§ 33', '658547899', '84512365985521457893', '757575757575'),
('898745145632', '74445882R', 'Laura', 'Gomez Marquez', 'Santa Cruz', 'C/ Ramirez Lasala N§ 67', '784547893', '12454632569874589658', '757575757575'),
('956832564785', '58463215G', 'Esperanza', 'Dominguez Bravo', 'Valdepe¤as', 'C/ Datiles N§ 57 1§ B', '753951852', '26589636974114789652', '757575757575');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `login` varchar(50) NOT NULL,
  `nombre` varchar(25) NOT NULL,
  `clave` varchar(500) DEFAULT NULL,
  `nivel` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`login`, `nombre`, `clave`, `nivel`) VALUES
('edu', 'Eduardo', '3fbaab6bbeea838d46186119a7ed977c', 2),
('miguel', 'Miguel Angel', '3fbaab6bbeea838d46186119a7ed977c', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vinilos`
--

CREATE TABLE `vinilos` (
  `id` int(10) UNSIGNED NOT NULL,
  `rpm` enum('33','45') DEFAULT NULL,
  `num_ref_disco` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `vinilos`
--

INSERT INTO `vinilos` (`id`, `rpm`, `num_ref_disco`) VALUES
(1, '45', 56789),
(2, '33', 45609),
(3, '33', 57412),
(4, '45', 37890),
(5, '45', 89651),
(6, '33', 89564),
(7, '45', 22869),
(8, '45', 47258),
(9, '33', 74982),
(10, '33', 83256),
(11, '45', 45896),
(12, '45', 14589);

-- --------------------------------------------------------

--
-- Estructura para la vista `pedidos_iva`
--
DROP TABLE IF EXISTS `pedidos_iva`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY INVOKER VIEW `pedidos_iva`  AS  select `discos_pedidos`.`id_pedido` AS `Num Pedido`,round(sum((`discos_pedidos`.`cantidad` * (select (`discos`.`precio_unidad` * 1.10) from `discos` where (`discos`.`num_referencia` = `discos_pedidos`.`num_ref_disco`)))),2) AS `Importe` from `discos_pedidos` group by `discos_pedidos`.`id_pedido` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `preparadores`
--
DROP TABLE IF EXISTS `preparadores`;

CREATE ALGORITHM=TEMPTABLE DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `preparadores`  AS  select `pedidos`.`id` AS `Num Pedido`,`trabajadores`.`nombre` AS `Nombre Preparador`,`trabajadores`.`apellidos` AS `apellidos` from (`pedidos` join `trabajadores` on((`pedidos`.`num_ss_trabajador_prepara` = `trabajadores`.`numero_ss`))) order by `pedidos`.`id` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agencias_transporte`
--
ALTER TABLE `agencias_transporte`
  ADD PRIMARY KEY (`cif`);

--
-- Indices de la tabla `cds`
--
ALTER TABLE `cds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `num_ref_disco` (`num_ref_disco`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`dni`);

--
-- Indices de la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `num_ss_trabajador` (`num_ss_trabajador`);

--
-- Indices de la tabla `curriculums`
--
ALTER TABLE `curriculums`
  ADD PRIMARY KEY (`dni`);

--
-- Indices de la tabla `direccion_clientes`
--
ALTER TABLE `direccion_clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dni_cliente` (`dni_cliente`);

--
-- Indices de la tabla `discos`
--
ALTER TABLE `discos`
  ADD PRIMARY KEY (`num_referencia`),
  ADD KEY `cif_sello_discografico` (`cif_sello_discografico`);

--
-- Indices de la tabla `discos_pedidos`
--
ALTER TABLE `discos_pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `num_ref_disco` (`num_ref_disco`);

--
-- Indices de la tabla `facturas_clientes`
--
ALTER TABLE `facturas_clientes`
  ADD PRIMARY KEY (`num_referencia`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `mp3`
--
ALTER TABLE `mp3`
  ADD PRIMARY KEY (`id`),
  ADD KEY `num_ref_disco` (`num_ref_disco`);

--
-- Indices de la tabla `nominas`
--
ALTER TABLE `nominas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_contrato` (`id_contrato`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dni_cliente` (`dni_cliente`),
  ADD KEY `cif_agencia_transporte` (`cif_agencia_transporte`),
  ADD KEY `num_ss_trabajador_prepara` (`num_ss_trabajador_prepara`);

--
-- Indices de la tabla `sellos_discograficos`
--
ALTER TABLE `sellos_discograficos`
  ADD PRIMARY KEY (`cif`);

--
-- Indices de la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD PRIMARY KEY (`numero_ss`),
  ADD KEY `num_ss_jefe` (`num_ss_jefe`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`login`);

--
-- Indices de la tabla `vinilos`
--
ALTER TABLE `vinilos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `num_ref_disco` (`num_ref_disco`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cds`
--
ALTER TABLE `cds`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `direccion_clientes`
--
ALTER TABLE `direccion_clientes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `discos_pedidos`
--
ALTER TABLE `discos_pedidos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `facturas_clientes`
--
ALTER TABLE `facturas_clientes`
  MODIFY `num_referencia` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `mp3`
--
ALTER TABLE `mp3`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `nominas`
--
ALTER TABLE `nominas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `vinilos`
--
ALTER TABLE `vinilos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cds`
--
ALTER TABLE `cds`
  ADD CONSTRAINT `cds_ibfk_1` FOREIGN KEY (`num_ref_disco`) REFERENCES `discos` (`num_referencia`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `contratos_ibfk_1` FOREIGN KEY (`num_ss_trabajador`) REFERENCES `trabajadores` (`numero_ss`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `direccion_clientes`
--
ALTER TABLE `direccion_clientes`
  ADD CONSTRAINT `direccion_clientes_ibfk_1` FOREIGN KEY (`dni_cliente`) REFERENCES `clientes` (`dni`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `discos`
--
ALTER TABLE `discos`
  ADD CONSTRAINT `discos_ibfk_1` FOREIGN KEY (`cif_sello_discografico`) REFERENCES `sellos_discograficos` (`cif`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `discos_pedidos`
--
ALTER TABLE `discos_pedidos`
  ADD CONSTRAINT `discos_pedidos_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `discos_pedidos_ibfk_2` FOREIGN KEY (`num_ref_disco`) REFERENCES `discos` (`num_referencia`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `facturas_clientes`
--
ALTER TABLE `facturas_clientes`
  ADD CONSTRAINT `facturas_clientes_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `mp3`
--
ALTER TABLE `mp3`
  ADD CONSTRAINT `mp3_ibfk_1` FOREIGN KEY (`num_ref_disco`) REFERENCES `discos` (`num_referencia`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `nominas`
--
ALTER TABLE `nominas`
  ADD CONSTRAINT `nominas_ibfk_1` FOREIGN KEY (`id_contrato`) REFERENCES `contratos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`dni_cliente`) REFERENCES `clientes` (`dni`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`cif_agencia_transporte`) REFERENCES `agencias_transporte` (`cif`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_3` FOREIGN KEY (`num_ss_trabajador_prepara`) REFERENCES `trabajadores` (`numero_ss`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `trabajadores`
--
ALTER TABLE `trabajadores`
  ADD CONSTRAINT `trabajadores_ibfk_1` FOREIGN KEY (`num_ss_jefe`) REFERENCES `trabajadores` (`numero_ss`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vinilos`
--
ALTER TABLE `vinilos`
  ADD CONSTRAINT `vinilos_ibfk_1` FOREIGN KEY (`num_ref_disco`) REFERENCES `discos` (`num_referencia`) ON UPDATE CASCADE;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `rebaja` ON SCHEDULE EVERY 1 QUARTER STARTS '2019-03-03 20:10:38' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN

update discos set precio_unidad=precio_unidad-(precio_unidad*0.05) where TIMESTAMPDIFF(YEAR, fecha_lanzamiento, now()) >= 1;

end$$

CREATE DEFINER=`root`@`localhost` EVENT `aumento_56789` ON SCHEDULE EVERY 1 YEAR STARTS '2019-04-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN

update discos set precio_unidad=precio_unidad+5 where num_referencia=56789;

end$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
