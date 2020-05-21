INSERT INTO `formatos_calidad` (`ID`, `Nombre`, `Version`, `Codigo`, `Fecha`, `CuerpoFormato`, `NotasPiePagina`, `Updated`, `Sync`) VALUES
(2001,	'RELACION DE FACTURACION',	'001',	'F-ARF',	'2020-04-02',	'',	'',	'2020-04-02 11:36:26',	'0000-00-00 00:00:00');

ALTER TABLE `facturas` ADD `CuentaRIPS` INT(4) UNSIGNED ZEROFILL NOT NULL AFTER `Observaciones`, ADD INDEX `CuentaRIPS` (`CuentaRIPS`);
