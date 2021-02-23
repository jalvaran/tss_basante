INSERT INTO `servidores` (`ID`, `IP`, `Nombre`, `Usuario`, `Password`, `DataBase`, `Puerto`, `TipoServidor`, `Observaciones`, `Updated`, `Sync`) VALUES
(2006,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/EntregaXFecha/',	'Consultar Entrega Mipres',	'',	'',	'',	0,	'REST',	'Ruta para consultar entregas de un mipres en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36');

INSERT INTO `servidores` (`ID`, `IP`, `Nombre`, `Usuario`, `Password`, `DataBase`, `Puerto`, `TipoServidor`, `Observaciones`, `Updated`, `Sync`) VALUES
(2007,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ProgramacionXFecha/',	'Consultar Programacion por Fecha Mipres',	'',	'',	'',	0,	'REST',	'Ruta para consultar la programacion de un mipres en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36');

INSERT INTO `servidores` (`ID`, `IP`, `Nombre`, `Usuario`, `Password`, `DataBase`, `Puerto`, `TipoServidor`, `Observaciones`, `Updated`, `Sync`) VALUES
(2008,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/DireccionamientoXPrescripcion/',	'Consultar direccionamiento por prescripcion',	'',	'',	'',	0,	'REST',	'Ruta para consultar el direccionamiento x No. de prescripcion de un mipres en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36');

INSERT INTO `servidores` (`ID`, `IP`, `Nombre`, `Usuario`, `Password`, `DataBase`, `Puerto`, `TipoServidor`, `Observaciones`, `Updated`, `Sync`) VALUES
(2009,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ProgramacionXPrescripcion/',	'Consultar programacion por prescripcion',	'',	'',	'',	0,	'REST',	'Ruta para consultar programacion x No. de prescripcion de un mipres en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36');

INSERT INTO `servidores` (`ID`, `IP`, `Nombre`, `Usuario`, `Password`, `DataBase`, `Puerto`, `TipoServidor`, `Observaciones`, `Updated`, `Sync`) VALUES
(2010,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/EntregaXPrescripcion/',	'Consultar entrega por prescripcion',	'',	'',	'',	0,	'REST',	'Ruta para consultar entregas x No. de prescripcion de un mipres en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36');

DROP TABLE IF EXISTS `mipres_causas_no_entrega`;
CREATE TABLE `mipres_causas_no_entrega` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `causa` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `mipres_causas_no_entrega` (`ID`, `causa`) VALUES
(7,	'No fue posible contactar al paciente'),
(8,	'Paciente fallecido'),
(9,	'Paciente se niega a recibir el suministro');

DROP TABLE IF EXISTS `mipres_direccionamiento`;
CREATE TABLE `mipres_direccionamiento` (
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


DROP TABLE IF EXISTS `mipres_entrega`;
CREATE TABLE `mipres_entrega` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `IDEntrega` bigint(20) NOT NULL,
  `NoPrescripcion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `TipoTec` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `ConTec` int(11) NOT NULL,
  `TipoIDPaciente` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `NoIDPaciente` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `NoEntrega` int(11) NOT NULL,
  `CodSerTecEntregado` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `CantTotEntregada` int(11) NOT NULL,
  `EntTotal` int(11) NOT NULL,
  `CausaNoEntrega` int(11) NOT NULL,
  `FecEntrega` date NOT NULL,
  `NoLote` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `TipoIDRecibe` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `NoIDRecibe` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `EstEntrega` int(11) NOT NULL,
  `FecAnulacion` date NOT NULL,
  `CodigosEntrega` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`ID`),
  KEY `IDEntrega` (`IDEntrega`),
  KEY `NoPrescripcion` (`NoPrescripcion`),
  KEY `NoIDPaciente` (`NoIDPaciente`),
  KEY `CausaNoEntrega` (`CausaNoEntrega`),
  KEY `EstEntrega` (`EstEntrega`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


DROP TABLE IF EXISTS `mipres_estados_direccionamiento`;
CREATE TABLE `mipres_estados_direccionamiento` (
  `ID` int(11) NOT NULL,
  `estado_direccionamiento` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `mipres_estados_direccionamiento` (`ID`, `estado_direccionamiento`) VALUES
(0,	'ANULADO'),
(1,	'ACTIVO'),
(2,	'PROCESADO');

DROP TABLE IF EXISTS `mipres_estados_entrega`;
CREATE TABLE `mipres_estados_entrega` (
  `ID` int(11) NOT NULL,
  `estado_entrega` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `mipres_estados_entrega` (`ID`, `estado_entrega`) VALUES
(0,	'ANULADA'),
(1,	'NO ENTREGADO'),
(2,	'ENTREGADO');

DROP TABLE IF EXISTS `mipres_estados_programacion`;
CREATE TABLE `mipres_estados_programacion` (
  `ID` int(11) NOT NULL,
  `estado_programacion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `mipres_estados_programacion` (`ID`, `estado_programacion`) VALUES
(0,	'ANULADO'),
(1,	'PTE. POR ENTREGAR'),
(2,	'ENTREGADO');

DROP TABLE IF EXISTS `mipres_programacion`;
CREATE TABLE `mipres_programacion` (
  `ID` bigint(20) NOT NULL,
  `IDProgramacion` bigint(20) NOT NULL,
  `NoPrescripcion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `TipoTec` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `ConTec` int(11) NOT NULL,
  `TipoIDPaciente` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `NoIDPaciente` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `NoEntrega` int(11) NOT NULL,
  `FecMaxEnt` date NOT NULL,
  `TipoIDSedeProv` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `NoIDSedeProv` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `CodSedeProv` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `CodSerTecAEntregar` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `CantTotAEntregar` int(11) NOT NULL,
  `FecProgramacion` datetime NOT NULL,
  `EstProgramacion` int(11) NOT NULL,
  `FecAnulacion` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`ID`),
  KEY `IDProgramacion` (`IDProgramacion`),
  KEY `NoPrescripcion` (`NoPrescripcion`),
  KEY `TipoTec` (`TipoTec`),
  KEY `NoIDPaciente` (`NoIDPaciente`),
  KEY `NoIDSedeProv` (`NoIDSedeProv`),
  KEY `EstProgramacion` (`EstProgramacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


DROP TABLE IF EXISTS `mipres_registro_entrega`;
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


DROP TABLE IF EXISTS `mipres_registro_programacion`;
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


