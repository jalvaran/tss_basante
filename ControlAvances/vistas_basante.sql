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

