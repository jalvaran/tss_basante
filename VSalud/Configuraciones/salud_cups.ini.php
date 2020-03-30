<?php

$myTabla="salud_cups";
$idTabla="ID";
$myPage="salud_cups.php";
$myTitulo="CUPS";



/////Asigno Datos necesarios para la visualizacion de la tabla en el formato que se desea
////
///
//print($statement);
$Vector["Tabla"]=$myTabla;          //Tabla
$Vector["Titulo"]=$myTitulo;        //Titulo
$Vector["VerDesde"]=$startpoint;    //Punto desde donde empieza
$Vector["Limit"]=$limit;            //Numero de Registros a mostrar

$Vector["VerRegistro"]["Deshabilitado"]=1; 
/*
 * Deshabilito Acciones
 * 
 */

$Vector["Excluir"]["fecha_hora_registro"]=1;    //Indico que esta columna no se mostrará
$Vector["Excluir"]["user"]=1;                   //Indico que esta columna no se mostrará
$Vector["Excluir"]["ID"]=1;                    //Indico que esta columna no se mostrará
$Vector["Excluir"]["grupo"]=1;                 //Indico que esta columna no se mostrará
$Vector["Excluir"]["subgrupo"]=1;              //Indico que esta columna no se mostrará
$Vector["Excluir"]["categoria"]=1;             //Indico que esta columna no se mostrará
$Vector["Excluir"]["subcategoria"]=1;          //Indico que esta columna no se mostrará
$Vector["Excluir"]["codigo_ley"]=1;            //Indico que esta columna no se mostrará

$Vector["Manual"]["Vinculo"]=1;                 //Indico que esta columna tendra un vinculo
$Vector["Manual"]["TablaVinculo"]="salud_manuales_tarifarios";  //tabla de donde se vincula
$Vector["Manual"]["IDTabla"]="ID"; //id de la tabla que se vincula
$Vector["Manual"]["Display"]="Nombre"; 
$Vector["Manual"]["Predeterminado"]=1;

///Filtros y orden
$Vector["Order"]=" $idTabla DESC ";   //Orden
?>