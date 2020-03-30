--
-- 1.  Vista para ver las facturas pagas
--
DROP VIEW IF EXISTS `vista_salud_facturas_pagas`;
CREATE VIEW vista_salud_facturas_pagas AS 
SELECT t1.`id_fac_mov_generados` as id_factura_generada,t1.`cod_prest_servicio`,t1.`razon_social`,t1.`num_factura`,t1.`fecha_factura`,
t1.`cod_enti_administradora`,t1.`nom_enti_administradora`,t1.`valor_neto_pagar`,t2.`id_pagados` as id_factura_pagada,t2.`fecha_pago_factura`,t2.`valor_pagado`,t2.`num_pago` ,t1.`tipo_negociacion`,
t1.`dias_pactados`,t1.`fecha_radicado`,t1.`numero_radicado`,t1.`Soporte` 
FROM salud_archivo_facturacion_mov_generados t1 INNER JOIN salud_archivo_facturacion_mov_pagados t2 ON t1.`num_factura`=t2.`num_factura`
WHERE t1.estado='PAGADA';
--
-- 2. Vista para seleccionar lo que se pagó pero no fue generado
--
DROP VIEW IF EXISTS `vista_salud_facturas_no_pagas`;
CREATE VIEW vista_salud_facturas_no_pagas AS 
SELECT t1.`id_fac_mov_generados` as id_factura_generada, 
(SELECT DATEDIFF(now(),t1.`fecha_radicado` ) - t1.`dias_pactados`) as DiasMora ,t1.`cod_prest_servicio`,t1.`razon_social`,t1.`num_factura`,
t1.`fecha_factura`, t1.`fecha_radicado`,t1.`numero_radicado`,t1.`cod_enti_administradora`,t1.`nom_enti_administradora`,t1.`valor_neto_pagar` ,t1.`tipo_negociacion`, 
t1.`dias_pactados`,t1.`Soporte`,t1.`EstadoCobro`,
(SELECT tipo_regimen FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.cod_enti_administradora LIMIT 1) as Regimen,
(SELECT nit FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.cod_enti_administradora LIMIT 1) as NIT_EPS,
(SELECT nombre_completo FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.cod_enti_administradora LIMIT 1) as RazonSocialEPS
FROM salud_archivo_facturacion_mov_generados t1 WHERE t1.tipo_negociacion='evento' AND (t1.estado='RADICADO' OR t1.estado=''); 
--
-- 3. Vista para ver las facturas con diferencias
--
DROP VIEW IF EXISTS `vista_salud_facturas_diferencias`;
CREATE VIEW vista_salud_facturas_diferencias AS 
SELECT t1.`id_fac_mov_generados` as id_factura_generada,t1.`cod_prest_servicio`,t1.`razon_social`,t1.`num_factura`,t1.`fecha_factura`,
t1.`cod_enti_administradora`,t1.`nom_enti_administradora`,t1.`valor_neto_pagar`,t2.`id_pagados` as id_factura_pagada,t2.`fecha_pago_factura`,t2.`valor_pagado`,t2.`num_pago` ,
(SELECT t1.`valor_neto_pagar`-t2.`valor_pagado`) as DiferenciaEnPago ,t1.`tipo_negociacion`,
t1.`dias_pactados`,t1.`fecha_radicado`,t1.`numero_radicado`,t1.`Soporte` 
FROM salud_archivo_facturacion_mov_generados t1 INNER JOIN salud_archivo_facturacion_mov_pagados t2 ON t1.`num_factura`=t2.`num_factura`
WHERE t1.estado='DIFERENCIA' AND t1.tipo_negociacion='evento';

--
-- 4. Vista para seleccionar lo que se pagó pero no fue generado
--
DROP VIEW IF EXISTS `vista_salud_pagas_no_generadas`;
CREATE VIEW vista_salud_pagas_no_generadas AS 
Select T1.* From salud_archivo_facturacion_mov_pagados T1 
Left Outer Join salud_archivo_facturacion_mov_generados T2 ON T1.num_factura = T2.num_factura 
where T2.num_factura is null ;
--
-- 5. Vista para ver los cobros prejuridicos
--

DROP VIEW IF EXISTS `vista_salud_facturas_prejuridicos`;
CREATE VIEW vista_salud_facturas_prejuridicos AS 
SELECT t2.`id_fac_mov_generados` as ID,t1.idCobroPrejuridico,t2.`num_factura`,`cod_prest_servicio`,`razon_social`,`num_ident_prest_servicio`,`fecha_factura`,`cod_enti_administradora`,`nom_enti_administradora`,`valor_neto_pagar`,`tipo_negociacion`,`fecha_radicado`,`numero_radicado`,`Soporte` as SoporteRadicado,(SELECT Soporte FROM salud_cobros_prejuridicos WHERE ID=t1.idCobroPrejuridico) AS SoporteCobro,`estado` as EstadoFactura,`EstadoCobro` FROM `salud_cobros_prejuridicos_relaciones` t1 
INNER JOIN salud_archivo_facturacion_mov_generados t2 ON t1.`num_factura`=t2.`num_factura`
WHERE t2.EstadoCobro='PREJURIDICO1' OR t2.EstadoCobro='PREJURIDICO2';

--
-- 6. Vista para los procesos gerenciales
--
DROP VIEW IF EXISTS `vista_salud_procesos_gerenciales`;
CREATE VIEW vista_salud_procesos_gerenciales AS 
SELECT t1.`ID` as ID,t1.`idProceso` as idProceso,t1.`Fecha` as Fecha,
(SELECT nombre_completo FROM salud_eps WHERE cod_pagador_min=t2.EPS) as EPS,
t2.`NombreProceso`,t2.`Concepto`,t1.`Observaciones`,t1.`Soporte`
FROM `salud_procesos_gerenciales_archivos` t1 
INNER JOIN salud_procesos_gerenciales t2 ON t1.`idProceso`=t2.`ID`;

