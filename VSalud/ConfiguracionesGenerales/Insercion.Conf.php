<?php

/*
 * Tabla productos venta
 * Columnas excluidas
 */
$TablaConfig="salud_archivo_conceptos_glosas";
$VarInsert[$TablaConfig]["cod_glosa"]["TipoText"]="number";
$VarInsert[$TablaConfig]["cod_concepto_general"]["TipoText"]="number";
$VarInsert[$TablaConfig]["cod_concepto_especifico"]["TipoText"]="number";
$VarInsert[$TablaConfig]["cod_glosa"]["Required"]=1;
$VarInsert[$TablaConfig]["cod_concepto_general"]["Required"]=1;
$VarInsert[$TablaConfig]["descripcion_concepto_general"]["Required"]=1;
$VarInsert[$TablaConfig]["tipo_concepto_general"]["Required"]=1;
$VarInsert[$TablaConfig]["aplicacion_concepto_general"]["Required"]=1;
$VarInsert[$TablaConfig]["cod_concepto_especifico"]["Required"]=1;
$VarInsert[$TablaConfig]["descrpcion_concep_especifico"]["Required"]=1;

/*
 * Tabla SERVICIOS
 * Columnas excluidas
 */
$TablaConfig="servicios";
$VarInsert[$TablaConfig]["Kit"]["Excluir"]=1;

/*
 * Tabla Usuarios
 * Tipo de Texto
 */
$TablaConfig="usuarios";
$VarInsert[$TablaConfig]["Password"]["TipoText"]="password";


/*
 * Tabla Usuarios
 * Tipo de librodiario
 * Campos Requridos
 */
$TablaConfig="librodiario";
$VarInsert[$TablaConfig]["idEmpresa"]["Required"]=1;
$VarInsert[$TablaConfig]["idCentroCosto"]["Required"]=1;



/*
 * Tabla subcuentas
 * Columnas excluidas
 */
$TablaConfig="subcuentas";
$VarInsert[$TablaConfig]["Valor"]["Excluir"]=1;

/*
 * Parametros de configuracion subcuentas
 * Columnas Excluidas
 */
$TablaConfig="cuentasfrecuentes";
$VarInsert[$TablaConfig]["ClaseCuenta"]["Required"]=1;
$VarInsert[$TablaConfig]["UsoFuturo"]["Excluir"]=1;


/*
 * Tabla cot_itemscotizaciones
 * 
 * Campos Requridos
 */
$TablaConfig="cot_itemscotizaciones";
$VarInsert[$TablaConfig]["CuentaPUC"]["Required"]=1;


/*
 * Tabla Usuarios
 * Tipo de librodiario
 * Campos Requridos
 */
$TablaConfig="ordenesdetrabajo";
$VarInsert[$TablaConfig]["Hora"]["Required"]=1;
$VarInsert[$TablaConfig]["idCliente"]["Required"]=1;
$VarInsert[$TablaConfig]["idUsuarioCreador"]["Required"]=1;
$VarInsert[$TablaConfig]["Descripcion"]["Required"]=1;
$VarInsert[$TablaConfig]["FechaOT"]["Required"]=1;
$VarInsert[$TablaConfig]["Estado"]["Excluir"]=1;

$TablaConfig="productosalquiler";
$VarInsert[$TablaConfig]["Kit"]["Required"]=1;
$VarInsert[$TablaConfig]["Kit"]["Excluir"]=1;
//$VarInsert[$TablaConfig]["Required"]["Kit"]=1;

$TablaConfig="clientes";
$VarInsert[$TablaConfig]["Num_Identificacion"]["Required"]=1;   
$VarInsert[$TablaConfig]["Tipo_Documento"]["Required"]=1;   
$VarInsert[$TablaConfig]["DV"]["Required"]=1;   
$VarInsert[$TablaConfig]["RazonSocial"]["Required"]=1; 
$VarInsert[$TablaConfig]["Direccion"]["Required"]=1;   
$VarInsert[$TablaConfig]["Cod_Dpto"]["Required"]=1;   
$VarInsert[$TablaConfig]["Cod_Mcipio"]["Required"]=1;   
$VarInsert[$TablaConfig]["Pais_Domicilio"]["Required"]=1;   
$VarInsert[$TablaConfig]["Telefono"]["Required"]=1;   
$VarInsert[$TablaConfig]["Ciudad"]["Required"]=1;   
//$VarInsert[$TablaConfig]["CIUU"]["Required"]=1; 

