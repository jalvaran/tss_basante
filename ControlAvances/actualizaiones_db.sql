INSERT INTO `menu_submenus` (`ID`, `Nombre`, `idPestana`, `idCarpeta`, `Pagina`, `Target`, `Estado`, `Image`, `Orden`, `Updated`, `Sync`) 
VALUES (63, 'Historial de Facturas devueltas', '46', '6', 'salud_registro_devoluciones_facturas.php', '_SELF', b'1', 'historial.png', '1', '2018-08-03 23:54:16', '2018-07-13 15:42:34');

INSERT INTO `menu_submenus` (`ID`, `Nombre`, `idPestana`, `idCarpeta`, `Pagina`, `Target`, `Estado`, `Image`, `Orden`, `Updated`, `Sync`) VALUES
(64, 'Circular 014', 40, 6, 'salud_genere_circular_030.php', '_SELF', b'1', 'listasprecios.png', 6, '2018-09-03 11:49:36', '2018-07-13 15:42:34');

ALTER TABLE `salud_archivo_conceptos_glosas` ADD `descripcion_concepto_general` TEXT NOT NULL AFTER `cod_concepto_general`, ADD `tipo_concepto_general` VARCHAR(30) NOT NULL AFTER `descripcion_concepto_general`;
ALTER TABLE `salud_archivo_conceptos_glosas` ADD `aplicacion_concepto_general` TEXT NOT NULL AFTER `tipo_concepto_general`;

ALTER TABLE `usuarios` ADD `Habilitado` VARCHAR(2) NOT NULL DEFAULT 'SI' AFTER `Email`;

ALTER TABLE `salud_glosas_iniciales_temp` ADD `idUser` INT NOT NULL AFTER `Soporte`;

ALTER TABLE `salud_cups` CHANGE `ID` `ID` INT(20) NOT NULL AUTO_INCREMENT;

INSERT INTO `menu_submenus` (`ID`, `Nombre`, `idPestana`, `idCarpeta`, `Pagina`, `Target`, `Estado`, `Image`, `Orden`, `Updated`, `Sync`) VALUES (65, 'Crear politicas de Acceso', '2', '3', 'CrearPoliticasAcceso.php', '_SELF', b'1', 'seguridadinformatica.png', '5', '2018-07-13 15:42:34', '2018-07-13 15:42:34');

UPDATE `menu_submenus` SET `Image` = 'historial2.png' WHERE `menu_submenus`.`ID` = 11;
UPDATE `menu_submenus` SET `Nombre` = 'Consolidado de Politicas de Acceso' WHERE `menu_submenus`.`ID` = 11;

DELETE FROM `menu_submenus` WHERE `menu_submenus`.`ID` = 2 

ALTER TABLE `salud_eps`
  DROP `saldo_inicial`,
  DROP `fecha_saldo_inicial`;

ALTER TABLE `salud_eps` ADD `RepresentanteLegal` VARCHAR(45) NOT NULL AFTER `Nombre_gerente`, ADD `NumeroRepresentanteLegal` VARCHAR(45) NOT NULL AFTER `RepresentanteLegal`;

DROP TABLE IF EXISTS `salud_manuales_tarifarios`;
CREATE TABLE `salud_manuales_tarifarios` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;;

INSERT INTO `salud_manuales_tarifarios` (`ID`, `Nombre`) VALUES
(1,	'SOAT'),
(2,	'CUPS'),
(3,	'ISS 2004'),
(4,	'Medicamentos'),
(5,	'Insumos'),
(6,	'ISS'),
(7,	'Act propias');

ALTER TABLE `salud_cups` ADD `Manual` INT NOT NULL DEFAULT '2' AFTER `descripcion_cups`;

DROP TABLE IF EXISTS `salud_regimen`;
CREATE TABLE `salud_regimen` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Regimen` varchar(45) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;;

INSERT INTO `salud_regimen` (`ID`, `Regimen`) VALUES
(1,	'CONTRIBUTIVO'),
(2,	'SUBSIDIADO'),
(3,	'OTRAS ENTIDADES'),
(4,	'ENTE TERRITORIAL'),
(5,	'ENTE MUNICIPAL'),
(6,	'ENTIDAD EN LIQUIDACION');


ALTER TABLE `salud_archivo_control_glosas_respuestas` ADD `EstadoGlosaHistorico` INT(3) NOT NULL AFTER `Tratado`;

DROP TABLE IF EXISTS `salud_upload_control_ct`;
CREATE TABLE `salud_upload_control_ct` (
  `id_upload_control` bigint(20) NOT NULL AUTO_INCREMENT,
  `nom_cargue` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_cargue` datetime NOT NULL,
  `idUser` int(11) NOT NULL,
  `Analizado` bit(1) NOT NULL,
  `CargadoTemp` bit(1) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_upload_control`),
  KEY `nom_cargue` (`nom_cargue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

