ALTER TABLE `empresapro`
ADD `TokenAPIMipres` text COLLATE 'utf8_spanish_ci' NOT NULL AFTER `TokenAPIFE`;

UPDATE `empresapro` SET `TokenAPIMipres` = '95A52628-6CE2-4AF6-AD47-66217E932B94' WHERE `idEmpresaPro` = '1';

ALTER TABLE `servidores`
CHANGE `IP` `IP` varchar(300) COLLATE 'utf8_spanish_ci' NOT NULL AFTER `ID`;
INSERT INTO `servidores` (`ID`, `IP`, `Nombre`, `Usuario`, `Password`, `DataBase`, `Puerto`, `TipoServidor`, `Observaciones`, `Updated`, `Sync`) VALUES
(2000,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/GenerarToken/',	'Consultar Token para API en mi pres',	'',	'',	'',	0,	'REST',	'Ruta para la consulta de token para el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 09:07:44',	'2020-07-25 10:06:36');

INSERT INTO `servidores` (`ID`, `IP`, `Nombre`, `Usuario`, `Password`, `DataBase`, `Puerto`, `TipoServidor`, `Observaciones`, `Updated`, `Sync`) VALUES
(2001,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/DireccionamientoXFecha/',	'Consultar direccionamiento x Fecha API mipres',	'',	'',	'',	0,	'REST',	'Ruta para consultar el direccionamiento x fecha en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36');

INSERT INTO `servidores` (`ID`, `IP`, `Nombre`, `Usuario`, `Password`, `DataBase`, `Puerto`, `TipoServidor`, `Observaciones`, `Updated`, `Sync`) VALUES
(2002,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/Programacion/',	'Programar Mipres',	'',	'',	'',	0,	'REST',	'Ruta para programar una auotirzacion en mipres en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36');

ALTER TABLE `empresapro`
ADD `CodSedeProv` varchar(30) NOT NULL AFTER `CodigoPrestadora`;

UPDATE `empresapro` SET `CodSedeProv` = 'PROV004612' WHERE `idEmpresaPro` = '1';

DROP TABLE IF EXISTS `mipres_programacion`;
CREATE TABLE `mipres_programacion` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `IDDireccionamiento` bigint(20) NOT NULL,
  `NoPrescripcion` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `TipoTec` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `ConTec` int(11) NOT NULL,
  `TipoIDPaciente` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  `NoIDPaciente` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NoEntrega` int(11) NOT NULL,
  `NoSubEntrega` int(11) NOT NULL,
  `FecMaxEnt` date NOT NULL,
  `TipoIDProv` varchar(4) COLLATE utf8_spanish_ci NOT NULL,
  `NoIDProv` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `CodMunEnt` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `CantTotAEntregar` int(11) NOT NULL,
  `DirPaciente` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `CodSerTecAEntregar` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NoIDEPS` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `CodEPS` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `FecDireccionamiento` datetime NOT NULL,
  `EstDireccionamiento` int(11) NOT NULL,
  `IdProgramacion` bigint(20) NOT NULL,
  `IdEntrega` bigint(20) NOT NULL,
  `IdReporteEntrega` bigint(20) NOT NULL,
  `FecAnulacion` date NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'usaurio que crea el registro',
  PRIMARY KEY (`ID`),
  KEY `IDProgramacion` (`IDDireccionamiento`),
  KEY `NoPrescripcion` (`NoPrescripcion`),
  KEY `TipoTec` (`TipoTec`),
  KEY `ConTec` (`ConTec`),
  KEY `NoIDPaciente` (`NoIDPaciente`),
  KEY `NoEntrega` (`NoEntrega`),
  KEY `EstProgramacion` (`EstDireccionamiento`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


DROP TABLE IF EXISTS `mipres_registro_programacion`;
CREATE TABLE `mipres_registro_programacion` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `IdProgramacion` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


