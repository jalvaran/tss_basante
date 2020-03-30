<?php

$myTabla="salud_tesoreria";
$idTabla="ID";
$myPage="salud_tesoreria.php";
$myTitulo="Historial de Pagos Registrados por Tesoreria";



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
$Vector["VerRegistro"]["Deshabilitado"]=1;                      
//$Vector["EditarRegistro"]["Deshabilitado"]=1; 

//$Vector["Soporte"]["Link"]=1;   //Indico que esta columna tendra un vinculo

$Vector["Excluir"]["Soporte"]=1;
// Nueva Accion
$Ruta="LegalizarPagos.php?ID=";
$Vector["NuevaAccionLink"][1]="Legalizar";
$Vector["NuevaAccion"]["Legalizar"]["Titulo"]="Legalizar";
$Vector["NuevaAccion"]["Legalizar"]["Link"]=$Ruta;
$Vector["NuevaAccion"]["Legalizar"]["ColumnaLink"]="ID";
$Vector["NuevaAccion"]["Legalizar"]["Target"]="_blank";

///Filtros y orden
$Vector["Order"]=" $idTabla DESC ";   //Orden
?>