DROP TABLE IF EXISTS `empresapro_regimenes`;
CREATE TABLE `empresapro_regimenes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Regimen` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;;

INSERT INTO `empresapro_regimenes` (`ID`, `Regimen`) VALUES
(1,	'COMUN'),
(2,	'SIMPLIFICADO');

INSERT INTO `menu_pestanas` (`ID`, `Nombre`, `idMenu`, `Orden`, `Estado`, `Updated`, `Sync`) VALUES
(49,	'Reportes',	6,	6,	CONV('1', 2, 10) + 0,	'2018-07-13 15:42:33',	'2018-07-13 15:42:33');

UPDATE `menu_pestanas` SET `Orden` = '3' WHERE `menu_pestanas`.`ID` = 40;
UPDATE `menu_pestanas` SET `Orden` = '2' WHERE `menu_pestanas`.`ID` = 49;

UPDATE `menu_submenus` SET `Estado` = b'0' WHERE `menu_submenus`.`ID` = 37;
UPDATE `menu_submenus` SET `Estado` = b'0' WHERE `menu_submenus`.`ID` = 36;
INSERT INTO `menu_submenus` (`ID`, `Nombre`, `idPestana`, `idCarpeta`, `Pagina`, `Target`, `Estado`, `Image`, `Orden`, `Updated`, `Sync`) VALUES (66, 'Reportes', '49', '6', 'ReportesCartera.php', '_SELF', 1, 'reportes.jpg', '1', '2018-10-07 07:53:03', '2018-07-13 15:42:34');


ALTER TABLE `salud_archivo_facturacion_mov_pagados` ADD `tipo_negociacion` VARCHAR(25) NOT NULL AFTER `valor_pagado`;

ALTER TABLE `salud_archivo_facturacion_mov_pagados` ADD `idEPS` VARCHAR(25) NOT NULL AFTER `num_factura`, ADD `nom_enti_administradora` VARCHAR(100) NOT NULL AFTER `idEPS`;

DROP TABLE IF EXISTS `salud_cartera_x_edades_temp`;
CREATE TABLE `salud_cartera_x_edades_temp` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `idEPS` varchar(25) NOT NULL,
  `RazonSocialEPS` varchar(45) NOT NULL,
  `Cantidad_1_30` int(11) NOT NULL,
  `Valor_1_30` double NOT NULL,
  `Cantidad_31_60` int(11) NOT NULL,
  `Valor_31_60` double NOT NULL,
  `Cantidad_61_90` int(11) NOT NULL,
  `Valor_61_90` double NOT NULL,
  `Cantidad_91_120` int(11) NOT NULL,
  `Valor_91_120` double NOT NULL,
  `Cantidad_121_180` int(11) NOT NULL,
  `Valor_121_180` double NOT NULL,
  `Cantidad_181_360` int(11) NOT NULL,
  `Valor_181_360` double NOT NULL,
  `Cantidad_360` int(11) NOT NULL,
  `Valor_360` double NOT NULL,
  `TotalFacturas` int(11) NOT NULL,
  `Total` double NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;;

ALTER TABLE `empresapro` ADD `DV` INT NOT NULL AFTER `NIT`;
ALTER TABLE `empresapro` ADD `CodigoDANE` INT NOT NULL AFTER `Ciudad`;
ALTER TABLE `empresapro` CHANGE `NIT` `NIT` BIGINT NULL DEFAULT NULL;

UPDATE `menu_submenus` SET `Nombre` = 'Circular 030 y 014' WHERE `menu_submenus`.`ID` = 35;
ALTER TABLE `salud_procesos_gerenciales` ADD `IPS` INT NOT NULL AFTER `Fecha`;

INSERT INTO `menu_submenus` (`ID`, `Nombre`, `idPestana`, `idCarpeta`, `Pagina`, `Target`, `Estado`, `Image`, `Orden`, `Updated`, `Sync`) VALUES (67, 'Listado de Bancos', '3', '6', 'salud_bancos.php', '_SELF', b'1', 'bancos.png', '4', '2018-08-05 23:09:34', '2018-07-13 15:42:34');
INSERT INTO `menu_submenus` (`ID`, `Nombre`, `idPestana`, `idCarpeta`, `Pagina`, `Target`, `Estado`, `Image`, `Orden`, `Updated`, `Sync`) VALUES (68, 'Conceptos Procesos', '3', '6', 'salud_procesos_gerenciales_conceptos.php', '_SELF', b'1', 'proyectos.png', '9', '2018-08-05 23:09:34', '2018-07-13 15:42:34');



