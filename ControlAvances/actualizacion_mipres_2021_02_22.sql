UPDATE `mipres_estados_entrega` SET `estado_entrega` = 'REPORTADO' WHERE `ID` = '2';
UPDATE `mipres_estados_entrega` SET `estado_entrega` = 'PTE. POR REPORTAR' WHERE `ID` = '1';

CREATE TABLE `mipres_estados_reporte_entrega` (
  `ID` int(11) NOT NULL,
  `estado_reporte_entrega` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

TRUNCATE `mipres_estados_reporte_entrega`;
INSERT INTO `mipres_estados_reporte_entrega` (`ID`, `estado_reporte_entrega`) VALUES
(0,	'ANULADA'),
(1,	'ENTREGADO');

CREATE TABLE `mipres_reporte_entrega` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `IDReporteEntrega` bigint(20) NOT NULL,
  `NoPrescripcion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `TipoTec` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `ConTec` int(11) NOT NULL,
  `TipoIDPaciente` varchar(3) COLLATE utf8_spanish_ci NOT NULL,
  `NoIDPaciente` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `NoEntrega` int(11) NOT NULL,
  `EstadoEntrega` int(11) NOT NULL,
  `CausaNoEntrega` int(11) NOT NULL,
  `ValorEntregado` int(11) NOT NULL,
  `CodTecEntregado` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `CantTotEntregada` int(11) NOT NULL,
  `NoLote` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `FecEntrega` date NOT NULL,
  `FecRepEntrega` date NOT NULL,
  `EstRepEntrega` int(11) NOT NULL,
  `FecAnulacion` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT 'CURRENT_TIMESTAMP',
  PRIMARY KEY (`ID`),
  KEY `IDReporteEntrega` (`IDReporteEntrega`),
  KEY `NoPrescripcion` (`NoPrescripcion`),
  KEY `CausaNoEntrega` (`CausaNoEntrega`),
  KEY `EstEntrega` (`EstadoEntrega`),
  KEY `EstRepEntrega` (`EstRepEntrega`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `servidores` (`ID`, `IP`, `Nombre`, `Usuario`, `Password`, `DataBase`, `Puerto`, `TipoServidor`, `Observaciones`, `Updated`, `Sync`) VALUES
(2011,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntrega/',	'Entrega reporte entrega Mipres',	'',	'',	'',	0,	'REST',	'Ruta para entregar una un mipres en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36'),
(2012,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/AnularReporteEntrega/',	'Anular Reporte Entrega Mipres',	'',	'',	'',	0,	'REST',	'Ruta para anular entrega de un mipres en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36'),
(2013,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntregaXPrescripcion/',	'Consultar reporte de entrega por prescripcion',	'',	'',	'',	0,	'REST',	'Ruta para consultar reprote de entregas x No. de prescripcion de un mipres en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36'),
(2014,	'https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/api/ReporteEntregaXFecha/',	'Consultar reporte de entrega por fecha',	'',	'',	'',	0,	'REST',	'Ruta para consultar reprote de entregas x fecha de un mipres en el api mipres, ver: https://wsmipres.sispro.gov.co/WSSUMMIPRESNOPBS/Swagger/',	'2021-01-25 11:51:52',	'2020-07-25 10:06:36');


CREATE TABLE `mipres_registro_reporte_entrega` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `mipres_id` bigint(20) NOT NULL,
  `IdReporteEntrega` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_id_anulacion` int(11) NOT NULL,
  `fecha_anulacion` datetime NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `mipres_id` (`mipres_id`),
  KEY `IdReporteEntrega` (`IdReporteEntrega`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


