<?php

$myTabla="empresapro";
$idTabla="idEmpresaPro";
$myPage="empresapro.php";
$myTitulo="Empresas";



/////Asigno Datos necesarios para la visualizacion de la tabla en el formato que se desea
////
///
//print($statement);
$Vector["Tabla"]=$myTabla;          //Tabla
$Vector["Titulo"]=$myTitulo;        //Titulo
$Vector["VerDesde"]=$startpoint;    //Punto desde donde empieza
$Vector["Limit"]=$limit;            //Numero de Registros a mostrar


$Vector["Ciudad"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["Ciudad"]["TablaVinculo"]="cod_municipios_dptos";  //tabla de donde se vincula
$Vector["Ciudad"]["IDTabla"]="Ciudad"; //id de la tabla que se vincula
$Vector["Ciudad"]["Display"]="Ciudad"; 


$Vector["Regimen"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["Regimen"]["TablaVinculo"]="empresapro_regimenes";  //tabla de donde se vincula
$Vector["Regimen"]["IDTabla"]="Regimen"; //id de la tabla que se vincula
$Vector["Regimen"]["Display"]="Regimen"; 
/*
 * Deshabilito Acciones
 * 
 */

$Vector["FacturaSinInventario"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["FacturaSinInventario"]["TablaVinculo"]="respuestas_condicional";  //tabla de donde se vincula
$Vector["FacturaSinInventario"]["IDTabla"]="Valor"; //id de la tabla que se vincula
$Vector["FacturaSinInventario"]["Display"]="Valor"; 
$Vector["FacturaSinInventario"]["Predeterminado"]=1;
        
$Vector["CXPAutomaticas"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["CXPAutomaticas"]["TablaVinculo"]="respuestas_condicional";  //tabla de donde se vincula
$Vector["CXPAutomaticas"]["IDTabla"]="Valor"; //id de la tabla que se vincula
$Vector["CXPAutomaticas"]["Display"]="Valor"; 
$Vector["CXPAutomaticas"]["Predeterminado"]=1;

$Vector["VerRegistro"]["Deshabilitado"]=1; 
///Filtros y orden
$Vector["Order"]=" $idTabla DESC ";   //Orden
?>