<?php

$myTabla="registra_ediciones";
$myPage="registra_ediciones.php";
$myTitulo="Historial de Ediciones";
$idTabla="ID";


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
$Vector["NuevoRegistro"]["Deshabilitado"]=1;                                 
$Vector["EditarRegistro"]["Deshabilitado"]=1;


//
$Vector["Facturas_idFacturas"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["Facturas_idFacturas"]["TablaVinculo"]="facturas";  //tabla de donde se vincula
$Vector["Facturas_idFacturas"]["IDTabla"]="idFacturas"; //id de la tabla que se vincula
$Vector["Facturas_idFacturas"]["Display"]="NumeroFactura";                    //Columna que quiero mostrar
$Vector["Facturas_idFacturas"]["Predeterminado"]="N";


///Filtros y orden
$Vector["Order"]=" $idTabla DESC ";   //Orden
?>