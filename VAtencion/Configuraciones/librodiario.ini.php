<?php

$myTabla="vista_libro_diario";
$idTabla="idLibroDiario";
$myPage="librodiario.php";
$myTitulo="Libro Diario";



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

/*
 * Datos vinculados
 * 
 */

$Vector["idCentroCosto"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["idCentroCosto"]["TablaVinculo"]="centrocosto";  //tabla de donde se vincula
$Vector["idCentroCosto"]["IDTabla"]="ID"; //id de la tabla que se vincula
$Vector["idCentroCosto"]["Display"]="Nombre"; 
$Vector["idCentroCosto"]["Predeterminado"]=1;

$Vector["idEmpresa"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["idEmpresa"]["TablaVinculo"]="empresapro";  //tabla de donde se vincula
$Vector["idEmpresa"]["IDTabla"]="idEmpresaPro"; //id de la tabla que se vincula
$Vector["idEmpresa"]["Display"]="RazonSocial"; 
$Vector["idEmpresa"]["Predeterminado"]=1;


/*
 * 
 * Requeridos
 */


///Filtros y orden
$Vector["Order"]=" $idTabla DESC ";   //Orden
?>