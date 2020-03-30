<?php

$myTabla="salud_cobros_prejuridicos";
$idTabla="ID";
$myPage="salud_cobros_prejuridicos.php";
$myTitulo="Historial de Cobros Prejuridicos";



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

        
$Vector["NuevoRegistro"]["Deshabilitado"]=1;            
//$Vector["VerRegistro"]["Deshabilitado"]=1;                      
$Vector["EditarRegistro"]["Deshabilitado"]=1; 

//Link para la accion ver
$Ruta="PDF_Documentos.php?idDocumento=1&idCobroPrejuridico=";
$Vector["VerRegistro"]["Link"]=$Ruta;
$Vector["VerRegistro"]["ColumnaLink"]="ID";

// Nueva Accion
$Ruta="ProcesadoresJS/GeneradorExcel.php?idDocumento=1&idCobro=";
$Vector["NuevaAccionLink"][1]="Relacion";
$Vector["NuevaAccion"]["Relacion"]["Titulo"]="Relacion de Facturas";
$Vector["NuevaAccion"]["Relacion"]["Link"]=$Ruta;
$Vector["NuevaAccion"]["Relacion"]["ColumnaLink"]="ID";
$Vector["NuevaAccion"]["Relacion"]["Target"]="_blank";

///Filtros y orden
$Vector["Order"]=" $idTabla DESC ";   //Orden
?>