CREATE TABLE `facturas_anulaciones` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `idFactura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,
  `TipoMovimiento` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `idUser` int(11) NOT NULL,
  `Created` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idFactura` (`idFactura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

DROP TABLE IF EXISTS `prefactura_reservas_anulaciones`;
CREATE TABLE `prefactura_reservas_anulaciones` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `idReserva` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Observaciones` text COLLATE utf8_spanish_ci NOT NULL,   
  `idUser` int(11) NOT NULL,
  `Created` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idReserva` (`idReserva`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



INSERT INTO `configuracion_general` (`ID`, `Descripcion`, `Valor`, `Updated`, `Sync`) VALUES
(10000,	'RUTA PARA EXPORTAR TABLAS EN CSV',	'../../exports/tabla.csv',	'2020-07-30 20:23:25',	'2018-07-13 15:42:21');


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
(SELECT COUNT(*) FROM prefactura_reservas_citas t6 WHERE t6.idReserva=t1.ID) as NumCitas,
(SELECT NombreArchivo FROM prefactura_reservas_validacion t5 WHERE t5.idReserva=t1.ID) as NombreArchivo

FROM prefactura_reservas t1 INNER JOIN prefactura_paciente t2 ON t1.`idPaciente`=t2.`ID`;

--
-- 1.  Vista para ver las citas
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

DROP VIEW IF EXISTS `vista_liquidacion_colaboradores`;
CREATE VIEW vista_liquidacion_colaboradores AS 
SELECT t2.ID,t2.Fecha,t1.idColaborador as Documento,t3.Nombre, t1.idServicio,t4.Descripcion,t1.idRecorrido , t1.Valor
FROM facturas_items t1 
INNER JOIN prefactura_reservas_citas t2 ON t1.idCita=t2.ID 
INNER JOIN colaboradores t3 ON t3.Identificacion=t1.idColaborador 
INNER JOIN catalogo_servicios t4 ON t4.CUPS=t1.idServicio ORDER BY idColaborador;


