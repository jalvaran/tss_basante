<?php

$myTabla="formatos_calidad";
$myPage="formatos_calidad.php";
$myTitulo="Formatos de Calidad";
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
//$Vector["NuevoRegistro"]["Deshabilitado"]=1;                                 
//$Vector["EditarRegistro"]["Deshabilitado"]=1;

// Nueva Accion
$Ruta="AgregarTextoEnriquecido.php?Tabla=formatos_calidad&idTabla=ID&Campo=CuerpoFormato&idItem=";
$Vector["NuevaAccionLink"][1]="TextoEnriquecido";
$Vector["NuevaAccion"]["TextoEnriquecido"]["Titulo"]="texto enriquecido para el Cuerpo del formato ";
$Vector["NuevaAccion"]["TextoEnriquecido"]["Link"]=$Ruta;
$Vector["NuevaAccion"]["TextoEnriquecido"]["ColumnaLink"]="ID";
$Vector["NuevaAccion"]["TextoEnriquecido"]["Target"]="_blank";

///Filtros y orden
$Vector["Order"]=" $idTabla DESC ";   //Orden
?>