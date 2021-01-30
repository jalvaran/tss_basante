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





CREATE TABLE `mipres_registro_entrega` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `mipres_id` bigint(20) NOT NULL,
  `IdEntrega` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_id_anulacion` int(11) NOT NULL,
  `fecha_anulacion` datetime NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `mipres_id` (`mipres_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


CREATE TABLE `mipres_registro_programacion` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `mipres_id` bigint(20) NOT NULL,
  `IdProgramacion` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_id_anulacion` int(11) NOT NULL,
  `fecha_anulacion` datetime NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `mipres_id` (`mipres_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
ALTER TABLE `prefactura_paciente`
ADD `reponsable_tipo_documento` varchar(2) COLLATE 'utf8_spanish_ci' NOT NULL COMMENT '(CC,CE,PA,RC,TI,AS,MS)' AFTER `Correo`,
ADD `reponsable_identificacion` bigint NOT NULL COMMENT 'Identificacion del responsable del paciente' AFTER `reponsable_tipo_documento`,
ADD `responsable_parentesco` int NOT NULL COMMENT 'parentesco del responsable del paciente' AFTER `reponsable_identificacion`,
ADD `responsable_nombre` varchar(200) COLLATE 'utf8_spanish_ci' NOT NULL COMMENT 'nombre del responsable del paciente' AFTER `responsable_parentesco`;


INSERT INTO `servidores` (`ID`, `IP`, `Nombre`, `Usuario`, `Password`, `DataBase`, `Puerto`, `TipoServidor`, `Observaciones`, `Updated`, `Sync`) VALUES
(2003,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/Entrega/',	'Entregar Mipres',	'',	'',	'',	0,	'REST',	'Ruta para entregar una un mipres en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36');

INSERT INTO `servidores` (`ID`, `IP`, `Nombre`, `Usuario`, `Password`, `DataBase`, `Puerto`, `TipoServidor`, `Observaciones`, `Updated`, `Sync`) VALUES
(2004,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/AnularProgramacion/',	'Anular programacion Mipres',	'',	'',	'',	0,	'REST',	'Ruta para anular una programacion de un mipres en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36');


INSERT INTO `servidores` (`ID`, `IP`, `Nombre`, `Usuario`, `Password`, `DataBase`, `Puerto`, `TipoServidor`, `Observaciones`, `Updated`, `Sync`) VALUES
(2005,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/AnularEntrega/',	'Anular Entrega Mipres',	'',	'',	'',	0,	'REST',	'Ruta para anular entrega de un mipres en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36');
