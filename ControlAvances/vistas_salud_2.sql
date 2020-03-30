DROP VIEW IF EXISTS `vista_salud_facturas_pagas`;
CREATE VIEW vista_salud_facturas_pagas AS 
SELECT t1.`id_fac_mov_generados` as id_factura_generada,t1.`CuentaRIPS` as CuentaRIPS,t1.`CuentaGlobal` as CuentaGlobal,t1.`cod_prest_servicio`,t1.`razon_social`,t1.`num_factura`,t1.`fecha_factura`,
t1.`cod_enti_administradora`,t1.`nom_enti_administradora`,t1.`valor_neto_pagar`,t2.`id_pagados` as id_factura_pagada,t2.`fecha_pago_factura`,t2.`valor_pagado`,t2.`num_pago` ,t1.`tipo_negociacion`,
t1.`dias_pactados`,t1.`fecha_radicado`,t1.`numero_radicado`,t1.`Soporte`,t1.`CuentaContable`
FROM salud_archivo_facturacion_mov_generados t1 INNER JOIN salud_archivo_facturacion_mov_pagados t2 ON t1.`num_factura`=t2.`num_factura`
WHERE t1.estado='PAGADA';

DROP VIEW IF EXISTS `vista_salud_facturas_no_pagas`;
CREATE VIEW vista_salud_facturas_no_pagas AS 
SELECT t1.`id_fac_mov_generados` as id_factura_generada, 
(SELECT DATEDIFF(now(),t1.`fecha_radicado` ) - (SELECT dias_convenio FROM salud_eps t2 WHERE t2.cod_pagador_min=t1.cod_enti_administradora LIMIT 1)) as DiasMora ,t1.`CuentaRIPS` as CuentaRIPS,t1.`CuentaGlobal` as CuentaGlobal,t1.`cod_prest_servicio`,t1.`razon_social`,t1.`num_factura`,
t1.`fecha_factura`, t1.`fecha_radicado`,t1.`numero_radicado`,t1.`cod_enti_administradora`,t1.`nom_enti_administradora`,t1.`valor_neto_pagar` ,t1.`tipo_negociacion`, 
t1.`dias_pactados`,t1.`Soporte`,t1.`EstadoCobro`,t1.CuentaContable,
(SELECT IFNULL((SELECT SUM(ValorGlosado) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaInicial,
(SELECT IFNULL((SELECT SUM(ValorLevantado) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaLevantada,
(SELECT IFNULL((SELECT SUM(ValorAceptado) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaAceptada,
(SELECT IFNULL((SELECT SUM(ValorXConciliar) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaXConciliar,
(SELECT IFNULL((SELECT SUM(valor_pagado) FROM salud_archivo_facturacion_mov_pagados WHERE salud_archivo_facturacion_mov_pagados.num_factura=t1.num_factura),0)) as TotalPagos,
(SELECT t1.`valor_neto_pagar` - (SELECT ValorGlosaAceptada) - (SELECT TotalPagos)) AS SaldoFinalFactura

FROM salud_archivo_facturacion_mov_generados t1 WHERE (t1.estado='RADICADO' OR t1.estado=''); 


DROP VIEW IF EXISTS `vista_salud_facturas_diferencias`;
CREATE VIEW vista_salud_facturas_diferencias AS 
SELECT t1.`id_fac_mov_generados` as id_factura_generada,t1.`CuentaRIPS` as CuentaRIPS,t1.`CuentaGlobal` as CuentaGlobal,t1.`cod_prest_servicio`,t1.`razon_social`,t1.`num_factura`,t1.`fecha_factura`,
t1.`cod_enti_administradora`,t1.`nom_enti_administradora`,t1.`valor_neto_pagar`,t2.`id_pagados` as id_factura_pagada,t2.`fecha_pago_factura`,t2.`valor_pagado`,t2.`num_pago` ,
(SELECT t1.`valor_neto_pagar`-t2.`valor_pagado`) as DiferenciaEnPago ,t1.`tipo_negociacion`,
t1.`dias_pactados`,t1.`fecha_radicado`,t1.`numero_radicado`,t1.`Soporte`,t1.CuentaContable,
(SELECT IFNULL((SELECT SUM(ValorGlosado) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaInicial,
(SELECT IFNULL((SELECT SUM(ValorLevantado) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaLevantada,
(SELECT IFNULL((SELECT SUM(ValorAceptado) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaAceptada,
(SELECT IFNULL((SELECT SUM(ValorXConciliar) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaXConciliar

FROM salud_archivo_facturacion_mov_generados t1 INNER JOIN salud_archivo_facturacion_mov_pagados t2 ON t1.`num_factura`=t2.`num_factura`
WHERE t1.estado='DIFERENCIA';

DROP VIEW IF EXISTS `vista_cartera_x_edades`;
CREATE VIEW vista_cartera_x_edades AS 

SELECT 'T' as RangoDias,cod_enti_administradora AS idEPS, nom_enti_administradora,SUM(valor_neto_pagar) AS TotalCartera,COUNT(num_factura) as TotalItems,
(SELECT dias_convenio FROM salud_eps WHERE salud_eps.cod_pagador_min=vista_salud_facturas_no_pagas.cod_enti_administradora LIMIT 1) AS DiasPactados

FROM vista_salud_facturas_no_pagas  GROUP BY cod_enti_administradora

union all

SELECT '1' as RangoDias,cod_enti_administradora AS idEPS, nom_enti_administradora,SUM(valor_neto_pagar) AS TotalCartera,COUNT(num_factura) as TotalItems,
(SELECT dias_convenio FROM salud_eps WHERE salud_eps.cod_pagador_min=vista_salud_facturas_no_pagas.cod_enti_administradora LIMIT 1) AS DiasPactados

FROM vista_salud_facturas_no_pagas WHERE DiasMora<(1) GROUP BY cod_enti_administradora


union all

SELECT '1_30' as RangoDias,cod_enti_administradora AS idEPS, nom_enti_administradora,SUM(valor_neto_pagar) AS TotalCartera,COUNT(num_factura) as TotalItems,
(SELECT dias_convenio FROM salud_eps WHERE salud_eps.cod_pagador_min=vista_salud_facturas_no_pagas.cod_enti_administradora LIMIT 1) AS DiasPactados

FROM vista_salud_facturas_no_pagas WHERE DiasMora>=(1) AND DiasMora<=(30) GROUP BY cod_enti_administradora

union all

SELECT '31_60' as RangoDias,cod_enti_administradora AS idEPS, nom_enti_administradora,SUM(valor_neto_pagar) AS TotalCartera,COUNT(num_factura) as TotalItems,
(SELECT dias_convenio FROM salud_eps WHERE salud_eps.cod_pagador_min=vista_salud_facturas_no_pagas.cod_enti_administradora LIMIT 1) AS DiasPactados

FROM vista_salud_facturas_no_pagas WHERE DiasMora>=(31) AND DiasMora<=(60) GROUP BY cod_enti_administradora

union all

SELECT '61_90' as RangoDias,cod_enti_administradora AS idEPS, nom_enti_administradora,SUM(valor_neto_pagar) AS TotalCartera,COUNT(num_factura) as TotalItems,
(SELECT dias_convenio FROM salud_eps WHERE salud_eps.cod_pagador_min=vista_salud_facturas_no_pagas.cod_enti_administradora LIMIT 1) AS DiasPactados

FROM vista_salud_facturas_no_pagas WHERE DiasMora>=(61) AND DiasMora<=(90) GROUP BY cod_enti_administradora

union all

SELECT '91_120' as RangoDias,cod_enti_administradora AS idEPS, nom_enti_administradora,SUM(valor_neto_pagar) AS TotalCartera,COUNT(num_factura) as TotalItems,
(SELECT dias_convenio FROM salud_eps WHERE salud_eps.cod_pagador_min=vista_salud_facturas_no_pagas.cod_enti_administradora LIMIT 1) AS DiasPactados

FROM vista_salud_facturas_no_pagas WHERE DiasMora>=(91) AND DiasMora<=(120) GROUP BY cod_enti_administradora

union all

SELECT '121_180' as RangoDias,cod_enti_administradora AS idEPS, nom_enti_administradora,SUM(valor_neto_pagar) AS TotalCartera,COUNT(num_factura) as TotalItems,
(SELECT dias_convenio FROM salud_eps WHERE salud_eps.cod_pagador_min=vista_salud_facturas_no_pagas.cod_enti_administradora LIMIT 1) AS DiasPactados

FROM vista_salud_facturas_no_pagas WHERE DiasMora>=(121) AND DiasMora<=(180) GROUP BY cod_enti_administradora

union all

SELECT '181_360' as RangoDias,cod_enti_administradora AS idEPS, nom_enti_administradora,SUM(valor_neto_pagar) AS TotalCartera,COUNT(num_factura) as TotalItems,
(SELECT dias_convenio FROM salud_eps WHERE salud_eps.cod_pagador_min=vista_salud_facturas_no_pagas.cod_enti_administradora LIMIT 1) AS DiasPactados

FROM vista_salud_facturas_no_pagas WHERE DiasMora>=(181) AND DiasMora<=(360) GROUP BY cod_enti_administradora

union all

SELECT '360' as RangoDias,cod_enti_administradora AS idEPS, nom_enti_administradora,SUM(valor_neto_pagar) AS TotalCartera,COUNT(num_factura) as TotalItems,
(SELECT dias_convenio FROM salud_eps WHERE salud_eps.cod_pagador_min=vista_salud_facturas_no_pagas.cod_enti_administradora LIMIT 1) AS DiasPactados

FROM vista_salud_facturas_no_pagas WHERE DiasMora>=(360) GROUP BY cod_enti_administradora;


DROP VIEW IF EXISTS `vista_salud_procesos_gerenciales`;
CREATE VIEW vista_salud_procesos_gerenciales AS 
SELECT t1.`ID` as ID,t1.`idProceso` as idProceso,t1.`Fecha` as Fecha,
(SELECT RazonSocial FROM empresapro WHERE idEmpresaPro=t2.IPS LIMIT 1) as IPS,
(SELECT nombre_completo FROM salud_eps WHERE cod_pagador_min=t2.EPS LIMIT 1) as EPS,

t2.`NombreProceso`,t2.`Concepto`,t1.`Observaciones`,t1.`Soporte`
FROM `salud_procesos_gerenciales_archivos` t1 
INNER JOIN salud_procesos_gerenciales t2 ON t1.`idProceso`=t2.`ID`;


DROP VIEW IF EXISTS `vista_salud_facturas_diferencias`;
CREATE VIEW vista_salud_facturas_diferencias AS 
SELECT t1.`id_fac_mov_generados` as id_factura_generada,t1.`CuentaRIPS` as CuentaRIPS,t1.`CuentaGlobal` as CuentaGlobal,t1.`cod_prest_servicio`,t1.`razon_social`,t1.`num_factura`,t1.`fecha_factura`,
t1.`cod_enti_administradora`,t1.`nom_enti_administradora`,t1.`valor_neto_pagar`,t2.`id_pagados` as id_factura_pagada,t2.`fecha_pago_factura`,t2.`valor_pagado`,t2.`num_pago` ,
(SELECT t1.`valor_neto_pagar`-t2.`valor_pagado`) as DiferenciaEnPago ,t1.`tipo_negociacion`,
t1.`dias_pactados`,t1.`fecha_radicado`,t1.`numero_radicado`,t1.`Soporte` 
FROM salud_archivo_facturacion_mov_generados t1 INNER JOIN salud_archivo_facturacion_mov_pagados t2 ON t1.`num_factura`=t2.`num_factura`
WHERE t1.estado='DIFERENCIA';

DROP VIEW IF EXISTS `vista_circular_07`;
CREATE VIEW vista_circular_07 AS 
SELECT t1.`id_fac_mov_generados` as id_factura_generada, 
(SELECT DATEDIFF(now(),t1.`fecha_radicado` ) - (SELECT dias_convenio FROM salud_eps t2 WHERE t2.cod_pagador_min=t1.cod_enti_administradora LIMIT 1)) as DiasMora ,t1.`CuentaRIPS` as CuentaRIPS,t1.`CuentaGlobal` as CuentaGlobal,t1.`cod_prest_servicio`,t1.`razon_social`,t1.`num_factura`,
t1.`fecha_factura`, t1.`fecha_radicado`,t1.`numero_radicado`,
(SELECT DATE_ADD(t1.`fecha_radicado`, INTERVAL (SELECT dias_convenio FROM salud_eps t2 WHERE t2.cod_pagador_min=t1.cod_enti_administradora LIMIT 1) DAY)) AS FechaVencimiento,
t1.`cod_enti_administradora`,t1.`nom_enti_administradora`,
(SELECT nit FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.`cod_enti_administradora`) as NitEPS,
(SELECT tipo_regimen FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.`cod_enti_administradora`) as RegimenEPS,
(SELECT Genera07 FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.`cod_enti_administradora`) as Genera07,
t1.`valor_neto_pagar` ,t1.`tipo_negociacion`, 
t1.`dias_pactados`,t1.`Soporte`,t1.`EstadoCobro`,
(SELECT IF(t1.EstadoGlosa=9,( t1.`valor_neto_pagar`) ,0)) AS TotalDevolucion,  
(SELECT IFNULL((SELECT SUM(ValorGlosado) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaInicial,
(SELECT IFNULL((SELECT SUM(ValorLevantado) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaLevantada,
(SELECT IFNULL((SELECT SUM(ValorAceptado) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaAceptada,
(SELECT IFNULL((SELECT SUM(ValorXConciliar) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaXConciliar,
(SELECT IFNULL((SELECT SUM(valor_pagado) FROM salud_archivo_facturacion_mov_pagados WHERE salud_archivo_facturacion_mov_pagados.num_factura=t1.num_factura),0)) as TotalPagos,
(SELECT t1.`valor_neto_pagar` - (SELECT ValorGlosaAceptada) - (SELECT TotalPagos)- (SELECT TotalDevolucion)) AS SaldoFinalFactura
FROM salud_archivo_facturacion_mov_generados t1 WHERE t1.estado<>'' AND t1.estado<>'PAGADA'; 



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
       
       (SELECT tipo_ident_usuario FROM salud_archivo_usuarios WHERE salud_archivo_usuarios.num_ident_usuario=(SELECT identificacion) LIMIT 1) as tipo_identificacion,
       (SELECT edad FROM salud_archivo_usuarios WHERE salud_archivo_usuarios.num_ident_usuario=(SELECT identificacion) LIMIT 1) as edad_usuario,
       (SELECT unidad_medida_edad FROM salud_archivo_usuarios WHERE salud_archivo_usuarios.num_ident_usuario=(SELECT identificacion) LIMIT 1) as unidad_medida_edad,
       (SELECT sexo FROM salud_archivo_usuarios WHERE salud_archivo_usuarios.num_ident_usuario=(SELECT identificacion) LIMIT 1) as sexo_usuario,
       
       (SELECT CodigoGlosa FROM salud_glosas_iniciales WHERE salud_archivo_control_glosas_respuestas.idGlosa=salud_glosas_iniciales.ID LIMIT 1) AS cod_glosa_inicial,
       
       (SELECT descrpcion_concep_especifico FROM salud_archivo_conceptos_glosas WHERE (SELECT cod_glosa_inicial)= salud_archivo_conceptos_glosas.cod_glosa LIMIT 1) as descripcion_glosa_inicial,
       
       (SELECT descrpcion_concep_especifico FROM salud_archivo_conceptos_glosas WHERE salud_archivo_conceptos_glosas.cod_glosa= salud_archivo_control_glosas_respuestas.id_cod_glosa LIMIT 1) as descripcion_glosa_respuesta,
       
        (SELECT Estado_glosa FROM salud_estado_glosas WHERE salud_archivo_control_glosas_respuestas.EstadoGlosa=salud_estado_glosas.ID LIMIT 1) as descripcion_estado,
       (SELECT Estado_glosa FROM salud_estado_glosas WHERE salud_archivo_control_glosas_respuestas.EstadoGlosaHistorico=salud_estado_glosas.ID LIMIT 1) as descripcion_estado_historico
       
       
FROM salud_archivo_control_glosas_respuestas;



DROP VIEW IF EXISTS `vista_salud_facturas_usuarios`;
CREATE VIEW vista_salud_facturas_usuarios AS 
SELECT num_factura, num_ident_usuario FROM salud_archivo_consultas 
UNION ALL
SELECT num_factura, num_ident_usuario FROM salud_archivo_procedimientos
UNION ALL
SELECT num_factura, num_ident_usuario FROM salud_archivo_otros_servicios
UNION ALL
SELECT num_factura, num_ident_usuario FROM salud_archivo_medicamentos;


DROP VIEW IF EXISTS `vista_af_semaforo`;
CREATE VIEW vista_af_semaforo AS
SELECT *,
(SELECT MAX(DiasTranscurridos) FROM vista_glosas_iniciales
WHERE vista_glosas_iniciales.num_factura=salud_archivo_facturacion_mov_generados.num_factura 
AND vista_glosas_iniciales.EstadoGlosa=1) AS Dias,
(SELECT IFNULL((SELECT num_ident_usuario FROM salud_archivo_consultas 
WHERE salud_archivo_consultas.num_factura=salud_archivo_facturacion_mov_generados.num_factura LIMIT 1),
(SELECT IFNULL((SELECT num_ident_usuario FROM salud_archivo_procedimientos 
WHERE salud_archivo_procedimientos.num_factura=salud_archivo_facturacion_mov_generados.num_factura LIMIT 1),

(SELECT IFNULL((SELECT num_ident_usuario FROM salud_archivo_otros_servicios 
WHERE salud_archivo_otros_servicios.num_factura=salud_archivo_facturacion_mov_generados.num_factura LIMIT 1),

(SELECT num_ident_usuario FROM salud_archivo_medicamentos 
WHERE salud_archivo_medicamentos.num_factura=salud_archivo_facturacion_mov_generados.num_factura LIMIT 1)))))))
 AS identificacion_usuario  

FROM `salud_archivo_facturacion_mov_generados`;



DROP VIEW IF EXISTS `vista_circular030_1_radicados`;
CREATE VIEW vista_circular030_1_radicados AS 

SELECT '2' as TipoRegistro,
            tipo_ident_prest_servicio as TipoIdentificacionERP, 
            (SELECT nit FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.cod_enti_administradora) as NumeroIdentificacionERP, 
            nom_enti_administradora as RazonSocialIPS, 
            'NI' as TipoIdentificacionIPS, 
            (SELECT NIT FROM empresapro WHERE idEmpresaPro=1) as NumeroIdentificacionIPS, 
            'F' as TipoCobro,num_factura,'I' as IndicadorActualizacion,valor_neto_pagar as Valor,
            fecha_factura as FechaEmision,fecha_radicado as FechaPresentacion,'' as FechaDevolucion,
            '0' as ValorPagado,'0' as ValorGlosaAceptada,'NO' as GlosaRespondida, 
            (SELECT Valor-ValorPagado-ValorGlosaAceptada) as SaldoFactura, 'NO' as CobroJuridico, '0' as EtapaCobroJuridico
            FROM vista_af t1 
            WHERE  t1.GeneraCircular='S' AND t1.estado='RADICADO';
                

DROP VIEW IF EXISTS `vista_circular030_2_juridicos`;
CREATE VIEW vista_circular030_2_juridicos AS 

SELECT '2' as TipoRegistro,
            tipo_ident_prest_servicio as TipoIdentificacionERP, 
            (SELECT nit FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.cod_enti_administradora) as NumeroIdentificacionERP, 
            nom_enti_administradora as RazonSocialIPS, 
            'NI' as TipoIdentificacionIPS, 
            (SELECT NIT FROM empresapro WHERE idEmpresaPro=1) as NumeroIdentificacionIPS, 
            'F' as TipoCobro,num_factura,'I' as IndicadorActualizacion,valor_neto_pagar as Valor,
            fecha_factura as FechaEmision,fecha_radicado as FechaPresentacion,'' as FechaDevolucion,
            '0' as ValorPagado,'0' as ValorGlosaAceptada,'NO' as GlosaRespondida, 
            (SELECT Valor-ValorPagado-ValorGlosaAceptada) as SaldoFactura, 'NO' as CobroJuridico, '0' as EtapaCobroJuridico
            FROM vista_af t1 
            WHERE  t1.GeneraCircular='S' AND t1.estado='JURIDICO';



INSERT INTO `menu_submenus` (`ID`, `Nombre`, `idPestana`, `idCarpeta`, `idMenu`, `TablaAsociada`, `TipoLink`, `JavaScript`, `Pagina`, `Target`, `Estado`, `Image`, `Orden`, `Updated`, `Sync`) VALUES
(70,	'Registrar Descuentos BDUA',	36,	8,	0,	'',	0,	'',	'RegistrarDescuentosBDUA.php',	'_SELF',	CONV('1', 2, 10) + 0,	'pedidos.png',	10,	'2019-06-13 10:06:18',	'2018-07-13 15:42:34');
INSERT INTO `menu_carpetas` (`ID`, `Ruta`, `Updated`, `Sync`) VALUES
(8,	'../modulos/DescuentosBDUA/',	'2018-07-13 15:42:32',	'2018-07-13 15:42:32');


CREATE TABLE `descuentos_bdua` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Fecha` date NOT NULL,
  `NumeroFactura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `NumeroRadicado` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `AfiliadosIMA` double NOT NULL,
  `ValorDescuento` double NOT NULL,
  `idUser` int(11) NOT NULL,
  `FechaRegistro` date NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `NumeroRadicado` (`NumeroRadicado`),
  KEY `NumeroFactura` (`NumeroFactura`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


DROP VIEW IF EXISTS `vista_af`;
CREATE VIEW vista_af AS
SELECT *,
(SELECT Genera030 FROM salud_eps WHERE salud_eps.cod_pagador_min=`salud_archivo_facturacion_mov_generados`. cod_enti_administradora) as GeneraCircular,
(SELECT fecha_pago_factura FROM salud_archivo_facturacion_mov_pagados WHERE salud_archivo_facturacion_mov_pagados.num_factura=salud_archivo_facturacion_mov_generados.num_factura ORDER BY fecha_pago_factura DESC LIMIT 1) AS FechaPagoFactura 
FROM `salud_archivo_facturacion_mov_generados` ;


DROP VIEW IF EXISTS `vista_ar_listado`;
CREATE VIEW vista_ar_listado AS
SELECT t1.*,
(SELECT t2.CuentaContable FROM salud_archivo_facturacion_mov_generados t2 WHERE t1.num_factura=t2. num_factura LIMIT 1) as CuentaContable

FROM `salud_archivo_facturacion_mov_pagados` t1;


DROP VIEW IF EXISTS `vista_consolidado_facturacion`;
CREATE VIEW vista_consolidado_facturacion AS 
SELECT t1.`id_fac_mov_generados` as id_factura_generada,t1.CuentaContable, 
(SELECT DATEDIFF(now(),t1.`fecha_radicado` ) - (SELECT dias_convenio FROM salud_eps t2 WHERE t2.cod_pagador_min=t1.cod_enti_administradora LIMIT 1)) as DiasMora ,t1.`CuentaRIPS` as CuentaRIPS,t1.`CuentaGlobal` as CuentaGlobal,t1.`cod_prest_servicio`,t1.`razon_social`,t1.`num_factura`,
t1.`fecha_factura`, t1.`fecha_radicado`,t1.`numero_radicado`,
(SELECT DATE_ADD(t1.`fecha_radicado`, INTERVAL (SELECT dias_convenio FROM salud_eps t2 WHERE t2.cod_pagador_min=t1.cod_enti_administradora LIMIT 1) DAY)) AS FechaVencimiento,
t1.`cod_enti_administradora`,t1.`nom_enti_administradora`,
(SELECT nit FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.`cod_enti_administradora`) as NitEPS,
(SELECT tipo_regimen FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.`cod_enti_administradora`) as RegimenEPS,
(SELECT Genera07 FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.`cod_enti_administradora`) as Genera07,
t1.`valor_neto_pagar` ,t1.`tipo_negociacion`, 
t1.`dias_pactados`,t1.`Soporte`,t1.`EstadoCobro`,
(SELECT IF(t1.EstadoGlosa=9,( t1.`valor_neto_pagar`) ,0)) AS TotalDevolucion,  
(SELECT IFNULL((SELECT SUM(ValorGlosado) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaInicial,
(SELECT IFNULL((SELECT SUM(ValorLevantado) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaLevantada,
(SELECT IFNULL((SELECT SUM(ValorAceptado) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaAceptada,
(SELECT IFNULL((SELECT SUM(ValorXConciliar) FROM salud_glosas_iniciales WHERE salud_glosas_iniciales.num_factura=t1.num_factura AND salud_glosas_iniciales.EstadoGlosa<=7),0)) as ValorGlosaXConciliar,
(SELECT IFNULL((SELECT SUM(valor_pagado) FROM salud_archivo_facturacion_mov_pagados WHERE salud_archivo_facturacion_mov_pagados.num_factura=t1.num_factura),0)) as TotalPagos,
(SELECT t1.`valor_neto_pagar` - (SELECT ValorGlosaAceptada) - (SELECT TotalPagos)- (SELECT TotalDevolucion)) AS SaldoFinalFactura
FROM salud_archivo_facturacion_mov_generados t1; 




DROP VIEW IF EXISTS `vista_saldos_x_eps`;
CREATE VIEW vista_saldos_x_eps AS

SELECT cod_enti_administradora,nom_enti_administradora,NitEPS,RegimenEPS,sum(valor_neto_pagar) as TotalFacturado,
sum(ValorGlosaInicial) as TotalGlosaInicial ,sum(ValorGlosaLevantada) as TotalGlosaLevantada ,
sum(ValorGlosaAceptada) as TotalGlosaAceptada ,sum(ValorGlosaXConciliar) as TotalGlosaXConciliar ,
sum(TotalPagos) as TotalPagos,sum(TotalDevolucion) as TotalDevolucion,sum(SaldoFinalFactura) as SaldoEPS  
FROM vista_circular_07 GROUP BY cod_enti_administradora,RegimenEPS;