ALTER TABLE `salud_archivo_facturacion_mov_pagados` CHANGE `Soporte` `Soporte` TEXT CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL;

ALTER TABLE `salud_archivo_facturacion_mov_pagados` ADD `NumeroFacturaAdres` VARCHAR(45) NOT NULL AFTER `Arma030Anterior`;

INSERT INTO `menu_submenus` (`ID`, `Nombre`, `idPestana`, `idCarpeta`, `Pagina`, `Target`, `Estado`, `Image`, `Orden`, `Updated`, `Sync`) VALUES (69, 'Devolver una factura', '13', '6', 'SaludDevolucionFactura.php', '_SELF', b'1', 'devolucion2.png', '2', '2018-07-13 15:42:34', '2018-07-13 15:42:34');

DROP TABLE IF EXISTS `configuraciones_nombres_campos`;
CREATE TABLE `configuraciones_nombres_campos` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `NombreDB` varchar(50) NOT NULL,
  `Visualiza` varchar(50) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;;

INSERT INTO `configuraciones_nombres_campos` (`ID`, `NombreDB`, `Visualiza`, `Updated`, `Sync`) VALUES
(1,	'id_medicamentos',	'ID',	'2018-11-06 15:11:32',	'0000-00-00 00:00:00'),
(2,	'TipoUser',	'Tipo de Usuario',	'2018-11-06 15:11:32',	'0000-00-00 00:00:00'),
(3,	'idUsuarios',	'ID',	'2018-11-06 15:11:32',	'0000-00-00 00:00:00'),
(4,	'Identificacion',	'Identificación',	'2018-11-06 15:11:32',	'0000-00-00 00:00:00'),
(5,	'Telefono',	'Teléfono',	'2018-11-06 15:11:32',	'0000-00-00 00:00:00'),
(6,	'idEmpresaPro',	'ID',	'2018-11-06 15:19:27',	'0000-00-00 00:00:00'),
(7,	'RazonSocial',	'Razón Social',	'2018-11-06 15:19:42',	'0000-00-00 00:00:00'),
(8,	'CodigoPrestadora',	'Código de Prestadora',	'2018-11-06 15:20:20',	'0000-00-00 00:00:00'),
(9,	'Direccion',	'Dirección',	'2018-11-06 15:20:39',	'0000-00-00 00:00:00'),
(10,	'ResolucionDian',	'Resolución',	'2018-11-06 15:21:13',	'0000-00-00 00:00:00'),
(11,	'Regimen',	'Régimen',	'2018-11-06 15:21:36',	'0000-00-00 00:00:00'),
(12,	'ObservacionesLegales',	'Observaciones Legales',	'2018-11-06 15:22:01',	'0000-00-00 00:00:00'),
(13,	'PuntoEquilibrio',	'Punto de Equilibrio',	'2018-11-06 15:22:20',	'0000-00-00 00:00:00'),
(14,	'DatosBancarios',	'Datos Bancarios',	'2018-11-06 15:22:35',	'0000-00-00 00:00:00'),
(15,	'RutaImagen',	'Imagen',	'2018-11-06 15:23:17',	'0000-00-00 00:00:00'),
(16,	'id_concepto_glosa',	'ID',	'2018-11-06 15:30:12',	'0000-00-00 00:00:00'),
(17,	'cod_glosa',	'Código de Glosa',	'2018-11-06 15:30:28',	'0000-00-00 00:00:00'),
(18,	'cod_concepto_especifico',	'Código del Concepto Específico',	'2018-11-06 15:31:45',	'0000-00-00 00:00:00'),
(19,	'descrpcion_concep_especifico',	'Descripción del Concepto Específico',	'2018-11-06 15:32:28',	'0000-00-00 00:00:00'),
(20,	'aplicacion',	'Aplicación',	'2018-11-06 15:32:44',	'0000-00-00 00:00:00'),
(21,	'cod_pagador_min',	'Código de Administradora',	'2018-11-06 15:37:48',	'0000-00-00 00:00:00'),
(22,	'nit',	'NIT',	'2018-11-06 15:38:07',	'0000-00-00 00:00:00'),
(23,	'sigla_nombre',	'Sigla del Nombre',	'2018-11-06 15:38:21',	'0000-00-00 00:00:00'),
(24,	'nombre_completo',	'Nombre Completo',	'2018-11-06 15:38:35',	'0000-00-00 00:00:00'),
(25,	'telefonos',	'Teléfonos',	'2018-11-06 15:38:52',	'0000-00-00 00:00:00'),
(26,	'email',	'Email',	'2018-11-06 15:39:04',	'0000-00-00 00:00:00'),
(27,	'tipo_regimen',	'Régimen',	'2018-11-06 15:39:23',	'0000-00-00 00:00:00'),
(28,	'dias_convenio',	'Convenio en Días',	'2018-11-06 15:39:41',	'0000-00-00 00:00:00'),
(29,	'Nombre_gerente',	'Nombre del Gerente',	'2018-11-06 15:39:56',	'0000-00-00 00:00:00'),
(30,	'RepresentanteLegal',	'Representante Legal',	'2018-11-06 15:40:07',	'0000-00-00 00:00:00'),
(31,	'NumeroRepresentanteLegal',	'Celular del Representante Legal',	'2018-11-06 15:40:30',	'0000-00-00 00:00:00'),
(32,	'Genera030',	'Genera Circular 030?',	'2018-11-06 15:41:00',	'0000-00-00 00:00:00'),
(33,	'codigo_sistema',	'Código',	'2018-11-06 15:45:00',	'0000-00-00 00:00:00'),
(34,	'descripcion_cups',	'Descripción',	'2018-11-06 15:45:18',	'0000-00-00 00:00:00'),
(35,	'observacion',	'Observaciones',	'2018-11-06 15:45:44',	'0000-00-00 00:00:00');

