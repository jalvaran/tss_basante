<?php

$myTabla="salud_procesos_gerenciales_conceptos";
$idTabla="ID";
$myPage="salud_procesos_gerenciales_conceptos.php";
$myTitulo="Listado Conceptos para procesos gerenciales";


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


/*
 * Datos vinculados
 * 
 */

$Vector["tipo_regimen"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["tipo_regimen"]["TablaVinculo"]="salud_regimen";  //tabla de donde se vincula
$Vector["tipo_regimen"]["IDTabla"]="Regimen"; //id de la tabla que se vincula
$Vector["tipo_regimen"]["Display"]="Regimen"; 
$Vector["tipo_regimen"]["Predeterminado"]=1;
        
/*
 * 
 * Requeridos
 */


///Filtros y orden
$Vector["Order"]=" $idTabla DESC ";   //Orden
?>