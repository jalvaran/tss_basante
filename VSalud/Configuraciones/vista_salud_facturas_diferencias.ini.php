<?php

$myTabla="vista_salud_facturas_diferencias";
$idTabla="id_factura_generada";
$myPage="vista_salud_facturas_diferencias.php";
$myTitulo="Historial Facturas con Diferencias";

/*
 * Opciones en Acciones
 * 
 */

$Vector["NuevoRegistro"]["Deshabilitado"]=1;            
$Vector["VerRegistro"]["Deshabilitado"]=1;                      
$Vector["EditarRegistro"]["Deshabilitado"]=1; 

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
$Vector["Order"]=" $idTabla DESC ";   //Orden
?>