ALTER TABLE `menu_submenus` ADD `idMenu` INT NOT NULL AFTER `idCarpeta`, ADD `TablaAsociada` VARCHAR(45) NOT NULL AFTER `idMenu`;

ALTER TABLE `menu` ADD `CSS_Clase` VARCHAR(20) NOT NULL AFTER `Image`;

UPDATE `menu` SET `CSS_Clase`='fa fa-share';

ALTER TABLE `menu_submenus` ADD `TipoLink` INT(1) NOT NULL AFTER `TablaAsociada`, ADD `JavaScript` VARCHAR(90) NOT NULL AFTER `TipoLink`;

UPDATE `menu_submenus` SET `TipoLink` = '1' WHERE `menu_submenus`.`ID` >=1 and `menu_submenus`.`ID` <=12;
UPDATE `menu_submenus` SET `TipoLink` = '1' WHERE `menu_submenus`.`ID` >=14 and `menu_submenus`.`ID` <=21;

UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'empresapro\')\";' WHERE `menu_submenus`.`ID` = 1;

UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'formatos_calidad\')\";' WHERE `menu_submenus`.`ID` = 3;

UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'centrocosto\')\";' WHERE `menu_submenus`.`ID` = 4;
UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'costos\')\";' WHERE `menu_submenus`.`ID` = 8;
UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'usuarios\')\";' WHERE `menu_submenus`.`ID` = 9;
UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'usuarios_tipo\')\";' WHERE `menu_submenus`.`ID` = 10;
UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'paginas_bloques\')\";' WHERE `menu_submenus`.`ID` = 11;
UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'salud_eps\')\";' WHERE `menu_submenus`.`ID` = 12;

UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'salud_archivo_consultas\')\";' WHERE `menu_submenus`.`ID` = 14;
UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'salud_archivo_medicamentos\')\";' WHERE `menu_submenus`.`ID` = 16;
UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'salud_archivo_otros_servicios\')\";' WHERE `menu_submenus`.`ID` = 17;
UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'salud_archivo_procedimientos\')\";' WHERE `menu_submenus`.`ID` = 18;
UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'salud_archivo_usuarios\')\";' WHERE `menu_submenus`.`ID` = 19;
UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'salud_archivo_hospitalizaciones\')\";' WHERE `menu_submenus`.`ID` = 15;
UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'salud_archivo_facturacion_mov_generados\')\";' WHERE `menu_submenus`.`ID` = 20;
UPDATE `menu_submenus` SET `JavaScript` = 'onclick=\"DibujeTabla(\'salud_archivo_facturacion_mov_pagados\')\";' WHERE `menu_submenus`.`ID` = 21;


