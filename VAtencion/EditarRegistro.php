<?php
ob_start();
$myPage="EditarRegistro.php";
include_once("../sesiones/php_control.php");
/* Desarrollado por Julian Alvaran, Techno Soluciones SAS
 * Este archivo se encargará de insertar un registro nuevo a una tabla
 * 
 */
//$Parametros = json_decode(urldecode($_REQUEST['TxtParametros']));  //Decodifico el Vector y llega como un objeto

$Parametros="";
$IDEdit=$_REQUEST['TxtIdEdit'];
$stament=$_REQUEST['Others'];
$Vector["ID"]=$IDEdit;
$Vector["stament"]= "$stament";
//print("Parametros: ");
//print_r($Parametros);
$TablaEdit=$_REQUEST['TxtTabla'];
$myTitulo="Editar Registro En ".$TablaEdit;

//Con esto visualizo los parametros recibidos
/*
echo ("<pre>");
print_r($Parametros);
echo ("</pre>");
*/
include_once("../modelo/php_tablas.php");  //Clases de donde se escribirán las tablas
include_once("css_construct.php");
$obTabla = new Tabla($db);
$obVenta = new conexion($idUser);
include_once("ConfiguracionesGenerales/Edicion.Conf.php"); //Procesa la insercion

print("<html>");
print("<head>");

$css =  new CssIni($myTitulo);
print("</head>");
print("<body>");
//Cabecera
$css->CabeceraIni($myTitulo); //Inicia la cabecera de la pagina
$css->CabeceraFin(); 

///////////////Creamos el contenedor
    /////
    /////
$css->CrearDiv("principal", "container", "center",1,1);
include_once("procesadores/procesaEdicion.php"); //Procesa la insercion
//print($statement);
///////////////Creamos la imagen representativa de la pagina
    /////
    /////
$MyPage="$TablaEdit.php";
if(isset($Vector[$TablaEdit]["MyPage"])){
    $MyPage=$Vector[$TablaEdit]["MyPage"];
}
$css->CrearImageLink($MyPage, "../images/volver.png", "_self",133,200);
$obTabla->FormularioEditarRegistro($Parametros,$Vector,$TablaEdit);

$css->CerrarDiv();//Cerramos contenedor Principal

$css->AgregaJS(); //Agregamos javascripts
print('<script type="text/javascript" src="jsPages/InsertarRegistro.js"></script>');
$css->AgregaSubir();    
////Fin HTML  
///Verifico si hay peticiones para exportar
///
///

print("</body></html>");
ob_end_flush();
?>