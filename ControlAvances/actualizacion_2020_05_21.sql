INSERT INTO `formatos_calidad` (`ID`, `Nombre`, `Version`, `Codigo`, `Fecha`, `CuerpoFormato`, `NotasPiePagina`, `Updated`, `Sync`) VALUES
(2001,	'RELACION DE FACTURACION',	'001',	'F-ARF',	'2020-04-02',	'',	'',	'2020-04-02 11:36:26',	'0000-00-00 00:00:00');

ALTER TABLE `facturas` ADD `CuentaRIPS` INT(4) UNSIGNED ZEROFILL NOT NULL AFTER `Observaciones`, ADD INDEX `CuentaRIPS` (`CuentaRIPS`);

CREATE TABLE `rips_consecutivos` (
  `CuentaRIPS` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `AF` bigint(20) NOT NULL,
  `AD` bigint(20) NOT NULL,
  `AT` bigint(20) NOT NULL,
  `US` bigint(20) NOT NULL,
  `Ruta` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Valor` double NOT NULL,
  `Estado` int(1) NOT NULL,
  `Created` datetime NOT NULL,
  `idUser` int(11) NOT NULL,
  UNIQUE KEY `CuentaRIPS` (`CuentaRIPS`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