UPDATE `menu_submenus` SET `TablaAsociada` = 'salud_archivo_consultas' WHERE `menu_submenus`.`ID` = 14;
UPDATE `menu_submenus` SET `TablaAsociada` = 'salud_archivo_medicamentos' WHERE `menu_submenus`.`ID` = 16;
UPDATE `menu_submenus` SET `TablaAsociada` = 'salud_archivo_otros_servicios' WHERE `menu_submenus`.`ID` = 17;
UPDATE `menu_submenus` SET `TablaAsociada` = 'salud_archivo_procedimientos' WHERE `menu_submenus`.`ID` = 18;
UPDATE `menu_submenus` SET `TablaAsociada` = 'salud_archivo_usuarios' WHERE `menu_submenus`.`ID` = 19;
UPDATE `menu_submenus` SET `TablaAsociada` = 'salud_archivo_hospitalizaciones' WHERE `menu_submenus`.`ID` = 15;
UPDATE `menu_submenus` SET `TablaAsociada` = 'salud_archivo_facturacion_mov_generados' WHERE `menu_submenus`.`ID` = 20;
UPDATE `menu_submenus` SET `TablaAsociada` = 'salud_archivo_facturacion_mov_pagados' WHERE `menu_submenus`.`ID` = 21;

INSERT INTO `menu_submenus` (`ID`, `Nombre`, `idPestana`, `idCarpeta`, `idMenu`, `TablaAsociada`, `TipoLink`, `JavaScript`, `Pagina`, `Target`, `Estado`, `Image`, `Orden`, `Updated`, `Sync`) VALUES (NULL, 'Archivo de Nacidos AN', '5', '6', '2', 'salud_archivo_nacidos', '1', 'onclick=\"DibujeTabla(\'salud_archivo_nacidos\')\";', 'salud_archivo_nacidos.php', '_SELF', b'1', 'am.png', '6', '2018-11-28 17:45:16', '2018-07-13 15:42:34');
ALTER TABLE `formatos_calidad` CHANGE `Codigo` `Codigo` VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL;

ALTER TABLE `salud_archivo_facturacion_mov_pagados` ENGINE = MyISAM;
ALTER TABLE `salud_archivo_hospitalizaciones` ENGINE = MyISAM;
ALTER TABLE `salud_archivo_nacidos` ENGINE = MyISAM;
ALTER TABLE `salud_archivo_urgencias` ENGINE = MyISAM;
ALTER TABLE `salud_archivo_usuarios` ENGINE = MyISAM;
ALTER TABLE `salud_glosas_iniciales` ENGINE = MyISAM;
ALTER TABLE `salud_tesoreria` ENGINE = MyISAM;

UPDATE `salud_eps` SET `nit` = '830009783' WHERE `salud_eps`.`ID` = 15;
UPDATE `salud_eps` SET `nit` = '837000084' WHERE `salud_eps`.`ID` = 59;
UPDATE `salud_eps` SET `nit` = '890102044' WHERE `salud_eps`.`ID` = 46;
UPDATE `salud_eps` SET `nit` = '804002105' WHERE `salud_eps`.`ID` = 74;
UPDATE `salud_eps` SET `nit` = '830113831' WHERE `salud_eps`.`ID` = 1;
UPDATE `salud_eps` SET `nit` = '890900840' WHERE `salud_eps`.`ID` = 25;
UPDATE `salud_eps` SET `nit` = '890101994' WHERE `salud_eps`.`ID` = 26;
UPDATE `salud_eps` SET `nit` = '890480110' WHERE `salud_eps`.`ID` = 27;
UPDATE `salud_eps` SET `nit` = '806008394' WHERE `salud_eps`.`ID` = 76;
UPDATE `salud_eps` SET `nit` = '800088702' WHERE `salud_eps`.`ID` = 7;
UPDATE `salud_eps` SET `nit` = '830006404' WHERE `salud_eps`.`ID` = 10;
UPDATE `salud_eps` SET `nit` = '860512237' WHERE `salud_eps`.`ID` = 11;
UPDATE `salud_eps` SET `nit` = '899999107' WHERE `salud_eps`.`ID` = 51;


