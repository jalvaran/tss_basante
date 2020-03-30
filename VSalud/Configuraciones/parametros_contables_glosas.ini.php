<?php

$myTabla="parametros_contables_glosas";
$idTabla="ID";
$myPage="parametros_contables_glosas.php";
$myTitulo="Parametros contables para las glosas";


/////Asigno Datos necesarios para la visualizacion de la tabla en el formato que se desea
////
///
//print($statement);
$Vector["Tabla"]=$myTabla;          //Tabla
$Vector["Titulo"]=$myTitulo;        //Titulo
$Vector["VerDesde"]=$startpoint;    //Punto desde donde empieza
$Vector["Limit"]=$limit;            //Numero de Registros a mostrar

/*
 * Deshabilito Acciones
 * 
 */

        
//$Vector["NuevoRegistro"]["Deshabilitado"]=1;            
$Vector["VerRegistro"]["Deshabilitado"]=1;                      
//$Vector["EditarRegistro"]["Deshabilitado"]=1; 


///Filtros y orden
$Vector["Order"]=" $idTabla ASC ";   //Orden
?>