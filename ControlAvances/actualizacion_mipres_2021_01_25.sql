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


