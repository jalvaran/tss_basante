DROP TABLE IF EXISTS `prefactura_reservas`;
CREATE TABLE `prefactura_reservas` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la reserva',
  `idPaciente` bigint(20) NOT NULL COMMENT 'identificador del paciente',
  `NumeroAutorizacion` varchar(45) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Autorizacion de la eps',
  `Cie10` varchar(45) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Diagnostico de la autorizacion',
  `CantidadServicios` int(11) NOT NULL COMMENT 'Cantidad de servicios que fueron autorizados',
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL COMMENT 'Observaciones de la reserva',
  `Estado` int(11) NOT NULL COMMENT 'Estado en que se encuentra la reserva',
  `idUser` int(11) NOT NULL,
  `Created` datetime NOT NULL,
  `Updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `idPaciente` (`idPaciente`),
  KEY `NumeroAutorizacion` (`NumeroAutorizacion`),
  KEY `Cie10` (`Cie10`),
  KEY `Estado` (`Estado`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


DROP TABLE IF EXISTS `prefactura_reservas_citas`;
CREATE TABLE `prefactura_reservas_citas` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `idReserva` bigint(20) NOT NULL COMMENT 'id de la reserva',
  `idHospital` bigint(20) NOT NULL COMMENT 'id del hospital',
  `Fecha` date NOT NULL COMMENT 'Fecha de la cita',
  `Hora` varchar(10) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Hora de la Cita',
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL COMMENT 'Observaciones de la cita',
  `Estado` int(11) NOT NULL COMMENT 'Estado de la cita',
  `idUser` int(11) NOT NULL COMMENT 'usuario creador',
  `Created` datetime NOT NULL COMMENT 'Fecha y hora del registro',
  `Updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


DROP TABLE IF EXISTS `prefactura_reservas_citas_adjuntos`;
CREATE TABLE `prefactura_reservas_citas_adjuntos` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `idCita` bigint(20) NOT NULL COMMENT 'id de la cita',
  `Ruta` varchar(300) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Ruta donde se guarda el archivo',
  `NombreArchivo` text COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo',
  `Extension` varchar(15) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Extension del archivo',
  `Tamano` bigint(20) NOT NULL COMMENT 'Tamano del archivo',
  `idUser` bigint(20) NOT NULL COMMENT 'usuario creador',
  `Created` datetime NOT NULL COMMENT 'fecha de creacion',
  `Updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha de actualizacion',
  PRIMARY KEY (`ID`),
  KEY `idCita` (`idCita`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


DROP TABLE IF EXISTS `prefactura_reservas_citas_estados`;
CREATE TABLE `prefactura_reservas_citas_estados` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EstadoCita` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `prefactura_reservas_citas_estados` (`ID`, `EstadoCita`) VALUES
(1,	'SIN CONFIRMAR'),
(2,	'SIN SOPORTAR'),
(3,	'SOPORTADA'),
(4,	'FACTURADA'),
(10,	'NO EJECUTADA'),
(11,	'ELIMINADA');