DROP VIEW IF EXISTS `vista_af`;
CREATE VIEW vista_af AS
SELECT *,(SELECT Genera030 FROM salud_eps WHERE salud_eps.cod_pagador_min=`salud_archivo_facturacion_mov_generados`. cod_enti_administradora) as GeneraCircular FROM `salud_archivo_facturacion_mov_generados` ;


DROP VIEW IF EXISTS `vista_SIHO`;
CREATE VIEW vista_SIHO AS 
SELECT t1.`id_fac_mov_generados` as id_factura_generada, (SELECT dias_convenio FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.cod_enti_administradora) as diasPago,
(SELECT DATEDIFF('2018-05-02',t1.`fecha_radicado` ) - (SELECT(diasPago))) as DiasMora ,t1.`cod_prest_servicio`,t1.`razon_social`,t1.`num_factura`,
t1.`fecha_factura`, t1.`fecha_radicado`,t1.`numero_radicado`,t1.`cod_enti_administradora`,t1.`nom_enti_administradora`,t1.`valor_neto_pagar` ,t1.`tipo_negociacion`, 
 t1.estado
FROM salud_archivo_facturacion_mov_generados t1 WHERE (t1.estado != 'PAGADA' AND t1.estado != 'DEVUELTA'  AND t1.estado != ''); 


DROP VIEW IF EXISTS `vista_af_duplicados`;
CREATE VIEW vista_af_duplicados AS 
SELECT t2.`id_fac_mov_generados` as ID,t1.`num_factura`,t1.`fecha_factura`,t1.`LineaArchivo`,t2.CuentaGlobal, t2.CuentaRIPS,
t2.EstadoGlosa,t2.fecha_cargue FROM `salud_rips_facturas_generadas_temp` t1 
INNER JOIN salud_archivo_facturacion_mov_generados t2 ON t1.`num_factura`=t2.`num_factura` 
WHERE t2.`EstadoGlosa`<>9;


DROP VIEW IF EXISTS `vista_af_devueltos`;
CREATE VIEW vista_af_devueltos AS 
SELECT t2.`id_fac_mov_generados` as ID,t1.`num_factura`,t1.`fecha_factura`,t1.`LineaArchivo`,t2.CuentaGlobal, t2.CuentaRIPS,
t2.EstadoGlosa,t2.fecha_cargue FROM `salud_rips_facturas_generadas_temp` t1 
INNER JOIN salud_archivo_facturacion_mov_generados t2 ON t1.`num_factura`=t2.`num_factura` 
WHERE t2.`EstadoGlosa`=9;


DROP VIEW IF EXISTS `vista_salud_cuentas_rips`;
CREATE VIEW vista_salud_cuentas_rips AS 
SELECT `CuentaRIPS`,CuentaGlobal ,`cod_enti_administradora`,`nom_enti_administradora`,
(SELECT sigla_nombre FROM salud_eps WHERE salud_eps.cod_pagador_min = cod_enti_administradora) as NombreCortoEPS,
(SELECT MIN(`fecha_factura`)) AS FechaDesde,
(SELECT MAX(`fecha_factura`)) AS FechaHasta,`fecha_radicado`,`numero_radicado`, 
(COUNT(`id_fac_mov_generados`)) AS NumFacturas,sum(`valor_neto_pagar`) as Total, MIN(EstadoGlosa) as idEstadoGlosa,
(SELECT Estado_glosa FROM salud_estado_glosas WHERE salud_estado_glosas.ID = MIN(`EstadoGlosa`)) as EstadoGlosa

FROM `salud_archivo_facturacion_mov_generados` GROUP BY `CuentaRIPS`;


DROP VIEW IF EXISTS `vista_salud_facturas_glosas`;
CREATE VIEW vista_salud_facturas_glosas AS 
SELECT `CuentaRIPS`,CuentaGlobal ,`cod_enti_administradora`,`nom_enti_administradora`,
`fecha_radicado` as FechaRadicado,`numero_radicado`, num_factura,fecha_factura,fecha_radicado,EstadoGlosa,
(SELECT tipo_ident_usuario FROM salud_archivo_consultas WHERE salud_archivo_consultas.num_factura = salud_archivo_facturacion_mov_generados.num_factura LIMIT 1) as TipoID,
(SELECT num_ident_usuario FROM salud_archivo_consultas WHERE salud_archivo_consultas.num_factura = salud_archivo_facturacion_mov_generados.num_factura LIMIT 1) as NumIdentificacion

FROM `salud_archivo_facturacion_mov_generados`;


DROP VIEW IF EXISTS `vista_salud_glosas_masivas`;
CREATE VIEW vista_salud_glosas_masivas AS 
SELECT `ID`,FechaIPS,FechaAuditoria,ValorGlosado,Analizado,GlosaInicial,GlosaControlRespuestas,CodigoActividad,Observaciones,Soporte,
(SELECT num_factura FROM salud_archivo_facturacion_mov_generados WHERE `salud_glosas_masivas_temp`.`num_factura`=salud_archivo_facturacion_mov_generados.num_factura LIMIT 1) AS Factura,
(SELECT `CuentaRips` FROM salud_archivo_facturacion_mov_generados WHERE `salud_glosas_masivas_temp`.`num_factura`=salud_archivo_facturacion_mov_generados.num_factura AND `salud_glosas_masivas_temp`.`CuentaRips`=salud_archivo_facturacion_mov_generados.CuentaRIPS LIMIT 1) AS CuentaRIPS,
(SELECT cod_enti_administradora FROM salud_archivo_facturacion_mov_generados WHERE salud_glosas_masivas_temp.`ID_EPS`=salud_archivo_facturacion_mov_generados.cod_enti_administradora AND `salud_glosas_masivas_temp`.`num_factura`=salud_archivo_facturacion_mov_generados.num_factura LIMIT 1) AS CodEps,
(SELECT nit FROM salud_eps WHERE `salud_glosas_masivas_temp`.`NIT_EPS`=salud_eps.nit AND `salud_glosas_masivas_temp`.`ID_EPS`=salud_eps.cod_pagador_min LIMIT 1) AS NIT,
(SELECT cod_glosa FROM salud_archivo_conceptos_glosas WHERE `salud_glosas_masivas_temp`.`CodigoGlosa`=salud_archivo_conceptos_glosas.cod_glosa LIMIT 1) AS CodigoGlosa,

