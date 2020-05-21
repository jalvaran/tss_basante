<?php

$myTabla="colaboradores";
$idTabla="idColaboradores";
$myPage="colaboradores.php";
$myTitulo="Colaboradores";



/////Asigno Datos necesarios para la visualizacion de la tabla en el formato que se desea
////
///
//print($statement);
$Vector["Tabla"]=$myTabla;          //Tabla
$Vector["Titulo"]=$myTitulo;        //Titulo
$Vector["VerDesde"]=$startpoint;    //Punto desde donde empieza
$Vector["Limit"]=$limit;            //Numero de Registros a mostrar

///Columnas excluidas

$Vector["Excluir"]["Password"]=1;   //Indico que esta columna no se mostrará

/*
 * Deshabilito Acciones
 * 
 */

      
$Vector["VerRegistro"]["Deshabilitado"]=1;       

///Filtros y orden
$Vector["Order"]=" $idTabla DESC ";   //Orden
?>