DROP TABLE IF EXISTS `prefactura_reservas_estados`;
CREATE TABLE `prefactura_reservas_estados` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EstadoReserva` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


DROP TABLE IF EXISTS `prefactura_reservas_validacion`;
CREATE TABLE `prefactura_reservas_validacion` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `idReserva` bigint(20) NOT NULL COMMENT 'id de la reserva',
  `Fecha` date NOT NULL COMMENT 'Fecha de la validacion',
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL COMMENT 'Observaciones de la validacion',
  `Ruta` varchar(300) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Ruta donde se guarda el archivo',
  `NombreArchivo` text COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre del archivo',
  `Extension` varchar(15) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Extension del archivo',
  `Tamano` bigint(20) NOT NULL COMMENT 'Tamano del archivo',
  `idUser` bigint(20) NOT NULL COMMENT 'usuario creador',
  `Created` datetime NOT NULL COMMENT 'fecha de creacion',
  `Updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha de actualizacion',
  PRIMARY KEY (`ID`),
  KEY `idReserva` (`idReserva`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


DROP TABLE IF EXISTS `prefactura_unidades_medida_edad`;
CREATE TABLE `prefactura_unidades_medida_edad` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NombreUnidad` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `prefactura_unidades_medida_edad` (`ID`, `NombreUnidad`) VALUES
(1,	'AÑOS'),
(2,	'MESES'),
(3,	'DIAS');


--
-- 1.  Vista para ver las reservas
--
DROP VIEW IF EXISTS `vista_prefactura_reservas`;
CREATE VIEW vista_prefactura_reservas AS 
SELECT t1.ID,t1.idPaciente, t2.TipoDocumento,t2.NumeroDocumento,
CONCAT(t2.PrimerNombre,' ',t2.SegundoNombre,' ',t2.PrimerApellido,' ',t2.SegundoApellido) as NombreCompleto,
t2.Sexo,t2.Telefono,t2.Direccion,t2.CodEPS,t2.CodigoDANE,t1.NumeroAutorizacion,t1.Cie10,t1.Observaciones,
(SELECT Nombre FROM catalogo_municipios t3 WHERE t3.CodigoDANE=t2.CodigoDANE) as Municipio,
(SELECT Departamento FROM catalogo_municipios t3 WHERE t3.CodigoDANE=t2.CodigoDANE) as Departamento,

t1.Estado,
(SELECT EstadoReserva FROM prefactura_reservas_estados t4 WHERE t4.ID=t1.Estado) as NombreEstado,
(SELECT Ruta FROM prefactura_reservas_validacion t5 WHERE t5.idReserva=t1.ID) as RutaSoporte,
(SELECT NombreArchivo FROM prefactura_reservas_validacion t5 WHERE t5.idReserva=t1.ID) as NombreArchivo

FROM prefactura_reservas t1 INNER JOIN prefactura_paciente t2 ON t1.`idPaciente`=t2.`ID`;

--
-- 2.  Vista para ver las citas
--
DROP VIEW IF EXISTS `vista_prefactura_reservas_citas`;
CREATE VIEW vista_prefactura_reservas_citas AS 
    SELECT t1.*,
    (SELECT t4.NumeroAutorizacion FROM prefactura_reservas t4 WHERE t4.ID=t1.idReserva) as NumeroAutorizacion,
    (SELECT t4.idPaciente FROM prefactura_reservas t4 WHERE t4.ID=t1.idReserva) as idPaciente,
    (SELECT t5.TipoDocumento FROM prefactura_paciente t5 WHERE t5.ID=(select (idPaciente) )) as TipoDocumento,
    (SELECT t5.NumeroDocumento FROM prefactura_paciente t5 WHERE t5.ID=(select (idPaciente) )) as NumeroDocumento,
    (SELECT CONCAT(t5.PrimerNombre,' ',t5.SegundoNombre,' ',t5.PrimerApellido,' ',t5.SegundoApellido) FROM prefactura_paciente t5 WHERE t5.ID=(select (idPaciente) )) as NombrePaciente,
    (SELECT t5.Telefono FROM prefactura_paciente t5 WHERE t5.ID=(select (idPaciente) )) as Telefono,
    (SELECT t5.Direccion FROM prefactura_paciente t5 WHERE t5.ID=(select (idPaciente) )) as Direccion,
    (SELECT t2.Nombre FROM ips t2 WHERE t2.ID=t1.idHospital) as NombreHospital,
    (SELECT t2.NIT FROM ips t2 WHERE t2.ID=t1.idHospital) as NITHospital,
    (SELECT t2.Direccion FROM ips t2 WHERE t2.ID=t1.idHospital) as DireccionHospital,
    (SELECT t2.Municipio FROM ips t2 WHERE t2.ID=t1.idHospital) as MunicipioHospital,
    (SELECT t2.Departamento FROM ips t2 WHERE t2.ID=t1.idHospital) as DepartamentoHospital,
    (SELECT t3.EstadoCita FROM prefactura_reservas_citas_estados t3 WHERE t3.ID=t1.Estado) as NombreEstado
    
FROM prefactura_reservas_citas t1;