CREATE TABLE `salud_pagos_contributivo` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Proceso` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NitEPS` bigint(20) NOT NULL,
  `CodigoEps` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NombreEPS` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `FechaPago` date NOT NULL,
  `numero_factura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `PrefijoFactura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NumeroFactura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `FechaFactura` date NOT NULL,
  `FormaContratacion` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `ValorGiro` double NOT NULL,
  `fecha_cargue` datetime NOT NULL,
  `Soporte` text COLLATE utf8_spanish_ci NOT NULL,
  `Estado` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `NitEPS` (`NitEPS`),
  KEY `numero_factura` (`numero_factura`),
  KEY `idUser` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE `salud_pagos_contributivo_temp` (
  `ID` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `Proceso` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NitEPS` bigint(20) NOT NULL,
  `CodigoEps` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NombreEPS` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `FechaPago` date NOT NULL,
  `numero_factura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `PrefijoFactura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NumeroFactura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `FechaFactura` date NOT NULL,
  `FormaContratacion` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `ValorGiro` double NOT NULL,
  `fecha_cargue` datetime NOT NULL,
  `Soporte` text COLLATE utf8_spanish_ci NOT NULL,
  `Estado` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `NitEPS` (`NitEPS`),
  KEY `numero_factura` (`numero_factura`),
  KEY `idUser` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


ALTER TABLE `salud_archivo_facturacion_mov_pagados` ADD `SubeDesde` VARCHAR(15) NOT NULL AFTER `NumeroFacturaAdres`;


ALTER TABLE `salud_tesoreria` ADD `valor_legalizado` DOUBLE NOT NULL AFTER `valor_transaccion`, ADD `valor_legalizar` DOUBLE NOT NULL AFTER `valor_legalizado`;
ALTER TABLE `salud_tesoreria` ADD `observaciones_cartera` TEXT NOT NULL AFTER `observacion`;
ALTER TABLE `salud_tesoreria` ADD `legalizado` VARCHAR(2) NOT NULL DEFAULT 'NO' AFTER `observaciones_cartera`;
ALTER TABLE `salud_tesoreria` CHANGE `valor_transaccion` `valor_transaccion` DOUBLE NOT NULL COMMENT 'Valor de transaccion ';


ALTER TABLE `menu` ADD `CSS_Clase` VARCHAR(20) NOT NULL AFTER `Image`;

UPDATE `menu` SET `CSS_Clase`='fa fa-share';

ALTER TABLE `menu_submenus` ADD `idMenu` INT NOT NULL AFTER `idCarpeta`, ADD `TablaAsociada` VARCHAR(45) NOT NULL AFTER `idMenu`;

ALTER TABLE `menu_submenus` ADD `TipoLink` INT(1) NOT NULL AFTER `TablaAsociada`, ADD `JavaScript` VARCHAR(90) NOT NULL AFTER `TipoLink`;


CREATE TABLE `registro_actualizacion_facturas` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `FacturaAnterior` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `FacturaNueva` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `FechaRegistro` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE `temporal_actualizacion_facturas` (
  `ID` varchar(1) COLLATE utf8_spanish_ci NOT NULL,
  `FacturaAnterior` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `FacturaNueva` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `FechaRegistro` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


INSERT INTO `menu_submenus` (`ID`, `Nombre`, `idPestana`, `idCarpeta`, `idMenu`, `TablaAsociada`, `TipoLink`, `JavaScript`, `Pagina`, `Target`, `Estado`, `Image`, `Orden`, `Updated`, `Sync`) VALUES
(69,	'Actualizacion de Facturas',	36,	7,	0,	'',	0,	'',	'ActualizarFacturas.php',	'_SELF',	CONV('1', 2, 10) + 0,	'actualizar.png',	9,	'2019-06-13 10:06:18',	'2018-07-13 15:42:34');

INSERT INTO `menu_carpetas` (`ID`, `Ruta`, `Updated`, `Sync`) VALUES
(7,	'../modulos/carteraips/',	'2018-07-13 15:42:32',	'2018-07-13 15:42:32');


ALTER TABLE `salud_cartera_x_edades_temp` ADD `RegimenEPS` VARCHAR(25) NOT NULL AFTER `Total`;

ALTER TABLE `salud_eps` ADD `Genera014` VARCHAR(1) NOT NULL DEFAULT 'S' AFTER `Genera030`, ADD `Genera07` VARCHAR(1) NOT NULL DEFAULT 'S' AFTER `Genera014`;


ALTER TABLE `salud_cartera_x_edades_temp` ADD `NIT_EPS` VARCHAR(20) NOT NULL AFTER `RegimenEPS`;


CREATE TABLE `salud_archivo_af_capita` (
  `id_fac_mov_generados` int(20) NOT NULL AUTO_INCREMENT,
  `cod_prest_servicio` bigint(12) NOT NULL COMMENT 'Código del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `razon_social` varchar(60) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Razón social o apellidos y nombre del prestado  " Ver Alineamientos tecnicos para ips ver pag 12"',
  `tipo_ident_prest_servicio` enum('NI','CC','CE','PA') COLLATE utf8_spanish_ci NOT NULL COMMENT 'Tipo de identificación del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `num_ident_prest_servicio` bigint(20) NOT NULL COMMENT 'Número de identificación del prestador de servicios de salud " Ver Alineamientos tecnicos para ips ver pag 12"',
  `num_factura` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Número de la factura " Ver Alineamientos tecnicos para ips ver pag 12"',
  `fecha_factura` date NOT NULL COMMENT 'Fecha de expedición de la factura " Ver Alineamientos tecnicos para ips ver pag 13"',
  `fecha_inicio` date NOT NULL COMMENT 'Fecha de inicio " Ver Alineamientos tecnicos para ips ver pag 13"',
  `fecha_final` date NOT NULL COMMENT 'Fecha final " Ver Alineamientos tecnicos para ips ver pag 13"',
  `cod_enti_administradora` varchar(6) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Código entidad administradora " Ver Alineamientos tecnicos para ips ver pag 13"',
  `nom_enti_administradora` varchar(200) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre entidad administradora " Ver Alineamientos tecnicos para ips ver pag 13" ',
  `num_contrato` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Número del contrato " Ver Alineamientos tecnicos para ips ver pag 13"',
  `plan_beneficios` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Plan de beneficios " Ver Alineamientos tecnicos para ips ver pag 13"',
  `num_poliza` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Número de la póliza " Ver Alineamientos tecnicos para ips ver pag 13" ',
  `valor_total_pago` double(15,2) NOT NULL COMMENT 'Valor total del pago compartido copago " Ver Alineamientos tecnicos para ips ver pag 14"',
  `valor_comision` double(15,2) NOT NULL COMMENT 'Valor de la comisión " Ver Alineamientos tecnicos para ips ver pag 14"',
  `valor_descuentos` double(15,2) NOT NULL COMMENT 'Valor total de descuentos " Ver Alineamientos tecnicos para ips ver pag 14"',
  `valor_neto_pagar` double(15,2) NOT NULL COMMENT 'Valor neto a pagar por la entidad contratante " Ver Alineamientos tecnicos para ips ver pag 14"',
  `tipo_negociacion` enum('evento','capita') COLLATE utf8_spanish_ci NOT NULL,
  `nom_cargue` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre con el que se hizo el cargue al sistema de cartera',
  `fecha_cargue` datetime NOT NULL COMMENT 'Fecha Y Hora que se hizo el cargue',
  `idUser` int(11) NOT NULL,
  `eps_radicacion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Eps en la que se radico la factura',
  `dias_pactados` int(2) DEFAULT NULL COMMENT 'Dias que se pactaron para el pago de la factura con eps',
  `fecha_radicado` date DEFAULT NULL COMMENT 'Fecha de la radicacion de la factura',
  `numero_radicado` varchar(20) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Numero con que se radico la factura',
  `Soporte` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Ruta de Archivo de comprobación de radicado',
  `estado` varchar(50) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Indica en que tabla esta el registro en un momento dado ',
  `EstadoCobro` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Arma030Anterior` enum('S','N') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  `Escenario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `CuentaGlobal` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `CuentaRIPS` int(6) unsigned zerofill NOT NULL,
  `EstadoGlosa` int(2) NOT NULL DEFAULT '8',
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id_fac_mov_generados`),
  UNIQUE KEY `num_factura` (`num_factura`),
  KEY `estado` (`estado`),
  KEY `tipo_negociacion` (`tipo_negociacion`),
  KEY `Updated` (`Updated`),
  KEY `EstadoCobro` (`EstadoCobro`),
  KEY `cod_enti_administradora` (`cod_enti_administradora`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Archivo de transacciones_AF_generadas';



ALTER TABLE `empresapro` ADD `CentroCostos` VARCHAR(5) NOT NULL AFTER `idEmpresaPro`, ADD INDEX `CentroCostos` (`CentroCostos`);
ALTER TABLE `salud_eps` ADD `TipoEntidad` VARCHAR(5) NOT NULL AFTER `nit`, ADD INDEX `TipoEntidad` (`TipoEntidad`);


DROP TABLE IF EXISTS `parametros_contables_glosas`;
CREATE TABLE `parametros_contables_glosas` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CuentaPUC` bigint(20) NOT NULL,
  `DescripcionCuenta` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `TipoCuentaGlosaInicial` enum('D','C') COLLATE utf8_spanish_ci NOT NULL,
  `TipoCuentaGlosaAceptada` enum('D','C') COLLATE utf8_spanish_ci NOT NULL,
  `TipoCuentaGlosaLevantada` enum('D','C') COLLATE utf8_spanish_ci NOT NULL,
  `Regimen` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `Vigencia` int(4) NOT NULL,
  `idUser` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `parametros_contables_glosas` (`ID`, `CuentaPUC`, `DescripcionCuenta`, `TipoCuentaGlosaInicial`, `TipoCuentaGlosaAceptada`, `TipoCuentaGlosaLevantada`, `Regimen`, `Vigencia`, `idUser`) VALUES
