<?php

$myTabla="centrocosto";
$idTabla="ID";
$myPage="centrocosto.php";
$myTitulo="Centros de costos";



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

        
$Vector["VerRegistro"]["Deshabilitado"]=1; 
///Columnas excluidas

 //Indico que esta columna no se mostrará

$Vector["EmpresaPro"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["EmpresaPro"]["TablaVinculo"]="empresapro";  //tabla de donde se vincula
$Vector["EmpresaPro"]["IDTabla"]="idEmpresaPro"; //id de la tabla que se vincula
$Vector["EmpresaPro"]["Display"]="RazonSocial"; 
///Filtros y orden
$Vector["Order"]=" $idTabla DESC ";   //Orden
?>