(SELECT cod_medicamento FROM salud_archivo_medicamentos WHERE salud_archivo_medicamentos.num_factura=salud_glosas_masivas_temp.num_factura 
AND salud_archivo_medicamentos.cod_medicamento=salud_glosas_masivas_temp.CodigoActividad LIMIT 1) AS CodigoActividadAM,
(SELECT nom_medicamento FROM salud_archivo_medicamentos WHERE salud_archivo_medicamentos.num_factura=salud_glosas_masivas_temp.num_factura 
AND salud_archivo_medicamentos.cod_medicamento=salud_glosas_masivas_temp.CodigoActividad LIMIT 1) AS NombreActividadAM,
(SELECT SUM(valor_total_medic) FROM salud_archivo_medicamentos WHERE salud_archivo_medicamentos.num_factura=salud_glosas_masivas_temp.num_factura 
AND salud_archivo_medicamentos.cod_medicamento=salud_glosas_masivas_temp.CodigoActividad LIMIT 1) AS TotalAM,

(SELECT cod_servicio FROM salud_archivo_otros_servicios WHERE salud_archivo_otros_servicios.num_factura=salud_glosas_masivas_temp.num_factura 
AND salud_archivo_otros_servicios.cod_servicio=salud_glosas_masivas_temp.CodigoActividad LIMIT 1) AS CodigoActividadAT,
(SELECT nom_servicio FROM salud_archivo_otros_servicios WHERE salud_archivo_otros_servicios.num_factura=salud_glosas_masivas_temp.num_factura 
AND salud_archivo_otros_servicios.cod_servicio=salud_glosas_masivas_temp.CodigoActividad LIMIT 1) AS NombreActividadAT,

(SELECT SUM(valor_total_material) FROM salud_archivo_otros_servicios WHERE salud_archivo_otros_servicios.num_factura=salud_glosas_masivas_temp.num_factura 
AND salud_archivo_otros_servicios.cod_servicio=salud_glosas_masivas_temp.CodigoActividad LIMIT 1) AS TotalAT,

(SELECT cod_procedimiento FROM salud_archivo_procedimientos WHERE salud_archivo_procedimientos.num_factura=salud_glosas_masivas_temp.num_factura 
AND salud_archivo_procedimientos.cod_procedimiento=salud_glosas_masivas_temp.CodigoActividad LIMIT 1) AS CodigoActividadAP,

(SELECT descripcion_cups FROM salud_cups WHERE salud_cups.codigo_sistema=salud_glosas_masivas_temp.CodigoActividad LIMIT 1) AS NombreActividad,

(SELECT SUM(valor_procedimiento) FROM salud_archivo_procedimientos WHERE salud_archivo_procedimientos.num_factura=salud_glosas_masivas_temp.num_factura 
AND salud_archivo_procedimientos.cod_procedimiento=salud_glosas_masivas_temp.CodigoActividad LIMIT 1) AS TotalAP,


(SELECT cod_consulta FROM salud_archivo_consultas WHERE salud_archivo_consultas.num_factura=salud_glosas_masivas_temp.num_factura 
AND salud_archivo_consultas.cod_consulta=salud_glosas_masivas_temp.CodigoActividad LIMIT 1) AS CodigoActividadAC,
(SELECT SUM(valor_consulta) FROM salud_archivo_consultas WHERE salud_archivo_consultas.num_factura=salud_glosas_masivas_temp.num_factura 
AND salud_archivo_consultas.cod_consulta=salud_glosas_masivas_temp.CodigoActividad LIMIT 1) AS TotalAC,

(SELECT ID FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=salud_glosas_masivas_temp.num_factura 
AND salud_glosas_iniciales.CodigoActividad=salud_glosas_masivas_temp.CodigoActividad 
AND salud_glosas_iniciales.CodigoGlosa=salud_glosas_masivas_temp.CodigoGlosa LIMIT 1) AS idGlosa,

(SELECT ID FROM salud_glosas_iniciales_temp WHERE salud_glosas_iniciales_temp.num_factura=salud_glosas_masivas_temp.num_factura 
AND salud_glosas_iniciales_temp.CodigoActividad=salud_glosas_masivas_temp.CodigoActividad 
AND salud_glosas_iniciales_temp.CodigoGlosa=salud_glosas_masivas_temp.CodigoGlosa LIMIT 1) AS idGlosaTemp

FROM `salud_glosas_masivas_temp`;



DROP VIEW IF EXISTS `vista_salud_consolidaciones_masivas`;
CREATE VIEW vista_salud_consolidaciones_masivas AS 
SELECT `ID`,FechaConciliacion,CuentaRIPS as CuentaRIPSTemp,num_factura,CodigoActividad,ValorLevantado,ValorAceptado,Observaciones,Soporte,Conciliada,
(SELECT FechaConciliacion>NOW()) AS Extemporanea,(SELECT ValorLevantado>=0) AS ValorLevantadoPositivo,(SELECT ValorAceptado>=0) AS ValorAceptadoPositivo,
(SELECT num_factura FROM salud_archivo_facturacion_mov_generados WHERE `salud_conciliaciones_masivas_temp`.`num_factura`=salud_archivo_facturacion_mov_generados.num_factura LIMIT 1) AS Factura,
(SELECT `CuentaRips` FROM salud_archivo_facturacion_mov_generados WHERE `salud_conciliaciones_masivas_temp`.`num_factura`=salud_archivo_facturacion_mov_generados.num_factura AND `salud_conciliaciones_masivas_temp`.`CuentaRips`=salud_archivo_facturacion_mov_generados.CuentaRIPS LIMIT 1) AS CuentaRIPS,

