<?php

$myTabla="paginas_bloques";
$myPage="paginas_bloques.php";
$myTitulo="Configurar Paginas De Acceso";
$MyID="ID";


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

$Vector["Excluir"]["Kit"]=1;
///Columnas requeridas al momento de una insercion
$Vector["Required"]["TipoUsuario"]=1;   
$Vector["Required"]["Pagina"]=1; 
$Vector["Required"]["Habilitado"]=1;

//
/*/*
$Vector["Pagina"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["Pagina"]["TablaVinculo"]="menu_submenus";  //tabla de donde se vincula
$Vector["Pagina"]["IDTabla"]="Pagina"; //id de la tabla que se vincula
$Vector["Pagina"]["Display"]="Nombre";                    //Columna que quiero mostrar
$Vector["Pagina"]["Predeterminado"]="N";
*/

$Vector["TipoUsuario"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["TipoUsuario"]["TablaVinculo"]="usuarios_tipo";  //tabla de donde se vincula
$Vector["TipoUsuario"]["IDTabla"]="Tipo"; //id de la tabla que se vincula
$Vector["TipoUsuario"]["Display"]="Tipo"; 
$Vector["TipoUsuario"]["Predeterminado"]="N";
/*
$Vector["Habilitado"]["Vinculo"]=1;   //Indico que esta columna tendra un vinculo
$Vector["Habilitado"]["TablaVinculo"]="respuestas_condicional";  //tabla de donde se vincula
$Vector["Habilitado"]["IDTabla"]="Valor"; //id de la tabla que se vincula
$Vector["Habilitado"]["Display"]="Valor";                    //Columna que quiero mostrar
$Vector["Habilitado"]["Predeterminado"]="SI";
*/
                  //Columna que quiero mostrar
///Filtros y orden
$Vector["Order"]=" $MyID DESC ";   //Orden
?>