(1,	83330113,	'EMPRESAS REG CONTRIBUTIVO',	'D',	'C',	'C',	'CONTRIBUTIVO',	2018,	1),
(2,	8915170101,	'EMPRESAS REG CONTRIBUTIVO',	'C',	'D',	'D',	'CONTRIBUTIVO',	2018,	1),
(3,	83330313,	'EMPRESAS REG SUBSIDIADO',	'D',	'C',	'C',	'SUBSIDIADO',	2018,	1),
(4,	8915170101,	'EMPRESAS REG SUBSIDIADO',	'C',	'D',	'D',	'SUBSIDIADO',	2018,	1),
(5,	83330114,	'EMPRESAS REG CONTRIBUTIVO',	'D',	'C',	'C',	'CONTRIBUTIVO',	2019,	1),
(6,	8915170101,	'EMPRESAS REG CONTRIBUTIVO',	'C',	'D',	'D',	'CONTRIBUTIVO',	2019,	1),
(7,	83330314,	'EMPRESAS REG SUBSIDIADO',	'D',	'C',	'C',	'SUBSIDIADO',	2019,	1),
(8,	8915170101,	'EMPRESAS REG SUBSIDIADO',	'C',	'D',	'D',	'SUBSIDIADO',	2019,	1);


ALTER TABLE `servidores` ADD `Puerto` int(11) NOT NULL AFTER `DataBase`;
ALTER TABLE `servidores` ADD `TipoServidor` VARCHAR(10) NOT NULL AFTER `Puerto`;
ALTER TABLE `salud_glosas_iniciales` ADD `EstadoReporteXMLFTP` INT NOT NULL AFTER `ValorConciliado`;