(SELECT cod_medicamento FROM salud_archivo_medicamentos WHERE salud_archivo_medicamentos.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_medicamentos.cod_medicamento=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS CodigoActividadAM,
(SELECT nom_medicamento FROM salud_archivo_medicamentos WHERE salud_archivo_medicamentos.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_medicamentos.cod_medicamento=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS NombreActividadAM,
(SELECT EstadoGlosa FROM salud_archivo_medicamentos WHERE salud_archivo_medicamentos.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_medicamentos.cod_medicamento=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS EstadoGlosaAM,
(SELECT SUM(valor_total_medic) FROM salud_archivo_medicamentos WHERE salud_archivo_medicamentos.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_medicamentos.cod_medicamento=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS TotalAM,


(SELECT cod_servicio FROM salud_archivo_otros_servicios WHERE salud_archivo_otros_servicios.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_otros_servicios.cod_servicio=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS CodigoActividadAT,
(SELECT nom_servicio FROM salud_archivo_otros_servicios WHERE salud_archivo_otros_servicios.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_otros_servicios.cod_servicio=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS NombreActividadAT,
(SELECT EstadoGlosa FROM salud_archivo_otros_servicios WHERE salud_archivo_otros_servicios.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_otros_servicios.cod_servicio=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS EstadoGlosaAT,
(SELECT SUM(valor_total_material) FROM salud_archivo_otros_servicios WHERE salud_archivo_otros_servicios.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_otros_servicios.cod_servicio=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS TotalAT,


(SELECT cod_procedimiento FROM salud_archivo_procedimientos WHERE salud_archivo_procedimientos.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_procedimientos.cod_procedimiento=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS CodigoActividadAP,
(SELECT EstadoGlosa FROM salud_archivo_procedimientos WHERE salud_archivo_procedimientos.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_procedimientos.cod_procedimiento=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS EstadoGlosaAP,
(SELECT SUM(valor_procedimiento) FROM salud_archivo_procedimientos WHERE salud_archivo_procedimientos.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_procedimientos.cod_procedimiento=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS TotalAP,

(SELECT descripcion_cups FROM salud_cups WHERE salud_cups.codigo_sistema=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS NombreActividad,

(SELECT cod_consulta FROM salud_archivo_consultas WHERE salud_archivo_consultas.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_consultas.cod_consulta=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS CodigoActividadAC,
(SELECT EstadoGlosa FROM salud_archivo_consultas WHERE salud_archivo_consultas.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_consultas.cod_consulta=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS EstadoGlosaAC,
(SELECT SUM(valor_consulta) FROM salud_archivo_consultas WHERE salud_archivo_consultas.num_factura=salud_conciliaciones_masivas_temp.num_factura 
AND salud_archivo_consultas.cod_consulta=salud_conciliaciones_masivas_temp.CodigoActividad LIMIT 1) AS TotalAC


FROM `salud_conciliaciones_masivas_temp`;



DROP VIEW IF EXISTS `vista_glosas_iniciales`;
CREATE VIEW vista_glosas_iniciales AS 
SELECT *, (SELECT (DATEDIFF(NOW(),FechaIPS))) AS DiasTranscurridos,
(SELECT CuentaRIPS FROM salud_archivo_facturacion_mov_generados 
WHERE salud_archivo_facturacion_mov_generados.num_factura=salud_glosas_iniciales.num_factura) as CuentaRIPS
FROM salud_glosas_iniciales;

DROP VIEW IF EXISTS `vista_salud_cuentas_rips`;
CREATE VIEW vista_salud_cuentas_rips AS 
SELECT `CuentaRIPS`,CuentaGlobal ,`cod_enti_administradora`,`nom_enti_administradora`,(SELECT MIN(`fecha_factura`)) AS FechaDesde,
(SELECT sigla_nombre FROM salud_eps WHERE salud_eps.cod_pagador_min = cod_enti_administradora) as NombreCortoEPS,
(SELECT MAX(`fecha_factura`)) AS FechaHasta,`fecha_radicado`,`numero_radicado`, 
(COUNT(`id_fac_mov_generados`)) AS NumFacturas,sum(`valor_neto_pagar`) as Total, MIN(EstadoGlosa) as idEstadoGlosa,
(SELECT Estado_glosa FROM salud_estado_glosas WHERE salud_estado_glosas.ID = MIN(`EstadoGlosa`)) as EstadoGlosa,
(SELECT MAX(DiasTranscurridos) FROM vista_glosas_iniciales WHERE vista_glosas_iniciales.CuentaRIPS = salud_archivo_facturacion_mov_generados.CuentaRIPS AND (SELECT EstadoGlosa)<=4) as Dias
FROM `salud_archivo_facturacion_mov_generados` GROUP BY `CuentaRIPS`;

DROP VIEW IF EXISTS `vista_salud_facturas_usuarios`;
CREATE VIEW vista_salud_facturas_usuarios AS 
SELECT num_factura, num_ident_usuario FROM salud_archivo_consultas GROUP BY num_factura UNION
SELECT num_factura, num_ident_usuario FROM salud_archivo_procedimientos GROUP BY num_factura UNION
SELECT num_factura, num_ident_usuario FROM salud_archivo_otros_servicios GROUP BY num_factura UNION
SELECT num_factura, num_ident_usuario FROM salud_archivo_medicamentos GROUP BY num_factura;


DROP VIEW IF EXISTS `vista_salud_respuestas`;
CREATE VIEW vista_salud_respuestas AS 
SELECT salud_archivo_control_glosas_respuestas.ID as ID,
       salud_archivo_control_glosas_respuestas.CuentaRIPS as cuenta,
       salud_archivo_control_glosas_respuestas.num_factura as factura,
       salud_archivo_control_glosas_respuestas.Tratado as Tratado,
       salud_archivo_control_glosas_respuestas.Soporte as Soporte,
       fecha_factura ,
       numero_radicado,
       fecha_radicado,
       salud_archivo_facturacion_mov_generados.valor_neto_pagar as valor_factura,
       vista_salud_facturas_usuarios.num_ident_usuario as identificacion,
       salud_archivo_usuarios.tipo_ident_usuario as tipo_identificacion,
       salud_archivo_usuarios.edad as edad_usuario,
       salud_archivo_usuarios.unidad_medida_edad as unidad_medida_edad,
       salud_archivo_usuarios.sexo as sexo_usuario,
       salud_archivo_control_glosas_respuestas.CodigoActividad as cod_actividad,
       salud_archivo_control_glosas_respuestas.DescripcionActividad as descripcion_actividad,
       salud_archivo_control_glosas_respuestas.valor_actividad as valor_total_actividad,
       salud_archivo_control_glosas_respuestas.idGlosa as id_glosa_inicial,
       
       (SELECT CodigoGlosa FROM salud_glosas_iniciales WHERE salud_archivo_control_glosas_respuestas.idGlosa=salud_glosas_iniciales.ID) AS cod_glosa_inicial,
       
       (SELECT descrpcion_concep_especifico FROM salud_archivo_conceptos_glosas WHERE (SELECT cod_glosa_inicial)= salud_archivo_conceptos_glosas.cod_glosa LIMIT 1) as descripcion_glosa_inicial,
       salud_archivo_control_glosas_respuestas.id_cod_glosa as cod_glosa_respuesta,
       (SELECT descrpcion_concep_especifico FROM salud_archivo_conceptos_glosas WHERE salud_archivo_conceptos_glosas.cod_glosa= salud_archivo_control_glosas_respuestas.id_cod_glosa LIMIT 1) as descripcion_glosa_respuesta,
       salud_archivo_control_glosas_respuestas.EstadoGlosa as cod_estado,
       salud_estado_glosas.Estado_glosa as descripcion_estado,
       salud_archivo_control_glosas_respuestas.valor_glosado_eps,
       salud_archivo_control_glosas_respuestas.valor_levantado_eps,
       salud_archivo_control_glosas_respuestas.valor_aceptado_ips,
       (salud_archivo_control_glosas_respuestas.valor_glosado_eps-salud_archivo_control_glosas_respuestas.valor_levantado_eps-salud_archivo_control_glosas_respuestas.valor_aceptado_ips) AS valor_x_conciliar,
       salud_archivo_control_glosas_respuestas.observacion_auditor,
       salud_archivo_control_glosas_respuestas.fecha_registo as fecha_respuesta,
       salud_archivo_facturacion_mov_generados.cod_enti_administradora as cod_administrador,
       salud_archivo_facturacion_mov_generados.nom_enti_administradora as nombre_administrador,
       salud_eps.nit as nit_administrador,
       salud_eps.tipo_regimen as regimen_eps,
       salud_archivo_facturacion_mov_generados.cod_prest_servicio as cod_prestador,
       salud_archivo_facturacion_mov_generados.razon_social as nombre_prestador,
       salud_archivo_facturacion_mov_generados.num_ident_prest_servicio as nit_prestador
FROM salud_archivo_control_glosas_respuestas
INNER JOIN salud_archivo_facturacion_mov_generados
ON salud_archivo_control_glosas_respuestas.num_factura=salud_archivo_facturacion_mov_generados.num_factura
INNER JOIN vista_salud_facturas_usuarios
ON salud_archivo_control_glosas_respuestas.num_factura=vista_salud_facturas_usuarios.num_factura

INNER JOIN salud_estado_glosas 
ON salud_archivo_control_glosas_respuestas.EstadoGlosa = salud_estado_glosas.ID
INNER JOIN salud_archivo_usuarios
ON vista_salud_facturas_usuarios.num_ident_usuario = salud_archivo_usuarios.num_ident_usuario
INNER JOIN salud_eps
ON salud_archivo_facturacion_mov_generados.cod_enti_administradora = salud_eps.cod_pagador_min;


DROP VIEW IF EXISTS `vista_salud_respuestas`;
CREATE VIEW vista_salud_respuestas AS 
SELECT salud_archivo_control_glosas_respuestas.ID as ID,
       salud_archivo_control_glosas_respuestas.CuentaRIPS as cuenta,
       salud_archivo_control_glosas_respuestas.num_factura as factura,
       salud_archivo_control_glosas_respuestas.Tratado as Tratado,
       salud_archivo_control_glosas_respuestas.Soporte as Soporte,
       salud_archivo_control_glosas_respuestas.valor_glosado_eps,
       salud_archivo_control_glosas_respuestas.valor_levantado_eps,
       salud_archivo_control_glosas_respuestas.valor_aceptado_ips,
       salud_archivo_control_glosas_respuestas.EstadoGlosa as cod_estado,
       (salud_archivo_control_glosas_respuestas.valor_glosado_eps-salud_archivo_control_glosas_respuestas.valor_levantado_eps-salud_archivo_control_glosas_respuestas.valor_aceptado_ips) AS valor_x_conciliar,
       salud_archivo_control_glosas_respuestas.observacion_auditor,
       salud_archivo_control_glosas_respuestas.fecha_registo as fecha_respuesta,
       salud_archivo_control_glosas_respuestas.id_cod_glosa as cod_glosa_respuesta,
       salud_archivo_control_glosas_respuestas.CodigoActividad as cod_actividad,
       salud_archivo_control_glosas_respuestas.DescripcionActividad as descripcion_actividad,
       salud_archivo_control_glosas_respuestas.valor_actividad as valor_total_actividad,
       salud_archivo_control_glosas_respuestas.idGlosa as id_glosa_inicial,
       salud_archivo_control_glosas_respuestas.EstadoGlosaHistorico as EstadoGlosaHistorico,

       (SELECT fecha_factura FROM salud_archivo_facturacion_mov_generados WHERE salud_archivo_facturacion_mov_generados.num_factura=salud_archivo_control_glosas_respuestas.num_factura LIMIT 1) as fecha_factura, 
       (SELECT numero_radicado FROM salud_archivo_facturacion_mov_generados WHERE salud_archivo_facturacion_mov_generados.num_factura=salud_archivo_control_glosas_respuestas.num_factura LIMIT 1) as numero_radicado, 
       (SELECT fecha_radicado FROM salud_archivo_facturacion_mov_generados WHERE salud_archivo_facturacion_mov_generados.num_factura=salud_archivo_control_glosas_respuestas.num_factura LIMIT 1) as fecha_radicado, 
       (SELECT valor_neto_pagar FROM salud_archivo_facturacion_mov_generados WHERE salud_archivo_facturacion_mov_generados.num_factura=salud_archivo_control_glosas_respuestas.num_factura LIMIT 1) as valor_factura, 
       (SELECT cod_enti_administradora FROM salud_archivo_facturacion_mov_generados WHERE salud_archivo_facturacion_mov_generados.num_factura=salud_archivo_control_glosas_respuestas.num_factura LIMIT 1) as cod_administrador, 
       (SELECT nom_enti_administradora FROM salud_archivo_facturacion_mov_generados WHERE salud_archivo_facturacion_mov_generados.num_factura=salud_archivo_control_glosas_respuestas.num_factura LIMIT 1) as nombre_administrador, 
       (SELECT cod_prest_servicio FROM salud_archivo_facturacion_mov_generados WHERE salud_archivo_facturacion_mov_generados.num_factura=salud_archivo_control_glosas_respuestas.num_factura LIMIT 1) as cod_prestador, 
       (SELECT razon_social FROM salud_archivo_facturacion_mov_generados WHERE salud_archivo_facturacion_mov_generados.num_factura=salud_archivo_control_glosas_respuestas.num_factura LIMIT 1) as nombre_prestador, 
       (SELECT num_ident_prest_servicio FROM salud_archivo_facturacion_mov_generados WHERE salud_archivo_facturacion_mov_generados.num_factura=salud_archivo_control_glosas_respuestas.num_factura LIMIT 1) as nit_prestador, 
       
       (SELECT nit FROM salud_eps WHERE salud_eps.cod_pagador_min=(SELECT cod_administrador) LIMIT 1) as nit_administrador,
       (SELECT tipo_regimen FROM salud_eps WHERE salud_eps.cod_pagador_min=(SELECT cod_administrador) LIMIT 1) as regimen_eps,
       
       (SELECT num_ident_usuario FROM vista_salud_facturas_usuarios WHERE vista_salud_facturas_usuarios.num_factura=(SELECT factura) LIMIT 1) as identificacion,
       
       (SELECT tipo_ident_usuario FROM salud_archivo_usuarios WHERE salud_archivo_usuarios.tipo_ident_usuario=(SELECT identificacion) LIMIT 1) as tipo_identificacion,
       (SELECT edad FROM salud_archivo_usuarios WHERE salud_archivo_usuarios.tipo_ident_usuario=(SELECT identificacion) LIMIT 1) as edad_usuario,
       (SELECT unidad_medida_edad FROM salud_archivo_usuarios WHERE salud_archivo_usuarios.tipo_ident_usuario=(SELECT identificacion) LIMIT 1) as unidad_medida_edad,
       (SELECT sexo FROM salud_archivo_usuarios WHERE salud_archivo_usuarios.tipo_ident_usuario=(SELECT identificacion) LIMIT 1) as sexo_usuario,
       
       (SELECT CodigoGlosa FROM salud_glosas_iniciales WHERE salud_archivo_control_glosas_respuestas.idGlosa=salud_glosas_iniciales.ID LIMIT 1) AS cod_glosa_inicial,
       
       (SELECT descrpcion_concep_especifico FROM salud_archivo_conceptos_glosas WHERE (SELECT cod_glosa_inicial)= salud_archivo_conceptos_glosas.cod_glosa LIMIT 1) as descripcion_glosa_inicial,
       
       (SELECT descrpcion_concep_especifico FROM salud_archivo_conceptos_glosas WHERE salud_archivo_conceptos_glosas.cod_glosa= salud_archivo_control_glosas_respuestas.id_cod_glosa LIMIT 1) as descripcion_glosa_respuesta,
       
        (SELECT Estado_glosa FROM salud_estado_glosas WHERE salud_archivo_control_glosas_respuestas.EstadoGlosa=salud_estado_glosas.ID LIMIT 1) as descripcion_estado,
       (SELECT Estado_glosa FROM salud_estado_glosas WHERE salud_archivo_control_glosas_respuestas.EstadoGlosaHistorico=salud_estado_glosas.ID LIMIT 1) as descripcion_estado_historico
       
       
FROM salud_archivo_control_glosas_respuestas;
 


DROP VIEW IF EXISTS `vista_af_semaforo`;
CREATE VIEW vista_af_semaforo AS
SELECT *,
(SELECT MAX(DiasTranscurridos) FROM vista_glosas_iniciales
WHERE vista_glosas_iniciales.num_factura=salud_archivo_facturacion_mov_generados.num_factura 
AND vista_glosas_iniciales.EstadoGlosa=1) AS Dias,

(SELECT num_ident_usuario FROM vista_salud_facturas_usuarios 
WHERE vista_salud_facturas_usuarios.num_factura=salud_archivo_facturacion_mov_generados.num_factura LIMIT 1)
 AS identificacion_usuario 

FROM `salud_archivo_facturacion_mov_generados`;

DROP VIEW IF EXISTS `vista_af_semaforo`;
CREATE VIEW vista_af_semaforo AS
SELECT *,
(SELECT MAX(DiasTranscurridos) FROM vista_glosas_iniciales
WHERE vista_glosas_iniciales.num_factura=salud_archivo_facturacion_mov_generados.num_factura 
AND vista_glosas_iniciales.EstadoGlosa=1) AS Dias

FROM `salud_archivo_facturacion_mov_generados`;


DROP VIEW IF EXISTS `vista_salud_cuentas_rips`;
CREATE VIEW vista_salud_cuentas_rips AS 
SELECT `CuentaRIPS`,CuentaGlobal ,`cod_enti_administradora`,`nom_enti_administradora`,(SELECT MIN(`fecha_factura`)) AS FechaDesde,
(SELECT sigla_nombre FROM salud_eps WHERE salud_eps.cod_pagador_min = cod_enti_administradora) as NombreCortoEPS,
(SELECT MAX(`fecha_factura`)) AS FechaHasta,`fecha_radicado`,`numero_radicado`, 
(COUNT(`id_fac_mov_generados`)) AS NumFacturas,sum(`valor_neto_pagar`) as Total, MIN(EstadoGlosa) as idEstadoGlosa,
(SELECT Estado_glosa FROM salud_estado_glosas WHERE salud_estado_glosas.ID = MIN(`EstadoGlosa`)) as EstadoGlosa,
(SELECT MAX(DiasTranscurridos) FROM vista_glosas_iniciales WHERE vista_glosas_iniciales.CuentaRIPS = salud_archivo_facturacion_mov_generados.CuentaRIPS AND (vista_glosas_iniciales.EstadoGlosa=1 or vista_glosas_iniciales.EstadoGlosa=3)  ) as Dias
FROM `salud_archivo_facturacion_mov_generados` GROUP BY `CuentaRIPS`;


DROP VIEW IF EXISTS `vista_glosas_iniciales_reportes`;
CREATE VIEW vista_glosas_iniciales_reportes AS 
SELECT *,
(SELECT descrpcion_concep_especifico FROM salud_archivo_conceptos_glosas 
WHERE salud_archivo_conceptos_glosas.cod_glosa=salud_glosas_iniciales.CodigoGlosa LIMIT 1) AS DescripcionGlosa,
(SELECT cod_enti_administradora FROM salud_archivo_facturacion_mov_generados 
WHERE salud_glosas_iniciales.num_factura=salud_archivo_facturacion_mov_generados.num_factura LIMIT 1) AS cod_administrador,

(SELECT fecha_factura FROM salud_archivo_facturacion_mov_generados 
WHERE salud_glosas_iniciales.num_factura=salud_archivo_facturacion_mov_generados.num_factura LIMIT 1) AS fecha_factura,

(SELECT razon_social FROM salud_archivo_facturacion_mov_generados 
WHERE salud_glosas_iniciales.num_factura=salud_archivo_facturacion_mov_generados.num_factura LIMIT 1) AS nombre_prestador,
(SELECT cod_prest_servicio FROM salud_archivo_facturacion_mov_generados 
WHERE salud_glosas_iniciales.num_factura=salud_archivo_facturacion_mov_generados.num_factura LIMIT 1) AS cod_prestador,
(SELECT num_ident_prest_servicio FROM salud_archivo_facturacion_mov_generados 
WHERE salud_glosas_iniciales.num_factura=salud_archivo_facturacion_mov_generados.num_factura LIMIT 1) AS nit_prestador,
(SELECT nit FROM salud_eps 
WHERE salud_eps.cod_pagador_min=(SELECT cod_administrador) LIMIT 1) AS nit_administrador ,
(SELECT nombre_completo FROM salud_eps 
WHERE salud_eps.cod_pagador_min=(SELECT cod_administrador) LIMIT 1) AS nombre_administrador ,
(SELECT tipo_regimen FROM salud_eps 
WHERE salud_eps.cod_pagador_min=(SELECT cod_administrador) LIMIT 1) AS regimen_eps

FROM `salud_glosas_iniciales` WHERE EstadoGlosa<=7;


--
-- 2. vista que se realizará desde la programacion
--
DROP VIEW IF EXISTS `vista_salud_carteraxdias_v2`;
CREATE VIEW vista_salud_carteraxdias_v2 AS 
SELECT t1.`id_fac_mov_generados` as id_factura_generada, 
(SELECT DATEDIFF(now(),t1.`fecha_radicado` ) - t1.`dias_pactados`) as DiasMora ,t1.`cod_prest_servicio`,t1.`razon_social`,t1.`num_factura`,
t1.`fecha_factura`, t1.`fecha_radicado`,t1.`numero_radicado`,t1.`cod_enti_administradora`,t1.`nom_enti_administradora`,t1.`valor_neto_pagar` ,t1.`tipo_negociacion`, 
t1.`dias_pactados`,t1.`Soporte`,t1.`EstadoCobro`,(SELECT tipo_regimen FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.cod_enti_administradora LIMIT 1) as Regimen
FROM salud_archivo_facturacion_mov_generados t1 WHERE t1.fecha_radicado<='2019-01-01' AND t1.tipo_negociacion='evento' AND (t1.estado='RADICADO' OR t1.estado=''); 

--
-- vista para saber cuales facturas han sido reportadas al ftp  xml
--

DROP VIEW IF EXISTS `vista_reporte_contable_facturas_xml_ftp`;
CREATE VIEW vista_reporte_contable_facturas_xml_ftp AS 
SELECT t1.id_fac_mov_generados as ID, t1.cod_prest_servicio, t1.razon_social, 
t1.num_ident_prest_servicio as NIT, t1.num_factura, fecha_factura,cod_enti_administradora,
nom_enti_administradora,plan_beneficios,EstadoGlosa,
(SELECT (FechaRegistro) FROM salud_glosas_iniciales t2 WHERE t1.num_factura=t2.num_factura LIMIT 1) as FechaRegistro,
(SELECT SUM(ValorLevantado+ValorAceptado) FROM salud_glosas_iniciales t2 WHERE t1.num_factura=t2.num_factura) as ValorGlosado,
(SELECT SUM(ValorLevantado) FROM salud_glosas_iniciales t2 WHERE t1.num_factura=t2.num_factura) as ValorLevantado,
(SELECT SUM(ValorAceptado) FROM salud_glosas_iniciales t2 WHERE t1.num_factura=t2.num_factura) as ValorAceptado,

(SELECT IFNULL((SELECT Xml_Glosa_Inicial FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura LIMIT 1),0)) as Xml_Glosa_Inicial,
(SELECT IFNULL((SELECT Xml_Glosa_Aceptada FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura LIMIT 1),0)) as Xml_Glosa_Aceptada,
(SELECT IFNULL((SELECT Xml_Glosa_Levantada FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura LIMIT 1),0)) as Xml_Glosa_Levantada,
(SELECT IFNULL((SELECT ReportadoXFtp FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura LIMIT 1),0)) as ReportadoXFtp,
(SELECT IFNULL((SELECT GlosaInicialReportadaPorFTP FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura LIMIT 1),0)) as GlosaInicialReportadaPorFTP,

(SELECT (ID) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as idRegistroGlosasXmlFtp,
(SELECT (NombreArchivoXMLGlosaInicial) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as NombreArchivoXMLGlosaInicial,
(SELECT (NombreArchivoXMLGlosaAceptada) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as NombreArchivoXMLGlosaAceptada,
(SELECT (NombreArchivoXMLGlosaLevantada) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as NombreArchivoXMLGlosaLevantada,
(SELECT (Ruta_Xml_GlosaInicial) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as Ruta_Xml_GlosaInicial,
(SELECT (Ruta_Xml_GlosaAceptada) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as Ruta_Xml_GlosaAceptada,
(SELECT (Ruta_Xml_GlosaLevantada) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as Ruta_Xml_GlosaLevantada,
(SELECT (Ruta_Ftp) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as Ruta_Ftp

  
FROM vista_af t1 WHERE t1.EstadoGlosa>=5 AND t1.EstadoGlosa<=7;

DROP VIEW IF EXISTS `vista_reporte_contable_facturas_xml_ftp_glosas_iniciales`;
CREATE VIEW vista_reporte_contable_facturas_xml_ftp_glosas_iniciales AS 
SELECT t1.id_fac_mov_generados as ID, t1.cod_prest_servicio, t1.razon_social, 
t1.num_ident_prest_servicio as NIT, t1.num_factura, fecha_factura,cod_enti_administradora,
nom_enti_administradora,plan_beneficios,EstadoGlosa,
(SELECT (FechaRegistro) FROM salud_glosas_iniciales t2 WHERE t1.num_factura=t2.num_factura LIMIT 1) as FechaRegistro,
(SELECT SUM(ValorLevantado+ValorAceptado) FROM salud_glosas_iniciales t2 WHERE t1.num_factura=t2.num_factura) as ValorGlosado,
(SELECT SUM(ValorLevantado) FROM salud_glosas_iniciales t2 WHERE t1.num_factura=t2.num_factura) as ValorLevantado,
(SELECT SUM(ValorAceptado) FROM salud_glosas_iniciales t2 WHERE t1.num_factura=t2.num_factura) as ValorAceptado,

(SELECT IFNULL((SELECT Xml_Glosa_Inicial FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura LIMIT 1),0)) as Xml_Glosa_Inicial,
(SELECT IFNULL((SELECT Xml_Glosa_Aceptada FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura LIMIT 1),0)) as Xml_Glosa_Aceptada,
(SELECT IFNULL((SELECT Xml_Glosa_Levantada FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura LIMIT 1),0)) as Xml_Glosa_Levantada,
(SELECT IFNULL((SELECT ReportadoXFtp FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura LIMIT 1),0)) as ReportadoXFtp,
(SELECT IFNULL((SELECT GlosaInicialReportadaPorFTP FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura LIMIT 1),0)) as GlosaInicialReportadaPorFTP,

(SELECT (ID) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as idRegistroGlosasXmlFtp,
(SELECT (NombreArchivoXMLGlosaInicial) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as NombreArchivoXMLGlosaInicial,
(SELECT (NombreArchivoXMLGlosaAceptada) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as NombreArchivoXMLGlosaAceptada,
(SELECT (NombreArchivoXMLGlosaLevantada) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as NombreArchivoXMLGlosaLevantada,
(SELECT (Ruta_Xml_GlosaInicial) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as Ruta_Xml_GlosaInicial,
(SELECT (Ruta_Xml_GlosaAceptada) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as Ruta_Xml_GlosaAceptada,
(SELECT (Ruta_Xml_GlosaLevantada) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as Ruta_Xml_GlosaLevantada,
(SELECT (Ruta_Ftp) FROM registro_glosas_xml_ftp t2 WHERE t1.num_factura=t2.num_factura) as Ruta_Ftp

  
FROM vista_af t1 WHERE t1.EstadoGlosa=1;


