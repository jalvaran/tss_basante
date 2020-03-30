<?php

$myTabla="vista_af_duplicados";
$idTabla="ID";
$myPage="vista_af_duplicados.php";
$myTitulo="Facturas Cargadas que están duplicadas";

/*
 * Opciones en Acciones
 * 
 */

$Vector["NuevoRegistro"]["Deshabilitado"]=1;            
$Vector["VerRegistro"]["Deshabilitado"]=1;                      
$Vector["EditarRegistro"]["Deshabilitado"]=1; 

$Vector["EstadoGlosa"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["EstadoGlosa"]["TablaVinculo"]="salud_estado_glosas";  //tabla de donde se vincula
$Vector["EstadoGlosa"]["IDTabla"]="ID"; //id de la tabla que se vincula
$Vector["EstadoGlosa"]["Display"]="Estado_glosa"; 

/////Asigno Datos necesarios para la visualizacion de la tabla en el formato que se desea
////
///
//print($statement);
$Vector["Tabla"]=$myTabla;          //Tabla
$Vector["Titulo"]=$myTitulo;        //Titulo
$Vector["VerDesde"]=$startpoint;    //Punto desde donde empieza
$Vector["Limit"]=$limit;            //Numero de Registros a mostrar
$Vector["MyPage"]=$myPage;            //pagina
///Columnas excluidas

$Vector["Soporte"]["Link"]=1;   //Indico que esta columna tendra un vinculo
///Filtros y orden
$Vector["Order"]=" $idTabla ASC ";   //Orden
?>