CREATE TABLE `cuentas_contables` (
  `PUC` bigint(20) NOT NULL,
  `Nombre` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `SolicitaBase` int(11) NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Sync` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`PUC`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

ALTER TABLE `salud_archivo_facturacion_mov_generados` ADD `CuentaContable` bigint NOT NULL AFTER `EstadoGlosa`;
ALTER TABLE `salud_rips_facturas_generadas_historico` ADD `CuentaContable` bigint NOT NULL AFTER `EstadoGlosa`;
ALTER TABLE `salud_archivo_af_capita` ADD `CuentaContable` bigint NOT NULL AFTER `EstadoGlosa`;
ALTER TABLE `salud_rips_facturas_generadas_temp` ADD `CuentaContable` bigint NOT NULL AFTER `CuentaRIPS`;
ALTER TABLE `salud_circular030_inicial` ADD `CuentaContable` bigint NOT NULL AFTER `Cod_Entidad_Administradora`;
ALTER TABLE `salud_tesoreria` ADD `TipoPago` int(11) NOT NULL AFTER `fecha_hora_registro`;
ALTER TABLE `salud_tesoreria` ADD INDEX(`TipoPago`);
ALTER TABLE `salud_archivo_facturacion_mov_generados` ADD INDEX(`CuentaContable`);
ALTER TABLE `salud_archivo_facturacion_mov_generados` ADD INDEX(`CuentaGlobal`);


CREATE TABLE `salud_tesoreria_tipos_pago` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TipoPago` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `salud_tesoreria_tipos_pago` (`ID`, `TipoPago`) VALUES
(1,	'PAGO EPS'),
(2,	'PAGO NOMINA');

INSERT INTO `menu_submenus` (`ID`, `Nombre`, `idPestana`, `idCarpeta`, `idMenu`, `TablaAsociada`, `TipoLink`, `JavaScript`, `Pagina`, `Target`, `Estado`, `Image`, `Orden`, `Updated`, `Sync`) VALUES
(71,	'Cuentas Contables',	3,	6,	0,	'',	0,	'',	'cuentas_contables.php',	'_SELF',	CONV('1', 2, 10) + 0,	'subcuentas.png',	10,	'2018-08-05 23:09:34',	'2018-07-13 15:42:34');

