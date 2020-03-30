<?php

$myTabla="vista_salud_procesos_gerenciales";
$idTabla="ID";
$myPage="salud_procesos_gerenciales.php";
$myTitulo="Procesos Gerenciales";



/////Asigno Datos necesarios para la visualizacion de la tabla en el formato que se desea
////
///
//print($statement);
$Vector["Tabla"]=$myTabla;          //Tabla
$Vector["Titulo"]=$myTitulo;        //Titulo
$Vector["VerDesde"]=$startpoint;    //Punto desde donde empieza
$Vector["Limit"]=$limit;            //Numero de Registros a mostrar
$Vector["MyPage"]=$myPage;            //Numero de Registros a mostrar

/*
 * Deshabilito Acciones
 * 
 */

        
$Vector["NuevoRegistro"]["Deshabilitado"]=1;            
$Vector["VerRegistro"]["Deshabilitado"]=1;                      
$Vector["EditarRegistro"]["Deshabilitado"]=1; 

$Vector["Soporte"]["Link"]=1;   //Indico que esta columna tendra un vinculo
/*
 * Datos vinculados
 * 
 */


/*
 * 
 * Requeridos
 */


///Filtros y orden
$Vector["Order"]=" idProceso DESC ";   //Orden
?>