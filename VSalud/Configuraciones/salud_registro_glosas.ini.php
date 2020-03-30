<?php

$myTabla="salud_registro_glosas";
$myPage="salud_registro_glosas.php";
$myTitulo="Glosas";
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
//$Vector["EditarRegistro"]["Deshabilitado"]=1;


$Vector["TipoGlosa"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["TipoGlosa"]["TablaVinculo"]="salud_tipo_glosas";  //tabla de donde se vincula
$Vector["TipoGlosa"]["IDTabla"]="ID"; //id de la tabla que se vincula
$Vector["TipoGlosa"]["Display"]="TipoGlosa";                    //Columna que quiero mostrar
$Vector["TipoGlosa"]["Predeterminado"]="N";

$Vector["Excluir"]["idArchivo"]=1;   //Indico que esta columna no se mostrará
$Vector["Excluir"]["TablaOrigen"]=1;   //Indico que esta columna no se mostrará

$Vector["Soporte"]["Link"]=1;   //Indico que esta columna tendra un vinculo

///Filtros y orden
$Vector["Order"]=" $idTabla DESC ";   //Orden
?>