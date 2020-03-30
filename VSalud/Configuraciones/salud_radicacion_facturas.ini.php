<?php

$myTabla="salud_archivo_facturacion_mov_generados";
$myPage="salud_radicacion_facturas.php";
$myTitulo="Radicacion de facturas";
$idTabla="id_fac_mov_generados";


/////Asigno Datos necesarios para la visualizacion de la tabla en el formato que se desea
////
///
//print($statement);
$Vector["Tabla"]=$myTabla;          //Tabla
$Vector["Titulo"]=$myTitulo;        //Titulo
$Vector["VerDesde"]=$startpoint;    //Punto desde donde empieza
$Vector["Limit"]=$limit;            //Numero de Registros a mostrar
$Vector["MyPage"]=$myPage;
/*
 * Deshabilito Acciones
 * 
 */
          
$Vector["VerRegistro"]["Deshabilitado"]=1;       
$Vector["NuevoRegistro"]["Deshabilitado"]=1;                                 
$Vector["EditarRegistro"]["Deshabilitado"]=1;

$Vector["Soporte"]["Link"]=1;   //Indico que esta columna tendra un vinculo
///Filtros y orden
$Vector["Order"]=" $idTabla DESC ";   //Orden
?>