$TablaConfig="proveedores";
$VarInsert[$TablaConfig]["Num_Identificacion"]["Required"]=1;   
$VarInsert[$TablaConfig]["Tipo_Documento"]["Required"]=1;   
$VarInsert[$TablaConfig]["DV"]["Required"]=1;   
$VarInsert[$TablaConfig]["RazonSocial"]["Required"]=1; 
$VarInsert[$TablaConfig]["Direccion"]["Required"]=1;   
$VarInsert[$TablaConfig]["Cod_Dpto"]["Required"]=1;   
$VarInsert[$TablaConfig]["Cod_Mcipio"]["Required"]=1;   
$VarInsert[$TablaConfig]["Pais_Domicilio"]["Required"]=1;   
$VarInsert[$TablaConfig]["Telefono"]["Required"]=1;   
$VarInsert[$TablaConfig]["Ciudad"]["Required"]=1;   
$VarInsert[$TablaConfig]["CIUU"]["Required"]=1; 


/*
 * Tabla ORDENES DE SERVICIO
 * Columnas excluidas
 */
$TablaConfig="ordenesdetrabajo";
$VarInsert[$TablaConfig]["Hora"]["Excluir"]=1;
$VarInsert[$TablaConfig]["idUsuarioCreador"]["Excluir"]=1;
$VarInsert[$TablaConfig]["TipoOrden"]["Required"]=1;

/*
 * Tabla ORDENES DE COMPRA
 * Columnas excluidas
 */
$TablaConfig="ordenesdecompra";
$VarInsert[$TablaConfig]["Created"]["Excluir"]=1;
$VarInsert[$TablaConfig]["UsuarioCreador"]["Excluir"]=1;

/*
 * Tabla produccion_ordenes_trabajo
 * Columnas excluidas
 */
$TablaConfig="produccion_ordenes_trabajo";
$VarInsert[$TablaConfig]["FechaTerminacion"]["Excluir"]=1;
$VarInsert[$TablaConfig]["TotalHorasPlaneadas"]["Excluir"]=1;
$VarInsert[$TablaConfig]["TotalHorasEmpleadas"]["Excluir"]=1;
$VarInsert[$TablaConfig]["Pausas_Operativas"]["Excluir"]=1;
$VarInsert[$TablaConfig]["Pausas_No_Operativas"]["Excluir"]=1;
$VarInsert[$TablaConfig]["Tiempo_Operacion"]["Excluir"]=1;
$VarInsert[$TablaConfig]["ValorSugerido"]["Excluir"]=1;

$VarInsert[$TablaConfig]["ValorMateriales"]["Excluir"]=1;
$VarInsert[$TablaConfig]["ValorCotizado"]["Excluir"]=1;
$VarInsert[$TablaConfig]["ValorFacturado"]["Excluir"]=1;
$VarInsert[$TablaConfig]["Estado"]["Excluir"]=1;
$VarInsert[$TablaConfig]["Facturado"]["Excluir"]=1;
$VarInsert[$TablaConfig]["NumFactura"]["Excluir"]=1;

$TablaConfig="salud_archivo_conceptos_glosas";
$VarInsert[$TablaConfig]["cod_concepto_general"]["Excluir"]=1;
$VarInsert[$TablaConfig]["descripcion_concepto_general"]["Excluir"]=1;
$VarInsert[$TablaConfig]["tipo_concepto_general"]["Excluir"]=1;
$VarInsert[$TablaConfig]["aplicacion_concepto_general"]["Excluir"]=1;

$TablaConfig="salud_cups";
$VarInsert[$TablaConfig]["user"]["Excluir"]=1;
$VarInsert[$TablaConfig]["fecha_hora_registro"]["Excluir"]=1;
$VarInsert[$TablaConfig]["ID"]["Excluir"]=1;
$VarInsert[$TablaConfig]["grupo"]["Excluir"]=1;
$VarInsert[$TablaConfig]["subgrupo"]["Excluir"]=1;
$VarInsert[$TablaConfig]["categoria"]["Excluir"]=1;
$VarInsert[$TablaConfig]["subcategoria"]["Excluir"]=1;
$VarInsert[$TablaConfig]["codigo_ley"]["Excluir"]=1;
$VarInsert[$TablaConfig]["codigo_sistema"]["Required"]=1;
$VarInsert[$TablaConfig]["descripcion_cups"]["Required"]=1;
$VarInsert[$TablaConfig]["observacion"]["Required"]=1;


$TablaConfig="salud_eps";
$VarInsert[$TablaConfig]["nit"]["TipoText"]="number";
$VarInsert[$TablaConfig]["Genera030"]["Excluir"]=1;
$VarInsert[$TablaConfig]["salud_eps"]["TipoText"]="number";
$VarInsert[$TablaConfig]["dias_convenio"]["TipoText"]="number";
?>