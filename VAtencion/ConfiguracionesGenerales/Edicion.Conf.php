<?php

$TablaConfig="salud_archivo_conceptos_glosas";
$Vector[$TablaConfig]["cod_concepto_general"]["Excluir"]=1;
$Vector[$TablaConfig]["descripcion_concepto_general"]["Excluir"]=1;
$Vector[$TablaConfig]["tipo_concepto_general"]["Excluir"]=1;
$Vector[$TablaConfig]["aplicacion_concepto_general"]["Excluir"]=1;

$TablaConfig="usuarios";
$Vector[$TablaConfig]["Excluir"]["Password"]=1;
$Vector[$TablaConfig]["Excluir"]["Role"]=1;

/*
 * Tabla librodiario
 * 
 * Campos Requridos
 */
$TablaConfig="librodiario";
$Vector[$TablaConfig]["Required"]["idEmpresa"]=1;
$Vector[$TablaConfig]["Required"]["idCentroCosto"]=1;

$TablaConfig="usuarios";

$Vector[$TablaConfig]["TipoUser"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector[$TablaConfig]["TipoUser"]["TablaVinculo"]="usuarios_tipo";  //tabla de donde se vincula
$Vector[$TablaConfig]["TipoUser"]["IDTabla"]="Tipo"; //id de la tabla que se vincula
$Vector[$TablaConfig]["TipoUser"]["Display"]="Tipo"; //Columna que quiero mostrar
//
$Vector[$TablaConfig]["Habilitado"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector[$TablaConfig]["Habilitado"]["TablaVinculo"]="respuestas_condicional";  //tabla de donde se vincula
$Vector[$TablaConfig]["Habilitado"]["IDTabla"]="Valor"; //id de la tabla que se vincula
$Vector[$TablaConfig]["Habilitado"]["Display"]="Valor"; //Columna que quiero mostrar
//$Vector[$TablaConfig]["TipoUser"]["Predeterminado"]="Tipo";

$TablaConfig="empresapro";

$Vector[$TablaConfig]["Regimen"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector[$TablaConfig]["Regimen"]["TablaVinculo"]="empresapro_regimenes";  //tabla de donde se vincula
$Vector[$TablaConfig]["Regimen"]["IDTabla"]="Regimen"; //id de la tabla que se vincula
$Vector[$TablaConfig]["Regimen"]["Display"]="Regimen"; //Columna que quiero mostrar

$Vector[$TablaConfig]["Ciudad"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector[$TablaConfig]["Ciudad"]["TablaVinculo"]="cod_municipios_dptos";  //tabla de donde se vincula
$Vector[$TablaConfig]["Ciudad"]["IDTabla"]="Ciudad"; //id de la tabla que se vincula
$Vector[$TablaConfig]["Ciudad"]["Display"]="Ciudad"; //Columna que quiero mostrar

$Vector[$TablaConfig]["FacturaSinInventario"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector[$TablaConfig]["FacturaSinInventario"]["TablaVinculo"]="respuestas_condicional";  //tabla de donde se vincula
$Vector[$TablaConfig]["FacturaSinInventario"]["IDTabla"]="Valor"; //id de la tabla que se vincula
$Vector[$TablaConfig]["FacturaSinInventario"]["Display"]="Valor"; //Columna que quiero mostrar
 

$Vector[$TablaConfig]["CXPAutomaticas"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector[$TablaConfig]["CXPAutomaticas"]["TablaVinculo"]="respuestas_condicional";  //tabla de donde se vincula
$Vector[$TablaConfig]["CXPAutomaticas"]["IDTabla"]="Valor"; //id de la tabla que se vincula
$Vector[$TablaConfig]["CXPAutomaticas"]["Display"]="Valor"; //Columna que quiero mostrar
 

$Vector[$TablaConfig]["NIT"]["TipoText"]="number";
$Vector[$TablaConfig]["CodigoPrestadora"]["TipoText"]="number";
$Vector[$TablaConfig]["CodigoPrestadora"]["Min"]=0;
$Vector[$TablaConfig]["NIT"]["Min"]=0;
?>