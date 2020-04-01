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

